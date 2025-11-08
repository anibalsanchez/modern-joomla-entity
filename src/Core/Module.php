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

namespace Extly\Joomla\Entity\Core;

defined('_JEXEC') || die;

use Extly\Joomla\Entity\ComponentEntity;
use Extly\Joomla\Entity\Core\Traits;

/**
 * Represents and entry from the #__modules table.
 *
 * @since   1.4.0
 */
class Module extends ComponentEntity
{
    use Traits\HasAccess;
    use Traits\HasAsset;
    use Traits\HasClient;
    use Traits\HasParams;
    use Traits\HasPublishDown;
    use Traits\HasPublishUp;
    use Traits\HasState;

    /**
     * Load this module assigned menu ids.
     *
     * @var  array
     */
    private $menusIds;

    /**
     * Get the menus this module is shown.
     *
     * @param   bool  $reload  Force to reload data from DB.
     *
     * @return  array
     */
    public function menusIds($reload = false)
    {
        if ($reload || null === $this->menusIds) {
            $this->menusIds = $this->loadMenusIds();
        }

        return $this->menusIds;
    }

    /**
     * Check if this entity is published.
     *
     * @return  bool
     */
    public function isPublished()
    {
        if (!$this->isOnState(self::STATE_PUBLISHED)) {
            return false;
        }

        return $this->isPublishedUp() && !$this->isPublishedDown();
    }

    /**
     * Check if this module is published on a specific menu item.
     *
     * @param   int  $menuId  Menu identifier
     *
     * @return  bool
     */
    public function isPublishedInMenu($menuId)
    {
        $menuId = (int) $menuId;
        $menusIds = $this->menusIds();

        if (in_array(0, $menusIds, true)) {
            return true;
        }

        if (!$menusIds || 0 === $menuId) {
            return false;
        }

        $assignedMenuId = reset($menusIds);

        return $assignedMenuId > 0 ? in_array($menuId, $menusIds, true) : !in_array(-1 * $menuId, $menusIds, true);
    }

    /**
     * Check if this entity is unpublished.
     *
     * @return  bool
     */
    public function isUnpublished()
    {
        return !$this->isPublished();
    }

    /**
     * Get a table.
     *
     * @param   string  $name     The table name. Optional.
     * @param   string  $prefix   The class prefix. Optional.
     * @param   array   $options  Configuration array for model. Optional.
     *
     * @return  \Joomla\CMS\Table\Table
     *
     * @codeCoverageIgnore
     */
    public function table($name = '', $prefix = null, $options = [])
    {
        $name = $name ?: 'Module';
        $prefix = $prefix ?: 'JTable';

        return parent::table($name, $prefix, $options);
    }

    /**
     * Load assigned menus ids from database.
     *
     * @return  int[]
     */
    private function loadMenusIds()
    {
        if (!$this->hasId()) {
            return [];
        }

        $jDatabaseDriver = $this->getDbo();

        $query = $jDatabaseDriver->getQuery(true)
            ->select('menuid')
            ->from($jDatabaseDriver->qn('#__modules_menu'))
            ->where($jDatabaseDriver->qn('moduleid').' = '.(int) $this->id());

        $jDatabaseDriver->setQuery($query);

        return array_map('intval', $jDatabaseDriver->loadColumn() ?: []);
    }
}
