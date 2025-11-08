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

use Extly\Joomla\Entity\Collection;
use Extly\Joomla\Entity\Entity;
use Extly\Joomla\Entity\Users\Traits\HasUsers;

/**
 * Sample class to test HasUsers traits.
 *
 * @since  1.1.0
 *
 * @codeCoverageIgnore
 */
class EntityWithUsers extends Entity
{
    use HasUsers;

    /**
     * Expected loadUsers result.
     *
     * @var  Collection
     */
    public $loadableUsers;

    /**
     * Load associated user groups from DB.
     *
     * @return  Collection
     */
    protected function loadUsers()
    {
        return $this->loadableUserGroups ?? new Collection();
    }
}
