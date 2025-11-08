<?php

/*
 * @package     Modern Joomla Entity
 *
 * @author      Anibal Sanchez <team@extly.com>
 * @copyright   Copyright (c)2025 Anibal Sanchez. All rights reserved.
 *              Based on phproberto/joomla-entity by Roberto Segura López
 *
 * @license     LGPL-2.1+
 *
 * @see         https://www.extly.com
 */

namespace Extly\Joomla\Entity\Users;

defined('_JEXEC') || die;

use Extly\Joomla\Entity\Acl\Contracts\Aclable;
use Extly\Joomla\Entity\Acl\Traits\HasAcl;
use Extly\Joomla\Entity\Collection;
use Extly\Joomla\Entity\ComponentEntity;
use Extly\Joomla\Entity\Core\Traits\HasParams;
use Extly\Joomla\Entity\Exception\SaveException;
use Extly\Joomla\Entity\Users\Traits\HasUserGroups;
use Extly\Joomla\Entity\Users\Traits\HasViewLevels;
use Extly\Joomla\Entity\Users\ViewLevel;
use Joomla\CMS\Factory;
use Joomla\CMS\User\UserHelper;
use Joomla\Registry\Registry;
use Joomla\Utilities\ArrayHelper;

/**
 * User entity.
 *
 * @since   1.0.0
 */
class User extends ComponentEntity implements Aclable
{
    use HasAcl;
    use HasParams;
    use HasUserGroups;
    use HasViewLevels;

    /**
     * Is this user root/super user?
     *
     * @var  bool
     */
    protected $isRoot;

    /**
     * Active user.
     *
     * @var    static
     * @since  1.6.0
     */
    private static $active;

    /**
     * Get the active joomla user.
     *
     * @return  static
     */
    public static function active()
    {
        if (null === self::$active) {
            $userId = (int) Factory::getUser()->get('id');

            self::$active = $userId !== 0 ? static::find($userId) : new static();
        }

        return self::$active;
    }

    /**
     * Add this user to an UserGroup.
     *
     * @param   int  $userGroupId  User group to add the user to
     *
     * @return  void
     *
     * @since   1.3.0
     */
    public function addToUserGroup(int $userGroupId)
    {
        $this->addToUserGroups([$userGroupId]);
    }

    /**
     * Add this user to a list of UserGroups.
     *
     * @param   int[]  $userGroupsIds  An array of user groups identifiers
     *
     * @return  void
     *
     * @since   1.3.0
     */
    public function addToUserGroups(array $userGroupsIds)
    {
        $userGroupsIds = array_unique(
            array_filter(
                ArrayHelper::toInteger($userGroupsIds)
            )
        );

        $currentIds = $this->userGroupsIds();
        $newIds = array_diff($userGroupsIds, $currentIds);

        if ($newIds === []) {
            return;
        }

        $groupsIds = array_unique(
            array_filter(
                array_merge($userGroupsIds, $currentIds)
            )
        );

        $this->assign('groups', $groupsIds);
        $this->save();
        $this->clearUserGroups();
    }

    /**
     * Proxy to JUser::authorise().
     *
     * @param   string  $action     The name of the action to check for permission.
     * @param   string  $assetname  The name of the asset on which to perform the action.
     *
     * @return  bool
     */
    public function authorise($action, $assetname = null)
    {
        if ($this->isRoot()) {
            return true;
        }

        try {
            return $this->joomlaUser()->authorise($action, $assetname);
        } catch (\Exception $exception) {
            return false;
        }
    }

    /**
     * Can this user administrate a component?
     *
     * @param   string  $component  Component to check for admin permission
     *
     * @return  bool
     */
    public function canAdmin($component)
    {
        if ($this->isRoot()) {
            return true;
        }

        return $this->authorise('core.admin', $component);
    }

    /**
     * Change this user password.
     *
     * @param   string  $newPassword  New password to assign
     *
     * @return  void
     *
     * @since   1.5.0
     */
    public function changePassword($newPassword)
    {
        if (!$this->hasId()) {
            throw new \RuntimeException('Trying to change password for unsaved user', 500);
        }

        $newPassword = trim($newPassword);

        if ($newPassword === '' || $newPassword === '0') {
            throw new \InvalidArgumentException('Cannot assign empty password to user');
        }

        $this->bind(
            [
                'password'     => UserHelper::hashPassword($newPassword),
                'raw_password' => $newPassword,
            ]
        );

        $this->save();
    }

    /**
     * Clear the active user.
     *
     * @return  void
     *
     * @since   1.6.0
     */
    public static function clearActive()
    {
        self::$active = null;
    }

    /**
     * Get the list of column aliases.
     *
     * @return  array
     */
    public function columnAliases()
    {
        return [
            Column::OWNER  => 'id',
        ];
    }

    /**
     * Get an array of the authorised access levels for this user.
     *
     * @return  array
     */
    public function getAuthorisedViewLevels()
    {
        try {
            return array_values(
                array_unique(
                    $this->joomlaUser()->getAuthorisedViewLevels()
                )
            );
        } catch (\Exception $exception) {
            return [];
        }
    }

    /**
     * Check if current user has been activated.
     *
     * @return  bool
     */
    public function isActivated()
    {
        if (!$this->hasId()) {
            return false;
        }

        return in_array($this->get('activation'), ['', '0']);
    }

    /**
     * Check if this user is active.
     *
     * @return  bool
     */
    public function isActive()
    {
        return !$this->isBlocked() && $this->isActivated();
    }

    /**
     * Check if this user is blocked.
     *
     * @return  bool
     */
    public function isBlocked()
    {
        if (!$this->hasId()) {
            return false;
        }

        return 1 === (int) $this->get('block');
    }

    /**
     * Is this user a guest?
     *
     * @return  bool
     */
    public function isGuest()
    {
        if (!$this->hasId()) {
            return true;
        }

        return 1 === (int) $this->joomlaUser()->get('guest');
    }

    /**
     * Check if this user is super user.
     *
     * @return  bool
     */
    public function isRoot()
    {
        if (null === $this->isRoot) {
            $this->isRoot = $this->joomlaUser()->authorise('core.admin');
        }

        return $this->isRoot;
    }

    /**
     * \Joomla\CMS\Factory::getUser() proxy for testing purposes
     *
     * @return  \JUser object
     */
    public function joomlaUser()
    {
        $jUser = $this->juser($this->id());

        if ((int) $jUser->get('id') !== $this->id()) {
            throw new \RuntimeException(sprintf('User (id: `%s`) does not exist', $this->id()));
        }

        return $jUser;
    }

    /**
     * Load an entity from columns data.
     *
     * @param   array   $data  Data to load the entity
     *
     * @return  false|static
     *
     * @since   1.6.0
     */
    public static function loadFromData(array $data)
    {
        $static = new static();
        $jDatabaseDriver = $static->getDbo();
        $table = $static->table();

        $query = $jDatabaseDriver->getQuery(true)
            ->select('*')
            ->from($table->getTableName());

        $fields = array_keys($table->getProperties());

        foreach ($data as $field => $value) {
            if (!in_array($field, $fields)) {
                throw new \UnexpectedValueException(sprintf('Missing field in database: %s &#160; %s.', get_class($table), $field));
            }

            $query->where($jDatabaseDriver->qn($field).' = '.$jDatabaseDriver->q($value));
        }

        $jDatabaseDriver->setQuery($query);

        $row = $jDatabaseDriver->loadAssoc();

        // Check that we have a result.
        if (empty($row)) {
            return false;
        }

        return $static->bind($row);
    }

    /**
     * Removes this user from all assigned user groups.
     *
     * @return  void
     */
    public function removeFromAllUserGroups()
    {
        if ($this->hasId()) {
            $db = $this->getDbo();

            $query = $db->getQuery(true)
                ->delete('#__user_usergroup_map')
                ->where($db->qn('user_id').' = '.$this->id());

            $db->setQuery($query);
            $db->execute();
        }

        $this->assign('groups', []);
        $this->clearUserGroups();
    }

    /**
     * Remove this user from an UserGroup.
     *
     * @param   int  $userGroupId  ID of the UserGroup to remove
     *
     * @return  void
     *
     * @since   1.3.0
     */
    public function removeFromUserGroup(int $userGroupId)
    {
        $this->removeFromUserGroups([$userGroupId]);
    }

    /**
     * Remove this user from an user group.
     *
     * @param   int[]  $userGroupsIds  Array of user groups identifiers
     *
     * @return  void
     *
     * @since   1.3.0
     */
    public function removeFromUserGroups(array $userGroupsIds)
    {
        $userGroupsIds = array_unique(
            array_filter(
                ArrayHelper::toInteger($userGroupsIds)
            )
        );

        $currentIds = $this->userGroupsIds();
        $removableIds = array_intersect($currentIds, $userGroupsIds);

        if ($removableIds === []) {
            return;
        }

        $newGroups = array_diff($currentIds, $removableIds);

        // Someone decided in Joomla that you cannot save a user without groups....
        if ($newGroups === []) {
            $this->removeFromAllUserGroups();

            return;
        }

        $this->assign('groups', $newGroups);
        $this->clearUserGroups();
        $this->save();
    }

    /**
     * Save entity to the database.
     *
     * @return  self
     *
     * @throws  SaveException
     *
     * @since   1.6.0
     */
    public function save()
    {
        $isNew = !$this->hasId();

        $this->importPlugins();

        try {
            parent::save();
        } catch (\Exception $exception) {
            $this->dispatcher()->trigger(
                'onUserAfterSave',
                [
                    $this->all(), $isNew, false, $exception->getMessage(),
                ]
            );

            throw $exception;
        }

        $this->dispatcher()->trigger(
            'onUserAfterSave',
            [
                $this->all(), $isNew, true, '',
            ]
        );

        return $this;
    }

    /**
     * Set the active user.
     *
     * @param   static  $user  User that will be set as active
     *
     * @return  void
     *
     * @since   1.6.0
     */
    public static function setActive(self $user)
    {
        self::$active = $user;
    }

    /**
     * Get a table instance. Defauts to \JTableUser.
     *
     * @param   string  $name     Table name. Optional.
     * @param   string  $prefix   Class prefix. Optional.
     * @param   array   $options  Configuration array for the table. Optional.
     *
     * @return  \JTable
     *
     * @throws  \InvalidArgumentException
     */
    public function table($name = '', $prefix = null, $options = [])
    {
        $name = $name ?: 'User';
        $prefix = $prefix ?: 'JTable';

        return parent::table($name, $prefix, $options);
    }

    /**
     * Get an array of user groups associated to this user.
     *
     * @return  array
     *
     * @since   1.3.0
     */
    public function userGroupsIds()
    {
        return array_values(
            array_unique(
                array_filter(
                    ArrayHelper::toInteger((array) $this->get('groups'))
                )
            )
        );
    }

    /**
     * Get the plugin types that will be used by this entity.
     *
     * @return  array
     *
     * @since   1.6.0
     */
    protected function eventsPlugins()
    {
        return array_merge(parent::eventsPlugins(), ['user']);
    }

    /**
     * Load associated user groups from DB.
     *
     * @return  Collection
     */
    protected function loadUserGroups()
    {
        $collection = new Collection();

        if (!$this->hasId()) {
            return $collection;
        }

        $jDatabaseDriver = $this->getDbo();
        $query = $jDatabaseDriver->getQuery(true)
            ->select('ug.*')
            ->from($jDatabaseDriver->qn('#__usergroups', 'ug'))
            ->innerJoin(
                $jDatabaseDriver->qn('#__user_usergroup_map', 'ugm')
                .' ON '.$jDatabaseDriver->qn('ugm.group_id').' = '.$jDatabaseDriver->qn('ug.id')
            )
            ->where($jDatabaseDriver->qn('ugm.user_id').' = '.(int) $this->id);

        $jDatabaseDriver->setQuery($query);

        $items = $jDatabaseDriver->loadObjectList() ?: [];

        foreach ($items as $item) {
            $userGroup = UserGroup::find($item->id)->bind($item);

            $collection->add($userGroup);
        }

        return $collection;
    }

    /**
     * Load associated view levels.
     *
     * @return  Collection
     *
     * @since   1.2.0
     */
    protected function loadViewLevels()
    {
        $collection = new Collection();

        foreach ($this->getAuthorisedViewLevels() as $id) {
            $collection->add(ViewLevel::find($id));
        }

        return $collection;
    }
}
