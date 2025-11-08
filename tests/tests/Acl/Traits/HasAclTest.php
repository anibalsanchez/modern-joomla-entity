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

namespace Extly\Joomla\Entity\Tests\Acl\Traits;

use Extly\Joomla\Entity\Acl\Acl;
use Extly\Joomla\Entity\Tests\Acl\Stubs\EntityWithAcl;
use Extly\Joomla\Entity\Users\User;

/**
 * HasAcl trait tests.
 *
 * @since   1.1.0
 */
class HasAclTest extends \PHPUnit\Framework\TestCase
{
    /**
     * acl returns Acl instance.
     *
     * @return  void
     */
    public function testAclReturnsAclInstance()
    {
        $entityWithAcl = new EntityWithAcl(666);
        $user = new User(999);

        $acl = $entityWithAcl->acl($user);

        $reflectionClass = new \ReflectionClass($acl);
        $reflectionProperty = $reflectionClass->getProperty('entity');
        $reflectionProperty->setAccessible(true);

        $userProperty = $reflectionClass->getProperty('user');
        $userProperty->setAccessible(true);

        $this->assertInstanceOf(Acl::class, $acl);
        $this->assertSame($user, $userProperty->getValue($acl));
        $this->assertSame($entityWithAcl, $reflectionProperty->getValue($acl));
    }

    /**
     * aclPrefix returns correct value.
     *
     * @return  void
     */
    public function testAclPrefixReturnsCorrectValue()
    {
        $entityWithAcl = new EntityWithAcl();

        $reflectionClass = new \ReflectionClass($entityWithAcl);
        $reflectionMethod = $reflectionClass->getMethod('aclPrefix');
        $reflectionMethod->setAccessible(true);

        $this->assertSame('core', $reflectionMethod->invoke($entityWithAcl));
    }

    /**
     * aclAssetName returns component option when no id.
     *
     * @return  void
     */
    public function testAclAssetNameReturnsComponentOptionWhenNoId()
    {
        $phpUnitFrameworkMockObjectMockObject = $this->getMockBuilder(Component::class)
            ->setMethods(['option'])
            ->getMock();

        $phpUnitFrameworkMockObjectMockObject->expects($this->once())
            ->method('option')
            ->willReturn('com_phproberto');

        $entity = $this->getMockBuilder(EntityWithAcl::class)
            ->setMethods(['component'])
            ->getMock();

        $entity->expects($this->once())
            ->method('component')
            ->willReturn($phpUnitFrameworkMockObjectMockObject);

        $this->assertSame('com_phproberto', $entity->aclAssetName());
    }

    /**
     * aclAssetName returns component option when no id.
     *
     * @return  void
     */
    public function testAclAssetNameReturnsOptionAndNameWhenHasId()
    {
        $phpUnitFrameworkMockObjectMockObject = $this->getMockBuilder(Component::class)
            ->setMethods(['option'])
            ->getMock();

        $phpUnitFrameworkMockObjectMockObject->expects($this->once())
            ->method('option')
            ->willReturn('com_phproberto');

        $entity = $this->getMockBuilder(EntityWithAcl::class)
            ->setMethods(['component', 'name'])
            ->getMock();

        $entity->expects($this->once())
            ->method('component')
            ->willReturn($phpUnitFrameworkMockObjectMockObject);

        $entity->expects($this->once())
            ->method('name')
            ->willReturn('sample');

        $reflectionClass = new \ReflectionClass($entity);
        $reflectionProperty = $reflectionClass->getProperty('id');
        $reflectionProperty->setAccessible(true);
        $reflectionProperty->setValue($entity, 999);

        $this->assertSame('com_phproberto.sample.999', $entity->aclAssetName());
    }
}
