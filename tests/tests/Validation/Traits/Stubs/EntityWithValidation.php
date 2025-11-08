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

namespace Extly\Joomla\Entity\Tests\Validation\Traits\Stubs;

use Extly\Joomla\Entity\ComponentEntity;
use Extly\Joomla\Entity\Validation\Contracts\Validable;
use Extly\Joomla\Entity\Validation\Traits\HasValidation;

/**
 * Entity to test HasValidation trait.
 *
 * @since  1.1.0
 */
class EntityWithValidation extends ComponentEntity implements Validable
{
    use HasValidation;
}
