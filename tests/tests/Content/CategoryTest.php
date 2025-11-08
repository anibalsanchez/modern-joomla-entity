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

namespace Extly\Joomla\Entity\Tests\Content;

defined('_JEXEC') || die;

use Extly\Joomla\Entity\Acl\Acl;
use Extly\Joomla\Entity\Collection;
use Extly\Joomla\Entity\Content\Article;
use Extly\Joomla\Entity\Content\Category;
use Extly\Joomla\Entity\Tags\Tag;
use Extly\Joomla\Entity\Users\User;

/**
 * Content category entity tests.
 *
 * @since   1.1.0
 */
class CategoryTest extends \TestCaseDatabase
{
    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     *
     * @return  void
     */
    protected function tearDown()
    {
        Category::clearAll();

        parent::tearDown();
    }

    /**
     * @test
     *
     * @return void
     */
    public function contentTypeAliasReturnsExpectedValue()
    {
        $this->assertSame('com_content.category', Category::contentTypeAlias());
    }

    /**
     * @test
     *
     * @return void
     */
    public function searchArticlesReturnsEmptyCollectionForEntityWithoutId()
    {
        $category = new Category();

        $collection = $category->searchArticles();

        $this->assertInstanceOf(Collection::class, $collection);
        $this->assertTrue($collection->isEmpty());
    }

    /**
     * @test
     *
     * @return void
     */
    public function searchArticlesReturnsExpectedArticles()
    {
        $category = Category::find(29);

        $collection = $category->searchArticles();

        $this->assertInstanceOf(Collection::class, $collection);
        $this->assertFalse($collection->isEmpty());

        foreach ($collection as $article) {
            $this->assertSame(29, (int) $article->get('catid'));
        }
    }

    /**
     * Acl can be retrieved.
     *
     * @return  void
     */
    public function testAclCanBeRetrieved()
    {
        $category = new Category(666);
        $user = new User(999);

        $acl = $category->acl($user);

        $reflectionClass = new \ReflectionClass($acl);
        $reflectionProperty = $reflectionClass->getProperty('entity');
        $reflectionProperty->setAccessible(true);

        $userProperty = $reflectionClass->getProperty('user');
        $userProperty->setAccessible(true);

        $this->assertInstanceOf(Acl::class, $acl);
        $this->assertSame($user, $userProperty->getValue($acl));
        $this->assertSame($category, $reflectionProperty->getValue($acl));
    }

    /**
     * loadArticles returns correct value.
     *
     * @return  void
     */
    public function testLoadArticlesReturnsCorrectValue()
    {
        $category = new Category();

        $reflectionClass = new \ReflectionClass($category);
        $reflectionMethod = $reflectionClass->getMethod('loadArticles');
        $reflectionMethod->setAccessible(true);

        $this->assertEquals(new Collection(), $reflectionMethod->invoke($category));

        $articlesItems = [
            (object) [
                'id' => 999,
                'Sample article',
            ],
            (object) [
                'id' => 1000,
                'Sample 1000 article',
            ],
        ];

        $category = $this->getCategoryMock(666, $articlesItems);

        $expectedCollection = new Collection();

        $articles = $reflectionMethod->invoke($category);

        $this->assertInstanceOf(Collection::class, $articles);
        $this->assertSame(2, $articles->count());
        $this->assertTrue($articles->has(999));
        $this->assertTrue($articles->has(1000));
    }

    /**
     * getArticlesModel returns correct model.
     *
     * @return  void
     */
    public function testGetArticlesModelReturnsCorrectModel()
    {
        $category = new Category();

        $reflectionClass = new \ReflectionClass($category);
        $reflectionMethod = $reflectionClass->getMethod('getArticlesModel');
        $reflectionMethod->setAccessible(true);

        $model = $reflectionMethod->invoke($category);

        $this->assertInstanceOf('ContentModelArticles', $model);
        $this->assertSame(null, $model->getState('filter.category_id'));

        $category = new Category(34);

        $model = $reflectionMethod->invoke($category);

        $this->assertInstanceOf('ContentModelArticles', $model);
        $this->assertSame(34, $model->getState('filter.category_id'));
    }

    /**
     * loadTags returns empty collection for missing id.
     *
     * @return  void
     */
    public function testLoadTagsReturnsEmptyCollectionForMissingId()
    {
        $category = new Category();

        $reflectionClass = new \ReflectionClass($category);
        $reflectionMethod = $reflectionClass->getMethod('loadTags');
        $reflectionMethod->setAccessible(true);

        $this->assertEquals(new Collection(), $reflectionMethod->invoke($category));
    }

    /**
     * loadTags loads correct data for existing id.
     *
     * @return  void
     */
    public function testLoadTagsReturnsCorrectDataForExistingId()
    {
        $helperMock = $this->getMockBuilder(\Joomla\CMS\Helper\TagsHelper::class)
            ->disableOriginalConstructor()
            ->setMethods(['getItemTags'])
            ->getMock();

        $helperMock->method('getItemTags')
            ->willReturn(
                [
                    (object) [
                        'id' => 23,
                        'title' => 'Sample tag',
                    ],
                ]
            );

        $entity = $this->getMockBuilder(Category::class)
            ->setMethods(['getTagsHelperInstance'])
            ->getMock();

        $entity
            ->method('getTagsHelperInstance')
            ->willReturn($helperMock);

        $reflection = new \ReflectionClass($entity);
        $reflectionProperty = $reflection->getProperty('id');
        $reflectionProperty->setAccessible(true);

        $reflectionProperty->setValue($entity, 999);

        $reflectionMethod = $reflection->getMethod('loadTags');
        $reflectionMethod->setAccessible(true);

        $tag = new Tag(23);

        $tagReflection = new \ReflectionClass($tag);
        $rowProperty = $reflection->getProperty('row');
        $rowProperty->setAccessible(true);
        $rowProperty->setValue($tag, ['id' => 23, 'title' => 'Sample tag']);

        $this->assertEquals(new Collection([$tag]), $reflectionMethod->invoke($entity));
    }

    /**
     * Gets the data set to be loaded into the database during setup
     *
     * @return  \PHPUnit_Extensions_Database_DataSet_CsvDataSet
     */
    protected function getDataSet()
    {
        $phpUnitExtensionsDatabaseDataSetCsvDataSet = new \PHPUnit_Extensions_Database_DataSet_CsvDataSet(',', "'", '\\');
        $phpUnitExtensionsDatabaseDataSetCsvDataSet->addTable('jos_categories', JPATH_TEST_DATABASE.'/jos_categories.csv');
        $phpUnitExtensionsDatabaseDataSetCsvDataSet->addTable('jos_content', JPATH_TEST_DATABASE.'/jos_content.csv');

        return $phpUnitExtensionsDatabaseDataSetCsvDataSet;
    }

    /**
     * Get a mock of the articles model returning specific items.
     *
     * @param   array  $items  Items returned
     *
     * @return  \PHPUnit_Framework_MockObject_MockObject
     */
    private function getArticlesModelMock(array $items = [])
    {
        $mock = $this->getMockBuilder('ArticlesModelMock')
            ->disableOriginalConstructor()
            ->setMethods(['getItems'])
            ->getMock();

        $mock->expects($this->once())
            ->method('getItems')
            ->willReturn($items);

        return $mock;
    }

    /**
     * Get a mock of a categoryy returning specific items.
     *
     * @param   int  $id     Identifier to assign
     * @param   array    $items  Items returned
     *
     * @return  \PHPUnit_Framework_MockObject_MockObject
     */
    private function getCategoryMock($id = null, array $items = [])
    {
        $category = $this->getMockBuilder(Category::class)
            ->setMethods(['getArticlesModel'])
            ->getMock();

        $category->expects($this->once())
            ->method('getArticlesModel')
            ->willReturn($this->getArticlesModelMock($items));

        if ($id) {
            $reflectionClass = new \ReflectionClass($category);
            $idProperty = $reflectionClass->getProperty('id');
            $idProperty->setAccessible(true);
            $idProperty->setValue($category, $id);
        }

        return $category;
    }
}
