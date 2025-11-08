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

namespace Extly\Joomla\Entity\Validation;

defined('_JEXEC') || die;

use Extly\Joomla\Entity\Decorator;
use Extly\Joomla\Entity\Validation\Contracts\Validator as ValidatorContract;

/**
 * Entity validator.
 *
 * @since   1.0.0
 */
abstract class Rule
{
    /**
     * Id of this rule.
     *
     * @var  string
     */
    protected $id;

    /**
     * Name of this rule.
     *
     * @var  string
     */
    protected $name;

    /**
     * Constructor
     *
     * @param   mixed  $name  Name of this rule
     */
    public function __construct($name = null)
    {
        $this->name = $name;
    }

    /**
     * Check if a value is not valid.
     *
     * @param   mixed  $value  Value to check
     *
     * @return  bool
     */
    public function fails($value)
    {
        return !$this->passes($value);
    }

    /**
     * Id of this rule.
     *
     * @return  string
     */
    public function id()
    {
        if (null === $this->id) {
            $this->id = spl_object_hash($this);
        }

        return $this->id;
    }

    /**
     * Name of this rule.
     *
     * @return  string
     */
    public function name()
    {
        return null === $this->name ? get_class($this) : \Joomla\CMS\Language\Text::_($this->name);
    }
}
