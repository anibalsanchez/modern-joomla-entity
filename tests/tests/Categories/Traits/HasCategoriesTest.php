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

namespace Extly\Joomla\Entity\Tests\Categories\Traits;

use Extly\Joomla\Entity\Categories\Category;
use Extly\Joomla\Entity\Collection;
use Extly\Joomla\Entity\Tests\Categories\Traits\Stubs\ClassWithCategories;

/**
 * HasCategories trait tests.
 *
 * @since   1.1.0
 */
class HasCategoriesTest extends \PHPUnit\Framework\TestCase
{
    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     *
     * @return  void
     */
    protected function tearDown()
    {
        ClassWithCategories::clearAll();

        parent::tearDown();
    }

    /**
     * clearCategories clears categories property.
     *
     * @return  void
     */
    public function testClearCategoriesClearsCategoriesProperty()
    {
        $classWithCategories = new ClassWithCategories();

        $reflectionClass = new \ReflectionClass($classWithCategories);
        $reflectionProperty = $reflectionClass->getProperty('categories');
        $reflectionProperty->setAccessible(true);

        $this->assertEquals(null, $reflectionProperty->getValue($classWithCategories));

        $collection = new Collection(
            [
                new Category(23),
                new Category(24),
                new Category(25),
            ]
        );

        $reflectionProperty->setValue($classWithCategories, $collection);
        $this->assertEquals($collection, $reflectionProperty->getValue($classWithCategories));

        $classWithCategories->clearCategories();
        $this->assertEquals(null, $reflectionProperty->getValue($classWithCategories));
    }

    /**
     * clearCategories is chainable.
     *
     * @return  void
     */
    public function testClearCategoriesIsChainable()
    {
        $classWithCategories = new ClassWithCategories();

        $this->assertTrue($classWithCategories->clearCategories() instanceof ClassWithCategories);
    }

    /**
     * getCategories returns correct data.
     *
     * @return  void
     */
    public function testGetCategoriesReturnsCorrectData()
    {
        $classWithCategories = new ClassWithCategories();

        $this->assertEquals(new Collection(), $classWithCategories->categories());

        $classWithCategories->categoriesIds = [999];

        // Previous data with no reload
        $this->assertEquals(new Collection(), $classWithCategories->categories());
        $this->assertEquals(new Collection([new Category(999)]), $classWithCategories->categories(true));
    }

    /**
     * hasCategory returns correct value.
     *
     * @return  void
     */
    public function testHasCategoryReturnsCorrectValue()
    {
        $classWithCategories = new ClassWithCategories();

        $classWithCategories->categoriesIds = [999, 1001, 1003];

        $this->assertFalse($classWithCategories->hasCategory(998));
        $this->assertTrue($classWithCategories->hasCategory(999));
        $this->assertFalse($classWithCategories->hasCategory(1000));
        $this->assertTrue($classWithCategories->hasCategory(1001));
        $this->assertFalse($classWithCategories->hasCategory(1002));
        $this->assertTrue($classWithCategories->hasCategory(1003));
    }

    /**
     * hasCategories returns correct value.
     *
     * @return  void
     */
    public function testHasCategoriesReturnsCorrectValue()
    {
        $entity = new ClassWithCategories();

        $this->assertFalse($entity->hasCategories());

        $entity = new ClassWithCategories();
        $entity->categoriesIds = [999, 1001, 1003];

        $this->assertTrue($entity->hasCategories());
    }
}
