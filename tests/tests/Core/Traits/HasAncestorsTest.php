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
use Extly\Joomla\Entity\Tests\Core\Traits\Stubs\EntityWithAncestors;

/**
 * HasAncestors tests.
 *
 * @since   1.4.0
 */
class HasAncestorsTest extends \TestCaseDatabase
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

        $this->entity = new EntityWithAncestors();
        $this->entity->bind(['id' => 666, 'name' => 'Testing entity']);
        $this->entity->loadableAncestors = new Collection(
            array_map(
                function ($data) {
                    $entityWithAncestors = new EntityWithAncestors();
                    $entityWithAncestors->bind($data);

                    return $entityWithAncestors;
                },
                [
                    ['id' => 1001, 'name' => 'Top ancestor'],
                    ['id' => 1003, 'name' => 'An ancestor'],
                    ['id' => 1005, 'name' => 'Another ancestor'],
                    ['id' => 1002, 'name' => 'Yet another ancestor'],
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
        EntityWithAncestors::clearAll();

        parent::tearDown();
    }

    /**
     * @test
     *
     * @return void
     */
    public function ancestorReturnsSpecificAncestor()
    {
        $ancestor = $this->entity->ancestor(1003);

        $this->assertInstanceOf(EntityWithAncestors::class, $ancestor);
        $this->assertSame(1003, $ancestor->id());
    }

    /**
     * @test
     *
     * @return void
     */
    public function ancestorsReturnsExpectedAncestors()
    {
        $ancestors = $this->entity->ancestors();

        $this->assertInstanceOf(Collection::class, $ancestors);
        $this->assertEquals([1001, 1003, 1005, 1002], $ancestors->ids());
    }

    /**
     * @test
     *
     * @return void
     */
    public function hasAncestorReturnsExpectedValue()
    {
        $this->assertFalse($this->entity->hasAncestor(1000));
        $this->assertTrue($this->entity->hasAncestor(1001));
        $this->assertFalse($this->entity->hasAncestor(1004));
        $this->assertTrue($this->entity->hasAncestor(1002));
    }

    /**
     * @test
     *
     * @return void
     */
    public function ancestorsReturnsExpectedValue()
    {
        $entityWithAncestors = new EntityWithAncestors();

        $this->assertFalse($entityWithAncestors->hasAncestors());

        $this->assertTrue($this->entity->hasAncestors());
    }
}
