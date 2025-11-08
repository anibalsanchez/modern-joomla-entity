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

use Extly\Joomla\Entity\Tests\Users\Traits\Stubs\EntityWithUser;
use Extly\Joomla\Entity\Users\User;

/**
 * HasUser trait tests.
 *
 * @since   1.1.0
 */
class HasUserTest extends \PHPUnit\Framework\TestCase
{
    /**
     * Column to use to load/store user.
     *
     * @const
     */
    public const COLUMN_USER = 'userid';

    /**
     * user returns cached data.
     *
     * @return  void
     */
    public function testUserReturnsCachedData()
    {
        $entityWithUser = new EntityWithUser();

        $reflectionClass = new \ReflectionClass($entityWithUser);

        $reflectionProperty = $reflectionClass->getProperty('user');
        $reflectionProperty->setAccessible(true);

        $this->assertSame(null, $reflectionProperty->getValue($entityWithUser));

        $user = new User(666);
        $reflectionProperty->setValue($entityWithUser, $user);

        $this->assertSame($user, $entityWithUser->user());
    }

    /**
     * user returns loadUser result when not cached.
     *
     * @return  void
     */
    public function testUserReturnsLoadUserResultWhenNotCached()
    {
        $user = new User(999);

        $phpUnitFrameworkMockObjectMockObject = $this->getMockBuilder(EntityWithUser::class)
            ->setMethods(['loadUser'])
            ->getMock();

        $phpUnitFrameworkMockObjectMockObject->expects($this->once())
            ->method('loadUser')
            ->willReturn($user);

        $reflectionClass = new \ReflectionClass($phpUnitFrameworkMockObjectMockObject);

        $reflectionProperty = $reflectionClass->getProperty('user');
        $reflectionProperty->setAccessible(true);

        $this->assertSame(null, $reflectionProperty->getValue($phpUnitFrameworkMockObjectMockObject));
        $this->assertSame($user, $phpUnitFrameworkMockObjectMockObject->user());
    }

    /**
     * HasUser returns false for empty entity.
     *
     * @return  void
     */
    public function testHasUserReturnsFalseForEmptyEntity()
    {
        $phpUnitFrameworkMockObjectMockObject = $this->getEntity();

        $this->assertFalse($phpUnitFrameworkMockObjectMockObject->hasUser());
    }

    /**
     * hasUser returns true for entities with user id.
     *
     * @return  void
     */
    public function testHasUserReturnsTrueForEntitiesWithUserId()
    {
        $phpUnitFrameworkMockObjectMockObject = $this->getEntity(
            [
                'id' => 23,
                self::COLUMN_USER => 666,
            ]
        );

        $this->assertTrue($phpUnitFrameworkMockObjectMockObject->hasUser());
    }

    /**
     * loadUser returns correct value for entities with user id.
     *
     * @return  void
     */
    public function testLoadUserReturnsCorrectValueForEntitiesWithUserId()
    {
        $phpUnitFrameworkMockObjectMockObject = $this->getEntity(
            [
                'id' => 23,
                self::COLUMN_USER => 666,
            ]
        );

        $reflectionClass = new \ReflectionClass($phpUnitFrameworkMockObjectMockObject);

        $reflectionMethod = $reflectionClass->getMethod('loadUser');
        $reflectionMethod->setAccessible(true);

        $this->assertEquals(User::find(666), $reflectionMethod->invoke($phpUnitFrameworkMockObjectMockObject));
    }

    /**
     * @test
     *
     * @return void
     */
    public function userIdReturnsZeroForNotLoadedUser()
    {
        $entityWithUser = new EntityWithUser();

        $this->assertSame(0, $entityWithUser->userId());
    }

    /**
     * @test
     *
     * @return void
     */
    public function userIdReturnsSetUserId()
    {
        $phpUnitFrameworkMockObjectMockObject = $this->getEntity();

        $phpUnitFrameworkMockObjectMockObject->bind(
            [
                'id'              => 999,
                self::COLUMN_USER => '939',
            ]
        );

        $this->assertSame(939, $phpUnitFrameworkMockObjectMockObject->userId());
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
        $phpUnitFrameworkMockObjectMockObject = $this->getMockBuilder(EntityWithUser::class)
            ->setMethods(['columnAlias'])
            ->getMock();

        $phpUnitFrameworkMockObjectMockObject->method('columnAlias')
            ->willReturn(static::COLUMN_USER);

        $phpUnitFrameworkMockObjectMockObject->bind($row);

        return $phpUnitFrameworkMockObjectMockObject;
    }
}
