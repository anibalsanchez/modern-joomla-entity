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
use Extly\Joomla\Entity\Tests\Core\Traits\Stubs\EntityWithFeatured;

/**
 * HasFeatured trait tests.
 *
 * @since   1.1.0
 */
class HasFeaturedTest extends \PHPUnit\Framework\TestCase
{
    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     *
     * @return  void
     */
    protected function tearDown()
    {
        EntityWithFeatured::clearAll();

        parent::tearDown();
    }

    /**
     * isFeatured returns correct value.
     *
     * @return  void
     */
    public function testIsFeaturedReturnsCorrectValue()
    {
        $phpUnitFrameworkMockObjectMockObject = $this->getEntity(['id' => 999, Column::FEATURED => 0]);

        $reflectionClass = new \ReflectionClass($phpUnitFrameworkMockObjectMockObject);
        $reflectionProperty = $reflectionClass->getProperty('row');
        $reflectionProperty->setAccessible(true);

        $this->assertFalse($phpUnitFrameworkMockObjectMockObject->isFeatured(true));

        $reflectionProperty->setValue($phpUnitFrameworkMockObjectMockObject, ['id' => 999, Column::FEATURED => '0']);

        $this->assertFalse($phpUnitFrameworkMockObjectMockObject->isFeatured(true));

        $reflectionProperty->setValue($phpUnitFrameworkMockObjectMockObject, ['id' => 999, Column::FEATURED => '1']);

        $this->assertTrue($phpUnitFrameworkMockObjectMockObject->isFeatured(true));

        $reflectionProperty->setValue($phpUnitFrameworkMockObjectMockObject, ['id' => 999, Column::FEATURED => 1]);

        $this->assertTrue($phpUnitFrameworkMockObjectMockObject->isFeatured(true));
    }

    /**
     * Get a mocked entity.
     *
     * @param   array  $row  Row returned by the entity as data
     *
     * @return  \PHPUnit_Framework_MockObject_MockObject
     */
    private function getEntity($row = [])
    {
        $phpUnitFrameworkMockObjectMockObject = $this->getMockBuilder(EntityWithFeatured::class)
            ->setMethods(['columnAlias'])
            ->getMock();

        $phpUnitFrameworkMockObjectMockObject->method('columnAlias')
            ->willReturn('featured');

        $phpUnitFrameworkMockObjectMockObject->bind($row);

        return $phpUnitFrameworkMockObjectMockObject;
    }
}
