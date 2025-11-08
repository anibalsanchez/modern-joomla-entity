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

namespace Extly\Joomla\Entity\Users\Contracts;

defined('_JEXEC') || die;

use Extly\Joomla\Entity\Users\User;

/**
 * Describes methods required by entities with an owner.
 *
 * @since  1.0.0
 */
interface Ownerable
{
    /**
     * Get the owner of this entity.
     *
     * @return  User
     */
    public function owner();

    /**
     * Check if this entit has an owner.
     *
     * @return  bool
     */
    public function hasOwner();

    /**
     * Check if an user is this entity owner.
     *
     * @param   User  $user  User to check for ownership. Defaults to active user.
     *
     * @return  bool
     */
    public function isOwner(?User $user = null);
}
