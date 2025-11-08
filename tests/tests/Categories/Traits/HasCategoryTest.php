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
use Extly\Joomla\Entity\Tests\Categories\Traits\Stubs\ClassWithCategory;

/**
 * HasCategory trait tests.
 *
 * @since   1.1.0
 */
class HasCategoryTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @test
     *
     * @return void
     */
    public function categoryIdReturnsZeroForUnexistingColumn()
    {
        $classWithCategory = new ClassWithCategory();

        $this->assertSame(0, $classWithCategory->categoryId());
    }

    /**
     * @test
     *
     * @return void
     */
    public function categoryIdReturnsExpectedValue()
    {
        $classWithCategory = new ClassWithCategory();
        $classWithCategory->bind(
            [
                'id' => 45,
                'category_id' => 12,
            ]
        );

        $this->assertSame(12, $classWithCategory->categoryId());
    }

    /**
     * @test
     *
     * @return void
     */
    public function categoryIdReturnsExpectedValueWithCustomCategoryColumn()
    {
        $phpUnitFrameworkMockObjectMockObject = $this->getMockBuilder(ClassWithCategory::class)
            ->setMethods(['columnAlias'])
            ->getMock();

        $phpUnitFrameworkMockObjectMockObject->method('columnAlias')
            ->willReturn('catid');

        $phpUnitFrameworkMockObjectMockObject->bind(
            [
                'id' => 45,
                'catid' => 23,
            ]
        );

        $this->assertSame(23, $phpUnitFrameworkMockObjectMockObject->categoryId());
    }

    /**
     * getCategory works for catid column.
     *
     * @return  void
     */
    public function testGetCategoryWorksForCatidColumn()
    {
        $phpUnitFrameworkMockObjectMockObject = $this->getMockBuilder(ClassWithCategory::class)
            ->setMethods(['columnAlias'])
            ->getMock();

        $phpUnitFrameworkMockObjectMockObject->method('columnAlias')
            ->willReturn('catid');

        $reflectionClass = new \ReflectionClass($phpUnitFrameworkMockObjectMockObject);
        $reflectionProperty = $reflectionClass->getProperty('row');
        $reflectionProperty->setAccessible(true);

        $reflectionProperty->setValue($phpUnitFrameworkMockObjectMockObject, ['id' => 999, 'catid' => 666]);

        $this->assertSame(666, $phpUnitFrameworkMockObjectMockObject->category()->id());
    }

    /**
     * getCategory works for category_id column.
     *
     * @return  void
     */
    public function testGetCategoryWorksForCategoryIdColumn()
    {
        $classWithCategory = new ClassWithCategory();

        $reflectionClass = new \ReflectionClass($classWithCategory);
        $reflectionProperty = $reflectionClass->getProperty('row');
        $reflectionProperty->setAccessible(true);

        $reflectionProperty->setValue($classWithCategory, ['id' => 999, 'category_id' => 666]);

        $this->assertEquals(new Category(666), $classWithCategory->category());
    }

    /**
     * getCategory reload works.
     *
     * @return  void
     */
    public function testGetCategoryReloadWorks()
    {
        $classWithCategory = new ClassWithCategory();

        $reflectionClass = new \ReflectionClass($classWithCategory);
        $reflectionProperty = $reflectionClass->getProperty('row');
        $reflectionProperty->setAccessible(true);

        $reflectionProperty->setValue($classWithCategory, ['id' => 999, 'category_id' => 666]);

        $this->assertEquals(new Category(666), $classWithCategory->category());

        $reflectionProperty->setValue($classWithCategory, ['id' => 999, 'category_id' => 667]);

        $this->assertEquals(new Category(666), $classWithCategory->category());
        $this->assertEquals(new Category(667), $classWithCategory->category(true));
    }

    /**
     * getCategory returns empty category for unset column.
     *
     * @return  void
     */
    public function testGetCategoryReturnsEmptyCategoryForUnsetColumn()
    {
        $classWithCategory = new ClassWithCategory();

        $reflectionClass = new \ReflectionClass($classWithCategory);
        $reflectionProperty = $reflectionClass->getProperty('row');
        $reflectionProperty->setAccessible(true);

        $reflectionProperty->setValue($classWithCategory, ['id' => 999, 'name' => 'Sample class']);

        $this->assertEquals(new Category(), $classWithCategory->category());
    }
}
