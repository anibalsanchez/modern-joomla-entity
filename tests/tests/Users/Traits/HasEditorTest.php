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
 * HasEditor trait tests.
 *
 * @since   1.1.0
 */
class HasEditorTest extends \PHPUnit\Framework\TestCase
{
    /**
     * Name of the editor column.
     *
     * @const
     */
    public const EDITOR_COLUMN = 'modified_by';

    /**
     * @test
     *
     * @return void
     */
    public function editorIdReturnsZeroForMissingEditorColumn()
    {
        $phpUnitFrameworkMockObjectMockObject = $this->getMockBuilder(EntityWithAuthorAndEditor::class)
            ->disableOriginalConstructor()
            ->setMethods(['columnAlias'])
            ->getMock();

        $phpUnitFrameworkMockObjectMockObject->method('columnAlias')
            ->willReturn(static::EDITOR_COLUMN);

        $this->assertSame(0, $phpUnitFrameworkMockObjectMockObject->editorId());
    }

    /**
     * @test
     *
     * @return void
     */
    public function editorIdReturnsCorrectId()
    {
        $phpUnitFrameworkMockObjectMockObject = $this->getMockBuilder(EntityWithAuthorAndEditor::class)
            ->disableOriginalConstructor()
            ->setMethods(['columnAlias'])
            ->getMock();

        $phpUnitFrameworkMockObjectMockObject->method('columnAlias')
            ->willReturn(static::EDITOR_COLUMN);

        $phpUnitFrameworkMockObjectMockObject->bind(
            [
                'id' => 99,
                static::EDITOR_COLUMN => 34,
            ]
        );

        $this->assertSame(34, $phpUnitFrameworkMockObjectMockObject->editorId());

        $phpUnitFrameworkMockObjectMockObject->bind(
            [
                'id' => 99,
                static::EDITOR_COLUMN => 333,
            ]
        );

        $this->assertSame(333, $phpUnitFrameworkMockObjectMockObject->editorId());
    }

    /**
     * editor calls loadEditor.
     *
     * @return  void
     */
    public function testEditorCallsLoadEditor()
    {
        $user = new User(24);

        $phpUnitFrameworkMockObjectMockObject = $this->getMockBuilder(EntityWithAuthorAndEditor::class)
            ->disableOriginalConstructor()
            ->setMethods(['loadEditor'])
            ->getMock();

        $phpUnitFrameworkMockObjectMockObject->expects($this->once())
            ->method('loadEditor')
            ->willReturn($user);

        $this->assertSame($user, $phpUnitFrameworkMockObjectMockObject->editor());
    }

    /**
     * editor returns cached instance.
     *
     * @return  void
     */
    public function testAuthorReturnsCachedInstance()
    {
        $user = new User(999);

        $entityWithAuthorAndEditor = new EntityWithAuthorAndEditor();

        $reflectionClass = new \ReflectionClass($entityWithAuthorAndEditor);

        $reflectionProperty = $reflectionClass->getProperty('editor');
        $reflectionProperty->setAccessible(true);
        $reflectionProperty->setValue($entityWithAuthorAndEditor, $user);

        $this->assertSame($user, $entityWithAuthorAndEditor->editor());
    }

    /**
     * editor reloads data.
     *
     * @return  void
     */
    public function testEditorReloadsData()
    {
        $editor = new User(24);
        $reloadedEditor = new User(999);

        $phpUnitFrameworkMockObjectMockObject = $this->getMockBuilder(EntityWithAuthorAndEditor::class)
            ->disableOriginalConstructor()
            ->setMethods(['loadEditor'])
            ->getMock();

        $phpUnitFrameworkMockObjectMockObject->expects($this->at(0))
            ->method('loadEditor')
            ->willReturn($editor);

        $phpUnitFrameworkMockObjectMockObject->expects($this->at(1))
            ->method('loadEditor')
            ->willReturn($reloadedEditor);

        $this->assertSame($editor, $phpUnitFrameworkMockObjectMockObject->editor());
        $this->assertSame($reloadedEditor, $phpUnitFrameworkMockObjectMockObject->editor(true));
        $this->assertSame($reloadedEditor, $phpUnitFrameworkMockObjectMockObject->editor());
    }

    /**
     * hasEditor returns correct value.
     *
     * @return  void
     */
    public function testHasEditorReturnsCorrectValue()
    {
        $phpUnitFrameworkMockObjectMockObject = $this->getMockBuilder(EntityWithAuthorAndEditor::class)
            ->disableOriginalConstructor()
            ->setMethods(['columnAlias'])
            ->getMock();

        $phpUnitFrameworkMockObjectMockObject->method('columnAlias')
            ->willReturn(static::EDITOR_COLUMN);

        $reflectionClass = new \ReflectionClass($phpUnitFrameworkMockObjectMockObject);

        $reflectionProperty = $reflectionClass->getProperty('id');
        $reflectionProperty->setAccessible(true);

        $reflectionProperty->setValue($phpUnitFrameworkMockObjectMockObject, 999);

        $rowProperty = $reflectionClass->getProperty('row');
        $rowProperty->setAccessible(true);

        $rowProperty->setValue($phpUnitFrameworkMockObjectMockObject, ['id' => 999, static::EDITOR_COLUMN => 22]);

        $this->assertSame(true, $phpUnitFrameworkMockObjectMockObject->hasEditor());
    }

    /**
     * loadEditor returns correct user.
     *
     * @return  void
     */
    public function testLoadEditorReturnsCorrectUser()
    {
        $phpUnitFrameworkMockObjectMockObject = $this->getMockBuilder(EntityWithAuthorAndEditor::class)
            ->disableOriginalConstructor()
            ->setMethods(['columnAlias'])
            ->getMock();

        $phpUnitFrameworkMockObjectMockObject->expects($this->once())
            ->method('columnAlias')
            ->willReturn(static::EDITOR_COLUMN);

        $reflectionClass = new \ReflectionClass($phpUnitFrameworkMockObjectMockObject);

        $reflectionProperty = $reflectionClass->getProperty('id');
        $reflectionProperty->setAccessible(true);
        $reflectionProperty->setValue($phpUnitFrameworkMockObjectMockObject, 999);

        $rowProperty = $reflectionClass->getProperty('row');
        $rowProperty->setAccessible(true);
        $rowProperty->setValue($phpUnitFrameworkMockObjectMockObject, ['id' => 999, static::EDITOR_COLUMN => 22]);

        $reflectionMethod = $reflectionClass->getMethod('loadEditor');
        $reflectionMethod->setAccessible(true);

        $this->assertSame(User::find(22), $reflectionMethod->invoke($phpUnitFrameworkMockObjectMockObject));
    }
}
