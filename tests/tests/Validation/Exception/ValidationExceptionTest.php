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

namespace Extly\Joomla\Entity\Tests\Validation\Exception;

use Extly\Joomla\Entity\Tests\Stubs\Entity;
use Extly\Joomla\Entity\Validation\Exception\ValidationException;
use Extly\Joomla\Entity\Validation\Rule;

/**
 * ValidationException tests.
 *
 * @since   1.1.0
 */
class ValidationExceptionTest extends \TestCase
{
    /**
     * invalidEntity returns ValidationException.
     *
     * @return  void
     */
    public function testInvalidEntityReturnsValidationException()
    {
        $entity = new Entity(999);
        $errors = [
            '`alias` cannot be empty',
            '`column` is wrong',
        ];

        $validationException = ValidationException::invalidEntity($entity, $errors);

        $this->assertInstanceOf(ValidationException::class, $validationException);
        $this->assertTrue(strlen($validationException->getMessage()) > 0);
    }

    /**
     * invalidColumn returns ValidationException.
     *
     * @return  void
     */
    public function testInvalidColumnReturnsValidationException()
    {
        $entity = new Entity(999);
        $failedRules = [
            new Rule\IsPositiveInteger('Is not a positive integer'),
            new Rule\IsNotEmptyString('Cannot be an empty string'),
        ];

        $validationException = ValidationException::invalidColumn('sample_column', $failedRules);

        $this->assertInstanceOf(ValidationException::class, $validationException);
        $this->assertTrue(strlen($validationException->getMessage()) > 0);
    }
}
