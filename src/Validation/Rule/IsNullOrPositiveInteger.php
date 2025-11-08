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

namespace Extly\Joomla\Entity\Validation\Rule;

defined('_JEXEC') || die;

use Extly\Joomla\Entity\Validation\Contracts\Rule as RuleContract;
use Extly\Joomla\Entity\Validation\Exception\ValidationException;
use Extly\Joomla\Entity\Validation\Rule;

/**
 * Check that value is null or a positve integer.
 *
 * @since   1.7.0
 */
class IsNullOrPositiveInteger extends Rule implements RuleContract
{
    /**
     * Check if a value is valid.
     *
     * @param   mixed  $value  Value to check
     *
     * @return  bool
     */
    public function passes($value)
    {
        $isNull = new IsNull($this->name);

        if ($isNull->passes($value)) {
            return true;
        }

        $isPositiveInteger = new IsPositiveInteger($this->name);

        return $isPositiveInteger->passes($value);
    }
}
