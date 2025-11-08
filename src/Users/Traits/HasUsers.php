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
 * Trait for entities that have associated users.
 *
 * @since  1.0.0
 */
trait HasUsers
{
    /**
     * Associated users.
     *
     * @var  Collection
     */
    protected $users;

    /**
     * Clear already loaded users.
     *
     * @return  self
     */
    public function clearUsers()
    {
        $this->users = null;

        return $this;
    }

    /**
     * Get the associated users.
     *
     * @return  Collection
     */
    public function users()
    {
        if (null === $this->users) {
            $this->users = $this->loadUsers();
        }

        return $this->users;
    }

    /**
     * Check if this entity has an associated user.
     *
     * @param   int   $id  User identifier
     *
     * @return  bool
     */
    public function hasUser($id)
    {
        return $this->users()->has($id);
    }

    /**
     * Check if this entity has associated users.
     *
     * @return  bool
     */
    public function hasUsers()
    {
        return !$this->users()->isEmpty();
    }

    /**
     * Load associated users from DB.
     *
     * @return  Collection
     */
    abstract protected function loadUsers();
}
