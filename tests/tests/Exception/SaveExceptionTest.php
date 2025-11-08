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

use Extly\Joomla\Entity\Exception\SaveException;
use Extly\Joomla\Entity\Tests\Stubs\Entity;
use Extly\Joomla\Entity\Validation\Exception\ValidationException;
use Extly\Joomla\Entity\Validation\Rule;

/**
 * SaveException tests.
 *
 * @since   1.1.0
 */
class SaveExceptionTest extends \TestCase
{
    /**
     * table returns SaveException.
     *
     * @return  void
     */
    public function testTableReturnsSaveException()
    {
        $entity = new Entity(999);

        $table = $this->getMockBuilder(\JTable::class)
            ->disableOriginalConstructor()
            ->setMethods(['getError'])
            ->getMock();

        $table->method('getError')
            ->willReturn('Save failed');

        $saveException = SaveException::table($entity, $table);

        $this->assertInstanceOf(SaveException::class, $saveException);
        $this->assertTrue(strlen($saveException->getMessage()) > 0);
    }

    /**
     * validation returns SaveException.
     *
     * @return  void
     */
    public function testValidationReturnsSaveException()
    {
        $entity = new Entity(999);
        $validationException = new ValidationException('Something went wrong');

        $exception = SaveException::validation($entity, $validationException);

        $this->assertInstanceOf(SaveException::class, $exception);
        $this->assertTrue(strlen($exception->getMessage()) > 0);

        $entity = new Entity();
        $validationException = new ValidationException('Something went wrong');

        $exception = SaveException::validation($entity, $validationException);

        $this->assertInstanceOf(SaveException::class, $exception);
        $this->assertTrue(strlen($exception->getMessage()) > 0);
    }
}
