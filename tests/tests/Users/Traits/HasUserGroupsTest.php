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
use Extly\Joomla\Entity\Tests\Users\Traits\Stubs\EntityWithUserGroups;
use Extly\Joomla\Entity\Users\UserGroup;

/**
 * HasUserGroups trait tests.
 *
 * @since   1.1.0
 */
class HasUserGroupsTest extends \PHPUnit\Framework\TestCase
{
    /**
     * clearUserGroups clears userGroups property.
     *
     * @return  void
     */
    public function testClearUserGroupsClearsUserGroupsProperty()
    {
        $entityWithUserGroups = new EntityWithUserGroups();

        $reflectionClass = new \ReflectionClass($entityWithUserGroups);

        $reflectionProperty = $reflectionClass->getProperty('userGroups');
        $reflectionProperty->setAccessible(true);

        $this->assertSame(null, $reflectionProperty->getValue($entityWithUserGroups));

        $collection = new Collection([UserGroup::find(333)]);

        $reflectionProperty->setValue($entityWithUserGroups, $collection);

        $this->assertSame($collection, $reflectionProperty->getValue($entityWithUserGroups));

        $entityWithUserGroups->clearUserGroups();

        $this->assertSame(null, $reflectionProperty->getValue($entityWithUserGroups));
    }

    /**
     * userGroups returns cached data.
     *
     * @return  void
     */
    public function testUserGroupsReturnsCachedData()
    {
        $entityWithUserGroups = new EntityWithUserGroups();

        $reflectionClass = new \ReflectionClass($entityWithUserGroups);

        $reflectionProperty = $reflectionClass->getProperty('userGroups');
        $reflectionProperty->setAccessible(true);

        $this->assertSame(null, $reflectionProperty->getValue($entityWithUserGroups));

        $collection = new Collection([UserGroup::find(333)]);

        $reflectionProperty->setValue($entityWithUserGroups, $collection);

        $this->assertSame($collection, $entityWithUserGroups->userGroups());
    }

    /**
     * userGroups returns loadUserGroups result if not cached.
     *
     * @return  void
     */
    public function testUserGroupsReturnsLoadUserGroupsResultIfNotCached()
    {
        $collection = new Collection(
            [
                UserGroup::find(333),
                UserGroup::find(666),
            ]
        );

        $phpUnitFrameworkMockObjectMockObject = $this->getMockBuilder(EntityWithUserGroups::class)
            ->setMethods(['loadUserGroups'])
            ->getMock();

        $phpUnitFrameworkMockObjectMockObject->expects($this->once())
            ->method('loadUserGroups')
            ->willReturn($collection);

        $this->assertSame($collection, $phpUnitFrameworkMockObjectMockObject->userGroups());
    }

    /**
     * hasUserGroup returns correct value.
     *
     * @return  void
     */
    public function testHasUserGroupReturnsCorrectValue()
    {
        $collection = new Collection(
            [
                UserGroup::find(666),
                UserGroup::find(999),
            ]
        );

        $entityWithUserGroups = new EntityWithUserGroups();
        $reflectionClass = new \ReflectionClass($entityWithUserGroups);

        $reflectionProperty = $reflectionClass->getProperty('userGroups');
        $reflectionProperty->setAccessible(true);
        $reflectionProperty->setValue($entityWithUserGroups, $collection);

        $this->assertTrue($entityWithUserGroups->hasUserGroup(666));
        $this->assertFalse($entityWithUserGroups->hasUserGroup(333));
        $this->assertTrue($entityWithUserGroups->hasUserGroup(999));
    }

    /**
     * hasUserGroups returns correct value.
     *
     * @return  void
     */
    public function testHasUserGroupsReturnsCorrectValue()
    {
        $collection = new Collection(
            [
                UserGroup::find(333),
                UserGroup::find(666),
            ]
        );

        $entityWithUserGroups = new EntityWithUserGroups();
        $reflectionClass = new \ReflectionClass($entityWithUserGroups);

        $reflectionProperty = $reflectionClass->getProperty('userGroups');
        $reflectionProperty->setAccessible(true);
        $reflectionProperty->setValue($entityWithUserGroups, $collection);

        $this->assertTrue($entityWithUserGroups->hasUserGroups());

        $reflectionProperty->setValue($entityWithUserGroups, new Collection());

        $this->assertFalse($entityWithUserGroups->hasUserGroups());
    }
}
