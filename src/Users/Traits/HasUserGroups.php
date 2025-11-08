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

namespace Extly\Joomla\Entity\Users\Traits;

defined('_JEXEC') || die;

/**
 * Trait for entities that have associated user groups.
 *
 * @since  1.0.0
 */
trait HasUserGroups
{
    /**
     * Associated user groups.
     *
     * @var  Collection
     */
    protected $userGroups;

    /**
     * Clear already loaded userGroups.
     *
     * @return  self
     */
    public function clearUserGroups()
    {
        $this->userGroups = null;

        return $this;
    }

    /**
     * Get the associated user groups.
     *
     * @return  Collection
     */
    public function userGroups()
    {
        if (null === $this->userGroups) {
            $this->userGroups = $this->loadUserGroups();
        }

        return $this->userGroups;
    }

    /**
     * Check if this entity has an associated user group.
     *
     * @param   int   $id  User identifier
     *
     * @return  bool
     */
    public function hasUserGroup($id)
    {
        return $this->userGroups()->has($id);
    }

    /**
     * Check if this entity has associated user groups.
     *
     * @return  bool
     */
    public function hasUserGroups()
    {
        return !$this->userGroups()->isEmpty();
    }

    /**
     * Load associated user groups from DB.
     *
     * @return  Collection
     */
    abstract protected function loadUserGroups();
}
