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

namespace Extly\Joomla\Entity\Tests\Users\Traits\Stubs;

use Extly\Joomla\Entity\Entity;
use Extly\Joomla\Entity\Users\Contracts\Ownerable;
use Extly\Joomla\Entity\Users\Traits\HasOwner;
use Extly\Joomla\Entity\Users\User;

/**
 * Sample class to test HasOwner trait.
 *
 * @since  1.1.0
 *
 * @codeCoverageIgnore
 */
class EntityWithOwner extends Entity implements Ownerable
{
    use HasOwner;

    /**
     * Expected active user.
     *
     * @var  User
     */
    public $activeUser;

    /**
     * Retrieve active user.
     *
     * @return  User
     */
    private function activeUser()
    {
        return $this->activeUser ?: new User();
    }
}
