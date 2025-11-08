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

namespace Extly\Joomla\Entity\Tests\Validation\Traits;

use Extly\Joomla\Entity\Tests\Validation\Traits\Stubs\EntityWithValidation;
use Extly\Joomla\Entity\Validation\Validator;

/**
 * HasValidation trait tests.
 *
 * @since   1.1.0
 */
class HasValidationTest extends \PHPUnit\Framework\TestCase
{
    /**
     * isValid returns validator isValid.
     *
     * @return  void
     */
    public function testIsValidReturnsValidatorIsValid()
    {
        $phpUnitFrameworkMockObjectMockObject = $this->getMockBuilder('MockedValidator')
            ->setMethods(['isValid'])
            ->getMock();

        $phpUnitFrameworkMockObjectMockObject->method('isValid')
            ->will($this->onConsecutiveCalls(false, true));

        $entity = $this->getMockBuilder(EntityWithValidation::class)
            ->setMethods(['validator'])
            ->getMock();

        $entity->method('validator')
            ->willReturn($phpUnitFrameworkMockObjectMockObject);

        $this->assertFalse($entity->isValid());
        $this->assertTrue($entity->isValid());
    }

    /**
     * setValidator sets validator.
     *
     * @return  void
     */
    public function testSetValidatorSetsValidator()
    {
        $entityWithValidation = new EntityWithValidation(999);

        $reflection = new \ReflectionClass($entityWithValidation);
        $validatorProperty = $reflection->getProperty('validator');
        $validatorProperty->setAccessible(true);

        $this->assertSame(null, $validatorProperty->getValue($entityWithValidation));

        $validator = new Validator($entityWithValidation);
        $entityWithValidation->setValidator($validator);

        $reflection = new \ReflectionClass($entityWithValidation);
        $validatorProperty = $reflection->getProperty('validator');
        $validatorProperty->setAccessible(true);

        $this->assertSame($validator, $validatorProperty->getValue($entityWithValidation));
    }

    /**
     * validator returns correct value.
     *
     * @return  void
     */
    public function testValidatorReturnsCorrectValue()
    {
        $entityWithValidation = new EntityWithValidation(999);

        $customValidator = new Validator($entityWithValidation);
        $this->assertEquals(new Validator($entityWithValidation), $entityWithValidation->validator());

        $reflectionClass = new \ReflectionClass($entityWithValidation);
        $reflectionProperty = $reflectionClass->getProperty('validator');
        $reflectionProperty->setAccessible(true);
        $reflectionProperty->setValue($entityWithValidation, $customValidator);

        $this->assertSame($customValidator, $entityWithValidation->validator());
    }

    /**
     * validate returns validator validate.
     *
     * @return  void
     */
    public function testValidateReturnsValidatorValidate()
    {
        $phpUnitFrameworkMockObjectMockObject = $this->getMockBuilder('MockedValidator')
            ->setMethods(['validate'])
            ->getMock();

        $phpUnitFrameworkMockObjectMockObject->method('validate')
            ->will($this->onConsecutiveCalls(false, true));

        $entity = $this->getMockBuilder(EntityWithValidation::class)
            ->setMethods(['validator'])
            ->getMock();

        $entity->method('validator')
            ->willReturn($phpUnitFrameworkMockObjectMockObject);

        $this->assertFalse($entity->validate());
        $this->assertTrue($entity->validate());
    }
}
