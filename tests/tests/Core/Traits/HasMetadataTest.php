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

namespace Extly\Joomla\Entity\Tests\Core\Traits;

use Extly\Joomla\Entity\Core\Column;
use Extly\Joomla\Entity\Tests\Core\Traits\Stubs\EntityWithMetadata;

/**
 * HasMetadata trait tests.
 *
 * @since   1.1.0
 */
class HasMetadataTest extends \PHPUnit\Framework\TestCase
{
    /**
     * getMetadata returns correct value.
     *
     * @return  void
     */
    public function testGetMetadataReturnsCorrectValue()
    {
        $phpUnitFrameworkMockObjectMockObject = $this->getMockBuilder(EntityWithMetadata::class)
            ->setMethods(['columnAlias'])
            ->getMock();

        $phpUnitFrameworkMockObjectMockObject->method('columnAlias')
            ->willReturn(Column::METADATA);

        $reflectionClass = new \ReflectionClass($phpUnitFrameworkMockObjectMockObject);

        $reflectionProperty = $reflectionClass->getProperty('id');
        $reflectionProperty->setAccessible(true);
        $reflectionProperty->setValue($phpUnitFrameworkMockObjectMockObject, 999);

        $rowProperty = $reflectionClass->getProperty('row');
        $rowProperty->setAccessible(true);
        $rowProperty->setValue($phpUnitFrameworkMockObjectMockObject, ['id' => 999]);

        $rowProperty->setValue($phpUnitFrameworkMockObjectMockObject, ['id' => 999, Column::METADATA => '']);

        $this->assertEquals([], $phpUnitFrameworkMockObjectMockObject->metadata(true));

        $rowProperty->setValue($phpUnitFrameworkMockObjectMockObject, ['id' => 999, Column::METADATA => '{}']);

        $this->assertEquals([], $phpUnitFrameworkMockObjectMockObject->metadata(true));

        $rowProperty->setValue($phpUnitFrameworkMockObjectMockObject, ['id' => 999, Column::METADATA => '{"robots":"","author":"","rights":"","xreference":""}']);

        $this->assertEquals([], $phpUnitFrameworkMockObjectMockObject->metadata(true));

        $rowProperty->setValue($phpUnitFrameworkMockObjectMockObject, ['id' => 999, Column::METADATA => '{"robots":"noindex, follow","author":"Roberto Segura","rights":"Creative Commons","xreference":"http:\/\/phproberto.com"}']);

        // Without reload = old data
        $this->assertEquals([], $phpUnitFrameworkMockObjectMockObject->metadata());

        $expected = [
            'robots'     => 'noindex, follow',
            'author'     => 'Roberto Segura',
            'rights'     => 'Creative Commons',
            'xreference' => 'http://phproberto.com',
        ];

        $this->assertEquals($expected, $phpUnitFrameworkMockObjectMockObject->metadata(true));

        $rowProperty->setValue($phpUnitFrameworkMockObjectMockObject, ['id' => 999, Column::METADATA => '{"robots":"noindex, follow","author":"Roberto Segura","xreference":"http:\/\/phproberto.com"}']);

        $expected = [
            'robots'     => 'noindex, follow',
            'author'     => 'Roberto Segura',
            'xreference' => 'http://phproberto.com',
        ];

        $this->assertEquals($expected, $phpUnitFrameworkMockObjectMockObject->metadata(true));

        $rowProperty->setValue($phpUnitFrameworkMockObjectMockObject, ['id' => 999, Column::METADATA => '{"author":"Roberto Segura","xreference":"http:\/\/phproberto.com"}']);

        $expected = [
            'author' => 'Roberto Segura',
            'xreference' => 'http://phproberto.com',
        ];

        $this->assertEquals($expected, $phpUnitFrameworkMockObjectMockObject->metadata(true));

        $rowProperty->setValue($phpUnitFrameworkMockObjectMockObject, ['id' => 999, Column::METADATA => '{"author":"Roberto Segura"}']);

        $expected = [
            'author' => 'Roberto Segura',
        ];

        $this->assertEquals($expected, $phpUnitFrameworkMockObjectMockObject->metadata(true));
    }
}
