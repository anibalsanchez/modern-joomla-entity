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
 * Check that a string is present in another string.
 *
 * @since   1.0.0
 */
class SubstrCount extends Rule implements RuleContract
{
    /**
     * String to search for.
     *
     * @var  string
     */
    protected $substr;

    /**
     * Constructor
     *
     * @param   string  $substr  String to search for
     * @param   mixed   $name    Name of this rule
     */
    public function __construct($substr, $name = null)
    {
        parent::__construct($name);

        $this->substr = $substr;
    }

    /**
     * Check if a value is valid.
     *
     * @param   mixed  $value  Value to check
     *
     * @return  bool
     */
    public function passes($value)
    {
        return substr_count($value, $this->substr) > 0;
    }
}
