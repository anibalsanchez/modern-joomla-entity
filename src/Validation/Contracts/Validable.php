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

namespace Extly\Joomla\Entity\Validation\Contracts;

defined('_JEXEC') || die;

use Extly\Joomla\Entity\Validation\Exception\ValidationException;

/**
 * Validable entities requirements.
 *
 * @since   1.0.0
 */
interface Validable
{
    /**
     * Check if this entity is valid.
     *
     * @return  bool
     */
    public function isValid();

    /**
     * Validate this entity.
     *
     * @return  bool
     *
     * @throws  ValidationException
     */
    public function validate();
}
