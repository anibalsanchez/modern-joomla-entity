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

namespace Extly\Joomla\Entity;

defined('_JEXEC') || die;

use Extly\Joomla\Entity\Contracts\ComponentEntityInterface;
use Extly\Joomla\Entity\Core\Traits as CoreTraits;

/**
 * Entity class.
 *
 * @since   1.0.0
 */
abstract class ComponentEntity extends Entity implements ComponentEntityInterface
{
    use CoreTraits\HasComponent;
}
