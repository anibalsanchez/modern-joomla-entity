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
use Extly\Joomla\Entity\Validation\Rule;

/**
 * Check that a date is empty.
 *
 * @since   1.0.0
 */
class IsEmptyDate extends Rule implements RuleContract
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
        return in_array($value, [null, '', $this->nullDate()]);
    }

    /**
     * Get the empty date for the active DB driver.
     *
     * @return  string
     *
     * @codeCoverageIgnore
     */
    protected function nullDate()
    {
        return \Joomla\CMS\Factory::getDbo()->getNullDate();
    }
}
