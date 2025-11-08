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

use Extly\Joomla\Entity\Users\Column;
use Extly\Joomla\Entity\Users\User;

/**
 * Trait for entities with an owner.
 *
 * @since   1.0.0
 */
trait HasOwner
{
    /**
     * Onwer of this entity.
     *
     * @var  User
     */
    protected $owner;

    /**
     * Get the owner of this entity.
     *
     * @param   bool  $reload  Force data reloading
     *
     * @return  User
     */
    public function owner($reload = false)
    {
        if ($reload || null === $this->owner) {
            $this->owner = $this->loadOwner();
        }

        return $this->owner;
    }

    /**
     * Check if this entity has an owner.
     *
     * @return  bool
     */
    public function hasOwner()
    {
        if (!$this->has($this->columnAlias(Column::OWNER))) {
            return false;
        }

        return 0 !== (int) $this->get($this->columnAlias(Column::OWNER));
    }

    /**
     * Check if an user is this entity owner.
     *
     * @param   User  $user  User to check for ownership. Defaults to active user.
     *
     * @return  bool
     */
    public function isOwner(?User $user = null)
    {
        $user = $user ?: User::active();

        if ($user->isGuest() || !$this->hasOwner()) {
            return false;
        }

        return $this->owner()->id() === $user->id();
    }

    /**
     * Load owner from DB.
     *
     * @return  User
     *
     * @throws  \InvalidArgumentException
     */
    protected function loadOwner()
    {
        $ownerId = (int) $this->get($this->columnAlias(Column::OWNER));

        if ($ownerId === 0) {
            $msg = sprintf('Entity %s does not have an owner', get_class($this));

            throw new \InvalidArgumentException($msg);
        }

        return User::find($ownerId);
    }
}
