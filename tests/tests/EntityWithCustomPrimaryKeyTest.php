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

namespace Extly\Joomla\Entity\Tests;

use Extly\Joomla\Entity\Tests\Stubs\EntityWithCustomPrimaryKey;

/**
 * Entity test.
 *
 * @since   1.1.0
 */
class EntityWithCustomPrimaryKeyTest extends EntityTest
{
    /**
     * Name of the primary key
     *
     * @const
     */
    public const PRIMARY_KEY = 'entity_id';
}
