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

namespace Extly\Joomla\Entity\Tests\Users\Traits;

use Extly\Joomla\Entity\Tests\Users\Traits\Stubs\EntityWithOwner;
use Extly\Joomla\Entity\Users\User;

/**
 * HasOwner trait tests.
 *
 * @since   1.1.0
 */
class HasOwnerWithCustomColumnTest extends HasOwnerTest
{
    /**
     * Name of the owner column.
     *
     * @const
     */
    public const OWNER_COLUMN = 'owner_id';
}
