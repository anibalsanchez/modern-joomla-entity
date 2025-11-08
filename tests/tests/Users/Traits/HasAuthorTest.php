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

use Extly\Joomla\Entity\Tests\Users\Traits\Stubs\EntityWithAuthorAndEditor;
use Extly\Joomla\Entity\Users\User;

/**
 * HasAuthor trait tests.
 *
 * @since   1.1.0
 */
class HasAuthorTest extends \PHPUnit\Framework\TestCase
{
    /**
     * Name of the author column.
     *
     * @const
     */
    public const AUTHOR_COLUMN = 'created_by';

    /**
     * @test
     *
     * @return void
     */
    public function authorIdReturnsZeroForMissingAuthorColumn()
    {
        $phpUnitFrameworkMockObjectMockObject = $this->getMockBuilder(EntityWithAuthorAndEditor::class)
            ->disableOriginalConstructor()
            ->setMethods(['columnAlias'])
            ->getMock();

        $phpUnitFrameworkMockObjectMockObject->method('columnAlias')
            ->willReturn(static::AUTHOR_COLUMN);

        $this->assertSame(0, $phpUnitFrameworkMockObjectMockObject->authorId());
    }

    /**
     * @test
     *
     * @return void
     */
    public function authorIdReturnsCorrectId()
    {
        $phpUnitFrameworkMockObjectMockObject = $this->getMockBuilder(EntityWithAuthorAndEditor::class)
            ->disableOriginalConstructor()
            ->setMethods(['columnAlias'])
            ->getMock();

        $phpUnitFrameworkMockObjectMockObject->method('columnAlias')
            ->willReturn(static::AUTHOR_COLUMN);

        $phpUnitFrameworkMockObjectMockObject->bind(
            [
                'id' => 99,
                static::AUTHOR_COLUMN => 34,
            ]
        );

        $this->assertSame(34, $phpUnitFrameworkMockObjectMockObject->authorId());

        $phpUnitFrameworkMockObjectMockObject->bind(
            [
                'id' => 99,
                static::AUTHOR_COLUMN => 333,
            ]
        );

        $this->assertSame(333, $phpUnitFrameworkMockObjectMockObject->authorId());
    }

    /**
     * author calls loadAuthor.
     *
     * @return  void
     */
    public function testAuthorCallsLoadAuthor()
    {
        $user = new User(24);

        $phpUnitFrameworkMockObjectMockObject = $this->getMockBuilder(EntityWithAuthorAndEditor::class)
            ->disableOriginalConstructor()
            ->setMethods(['loadAuthor'])
            ->getMock();

        $phpUnitFrameworkMockObjectMockObject->expects($this->once())
            ->method('loadAuthor')
            ->willReturn($user);

        $this->assertSame($user, $phpUnitFrameworkMockObjectMockObject->author());
    }

    /**
     * author returns cached instance.
     *
     * @return  void
     */
    public function testAuthorReturnsCachedInstance()
    {
        $user = new User(999);

        $entityWithAuthorAndEditor = new EntityWithAuthorAndEditor();

        $reflectionClass = new \ReflectionClass($entityWithAuthorAndEditor);

        $reflectionProperty = $reflectionClass->getProperty('author');
        $reflectionProperty->setAccessible(true);
        $reflectionProperty->setValue($entityWithAuthorAndEditor, $user);

        $this->assertSame($user, $entityWithAuthorAndEditor->author());
    }

    /**
     * author reloads data.
     *
     * @return  void
     */
    public function testAuthorReloadsData()
    {
        $author = new User(24);
        $reloadedAuthor = new User(999);

        $phpUnitFrameworkMockObjectMockObject = $this->getMockBuilder(EntityWithAuthorAndEditor::class)
            ->disableOriginalConstructor()
            ->setMethods(['loadAuthor'])
            ->getMock();

        $phpUnitFrameworkMockObjectMockObject
            ->method('loadAuthor')
            ->will($this->onConsecutiveCalls($author, $reloadedAuthor));

        $this->assertSame($author, $phpUnitFrameworkMockObjectMockObject->author());
        $this->assertSame($reloadedAuthor, $phpUnitFrameworkMockObjectMockObject->author(true));
        $this->assertSame($reloadedAuthor, $phpUnitFrameworkMockObjectMockObject->author());
    }

    /**
     * hasAuthor returns correct value.
     *
     * @return  void
     */
    public function testHasAuthorReturnsCorrectValue()
    {
        $phpUnitFrameworkMockObjectMockObject = $this->getMockBuilder(EntityWithAuthorAndEditor::class)
            ->disableOriginalConstructor()
            ->setMethods(['columnAlias'])
            ->getMock();

        $phpUnitFrameworkMockObjectMockObject->method('columnAlias')
            ->willReturn(static::AUTHOR_COLUMN);

        $this->assertSame(false, $phpUnitFrameworkMockObjectMockObject->hasAuthor());

        $reflectionClass = new \ReflectionClass($phpUnitFrameworkMockObjectMockObject);

        $reflectionProperty = $reflectionClass->getProperty('id');
        $reflectionProperty->setAccessible(true);
        $reflectionProperty->setValue($phpUnitFrameworkMockObjectMockObject, 999);

        $rowProperty = $reflectionClass->getProperty('row');
        $rowProperty->setAccessible(true);
        $rowProperty->setValue($phpUnitFrameworkMockObjectMockObject, ['id' => 999, static::AUTHOR_COLUMN => 22]);

        $this->assertSame(true, $phpUnitFrameworkMockObjectMockObject->hasAuthor());
    }

    /**
     * loadAuthor returns correct user.
     *
     * @return  void
     */
    public function testLoadAuthorReturnsCorrectUser()
    {
        $phpUnitFrameworkMockObjectMockObject = $this->getMockBuilder(EntityWithAuthorAndEditor::class)
            ->disableOriginalConstructor()
            ->setMethods(['columnAlias'])
            ->getMock();

        $phpUnitFrameworkMockObjectMockObject->expects($this->once())
            ->method('columnAlias')
            ->willReturn(static::AUTHOR_COLUMN);

        $reflectionClass = new \ReflectionClass($phpUnitFrameworkMockObjectMockObject);

        $reflectionProperty = $reflectionClass->getProperty('id');
        $reflectionProperty->setAccessible(true);
        $reflectionProperty->setValue($phpUnitFrameworkMockObjectMockObject, 999);

        $rowProperty = $reflectionClass->getProperty('row');
        $rowProperty->setAccessible(true);
        $rowProperty->setValue($phpUnitFrameworkMockObjectMockObject, ['id' => 999, static::AUTHOR_COLUMN => 22]);

        $reflectionMethod = $reflectionClass->getMethod('loadAuthor');
        $reflectionMethod->setAccessible(true);

        $this->assertSame(User::find(22), $reflectionMethod->invoke($phpUnitFrameworkMockObjectMockObject));
    }
}
