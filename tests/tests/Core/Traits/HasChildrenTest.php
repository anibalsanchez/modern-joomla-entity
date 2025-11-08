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

defined('_JEXEC') || die;

use Extly\Joomla\Entity\Collection;
use Extly\Joomla\Entity\Tests\Core\Traits\Stubs\EntityWithChildren;

/**
 * HasChildren tests.
 *
 * @since   1.4.0
 */
class HasChildrenTest extends \TestCaseDatabase
{
    public $entity;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     *
     * @return  void
     */
    protected function setUp()
    {
        parent::setUp();

        $this->entity = new EntityWithChildren();
        $this->entity->bind(['id' => 666, 'name' => 'Testing entity']);
        $this->entity->loadableChildren = new Collection(
            array_map(
                function ($data) {
                    $entityWithChildren = new EntityWithChildren();
                    $entityWithChildren->bind($data);

                    return $entityWithChildren;
                },
                [
                    ['id' => 9001, 'name' => 'First child'],
                    ['id' => 9003, 'name' => 'Second child'],
                    ['id' => 9005, 'name' => 'Third child'],
                    ['id' => 9002, 'name' => 'Fourth child'],
                ]
            )
        );
    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     *
     * @return  void
     */
    protected function tearDown()
    {
        EntityWithChildren::clearAll();

        parent::tearDown();
    }

    /**
     * @test
     *
     * @return void
     */
    public function childReturnsSpecificChild()
    {
        $child = $this->entity->child(9003);

        $this->assertInstanceOf(EntityWithChildren::class, $child);
        $this->assertSame(9003, $child->id());
    }

    /**
     * @test
     *
     * @return void
     */
    public function childrenReturnsExpectedChildren()
    {
        $children = $this->entity->children();

        $this->assertInstanceOf(Collection::class, $children);
        $this->assertEquals([9001, 9003, 9005, 9002], $children->ids());
    }

    /**
     * @test
     *
     * @return void
     */
    public function hasChildReturnsExpectedValue()
    {
        $this->assertFalse($this->entity->hasChild(9000));
        $this->assertTrue($this->entity->hasChild(9001));
        $this->assertFalse($this->entity->hasChild(9004));
        $this->assertTrue($this->entity->hasChild(9002));
    }

    /**
     * @test
     *
     * @return void
     */
    public function hasChildrenReturnsExpectedValue()
    {
        $entityWithChildren = new EntityWithChildren();

        $this->assertFalse($entityWithChildren->hasChildren());

        $this->assertTrue($this->entity->hasChildren());
    }
}
