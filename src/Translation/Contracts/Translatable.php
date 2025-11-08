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

namespace Extly\Joomla\Entity\Translation\Contracts;

use Extly\Joomla\Entity\Contracts\EntityInterface;

defined('_JEXEC') || die;

/**
 * Describes methods required by translatable entities.
 *
 * @since  1.0.0
 */
interface Translatable extends EntityInterface
{
    /**
     * Get a translation.
     *
     * @param   string  $langTag  Language string. Example: es-ES
     *
     * @return  static
     *
     * @throws  \InvalidArgumentException
     */
    public function translation($langTag);
}
