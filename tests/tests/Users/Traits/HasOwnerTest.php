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

use Extly\Joomla\Entity\Tests\Users\Traits\Stubs\EntityWithOwner;
use Extly\Joomla\Entity\Users\Column;
use Extly\Joomla\Entity\Users\User;

/**
 * HasOwner trait tests.
 *
 * @since   1.1.0
 */
class HasOwnerTest extends \TestCaseDatabase
{
    /**
     * Name of the owner column.
     *
     * @const
     */
    public const OWNER_COLUMN = 'created_by';

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     *
     * @return  void
     */
    protected function setUp()
    {
        parent::setUp();

        $this->saveFactoryState();

        \Joomla\CMS\Factory::$session = $this->getMockSession();
        \Joomla\CMS\Factory::$config = $this->getMockConfig();
        \Joomla\CMS\Factory::$application = $this->getMockCmsApp();
    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     *
     * @return  void
     */
    protected function tearDown()
    {
        EntityWithOwner::clearAll();
        User::clearActive();

        $this->restoreFactoryState();

        parent::tearDown();
    }

    /**
     * hasOwner returns correct value.
     *
     * @return  void
     */
    public function testHasOwnerReturnsCorrectValue()
    {
        $class = $this->getMockBuilder(EntityWithOwner::class)
            ->disableOriginalConstructor()
            ->setMethods(['columnAlias'])
            ->getMock();

        $class->method('columnAlias')
            ->willReturn(static::OWNER_COLUMN);

        $reflectionClass = new \ReflectionClass($class);

        $reflectionProperty = $reflectionClass->getProperty('id');
        $reflectionProperty->setAccessible(true);
        $reflectionProperty->setValue($class, 999);

        $rowProperty = $reflectionClass->getProperty('row');
        $rowProperty->setAccessible(true);
        $rowProperty->setValue($class, ['id' => 999, static::OWNER_COLUMN => 22]);

        $this->assertSame(true, $class->hasOwner());
    }

    /**
     * @test
     *
     * @return void
     */
    public function hasOwnerReturnFalseWhenNoOwnerColumnExists()
    {
        $entity = $this->getMockBuilder(EntityWithOwner::class)
            ->disableOriginalConstructor()
            ->setMethods(['columnAlias'])
            ->getMock();

        $entity->method('columnAlias')
            ->willReturn(static::OWNER_COLUMN);

        $reflectionClass = new \ReflectionClass($entity);

        $reflectionProperty = $reflectionClass->getProperty('id');
        $reflectionProperty->setAccessible(true);
        $reflectionProperty->setValue($entity, 999);

        $rowProperty = $reflectionClass->getProperty('row');
        $rowProperty->setAccessible(true);
        $rowProperty->setValue($entity, ['id' => 999, 'name' => 'Test']);

        $this->assertFalse($entity->hasOwner());
    }

    /**
     * isOwner returns false for guest.
     *
     * @return  void
     */
    public function testIsOwnerReturnsFalseForGuest()
    {
        $user = $this->getMockBuilder(User::class)
            ->disableOriginalConstructor()
            ->setMethods(['isGuest'])
            ->getMock();

        $user->expects($this->once())
            ->method('isGuest')
            ->willReturn(true);

        $entityWithOwner = new EntityWithOwner();

        $this->assertFalse($entityWithOwner->isOwner($user));
    }

    /**
     * isOwner works with no user.
     *
     * @return  void
     */
    public function testIsOwnerWorksWithNoUser()
    {
        $entityWithOwner = new EntityWithOwner();

        $this->assertFalse($entityWithOwner->isOwner());
    }

    /**
     * isOwner returns false if user is not owner.
     *
     * @return  void
     */
    public function testIsOwnerReturnsCorrectValueForNonGuests()
    {
        $entity = $this->getMockBuilder(EntityWithOwner::class)
            ->disableOriginalConstructor()
            ->setMethods(['columnAlias'])
            ->getMock();

        $entity->method('columnAlias')
            ->willReturn(static::OWNER_COLUMN);

        $user = $this->getMockBuilder(User::class)
            ->disableOriginalConstructor()
            ->setMethods(['isGuest', 'id'])
            ->getMock();

        $user->method('isGuest')
            ->willReturn(false);

        $user->method('id')
            ->will($this->onConsecutiveCalls(333, 666));

        $entity->bind(['id' => 999, static::OWNER_COLUMN => 666]);

        $this->assertFalse($entity->isOwner($user));
        $this->assertTrue($entity->isOwner($user));
    }

    /**
     * loadOwner returns correct user.
     *
     * @return  void
     */
    public function testLoadOwnerReturnsCorrectUser()
    {
        $class = $this->getMockBuilder(EntityWithOwner::class)
            ->disableOriginalConstructor()
            ->setMethods(['columnAlias'])
            ->getMock();

        $class->expects($this->once())
            ->method('columnAlias')
            ->willReturn(static::OWNER_COLUMN);

        $reflectionClass = new \ReflectionClass($class);

        $reflectionProperty = $reflectionClass->getProperty('id');
        $reflectionProperty->setAccessible(true);
        $reflectionProperty->setValue($class, 999);

        $rowProperty = $reflectionClass->getProperty('row');
        $rowProperty->setAccessible(true);
        $rowProperty->setValue($class, ['id' => 999, static::OWNER_COLUMN => 22]);

        $reflectionMethod = $reflectionClass->getMethod('loadOwner');
        $reflectionMethod->setAccessible(true);

        $this->assertSame(User::find(22), $reflectionMethod->invoke($class));
    }

    /**
     * loadOwner throws exception when entity does not have owner.
     *
     * @return  void
     *
     * @expectedException  \InvalidArgumentException
     */
    public function testLoadOwnerThrowsExceptionWhenEntityDoesNotHaveOwner()
    {
        $entity = $this->getMockBuilder(EntityWithOwner::class)
            ->disableOriginalConstructor()
            ->setMethods(['columnAlias'])
            ->getMock();

        $entity->method('columnAlias')
            ->willReturn(static::OWNER_COLUMN);

        $reflectionClass = new \ReflectionClass($entity);

        $reflectionProperty = $reflectionClass->getProperty('id');
        $reflectionProperty->setAccessible(true);
        $reflectionProperty->setValue($entity, 999);

        $rowProperty = $reflectionClass->getProperty('row');
        $rowProperty->setAccessible(true);
        $rowProperty->setValue($entity, ['id' => 999, static::OWNER_COLUMN => null]);

        $reflectionMethod = $reflectionClass->getMethod('loadOwner');
        $reflectionMethod->setAccessible(true);

        $reflectionMethod->invoke($entity);
    }

    /**
     * loadOwner throws exception for missing owner column.
     *
     * @return  void
     *
     * @expectedException  \InvalidArgumentException
     */
    public function testLoadOwnerThrowsExceptionForMissingOwnerColumn()
    {
        $entity = $this->getMockBuilder(EntityWithOwner::class)
            ->disableOriginalConstructor()
            ->setMethods(['columnAlias'])
            ->getMock();

        $entity->method('columnAlias')
            ->willReturn(static::OWNER_COLUMN);

        $reflectionClass = new \ReflectionClass($entity);

        $reflectionProperty = $reflectionClass->getProperty('id');
        $reflectionProperty->setAccessible(true);
        $reflectionProperty->setValue($entity, 999);

        $rowProperty = $reflectionClass->getProperty('row');
        $rowProperty->setAccessible(true);
        $rowProperty->setValue($entity, ['id' => 999]);

        $reflectionMethod = $reflectionClass->getMethod('loadOwner');
        $reflectionMethod->setAccessible(true);

        $reflectionMethod->invoke($entity);
    }

    /**
     * owner calls loadOwner.
     *
     * @return  void
     */
    public function testOwnerCallsLoadOwner()
    {
        $user = new User(24);

        $class = $this->getMockBuilder(EntityWithOwner::class)
            ->disableOriginalConstructor()
            ->setMethods(['loadOwner'])
            ->getMock();

        $class->expects($this->once())
            ->method('loadOwner')
            ->willReturn($user);

        $this->assertSame($user, $class->owner());
    }

    /**
     * owner returns cached instance.
     *
     * @return  void
     */
    public function testOwnerReturnsCachedInstance()
    {
        $user = new User(999);

        $entityWithOwner = new EntityWithOwner();

        $reflectionClass = new \ReflectionClass($entityWithOwner);

        $reflectionProperty = $reflectionClass->getProperty('owner');
        $reflectionProperty->setAccessible(true);
        $reflectionProperty->setValue($entityWithOwner, $user);

        $this->assertSame($user, $entityWithOwner->owner());
    }

    /**
     * owner reloads data.
     *
     * @return  void
     */
    public function testOwnerReloadsData()
    {
        $owner = new User(24);
        $reloadedOwner = new User(999);

        $class = $this->getMockBuilder(EntityWithOwner::class)
            ->disableOriginalConstructor()
            ->setMethods(['loadOwner'])
            ->getMock();

        $class
            ->method('loadOwner')
            ->will($this->onConsecutiveCalls($owner, $reloadedOwner));

        $this->assertSame($owner, $class->owner());
        $this->assertSame($reloadedOwner, $class->owner(true));
        $this->assertSame($reloadedOwner, $class->owner());
    }
}
