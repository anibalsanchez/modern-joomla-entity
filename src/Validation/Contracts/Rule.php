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
 * Rule requirements.
 *
 * @since   1.0.0
 */
interface Rule
{
    /**
     * Id of this rule.
     *
     * @return  string
     */
    public function id();

    /**
     * Name of this rule.
     *
     * @return  string
     */
    public function name();

    /**
     * Check if a value is valid.
     *
     * @param   mixed  $value  Value to check
     *
     * @return  bool
     */
    public function passes($value);

    /**
     * Check if a value is not valid.
     *
     * @param   mixed  $value  Value to check
     *
     * @return  bool
     */
    public function fails($value);
}
