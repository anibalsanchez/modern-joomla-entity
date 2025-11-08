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

namespace Extly\Joomla\Tests\Core\Traits;

use Extly\Joomla\Entity\Tests\Core\Traits\Stubs\AnotherClassWithInstances;
use Extly\Joomla\Entity\Tests\Core\Traits\Stubs\ClassWithInstances;

/**
 * Tests for HasInstances trait.
 *
 * @since  1.1.0
 */
class HasInstancesTest extends \TestCase
{
    /**
     * Test constructor.
     *
     * @return  void
     */
    public function testConstructor()
    {
        $class = new ClassWithInstances(1337);

        $this->assertEquals(1337, $class->getId());
        $class->setName('Sample name');
        $this->assertEquals('Sample name', $class->getName());

        $class2 = new ClassWithInstances(1337);
        $this->assertNotEquals('Sample name', $class2->getName());
    }

    /**
     * clearAll clears all the instances.
     *
     * @return  void
     */
    public function testClearAllClearsAllTheInstances()
    {
        $reflectionClass = new \ReflectionClass(ClassWithInstances::class);
        $reflectionProperty = $reflectionClass->getProperty('instances');
        $reflectionProperty->setAccessible(true);

        $instances = [
            ClassWithInstances::class => [
                1337 => new ClassWithInstances(1337),
                1338 => new ClassWithInstances(1338),
            ],
        ];

        $reflectionProperty->setValue(ClassWithInstances::class, $instances);

        $this->assertEquals($instances, $reflectionProperty->getValue(ClassWithInstances::class));

        ClassWithInstances::clearAll();

        $this->assertEquals([], $reflectionProperty->getValue(ClassWithInstances::class));
    }

    /**
     * Test clear method.
     *
     * @return  void
     */
    public function testClear()
    {
        $classWithInstances = ClassWithInstances::find(1337);
        $classWithInstances->setName('Sample name');
        $this->assertEquals('Sample name', $classWithInstances->getName());

        $class2 = ClassWithInstances::find(1337);
        $this->assertEquals('Sample name', $class2->getName());

        ClassWithInstances::clear(1337);

        $class3 = ClassWithInstances::find(1337);
        $this->assertNotEquals('Sample name', $class3->getName());
    }

    /**
     * Test getFresh method.
     *
     * @return  void
     */
    public function testFresh()
    {
        $classWithInstances = ClassWithInstances::find(1337);
        $classWithInstances->setName('Sample name');
        $this->assertEquals('Sample name', $classWithInstances->getName());

        $class3 = ClassWithInstances::fresh(1337);
        $this->assertNotEquals('Sample name', $class3->getName());
    }

    /**
     * Test find method.
     *
     * @return  void
     */
    public function testFind()
    {
        $classWithInstances = ClassWithInstances::find(1337);
        $classWithInstances->setName('Sample name');
        $this->assertEquals('Sample name', $classWithInstances->getName());

        $class2 = ClassWithInstances::find(1337);
        $this->assertEquals('Sample name', $class2->getName());
    }

    /**
     * Test that different classes using the same id.
     *
     * @return  void
     */
    public function testNoCollisionsBetweenClasses()
    {
        $classWithInstances = ClassWithInstances::find(1337);
        $classWithInstances->setName('Sample name');
        $this->assertEquals('Sample name', $classWithInstances->getName());

        $anotherClassWithInstances = AnotherClassWithInstances::find(1337);
        $this->assertNotEquals('Sample name', $anotherClassWithInstances->getName());

        $class3 = ClassWithInstances::find(1337);
        $this->assertEquals('Sample name', $class3->getName());
    }
}
