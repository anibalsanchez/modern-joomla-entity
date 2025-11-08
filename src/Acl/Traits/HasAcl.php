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

namespace Extly\Joomla\Entity\Acl\Traits;

defined('_JEXEC') || die;

use Extly\Joomla\Entity\Acl\Acl;
use Extly\Joomla\Entity\Users\User;

/**
 * Trait for entities with ACL.
 *
 * @since   1.0.0
 */
trait HasAcl
{
    /**
     * Acl instance.
     *
     * @param   User|null  $user  User to check ACL against.
     *
     * @return  Acl
     */
    public function acl(?User $user = null)
    {
        return new Acl($this, $user);
    }

    /**
     * Get the ACL prefix applied to this entity
     *
     * @return  string
     */
    public function aclPrefix()
    {
        return 'core';
    }

    /**
     * Get the identifier of the associated asset
     *
     * @return  string
     */
    public function aclAssetName()
    {
        if ($this->hasId()) {
            return $this->component()->option().'.'.$this->name().'.'.$this->id();
        }

        return $this->component()->option();
    }
}
