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

namespace Extly\Joomla\Entity\Tests\Core\Traits\Stubs;

use Extly\Joomla\Entity\Core\Traits\HasComponent;
use Extly\Joomla\Entity\Entity;

/**
 * Sample class to test HasComponent trait.
 *
 * @since  1.1.0
 */
class ClassWithComponent extends Entity
{
    use HasComponent;
}
