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

use Extly\Joomla\Entity\Collection;
use Extly\Joomla\Entity\ComponentEntity;
use Extly\Joomla\Entity\Users\Traits\HasUsers;
use Extly\Joomla\Entity\Users\User;

/**
 * User Group entity.
 *
 * @since   1.0.0
 */
class UserGroup extends ComponentEntity
{
    use HasUsers;

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
        $name = $name ?: 'Usergroup';
        $prefix = $prefix ?: 'JTable';

        return parent::table($name, $prefix, $options);
    }

    /**
     * Load associated users from DB.
     *
     * @return  Collection
     */
    protected function loadUsers()
    {
        if (!$this->hasId()) {
            return new Collection();
        }

        $users = array_map(
            fn ($item) => User::find($item->id)->bind($item),
            $this->usersModel()->getItems() ?: []
        );

        return new Collection($users);
    }

    /**
     * Get an instance of the users model.
     *
     * @return  \UsersModelUsersModel
     */
    protected function usersModel()
    {
        \Joomla\CMS\MVC\Model\BaseDatabaseModel::addIncludePath(JPATH_ADMINISTRATOR.'/components/com_users/models', 'UsersModel');

        $model = \Joomla\CMS\MVC\Model\BaseDatabaseModel::getInstance('Users', 'UsersModel', ['ignore_request' => true]);

        if ($this->hasId()) {
            $model->setState('filter.group_id', $this->id());
        }

        return $model;
    }
}
