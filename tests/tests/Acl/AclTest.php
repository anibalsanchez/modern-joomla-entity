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

namespace Extly\Joomla\Entity\Tests\Acl;

use Extly\Joomla\Entity\Acl\Acl;
use Extly\Joomla\Entity\Tests\Acl\Stubs\EntityWithAcl;
use Extly\Joomla\Entity\Tests\Acl\Stubs\OwnerableEntityWithAcl;
use Extly\Joomla\Entity\Tests\Acl\Stubs\PublishableEntityWithAcl;
use Extly\Joomla\Entity\Tests\Stubs\Entity;
use Extly\Joomla\Entity\Users\User;

/**
 * Acl decorator tests.
 *
 * @since   1.1.0
 */
class AclTest extends \TestCase
{
    /**
     * can returns true when can administrate component.
     *
     * @return  void
     */
    public function testCanReturnsTrueWhenCanAdmin()
    {
        $acl = $this->getMockBuilder(Acl::class)
            ->disableOriginalConstructor()
            ->setMethods(['canAdmin'])
            ->getMock();

        $acl->expects($this->once())
            ->method('canAdmin')
            ->willReturn(true);

        $user = $this->getMockBuilder('MockedUser')
            ->setMethods(['isRoot'])
            ->getMock();

        $user->expects($this->once())
            ->method('isRoot')
            ->willReturn(false);

        $reflectionClass = new \ReflectionClass($acl);
        $reflectionProperty = $reflectionClass->getProperty('user');
        $reflectionProperty->setAccessible(true);
        $reflectionProperty->setValue($acl, $user);

        $this->assertTrue($acl->can('delete'));
    }

    /**
     * can calls authorise when cannot admin.
     *
     * @return  void
     */
    public function testCanCallsAuthoriseWhenNotAdmin()
    {
        $acl = $this->getMockBuilder(Acl::class)
            ->disableOriginalConstructor()
            ->setMethods(['canAdmin'])
            ->getMock();

        $acl->expects($this->exactly(2))
            ->method('canAdmin')
            ->willReturn(false);

        $user = $this->getMockBuilder('UserMock')
            ->setMethods(['authorise', 'isRoot'])
            ->getMock();

        $user->expects($this->at(1))
            ->method('authorise')
            ->with($this->equalTo('core.delete'), $this->equalTo('com_phproberto.sample.999'))
            ->willReturn(true);

        $user->expects($this->exactly(2))
            ->method('isRoot')
            ->willReturn(false);

        $user->expects($this->at(3))
            ->method('authorise')
            ->with($this->equalTo('core.delete'), $this->equalTo('com_phproberto.sample.999'))
            ->willReturn(false);

        $reflectionClass = new \ReflectionClass($acl);
        $reflectionProperty = $reflectionClass->getProperty('user');
        $reflectionProperty->setAccessible(true);
        $reflectionProperty->setValue($acl, $user);

        $entity = $this->getMockBuilder(EntityWithAcl::class)
            ->setMethods(['aclPrefix', 'aclAssetName'])
            ->getMock();

        $entity->expects($this->exactly(2))
            ->method('aclPrefix')
            ->willReturn('core');

        $entity->expects($this->exactly(2))
            ->method('aclAssetName')
            ->willReturn('com_phproberto.sample.999');

        $entityProperty = $reflectionClass->getProperty('entity');
        $entityProperty->setAccessible(true);
        $entityProperty->setValue($acl, $entity);

        $this->assertTrue($acl->can('delete'));
        $this->assertFalse($acl->can('delete'));
    }

    /**
     * canAdmin returns cached value.
     *
     * @return  void
     */
    public function testCanAdminReturnsCachedValue()
    {
        $acl = $this->getMockBuilder(Acl::class)
            ->disableOriginalConstructor()
            ->setMethods(['can'])
            ->getMock();

        $reflectionClass = new \ReflectionClass($acl);
        $reflectionProperty = $reflectionClass->getProperty('canAdmin');
        $reflectionProperty->setAccessible(true);
        $reflectionProperty->setValue($acl, false);

        $this->assertFalse($acl->canAdmin());

        $reflectionProperty->setValue($acl, true);

        $this->assertTrue($acl->canAdmin());
    }

    /**
     * canAdmin returns true for admins.
     *
     * @return  void
     */
    public function testCanAdminReturnsTrueForAdmins()
    {
        $acl = $this->getMockBuilder(Acl::class)
            ->disableOriginalConstructor()
            ->setMethods(['can'])
            ->getMock();

        $user = $this->getMockBuilder('MockedUser')
            ->setMethods(['authorise'])
            ->getMock();

        $user->expects($this->once())
            ->method('authorise')
            ->with('core.admin', 'com_phproberto.sample.999')
            ->willReturn(true);

        $reflectionClass = new \ReflectionClass($acl);
        $reflectionProperty = $reflectionClass->getProperty('user');
        $reflectionProperty->setAccessible(true);
        $reflectionProperty->setValue($acl, $user);

        $entity = $this->getMockBuilder(EntityWithAcl::class)
            ->setMethods(['aclAssetName'])
            ->getMock();

        $entity->expects($this->once())
            ->method('aclAssetName')
            ->willReturn('com_phproberto.sample.999');

        $entityProperty = $reflectionClass->getProperty('entity');
        $entityProperty->setAccessible(true);
        $entityProperty->setValue($acl, $entity);

        $this->assertTrue($acl->canAdmin());
    }

    /**
     * canCreate returns false if cannot create and not owner.
     *
     * @return  void
     */
    public function testCanCreateReturnsFalseIfCannotCreateAndNotOwner()
    {
        $acl = $this->getMockBuilder(Acl::class)
            ->disableOriginalConstructor()
            ->setMethods(['can', 'isOwner'])
            ->getMock();

        $acl->expects($this->once())
            ->method('can')
            ->with($this->equalTo('create'))
            ->willReturn(false);

        $acl->expects($this->once())
            ->method('isOwner')
            ->willReturn(false);

        $this->assertFalse($acl->canCreate());
    }

    /**
     * canCreate returns true for create permission.
     *
     * @return  void
     */
    public function testCanCreateReturnsTrueForCreatePermission()
    {
        $acl = $this->getMockBuilder(Acl::class)
            ->disableOriginalConstructor()
            ->setMethods(['can', 'isOwner'])
            ->getMock();

        $acl->expects($this->once())
            ->method('can')
            ->with($this->equalTo('create'))
            ->willReturn(true);

        $acl->expects($this->exactly(0))
            ->method('isOwner')
            ->willReturn(false);

        $this->assertTrue($acl->canCreate());
    }

    /**
     * canCreate returns true when owner and create own allowed.
     *
     * @return  void
     */
    public function testCanCreateReturnsTrueWhenOwnerAndCreateOwnAllowed()
    {
        $acl = $this->getMockBuilder(Acl::class)
            ->disableOriginalConstructor()
            ->setMethods(['can', 'isOwner'])
            ->getMock();

        $acl->expects($this->at(0))
            ->method('can')
            ->with($this->equalTo('create'))
            ->willReturn(false);

        $acl->expects($this->at(1))
            ->method('isOwner')
            ->willReturn(true);

        $acl->expects($this->at(2))
            ->method('can')
            ->with($this->equalTo('create.own'))
            ->willReturn(true);

        $this->assertTrue($acl->canCreate());
    }

    /**
     * canDelete returns false for missing id.
     *
     * @return  void
     */
    public function testCanDeleteReturnsFalseForMissingId()
    {
        $entity = $this->getMockBuilder(EntityWithAcl::class)
            ->setMethods(['hasId'])
            ->getMock();

        $entity->expects($this->once())
            ->method('hasId')
            ->willReturn(false);

        $acl = $this->getMockBuilder(Acl::class)
            ->disableOriginalConstructor()
            ->setMethods(['can'])
            ->getMock();

        $reflectionClass = new \ReflectionClass($acl);
        $reflectionProperty = $reflectionClass->getProperty('entity');
        $reflectionProperty->setAccessible(true);
        $reflectionProperty->setValue($acl, $entity);

        $this->assertFalse($acl->canDelete());
    }

    /**
     * canDelete returns false when no delete and not owner.
     *
     * @return  void
     */
    public function testCanDeleteReturnsFalseWhenNoDeleteAllowedAndNotOwner()
    {
        $acl = $this->getMockBuilder(Acl::class)
            ->disableOriginalConstructor()
            ->setMethods(['can', 'isOwner'])
            ->getMock();

        $acl->expects($this->once())
            ->method('can')
            ->with($this->equalTo('delete'))
            ->willReturn(false);

        $acl->expects($this->once())
            ->method('isOwner')
            ->willReturn(false);

        $entity = $this->getMockBuilder(EntityWithAcl::class)
            ->setMethods(['hasId'])
            ->getMock();

        $entity->expects($this->once())
            ->method('hasId')
            ->willReturn(true);

        $reflectionClass = new \ReflectionClass($acl);
        $reflectionProperty = $reflectionClass->getProperty('entity');
        $reflectionProperty->setAccessible(true);
        $reflectionProperty->setValue($acl, $entity);

        $this->assertFalse($acl->canDelete());
    }

    /**
     * canDelete returns false when owner and delete own not allowed.
     *
     * @return  void
     */
    public function testCanDeleteReturnsFalseWhenOwnerAndDeleteOwnNotAllowed()
    {
        $acl = $this->getMockBuilder(Acl::class)
            ->disableOriginalConstructor()
            ->setMethods(['can', 'isOwner'])
            ->getMock();

        $acl->expects($this->at(0))
            ->method('can')
            ->with($this->equalTo('delete'))
            ->willReturn(false);

        $acl->expects($this->at(1))
            ->method('isOwner')
            ->willReturn(true);

        $acl->expects($this->at(2))
            ->method('can')
            ->with($this->equalTo('delete.own'))
            ->willReturn(false);

        $entity = $this->getMockBuilder(EntityWithAcl::class)
            ->setMethods(['hasId'])
            ->getMock();

        $entity->expects($this->once())
            ->method('hasId')
            ->willReturn(true);

        $reflectionClass = new \ReflectionClass($acl);
        $reflectionProperty = $reflectionClass->getProperty('entity');
        $reflectionProperty->setAccessible(true);
        $reflectionProperty->setValue($acl, $entity);

        $this->assertFalse($acl->canDelete());
    }

    /**
     * canDelete returns true when delete allowed.
     *
     * @return  void
     */
    public function testCanDeleteReturnsTrueWhenDeleteAllowed()
    {
        $acl = $this->getMockBuilder(Acl::class)
            ->disableOriginalConstructor()
            ->setMethods(['can', 'isOwner'])
            ->getMock();

        $acl->expects($this->once())
            ->method('can')
            ->with($this->equalTo('delete'))
            ->willReturn(true);

        $acl->expects($this->exactly(0))
            ->method('isOwner')
            ->willReturn(false);

        $entity = $this->getMockBuilder(EntityWithAcl::class)
            ->setMethods(['hasId'])
            ->getMock();

        $entity->expects($this->once())
            ->method('hasId')
            ->willReturn(true);

        $reflectionClass = new \ReflectionClass($acl);
        $reflectionProperty = $reflectionClass->getProperty('entity');
        $reflectionProperty->setAccessible(true);
        $reflectionProperty->setValue($acl, $entity);

        $this->assertTrue($acl->canDelete());
    }

    /**
     * canDelete returns true when owner and delete own allowed.
     *
     * @return  void
     */
    public function testCanDeleteReturnsTrueWhenOwnerAndDeleteOwnAllowed()
    {
        $acl = $this->getMockBuilder(Acl::class)
            ->disableOriginalConstructor()
            ->setMethods(['can', 'isOwner'])
            ->getMock();

        $acl->expects($this->at(0))
            ->method('can')
            ->with($this->equalTo('delete'))
            ->willReturn(false);

        $acl->expects($this->at(1))
            ->method('isOwner')
            ->willReturn(true);

        $acl->expects($this->at(2))
            ->method('can')
            ->with($this->equalTo('delete.own'))
            ->willReturn(true);

        $entity = $this->getMockBuilder(EntityWithAcl::class)
            ->setMethods(['hasId'])
            ->getMock();

        $entity->expects($this->once())
            ->method('hasId')
            ->willReturn(true);

        $reflectionClass = new \ReflectionClass($acl);
        $reflectionProperty = $reflectionClass->getProperty('entity');
        $reflectionProperty->setAccessible(true);
        $reflectionProperty->setValue($acl, $entity);

        $this->assertTrue($acl->canDelete());
    }

    /**
     * canEdit return false for no edit and no owner..
     *
     * @return  void
     */
    public function testCanEditReturnsFalseForNoEditAndNoOwner()
    {
        $acl = $this->getMockBuilder(Acl::class)
            ->disableOriginalConstructor()
            ->setMethods(['can', 'isOwner'])
            ->getMock();

        $acl->expects($this->once())
            ->method('can')
            ->with($this->equalTo('edit'))
            ->willReturn(false);

        $acl->expects($this->once())
            ->method('isOwner')
            ->willReturn(false);

        $entity = $this->getMockBuilder(EntityWithAcl::class)
            ->setMethods(['hasId'])
            ->getMock();

        $entity->expects($this->once())
            ->method('hasId')
            ->willReturn(true);

        $reflectionClass = new \ReflectionClass($acl);
        $reflectionProperty = $reflectionClass->getProperty('entity');
        $reflectionProperty->setAccessible(true);
        $reflectionProperty->setValue($acl, $entity);

        $this->assertFalse($acl->canEdit());
    }

    /**
     * canEdit return false for no id..
     *
     * @return  void
     */
    public function testCanEditReturnsFalseForNoId()
    {
        $acl = $this->getMockBuilder(Acl::class)
            ->disableOriginalConstructor()
            ->setMethods(['can'])
            ->getMock();

        $entity = $this->getMockBuilder(EntityWithAcl::class)
            ->setMethods(['hasId'])
            ->getMock();

        $entity->expects($this->once())
            ->method('hasId')
            ->willReturn(false);

        $reflectionClass = new \ReflectionClass($acl);
        $reflectionProperty = $reflectionClass->getProperty('entity');
        $reflectionProperty->setAccessible(true);
        $reflectionProperty->setValue($acl, $entity);

        $this->assertFalse($acl->canEdit());
    }

    /**
     * canEdit returns true for edit permission.
     *
     * @return  void
     */
    public function testCanEditReturnsTrueForEditPermission()
    {
        $acl = $this->getMockBuilder(Acl::class)
            ->disableOriginalConstructor()
            ->setMethods(['can', 'isOwner'])
            ->getMock();

        $acl->expects($this->once())
            ->method('can')
            ->with($this->equalTo('edit'))
            ->willReturn(true);

        $acl->expects($this->exactly(0))
            ->method('isOwner')
            ->willReturn(false);

        $entity = $this->getMockBuilder(EntityWithAcl::class)
            ->setMethods(['hasId'])
            ->getMock();

        $entity->expects($this->once())
            ->method('hasId')
            ->willReturn(true);

        $reflectionClass = new \ReflectionClass($acl);
        $reflectionProperty = $reflectionClass->getProperty('entity');
        $reflectionProperty->setAccessible(true);
        $reflectionProperty->setValue($acl, $entity);

        $this->assertTrue($acl->canEdit());
    }

    /**
     * canEdit returns true when owner and owner edit permission.
     *
     * @return  void
     */
    public function testCanEditReturnsTrueWhenOwnerAndOwnerEditPermission()
    {
        $acl = $this->getMockBuilder(Acl::class)
            ->disableOriginalConstructor()
            ->setMethods(['can', 'isOwner'])
            ->getMock();

        $acl->expects($this->at(0))
            ->method('can')
            ->with($this->equalTo('edit'))
            ->willReturn(false);

        $acl->expects($this->at(1))
            ->method('isOwner')
            ->willReturn(true);

        $acl->expects($this->at(2))
            ->method('can')
            ->with($this->equalTo('edit.own'))
            ->willReturn(true);

        $entity = $this->getMockBuilder(EntityWithAcl::class)
            ->setMethods(['hasId'])
            ->getMock();

        $entity->expects($this->once())
            ->method('hasId')
            ->willReturn(true);

        $reflectionClass = new \ReflectionClass($acl);
        $reflectionProperty = $reflectionClass->getProperty('entity');
        $reflectionProperty->setAccessible(true);
        $reflectionProperty->setValue($acl, $entity);

        $this->assertTrue($acl->canEdit());
    }

    /**
     * canEditState returns false for no id.
     *
     * @return  void
     */
    public function testCanEditStateReturnsFalseForNoId()
    {
        $acl = $this->getMockBuilder(Acl::class)
            ->disableOriginalConstructor()
            ->setMethods(['can'])
            ->getMock();

        $entity = $this->getMockBuilder(EntityWithAcl::class)
            ->setMethods(['hasId'])
            ->getMock();

        $entity->expects($this->once())
            ->method('hasId')
            ->willReturn(false);

        $reflectionClass = new \ReflectionClass($acl);
        $reflectionProperty = $reflectionClass->getProperty('entity');
        $reflectionProperty->setAccessible(true);
        $reflectionProperty->setValue($acl, $entity);

        $this->assertFalse($acl->canEditState());
    }

    /**
     * canEditState return false for no edit and no owner..
     *
     * @return  void
     */
    public function testCanEditStateReturnsFalseForNoEditAndNoOwner()
    {
        $acl = $this->getMockBuilder(Acl::class)
            ->disableOriginalConstructor()
            ->setMethods(['can', 'isOwner'])
            ->getMock();

        $acl->expects($this->once())
            ->method('can')
            ->with($this->equalTo('edit.state'))
            ->willReturn(false);

        $acl->expects($this->once())
            ->method('isOwner')
            ->willReturn(false);

        $entity = $this->getMockBuilder(EntityWithAcl::class)
            ->setMethods(['hasId'])
            ->getMock();

        $entity->expects($this->once())
            ->method('hasId')
            ->willReturn(true);

        $reflectionClass = new \ReflectionClass($acl);
        $reflectionProperty = $reflectionClass->getProperty('entity');
        $reflectionProperty->setAccessible(true);
        $reflectionProperty->setValue($acl, $entity);

        $this->assertFalse($acl->canEditState());
    }

    /**
     * canEdit returns true for edit permission.
     *
     * @return  void
     */
    public function testCanEditStateReturnsTrueForEditPermission()
    {
        $acl = $this->getMockBuilder(Acl::class)
            ->disableOriginalConstructor()
            ->setMethods(['can', 'isOwner'])
            ->getMock();

        $acl->expects($this->once())
            ->method('can')
            ->with($this->equalTo('edit.state'))
            ->willReturn(true);

        $acl->expects($this->exactly(0))
            ->method('isOwner')
            ->willReturn(false);

        $entity = $this->getMockBuilder(EntityWithAcl::class)
            ->setMethods(['hasId'])
            ->getMock();

        $entity->expects($this->once())
            ->method('hasId')
            ->willReturn(true);

        $reflectionClass = new \ReflectionClass($acl);
        $reflectionProperty = $reflectionClass->getProperty('entity');
        $reflectionProperty->setAccessible(true);
        $reflectionProperty->setValue($acl, $entity);

        $this->assertTrue($acl->canEditState());
    }

    /**
     * canEdit returns true when owner and owner edit permission.
     *
     * @return  void
     */
    public function testCanEditStateReturnsTrueWhenOwnerAndOwnerEditPermission()
    {
        $acl = $this->getMockBuilder(Acl::class)
            ->disableOriginalConstructor()
            ->setMethods(['can', 'isOwner'])
            ->getMock();

        $acl->expects($this->at(0))
            ->method('can')
            ->with($this->equalTo('edit.state'))
            ->willReturn(false);

        $acl->expects($this->at(1))
            ->method('isOwner')
            ->willReturn(true);

        $acl->expects($this->at(2))
            ->method('can')
            ->with($this->equalTo('edit.state.own'))
            ->willReturn(true);

        $entity = $this->getMockBuilder(EntityWithAcl::class)
            ->setMethods(['hasId'])
            ->getMock();

        $entity->expects($this->once())
            ->method('hasId')
            ->willReturn(true);

        $reflectionClass = new \ReflectionClass($acl);
        $reflectionProperty = $reflectionClass->getProperty('entity');
        $reflectionProperty->setAccessible(true);
        $reflectionProperty->setValue($acl, $entity);

        $this->assertTrue($acl->canEditState());
    }

    /**
     * canView returns false for no id.
     *
     * @return  void
     */
    public function testCanViewReturnsFalseForNoId()
    {
        $acl = $this->getMockBuilder(Acl::class)
            ->disableOriginalConstructor()
            ->setMethods(['can'])
            ->getMock();

        $entity = $this->getMockBuilder(EntityWithAcl::class)
            ->setMethods(['hasId'])
            ->getMock();

        $entity->expects($this->once())
            ->method('hasId')
            ->willReturn(false);

        $reflectionClass = new \ReflectionClass($acl);
        $reflectionProperty = $reflectionClass->getProperty('entity');
        $reflectionProperty->setAccessible(true);
        $reflectionProperty->setValue($acl, $entity);

        $this->assertFalse($acl->canView());
    }

    /**
     * canView returns true for canEdit permission.
     *
     * @return  void
     */
    public function testCanViewReturnsTrueForCanEditPermission()
    {
        $acl = $this->getMockBuilder(Acl::class)
            ->disableOriginalConstructor()
            ->setMethods(['canEdit'])
            ->getMock();

        $acl->expects($this->once())
            ->method('canEdit')
            ->willReturn(true);

        $entity = $this->getMockBuilder(EntityWithAcl::class)
            ->setMethods(['hasId'])
            ->getMock();

        $entity->expects($this->once())
            ->method('hasId')
            ->willReturn(true);

        $reflectionClass = new \ReflectionClass($acl);
        $reflectionProperty = $reflectionClass->getProperty('entity');
        $reflectionProperty->setAccessible(true);
        $reflectionProperty->setValue($acl, $entity);

        $this->assertTrue($acl->canView());
    }

    /**
     * canView returns true for canEdit permission.
     *
     * @return  void
     */
    public function testCanViewReturnsTrueForCanEditStatePermission()
    {
        $acl = $this->getMockBuilder(Acl::class)
            ->disableOriginalConstructor()
            ->setMethods(['canEdit', 'canEditState'])
            ->getMock();

        $acl->expects($this->once())
            ->method('canEdit')
            ->willReturn(false);

        $acl->expects($this->once())
            ->method('canEditState')
            ->willReturn(true);

        $entity = $this->getMockBuilder(EntityWithAcl::class)
            ->setMethods(['hasId'])
            ->getMock();

        $entity->method('hasId')
            ->willReturn(true);

        $reflectionClass = new \ReflectionClass($acl);

        $reflectionProperty = $reflectionClass->getProperty('entity');
        $reflectionProperty->setAccessible(true);
        $reflectionProperty->setValue($acl, $entity);

        $this->assertTrue($acl->canView());
    }

    /**
     * canView returns false if publishable entity not published.
     *
     * @return  void
     */
    public function testCanViewReturnsFalseIfPublishableEntityNotPublished()
    {
        $acl = $this->getMockBuilder(Acl::class)
            ->disableOriginalConstructor()
            ->setMethods(['isPublishedEntity'])
            ->getMock();

        $acl->expects($this->once())
            ->method('isPublishedEntity')
            ->willReturn(false);

        $entity = $this->getMockBuilder(PublishableEntityWithAcl::class)
            ->setMethods(['hasId'])
            ->getMock();

        $entity->expects($this->once())
            ->method('hasId')
            ->willReturn(true);

        $reflectionClass = new \ReflectionClass($acl);

        $reflectionProperty = $reflectionClass->getProperty('entity');
        $reflectionProperty->setAccessible(true);
        $reflectionProperty->setValue($acl, $entity);

        $this->assertFalse($acl->canView());
    }

    /**
     * canView returns tre for no access column.
     *
     * @return  void
     */
    public function testCanViewReturnsTrueForNoAccessColumn()
    {
        $acl = $this->getMockBuilder(Acl::class)
            ->disableOriginalConstructor()
            ->setMethods(['canEdit', 'canEditState'])
            ->getMock();

        $acl->expects($this->once())
            ->method('canEdit')
            ->willReturn(false);

        $acl->expects($this->once())
            ->method('canEditState')
            ->willReturn(false);

        $entity = $this->getMockBuilder(EntityWithAcl::class)
            ->setMethods(['hasId', 'has'])
            ->getMock();

        $entity->expects($this->once())
            ->method('hasId')
            ->willReturn(true);

        $entity->expects($this->once())
            ->method('has')
            ->with($this->equalTo('access'))
            ->willReturn(false);

        $reflectionClass = new \ReflectionClass($acl);
        $reflectionProperty = $reflectionClass->getProperty('entity');
        $reflectionProperty->setAccessible(true);
        $reflectionProperty->setValue($acl, $entity);

        $this->assertTrue($acl->canView());
    }

    /**
     * canView returns false if access not in viewLevels.
     *
     * @return  void
     */
    public function testCanViewReturnsFalseIfAccessNotInViewLevels()
    {
        $acl = $this->getMockBuilder(Acl::class)
            ->disableOriginalConstructor()
            ->setMethods(['canEdit', 'canEditState'])
            ->getMock();

        $acl->method('canEdit')
            ->willReturn(false);

        $acl->method('canEditState')
            ->willReturn(false);

        $entity = $this->getMockBuilder(EntityWithAcl::class)
            ->setMethods(['hasId', 'isPublished', 'has', 'get'])
            ->getMock();

        $entity->method('hasId')
            ->willReturn(true);

        $entity->method('isPublished')
            ->willReturn(true);

        $entity->method('has')
            ->with($this->equalTo('access'))
            ->willReturn(true);

        $entity
            ->method('get')
            ->with($this->equalTo('access'))
            ->will($this->onConsecutiveCalls(5, 7, 12));

        $reflectionClass = new \ReflectionClass($acl);
        $reflectionProperty = $reflectionClass->getProperty('entity');
        $reflectionProperty->setAccessible(true);
        $reflectionProperty->setValue($acl, $entity);

        $user = $this->getMockBuilder('MockedUser')
            ->setMethods(['getAuthorisedViewLevels'])
            ->getMock();

        $user->method('getAuthorisedViewLevels')
            ->willReturn([2, 5, 12]);

        $userProperty = $reflectionClass->getProperty('user');
        $userProperty->setAccessible(true);
        $userProperty->setValue($acl, $user);

        $this->assertTrue($acl->canView());
        $this->assertFalse($acl->canView());
        $this->assertTrue($acl->canView());
    }

    /**
     * Constructor sets entity.
     *
     * @return  void
     */
    public function testConstructorSetsEntityAndUser()
    {
        $entityWithAcl = new EntityWithAcl(666);

        $user = new User(999);

        $decorator = $this->getMockBuilder(Acl::class)
            ->setConstructorArgs([$entityWithAcl, $user])
            ->getMockForAbstractClass();

        $reflectionClass = new \ReflectionClass($decorator);
        $reflectionProperty = $reflectionClass->getProperty('entity');
        $reflectionProperty->setAccessible(true);

        $userProperty = $reflectionClass->getProperty('user');
        $userProperty->setAccessible(true);

        $this->assertSame($entityWithAcl, $reflectionProperty->getValue($decorator));
        $this->assertSame($user, $userProperty->getValue($decorator));
    }

    /**
     * isOwner returns false if no Ownerable instance.
     *
     * @return  void
     */
    public function testIsOwnerReturnsFalseIfNoOwnerableInstance()
    {
        $acl = $this->getMockBuilder(Acl::class)
            ->disableOriginalConstructor()
            ->setMethods(['can'])
            ->getMock();

        $entity = $this->getMockBuilder(EntityWithAcl::class)
            ->setMethods(['isOwner'])
            ->getMock();

        $entity->expects($this->exactly(0))
            ->method('isOwner')
            ->willReturn(true);

        $reflectionClass = new \ReflectionClass($acl);

        $reflectionProperty = $reflectionClass->getProperty('entity');
        $reflectionProperty->setAccessible(true);
        $reflectionProperty->setValue($acl, $entity);

        $reflectionMethod = $reflectionClass->getMethod('isOwner');
        $reflectionMethod->setAccessible(true);

        $this->assertFalse($reflectionMethod->invoke($acl));
    }

    /**
     * isOwner returns entity isOwner if Ownerable.
     *
     * @return  void
     */
    public function testIsOwnerReturnsEntityIsOwnerIfOwnerable()
    {
        $acl = $this->getMockBuilder(Acl::class)
            ->disableOriginalConstructor()
            ->setMethods(['can'])
            ->getMock();

        $entity = $this->getMockBuilder(OwnerableEntityWithAcl::class)
            ->setMethods(['isOwner'])
            ->getMock();

        $entity
            ->method('isOwner')
            ->will($this->onConsecutiveCalls(false, true, false));

        $reflectionClass = new \ReflectionClass($acl);

        $reflectionProperty = $reflectionClass->getProperty('entity');
        $reflectionProperty->setAccessible(true);
        $reflectionProperty->setValue($acl, $entity);

        $reflectionMethod = $reflectionClass->getMethod('isOwner');
        $reflectionMethod->setAccessible(true);

        $this->assertFalse($reflectionMethod->invoke($acl));
        $this->assertTrue($reflectionMethod->invoke($acl));
        $this->assertFalse($reflectionMethod->invoke($acl));
    }

    /**
     * isPublishedEntity returns true when entity does not implement Publishable.
     *
     * @return  void
     */
    public function testIsPublishedEntityReturnsTrueWhenEntityDoesNotImplementPublishable()
    {
        $acl = $this->getMockBuilder(Acl::class)
            ->disableOriginalConstructor()
            ->getMock();

        $entity = $this->getMockBuilder(EntityWithAcl::class)
            ->getMock();

        $reflectionClass = new \ReflectionClass($acl);

        $reflectionProperty = $reflectionClass->getProperty('entity');
        $reflectionProperty->setAccessible(true);
        $reflectionProperty->setValue($acl, $entity);

        $reflectionMethod = $reflectionClass->getMethod('isPublishedEntity');
        $reflectionMethod->setAccessible(true);

        $this->assertTrue($reflectionMethod->invoke($acl));
    }

    /**
     * isPublishedEntity returns entity isPublished for Publishable entities.
     *
     * @return  void
     */
    public function testIsPublishedEntityReturnsEntityIsPublishedIfPublishableEntities()
    {
        $acl = $this->getMockBuilder(Acl::class)
            ->disableOriginalConstructor()
            ->getMock();

        $entity = $this->getMockBuilder(PublishableEntityWithAcl::class)
            ->setMethods(['isPublished'])
            ->getMock();

        $entity
            ->method('isPublished')
            ->will($this->onConsecutiveCalls(false, true, false));

        $reflectionClass = new \ReflectionClass($acl);

        $reflectionProperty = $reflectionClass->getProperty('entity');
        $reflectionProperty->setAccessible(true);
        $reflectionProperty->setValue($acl, $entity);

        $reflectionMethod = $reflectionClass->getMethod('isPublishedEntity');
        $reflectionMethod->setAccessible(true);

        $this->assertFalse($reflectionMethod->invoke($acl));
        $this->assertTrue($reflectionMethod->invoke($acl));
        $this->assertFalse($reflectionMethod->invoke($acl));
    }
}
