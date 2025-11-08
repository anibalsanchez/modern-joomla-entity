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
use Extly\Joomla\Entity\Tests\Core\Traits\Stubs\EntityWithDescendants;

/**
 * HasDescendants tests.
 *
 * @since   1.4.0
 */
class HasDescendantsTest extends \TestCaseDatabase
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

        $this->entity = new EntityWithDescendants();
        $this->entity->bind(['id' => 666, 'name' => 'Testing entity']);
        $this->entity->loadableDescendants = new Collection(
            array_map(
                function ($data) {
                    $entityWithDescendants = new EntityWithDescendants();
                    $entityWithDescendants->bind($data);

                    return $entityWithDescendants;
                },
                [
                    ['id' => 5001, 'name' => 'First descendant'],
                    ['id' => 5003, 'name' => 'Second descendant'],
                    ['id' => 5005, 'name' => 'Third descendant'],
                    ['id' => 5002, 'name' => 'Fourth descendant'],
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
        EntityWithDescendants::clearAll();

        parent::tearDown();
    }

    /**
     * @test
     *
     * @return void
     */
    public function descendantReturnsSpecificDescendant()
    {
        $descendant = $this->entity->descendant(5003);

        $this->assertInstanceOf(EntityWithDescendants::class, $descendant);
        $this->assertSame(5003, $descendant->id());
    }

    /**
     * @test
     *
     * @return void
     */
    public function descendantsReturnsExpectedDescendants()
    {
        $descendants = $this->entity->descendants();

        $this->assertInstanceOf(Collection::class, $descendants);
        $this->assertEquals([5001, 5003, 5005, 5002], $descendants->ids());
    }

    /**
     * @test
     *
     * @return void
     */
    public function hasDescendantReturnsExpectedValue()
    {
        $this->assertFalse($this->entity->hasDescendant(5000));
        $this->assertTrue($this->entity->hasDescendant(5001));
        $this->assertFalse($this->entity->hasDescendant(5004));
        $this->assertTrue($this->entity->hasDescendant(5002));
    }

    /**
     * @test
     *
     * @return void
     */
    public function descendantsReturnsExpectedValue()
    {
        $entityWithDescendants = new EntityWithDescendants();

        $this->assertFalse($entityWithDescendants->hasDescendants());

        $this->assertTrue($this->entity->hasDescendants());
    }
}
