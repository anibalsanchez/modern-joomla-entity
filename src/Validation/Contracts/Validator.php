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
 * Validator requirements.
 *
 * @since   1.0.0
 */
interface Validator
{
    /**
     * Validate entity.
     *
     * @return  bool
     *
     * @throws  ValidationException
     */
    public function validate();

    /**
     * Validate a column value.
     *
     * @param   string  $column  Column to check value against.
     * @param   mixed   $value   Value for the column.
     *
     * @return  bool
     *
     * @throws  ValidationException
     */
    public function validateColumnValue($column, $value);

    /**
     * Check if the entity is valid.
     *
     * @return  bool
     */
    public function isValid();

    /**
     * Check if a value is valid for a specific column.
     *
     * @param   string  $column  Column to validate against
     * @param   mixed   $value   Value to check
     *
     * @return  bool
     */
    public function isValidColumnValue($column, $value);
}
