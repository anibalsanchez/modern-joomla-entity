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

namespace Extly\Joomla\Entity\Tests\Tags\Traits;

use Extly\Joomla\Entity\Collection;
use Extly\Joomla\Entity\Tags\Tag;
use Extly\Joomla\Entity\Tests\Tags\Traits\Stubs\ClassWithSearchableTags;
use Extly\Joomla\Entity\Tests\Tags\Traits\Stubs\ClassWithTags;
use Joomla\CMS\Factory;

/**
 * HasTags trait tests.
 *
 * @since   1.1.0
 */
class HasTagsTest extends \TestCaseDatabase
{
    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     *
     * @return  void
     */
    protected function tearDown()
    {
        ClassWithTags::clearAll();

        parent::tearDown();
    }

    /**
     * @test
     *
     * @return void
     */
    public function contentTypeAliasReturnsExpectedString()
    {
        $this->assertSame('', ClassWithTags::contentTypeAlias());
    }

    /**
     * @test
     *
     * @return void
     */
    public function loadTagsReturnsEmptyCollectionForEntityWithoutId()
    {
        $classWithSearchableTags = new ClassWithSearchableTags();

        $reflectionClass = new \ReflectionClass($classWithSearchableTags);
        $reflectionMethod = $reflectionClass->getMethod('loadTags');
        $reflectionMethod->setAccessible(true);

        $tags = $reflectionMethod->invoke($classWithSearchableTags);

        $this->assertInstanceOf(Collection::class, $tags);
        $this->assertTrue($tags->isEmpty());
    }

    /**
     * @test
     *
     * @return void
     */
    public function loadTagsReturnsEmptyCollectionForEntityWithoutContentTypeAlias()
    {
        $classWithTags = new ClassWithTags(15);

        $reflectionClass = new \ReflectionClass($classWithTags);
        $reflectionMethod = $reflectionClass->getMethod('loadTags');
        $reflectionMethod->setAccessible(true);

        $tags = $reflectionMethod->invoke($classWithTags);

        $this->assertInstanceOf(Collection::class, $tags);
        $this->assertTrue($tags->isEmpty());
    }

    /**
     * @test
     *
     * @return void
     */
    public function loadTagsReturnsExpectedTags()
    {
        $mockSession = $this->getMockBuilder('JSession')
            ->setMethods(['_start', 'get'])
            ->getMock();

        $mockSession->expects($this->once())
            ->method('get')
            ->will($this->returnValue(new \JUser(42)));

        Factory::$session = $mockSession;

        $classWithSearchableTags = new ClassWithSearchableTags(15);

        $reflectionClass = new \ReflectionClass($classWithSearchableTags);
        $reflectionMethod = $reflectionClass->getMethod('loadTags');
        $reflectionMethod->setAccessible(true);

        $tags = $reflectionMethod->invoke($classWithSearchableTags);

        $this->assertInstanceOf(Collection::class, $tags);
        $this->assertFalse($tags->isEmpty());
    }

    /**
     * @test
     *
     * @return void
     */
    public function removeAllTagsRemovesAssignedTags()
    {
        $entity = $this->getMockBuilder(ClassWithSearchableTags::class)
            ->setConstructorArgs([15])
            ->setMethods(['getDbo'])
            ->getMock();

        $entity->expects($this->once())
            ->method('getDbo')
            ->willReturn(Factory::getDbo());

        $tags = $entity->searchTags();

        $this->assertInstanceOf(Collection::class, $tags);
        $this->assertFalse($tags->isEmpty());

        $entity->removeAllTags();

        $this->assertTrue($entity->searchTags()->isEmpty());
    }

    /**
     * @test
     *
     * @return void
     *
     * @expectedException  \RuntimeException
     */
    public function removeAllTagsThrowsExceptionForEntityWithoutId()
    {
        $classWithSearchableTags = new ClassWithSearchableTags();

        $classWithSearchableTags->removeAllTags();
    }

    /**
     * @test
     *
     * @return void
     */
    public function searchTagsReturnsEmptyCollectionForMissingId()
    {
        $classWithSearchableTags = new ClassWithSearchableTags();

        $collection = $classWithSearchableTags->searchTags();

        $this->assertInstanceOf(Collection::class, $collection);
        $this->assertTrue($collection->isEmpty());
    }

    /**
     * @test
     *
     * @return void
     */
    public function searchTagsReturnsEmptyCollectionForMissingAlias()
    {
        $classWithTags = new ClassWithTags();

        $collection = $classWithTags->searchTags();

        $this->assertInstanceOf(Collection::class, $collection);
        $this->assertTrue($collection->isEmpty());
    }

    /**
     * @test
     *
     * @return void
     */
    public function searchTagsReturnsExpectedTags()
    {
        $classWithSearchableTags = new ClassWithSearchableTags(15);

        $collection = $classWithSearchableTags->searchTags();

        $this->assertInstanceOf(Collection::class, $collection);
        $this->assertSame([4, 6], $collection->ids());
    }

    /**
     * clearTags clears tags property.
     *
     * @return  void
     */
    public function testClearTagsClearsTagsProperty()
    {
        $classWithTags = new ClassWithTags();

        $reflectionClass = new \ReflectionClass($classWithTags);
        $reflectionProperty = $reflectionClass->getProperty('tags');
        $reflectionProperty->setAccessible(true);

        $this->assertEquals(null, $reflectionProperty->getValue($classWithTags));

        $collection = new Collection(
            [
                new Tag(23),
                new Tag(24),
                new Tag(25),
            ]
        );

        $reflectionProperty->setValue($classWithTags, $collection);
        $this->assertEquals($collection, $reflectionProperty->getValue($classWithTags));

        $classWithTags->clearTags();
        $this->assertEquals(null, $reflectionProperty->getValue($classWithTags));
    }

    /**
     * clearTags is chainable.
     *
     * @return  void
     */
    public function testClearTagsIsChainable()
    {
        $classWithTags = new ClassWithTags();

        $this->assertTrue($classWithTags->clearTags() instanceof ClassWithTags);
    }

    /**
     * getTagsHelper returns correct class.
     *
     * @return  void
     */
    public function testGetTagsHelperReturnsCorrectInstance()
    {
        $classWithTags = new ClassWithTags();

        $reflectionClass = new \ReflectionClass($classWithTags);
        $reflectionMethod = $reflectionClass->getMethod('getTagsHelperInstance');
        $reflectionMethod->setAccessible(true);

        $this->assertTrue($reflectionMethod->invoke($classWithTags) instanceof \Joomla\CMS\Helper\TagsHelper);
    }

    /**
     * tags returns correct data.
     *
     * @return  void
     */
    public function testTagsReturnsCorrectData()
    {
        $classWithTags = new ClassWithTags();

        $this->assertEquals(new Collection(), $classWithTags->tags());

        $classWithTags->tagsIds = [999];

        // Previous data with no reload
        $this->assertEquals(new Collection(), $classWithTags->tags());
        $this->assertEquals(new Collection([new Tag(999)]), $classWithTags->tags(true));
    }

    /**
     * hasTag returns correct value.
     *
     * @return  void
     */
    public function testHasTagReturnsCorrectValue()
    {
        $classWithTags = new ClassWithTags();

        $classWithTags->tagsIds = [999, 1001, 1003];

        $this->assertFalse($classWithTags->hasTag(998));
        $this->assertTrue($classWithTags->hasTag(999));
        $this->assertFalse($classWithTags->hasTag(1000));
        $this->assertTrue($classWithTags->hasTag(1001));
        $this->assertFalse($classWithTags->hasTag(1002));
        $this->assertTrue($classWithTags->hasTag(1003));
    }

    /**
     * hasTags returns correct value.
     *
     * @return  void
     */
    public function testHasTagsReturnsCorrectValue()
    {
        $entity = new ClassWithTags();

        $this->assertFalse($entity->hasTags());

        $entity = new ClassWithTags();
        $entity->tagsIds = [999, 1001, 1003];

        $this->assertTrue($entity->hasTags());
    }

    /**
     * Gets the data set to be loaded into the database during setup
     *
     * @return  \PHPUnit_Extensions_Database_DataSet_CsvDataSet
     */
    protected function getDataSet()
    {
        $phpUnitExtensionsDatabaseDataSetCsvDataSet = new \PHPUnit_Extensions_Database_DataSet_CsvDataSet(',', "'", '\\');
        $phpUnitExtensionsDatabaseDataSetCsvDataSet->addTable('jos_tags', dirname(__DIR__).'/Stubs/Database/tags.csv');
        $phpUnitExtensionsDatabaseDataSetCsvDataSet->addTable('jos_contentitem_tag_map', dirname(__DIR__).'/Stubs/Database/contentitem_tag_map.csv');

        return $phpUnitExtensionsDatabaseDataSetCsvDataSet;
    }
}
