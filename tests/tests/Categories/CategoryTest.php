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

namespace Extly\Joomla\Entity\Tests\Categories;

defined('_JEXEC') || die;

use Extly\Joomla\Entity\Categories\Category;
use Extly\Joomla\Entity\Categories\Validation\CategoryValidator;
use Extly\Joomla\Entity\Collection;
use Extly\Joomla\Entity\Translation\Contracts\Translatable;
use Extly\Joomla\Entity\Users\User;
use Joomla\Registry\Registry;

/**
 * Category entity tests.
 *
 * @since   1.1.0
 */
class CategoryTest extends \TestCaseDatabase
{
    /**
     * This method is called before the first test of this test class is run.
     *
     * @return  void
     */
    public static function setUpBeforeClass()
    {
        parent::setUpBeforeClass();

        static::$driver->getConnection()->exec(file_get_contents(JPATH_TESTS_PHPROBERTO.'/tests/Categories/Stubs/Database/associations.sql'));

        \Joomla\CMS\Factory::$database = static::$driver;
    }

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     *
     * @return  void
     */
    protected function setUp()
    {
        parent::setUp();

        $this->saveFactoryState();

        \Joomla\CMS\Factory::$session = $this->getMockSession();
        \Joomla\CMS\Factory::$config = $this->getMockConfig();
        \Joomla\CMS\Factory::$application = $this->getMockCmsApp();
    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     *
     * @return  void
     */
    protected function tearDown()
    {
        Category::clearAll();

        $this->restoreFactoryState();

        parent::tearDown();
    }

    /**
     * @test
     *
     * @return void
     */
    public function ancestorsReturnsExpectedValue()
    {
        $category = new Category();

        $this->assertTrue($category->ancestors()->isEmpty());

        $category = Category::find(21);

        $this->assertSame([1, 14, 19, 20], $category->ancestors()->ids());
    }

    /**
     * @test
     *
     * @return void
     *
     * @expectedException  \Extly\Joomla\Entity\Exception\SaveException
     */
    public function cannotSaveIfValdationFails()
    {
        $category = new Category();
        $category->bind(
            [
                'title' => 'Sample category',
            ]
        );
        $category->save();
    }

    /**
     * @test
     *
     * @return void
     */
    public function descendantsReturnsExpectedValue()
    {
        $category = new Category();

        $this->assertTrue($category->descendants()->isEmpty());

        $category = Category::find(20);

        $this->assertSame([], array_diff($category->descendants()->ids(), [21, 22, 23, 24, 25, 64, 65, 66, 67, 68, 69, 70, 75]));
    }

    /**
     * @test
     *
     * @return void
     */
    public function childrenReturnsChildrenCategories()
    {
        $children = Category::find(37)->children();

        $this->assertInstanceOf(Collection::class, $children);
        $this->assertFalse($children->isEmpty());

        $this->assertFalse(in_array(22, $children->ids()));
    }

    /**
     * @test
     *
     * @return void
     */
    public function editingCategoryKeepsOldData()
    {
        $category = Category::load(37);

        $editedCategory = new Category();
        $editedCategory->bind(
            [
                'id'        => 37,
                'title'     => 'Category edited',
                'extension' => 'com_content',
            ]
        );
        $editedCategory->save();

        $this->assertSame($category->get('lft'), $editedCategory->get('lft'));
        $this->assertSame($category->get('rgt'), $editedCategory->get('rgt'));
    }

    /**
     * @test
     *
     * @return void
     */
    public function implementsTranslatable()
    {
        $category = new Category();

        $this->assertTrue($category instanceof Translatable);
    }

    /**
     * @test
     *
     * @return void
     */
    public function levelReturnsCategoryLevel()
    {
        $this->assertSame(2, Category::find(19)->level());
    }

    /**
     * @test
     *
     * @return void
     */
    public function parentReturnsCorrectParent()
    {
        $parent = Category::find(19)->parent();

        $this->assertInstanceOf(Category::class, $parent);
        $this->assertSame(14, $parent->id());
    }

    /**
     * @test
     *
     * @return void
     */
    public function saveWorksIfValidationPasses()
    {
        $category = new Category();
        $category->bind(
            [
                'title'     => 'My category',
                'extension' => 'com_phproberto',
            ]
        );

        $category->save();

        $this->assertTrue($category->hasId());
    }

    /**
     * @test
     *
     * @return void
     */
    public function searchChildrenReturnsEmptyCollectionForEntityWithoutId()
    {
        $category = new Category();
        $children = $category->children();

        $this->assertInstanceOf(Collection::class, $children);
        $this->assertSame(0, $children->count());
    }

    /**
     * @test
     *
     * @return void
     */
    public function searchChildrenFilters()
    {
        $category = Category::find(37);
        $collection = $category->searchChildren(['filter.published' => 1]);

        $this->assertInstanceOf(Collection::class, $collection);
        $this->assertFalse(in_array(42, $collection->ids()));

        $unpublishedChildren = $category->searchChildren(
            [
                'filter.published' => 0,
            ]
        );

        $this->assertTrue(in_array(42, $unpublishedChildren->ids()));
        $this->assertSame([], array_intersect($collection->ids(), $unpublishedChildren->ids()));

        $allChildren = $category->searchChildren(['filter.published' => [0, 1]]);

        $this->assertSame($collection->ids(), array_intersect($collection->ids(), $allChildren->ids()));
        $this->assertSame($unpublishedChildren->ids(), array_intersect($unpublishedChildren->ids(), $allChildren->ids()));

        $allWithNullChildren = $category->searchChildren(['filter.published' => null]);

        $this->assertSame($allChildren->ids(), $allWithNullChildren->ids());
    }

    /**
     * access retrieved.
     *
     * @return  void
     */
    public function testAccessRetrieved()
    {
        $category = $this->getMockBuilder(Category::class)
            ->setMethods(['columnAlias'])
            ->getMock();

        $category->method('columnAlias')
            ->willReturn('access');

        $reflectionClass = new \ReflectionClass($category);

        $reflectionProperty = $reflectionClass->getProperty('id');
        $reflectionProperty->setAccessible(true);
        $reflectionProperty->setValue($category, 999);

        $rowProperty = $reflectionClass->getProperty('row');
        $rowProperty->setAccessible(true);
        $rowProperty->setValue($category, ['id' => 999, 'access' => 0]);

        $this->assertSame(0, $category->access());

        $rowProperty->setValue($category, ['id' => 999, 'access' => 1]);

        $this->assertSame(1, $category->access());
    }

    /**
     * Asset can be retrieved.
     *
     * @return  void
     */
    public function testAssetCanBeRetrieved()
    {
        $category = new Category();
        $reflectionClass = new \ReflectionClass($category);
        $reflectionProperty = $reflectionClass->getProperty('row');
        $reflectionProperty->setAccessible(true);

        $reflectionProperty->setValue($category, ['id' => 999]);

        $asset = $category->asset();

        $this->assertInstanceOf(\Extly\Joomla\Entity\Core\Asset::class, $asset);
        $this->assertSame(0, $asset->id());

        $category = new Category();

        $reflectionProperty->setValue($category, ['id' => 999, 'asset_id' => 666]);

        $asset = $category->asset();

        $this->assertInstanceOf(\Extly\Joomla\Entity\Core\Asset::class, $asset);
        $this->assertSame(666, $asset->id());
    }

    /**
     * author retrieved.
     *
     * @return  void
     */
    public function testAuthorRetrieved()
    {
        $category = new Category(999);

        $reflectionClass = new \ReflectionClass($category);
        $reflectionProperty = $reflectionClass->getProperty('row');
        $reflectionProperty->setAccessible(true);

        $reflectionProperty->setValue($category, ['id' => 999, 'created_user_id' => 666]);

        $this->assertSame(User::find(666), $category->author());
    }

    /**
     * editor retrieved.
     *
     * @return  void
     */
    public function testEditorRetrieved()
    {
        $category = new Category(999);

        $reflectionClass = new \ReflectionClass($category);
        $reflectionProperty = $reflectionClass->getProperty('row');
        $reflectionProperty->setAccessible(true);

        $reflectionProperty->setValue($category, ['id' => 999, 'modified_user_id' => 666]);

        $this->assertSame(User::find(666), $category->editor());
    }

    /**
     * loadTranslations returns empty collection for missing associations.
     *
     * @return  void
     */
    public function testLoadTranslationsReturnsEmptyCollectionForMissingAssociations()
    {
        $category = new Category();

        $reflectionClass = new \ReflectionClass($category);
        $reflectionMethod = $reflectionClass->getMethod('loadTranslations');
        $reflectionMethod->setAccessible(true);

        $this->assertEquals(new Collection(), $reflectionMethod->invoke($category));
    }

    /**
     * loadTranslations returns correct data for existing associations.
     *
     * @return  void
     */
    public function testLoadTranslationsReturnsCorrectDataForExistingTranslations()
    {
        $category = $this->getMockBuilder(Category::class)
            ->setMethods(['associationsIds'])
            ->getMock();

        $category
            ->expects($this->once())
            ->method('associationsIds')
            ->willReturn([34, 35]);

        $reflectionClass = new \ReflectionClass($category);

        $reflectionMethod = $reflectionClass->getMethod('loadTranslations');
        $reflectionMethod->setAccessible(true);

        $this->assertEquals([34, 35], $reflectionMethod->invoke($category)->ids());
    }

    /**
     * params returns parameters.
     *
     * @return  void
     */
    public function testParamsReturnsParameters()
    {
        $category = new Category(999);

        $reflectionClass = new \ReflectionClass($category);
        $reflectionProperty = $reflectionClass->getProperty('row');
        $reflectionProperty->setAccessible(true);

        $reflectionProperty->setValue($category, ['id' => 999, 'params' => '{"foo":"var"}']);

        $this->assertEquals(new Registry(['foo' => 'var']), $category->params());
    }

    /**
     * table returns correct table instance.
     *
     * @return  void
     */
    public function testTableReturnsCorrectTableInstance()
    {
        $category = new Category();

        $this->assertInstanceOf('CategoriesTableCategory', $category->table());
    }

    /**
     * @test
     *
     * @return void
     */
    public function validatorReturnsCategoryValidatorInstance()
    {
        $category = new Category();

        $validator = $category->validator();

        $this->assertInstanceOf(CategoryValidator::class, $validator);
    }

    /**
     * Gets the data set to be loaded into the database during setup
     *
     * @return  \PHPUnit_Extensions_Database_DataSet_CsvDataSet
     */
    protected function getDataSet()
    {
        $phpUnitExtensionsDatabaseDataSetCsvDataSet = new \PHPUnit_Extensions_Database_DataSet_CsvDataSet(',', "'", '\\');
        $phpUnitExtensionsDatabaseDataSetCsvDataSet->addTable('jos_assets', JPATH_TESTS_PHPROBERTO.'/tests/Categories/Stubs/Database/assets.csv');
        $phpUnitExtensionsDatabaseDataSetCsvDataSet->addTable('jos_categories', JPATH_TESTS_PHPROBERTO.'/tests/Categories/Stubs/Database/categories.csv');
        $phpUnitExtensionsDatabaseDataSetCsvDataSet->addTable('jos_associations', JPATH_TESTS_PHPROBERTO.'/tests/Categories/Stubs/Database/associations.csv');

        return $phpUnitExtensionsDatabaseDataSetCsvDataSet;
    }
}
