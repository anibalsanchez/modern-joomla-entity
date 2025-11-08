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

use Extly\Joomla\Entity\Tests\Core\Traits\Stubs\EntityWithAccess;

/**
 * HasAccess trait tests.
 *
 * @since   1.1.0
 */
class HasAccessTest extends \PHPUnit\Framework\TestCase
{
    /**
     * canAccess returns correct value.
     *
     * @return  void
     */
    public function testCanAccessReturnsCorrectValue()
    {
        $entity = $this->getMockBuilder(EntityWithAccess::class)
            ->setMethods(['checkAccess'])
            ->getMock();

        $entity->expects($this->once())
            ->method('checkAccess')
            ->willReturn(false);

        $this->assertFalse($entity->canAccess());

        $entity = $this->getMockBuilder(EntityWithAccess::class)
            ->setMethods(['checkAccess'])
            ->getMock();

        $entity->expects($this->once())
            ->method('checkAccess')
            ->willReturn(true);

        $this->assertTrue($entity->canAccess(true));
    }

    /**
     * canAccess returns cached data.
     *
     * @return  void
     */
    public function testCanAccessReturnsCachedData()
    {
        $phpUnitFrameworkMockObjectMockObject = $this->getMockBuilder(EntityWithAccess::class)
            ->setMethods(['checkAccess'])
            ->getMock();

        $phpUnitFrameworkMockObjectMockObject->expects($this->once())
            ->method('checkAccess')
            ->willReturn(false);

        $reflectionClass = new \ReflectionClass($phpUnitFrameworkMockObjectMockObject);
        $reflectionProperty = $reflectionClass->getProperty('access');
        $reflectionProperty->setAccessible(true);

        $this->assertFalse($phpUnitFrameworkMockObjectMockObject->canAccess());
        $this->assertSame(false, $reflectionProperty->getValue($phpUnitFrameworkMockObjectMockObject));

        $reflectionProperty->setValue($phpUnitFrameworkMockObjectMockObject, true);

        $this->assertTrue($phpUnitFrameworkMockObjectMockObject->canAccess());
    }

    /**
     * access returns correct data.
     *
     * @return  void
     */
    public function testAccessReturnsCorrectValue()
    {
        $phpUnitFrameworkMockObjectMockObject = $this->getMockBuilder(EntityWithAccess::class)
            ->setMethods(['columnAlias'])
            ->getMock();

        $phpUnitFrameworkMockObjectMockObject->method('columnAlias')
            ->willReturn('access');

        $reflectionClass = new \ReflectionClass($phpUnitFrameworkMockObjectMockObject);

        $reflectionProperty = $reflectionClass->getProperty('id');
        $reflectionProperty->setAccessible(true);
        $reflectionProperty->setValue($phpUnitFrameworkMockObjectMockObject, 999);

        $rowProperty = $reflectionClass->getProperty('row');
        $rowProperty->setAccessible(true);
        $rowProperty->setValue($phpUnitFrameworkMockObjectMockObject, ['id' => 999, 'access' => 0]);

        $this->assertSame(0, $phpUnitFrameworkMockObjectMockObject->access());

        $rowProperty->setValue($phpUnitFrameworkMockObjectMockObject, ['id' => 999, 'access' => 1]);

        $this->assertSame(1, $phpUnitFrameworkMockObjectMockObject->access());

        $rowProperty->setValue($phpUnitFrameworkMockObjectMockObject, ['id' => 999, 'access' => 'nein']);

        $this->assertSame(0, $phpUnitFrameworkMockObjectMockObject->access());

        $rowProperty->setValue($phpUnitFrameworkMockObjectMockObject, ['id' => 999, 'access' => '1']);

        $this->assertSame(1, $phpUnitFrameworkMockObjectMockObject->access());
    }

    /**
     * access uses correct column.
     *
     * @return  void
     */
    public function testAccessUsesCorrectColumn()
    {
        $phpUnitFrameworkMockObjectMockObject = $this->getMockBuilder(EntityWithAccess::class)
            ->setMethods(['columnAlias'])
            ->getMock();

        $phpUnitFrameworkMockObjectMockObject->method('columnAlias')
            ->willReturn('access_level');

        $reflectionClass = new \ReflectionClass($phpUnitFrameworkMockObjectMockObject);

        $reflectionProperty = $reflectionClass->getProperty('row');
        $reflectionProperty->setAccessible(true);

        $reflectionProperty->setValue($phpUnitFrameworkMockObjectMockObject, ['id' => 999, 'access_level' => 0]);

        $this->assertSame(0, $phpUnitFrameworkMockObjectMockObject->access());

        $reflectionProperty->setValue($phpUnitFrameworkMockObjectMockObject, ['id' => 999, 'access_level' => 1]);

        $this->assertSame(1, $phpUnitFrameworkMockObjectMockObject->access());

        $reflectionProperty->setValue($phpUnitFrameworkMockObjectMockObject, ['id' => 999, 'access_level' => 'nein']);

        $this->assertSame(0, $phpUnitFrameworkMockObjectMockObject->access());

        $reflectionProperty->setValue($phpUnitFrameworkMockObjectMockObject, ['id' => 999, 'access_level' => '1']);

        $this->assertSame(1, $phpUnitFrameworkMockObjectMockObject->access());
    }
}
