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
use Extly\Joomla\Entity\Users\Traits\HasUserGroups;

/**
 * Sample class to test HasUserGroups traits.
 *
 * @since  1.1.0
 *
 * @codeCoverageIgnore
 */
class EntityWithUserGroups extends Entity
{
    use HasUserGroups;

    /**
     * Expected loadUserGroups result.
     *
     * @var  Collection
     */
    public $loadableUserGroups;

    /**
     * Load associated user groups from DB.
     *
     * @return  Collection
     */
    protected function loadUserGroups()
    {
        return $this->loadableUserGroups ?? new Collection();
    }
}
