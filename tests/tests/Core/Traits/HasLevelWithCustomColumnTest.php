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

defined('_JEXEC') || die;

use Extly\Joomla\Entity\Tests\Core\Traits\HasLevelTest;

/**
 * HasLevel tests.
 *
 * @since   1.4.0
 */
class HasLevelWithCustomColumnTest extends HasLevelTest
{
    /**
     * Name of the column used to store level.
     *
     * @const
     */
    public const LEVEL_COLUMN = 'custom_level';
}
