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

namespace Extly\Joomla\Entity\Tests\Fields\Traits\Stubs;

use Extly\Joomla\Entity\ComponentEntity;
use Extly\Joomla\Entity\Fields\Traits\HasFields;

/**
 * Entity to test HasFields trait.
 *
 * @since  1.1.0
 */
class EntityWithFields extends ComponentEntity
{
    use HasFields;
}
