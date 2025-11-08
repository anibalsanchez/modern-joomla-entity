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

namespace Extly\Joomla\Entity\Tests\Core\Traits;

use Extly\Joomla\Entity\Tests\Core\Traits\Stubs\EntityWithState;

/**
 * HasState trait tests.
 *
 * @since   1.1.0
 */
class HasStateWithCustomColumnTest extends HasStateTest
{
    /**
     * Column to use to load/store state.
     *
     * @const
     */
    public const COLUMN_STATE = 'state';
}
