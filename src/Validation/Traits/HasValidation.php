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

namespace Extly\Joomla\Entity\Validation\Traits;

defined('_JEXEC') || die;

use Extly\Joomla\Entity\Validation\Contracts\Validator as ValidatorContract;
use Extly\Joomla\Entity\Validation\Exception\ValidationException;
use Extly\Joomla\Entity\Validation\Validator;

/**
 * Trait for entities with validation.
 *
 * @since   1.0.0
 */
trait HasValidation
{
    /**
     * Associated validator.
     *
     * @var  ValidatorContract
     */
    protected $validator;

    /**
     * Check if this entity is valid.
     *
     * @return  bool
     */
    public function isValid()
    {
        return $this->validator()->isValid();
    }

    /**
     * Set validator.
     *
     * @param ValidatorContract $validatorContract Validator to use
     *
     * @return  self
     */
    public function setValidator(ValidatorContract $validatorContract)
    {
        $this->validator = $validatorContract;

        return $this;
    }

    /**
     * Retrieve entity validator.
     *
     * @return  ValidatorContract
     */
    public function validator()
    {
        if (null === $this->validator) {
            $this->validator = new Validator($this);
        }

        return $this->validator;
    }

    /**
     * Validate this entity.
     *
     * @return  bool
     *
     * @throws  ValidationException
     */
    public function validate()
    {
        return $this->validator()->validate();
    }
}
