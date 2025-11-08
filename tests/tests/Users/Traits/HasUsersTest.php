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

namespace Extly\Joomla\Entity\Tests\Users\Traits;

use Extly\Joomla\Entity\Collection;
use Extly\Joomla\Entity\Tests\Users\Traits\Stubs\EntityWithUsers;
use Extly\Joomla\Entity\Users\User;

/**
 * HasUsers trait tests.
 *
 * @since   1.1.0
 */
class HasUsersTest extends \PHPUnit\Framework\TestCase
{
    /**
     * clearUsers clears users property.
     *
     * @return  void
     */
    public function testClearUsersClearsUsersProperty()
    {
        $entityWithUsers = new EntityWithUsers();

        $reflectionClass = new \ReflectionClass($entityWithUsers);

        $reflectionProperty = $reflectionClass->getProperty('users');
        $reflectionProperty->setAccessible(true);

        $this->assertSame(null, $reflectionProperty->getValue($entityWithUsers));

        $collection = new Collection([User::find(333)]);

        $reflectionProperty->setValue($entityWithUsers, $collection);

        $this->assertSame($collection, $reflectionProperty->getValue($entityWithUsers));

        $entityWithUsers->clearUsers();

        $this->assertSame(null, $reflectionProperty->getValue($entityWithUsers));
    }

    /**
     * users returns cached data.
     *
     * @return  void
     */
    public function testUsersReturnsCachedData()
    {
        $entityWithUsers = new EntityWithUsers();

        $reflectionClass = new \ReflectionClass($entityWithUsers);

        $reflectionProperty = $reflectionClass->getProperty('users');
        $reflectionProperty->setAccessible(true);

        $this->assertSame(null, $reflectionProperty->getValue($entityWithUsers));

        $collection = new Collection([User::find(333)]);

        $reflectionProperty->setValue($entityWithUsers, $collection);

        $this->assertSame($collection, $entityWithUsers->users());
    }

    /**
     * users returns loadUsers result if not cached.
     *
     * @return  void
     */
    public function testUsersReturnsLoadUsersResultIfNotCached()
    {
        $collection = new Collection(
            [
                User::find(333),
                User::find(666),
            ]
        );

        $phpUnitFrameworkMockObjectMockObject = $this->getMockBuilder(EntityWithUsers::class)
            ->setMethods(['loadUsers'])
            ->getMock();

        $phpUnitFrameworkMockObjectMockObject->expects($this->once())
            ->method('loadUsers')
            ->willReturn($collection);

        $this->assertSame($collection, $phpUnitFrameworkMockObjectMockObject->users());
    }

    /**
     * hasUser returns correct value.
     *
     * @return  void
     */
    public function testHasUserReturnsCorrectValue()
    {
        $collection = new Collection(
            [
                User::find(666),
                User::find(999),
            ]
        );

        $entityWithUsers = new EntityWithUsers();
        $reflectionClass = new \ReflectionClass($entityWithUsers);

        $reflectionProperty = $reflectionClass->getProperty('users');
        $reflectionProperty->setAccessible(true);
        $reflectionProperty->setValue($entityWithUsers, $collection);

        $this->assertTrue($entityWithUsers->hasUser(666));
        $this->assertFalse($entityWithUsers->hasUser(333));
        $this->assertTrue($entityWithUsers->hasUser(999));
    }

    /**
     * hasUsers returns correct value.
     *
     * @return  void
     */
    public function testHasUsersReturnsCorrectValue()
    {
        $collection = new Collection(
            [
                User::find(333),
                User::find(666),
            ]
        );

        $entityWithUsers = new EntityWithUsers();
        $reflectionClass = new \ReflectionClass($entityWithUsers);

        $reflectionProperty = $reflectionClass->getProperty('users');
        $reflectionProperty->setAccessible(true);
        $reflectionProperty->setValue($entityWithUsers, $collection);

        $this->assertTrue($entityWithUsers->hasUsers());

        $reflectionProperty->setValue($entityWithUsers, new Collection());

        $this->assertFalse($entityWithUsers->hasUsers());
    }
}
