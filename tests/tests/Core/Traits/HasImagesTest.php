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

use Extly\Joomla\Entity\Tests\Core\Traits\Stubs\EntityWithImages;

/**
 * HasImages trait tests.
 *
 * @since   1.1.0
 */
class HasImagesTest extends \PHPUnit\Framework\TestCase
{
    /**
     * getFullTextImage returns correct value
     *
     * @return  void
     */
    public function testGetFullTextImageReturnsCorrectValue()
    {
        $entity = $this->getEntity(['id' => 999, 'images' => '']);

        $this->assertEquals([], $entity->getFullTextImage());

        $entity = $this->getEntity(['id' => 999, 'images' => '{"image_fulltext":"images\/joomla_black.png"}']);

        $this->assertEquals(['url' => 'images/joomla_black.png'], $entity->getFullTextImage());

        $entity = $this->getEntity(['id' => 999, 'images' => '{"image_intro":"","float_intro":"","image_intro_alt":"","image_intro_caption":"","image_fulltext":"","float_fulltext":"","image_fulltext_alt":"","image_fulltext_caption":""}']);

        $this->assertEquals([], $entity->getFullTextImage());
    }

    /**
     * getImages returns intro image if exists.
     *
     * @return  void
     */
    public function testGetImagesReturnsFullImageIfExists()
    {
        $_SERVER['HTTP_HOST'] = 'joomla-entity.test.com';
        $_SERVER['SCRIPT_NAME'] = '/index.php';

        $entity = $this->getEntity(['id' => 999, 'images' => '{"image_fulltext":"images\/joomla_black.png"}']);

        $images = $entity->getImages(true);

        $this->assertEquals('images/joomla_black.png', $images['full']['url']);
        $this->assertFalse(isset($images['full']['float']));
        $this->assertFalse(isset($images['full']['alt']));
        $this->assertFalse(isset($images['full']['caption']));

        $entity = $this->getEntity(['id' => 999, 'images' => '{"image_fulltext":"images\/joomla_black.png","float_fulltext":"left"}']);

        $images = $entity->getImages(true);

        $this->assertEquals('images/joomla_black.png', $images['full']['url']);
        $this->assertEquals('left', $images['full']['float']);
        $this->assertFalse(isset($images['full']['alt']));
        $this->assertFalse(isset($images['full']['caption']));

        $entity = $this->getEntity(['id' => 999, 'images' => '{"image_fulltext":"images\/joomla_black.png","float_fulltext":"left","image_fulltext_alt":"Alt text"}']);

        $images = $entity->getImages(true);

        $this->assertEquals('images/joomla_black.png', $images['full']['url']);
        $this->assertEquals('left', $images['full']['float']);
        $this->assertEquals('Alt text', $images['full']['alt']);
        $this->assertFalse(isset($images['full']['caption']));

        $entity = $this->getEntity(['id' => 999, 'images' => '{"image_fulltext":"images\/joomla_black.png","float_fulltext":"left","image_fulltext_alt":"Alt text","image_fulltext_caption":"Caption text"}']);

        $images = $entity->getImages(true);

        $this->assertEquals('images/joomla_black.png', $images['full']['url']);
        $this->assertEquals('left', $images['full']['float']);
        $this->assertEquals('Alt text', $images['full']['alt']);
        $this->assertEquals('Caption text', $images['full']['caption']);
    }

    /**
     * getImages returns intro image if exists.
     *
     * @return  void
     */
    public function testGetImagesReturnsIntroImageIfExists()
    {
        $_SERVER['HTTP_HOST'] = 'joomla-entity.test.com';
        $_SERVER['SCRIPT_NAME'] = '/index.php';

        $entity = $this->getEntity(['id' => 999, 'images' => '{"image_intro":"images\/joomla_black.png"}']);

        $images = $entity->getImages(true);

        $this->assertTrue(isset($entity->getImages()['intro']));
        $this->assertEquals('images/joomla_black.png', $images['intro']['url']);
        $this->assertFalse(isset($images['intro']['float']));
        $this->assertFalse(isset($images['intro']['alt']));
        $this->assertFalse(isset($images['intro']['caption']));

        $entity = $this->getEntity(['id' => 999, 'images' => '{"image_intro":"images\/joomla_black.png","float_intro":"left"}']);

        $images = $entity->getImages(true);

        $this->assertTrue(isset($images['intro']));
        $this->assertEquals('images/joomla_black.png', $images['intro']['url']);
        $this->assertEquals('left', $images['intro']['float']);
        $this->assertFalse(isset($images['intro']['alt']));
        $this->assertFalse(isset($images['intro']['caption']));

        $entity = $this->getEntity(['id' => 999, 'images' => '{"image_intro":"images\/joomla_black.png","float_intro":"left","image_intro_alt":"Alt text"}']);

        $images = $entity->getImages(true);

        $this->assertTrue(isset($images['intro']));
        $this->assertEquals('images/joomla_black.png', $images['intro']['url']);
        $this->assertEquals('left', $images['intro']['float']);
        $this->assertEquals('Alt text', $images['intro']['alt']);
        $this->assertFalse(isset($images['intro']['caption']));

        $entity = $this->getEntity(['id' => 999, 'images' => '{"image_intro":"images\/joomla_black.png","float_intro":"left","image_intro_alt":"Alt text","image_intro_caption":"Caption text"}']);

        $images = $entity->getImages(true);

        $this->assertTrue(isset($images['intro']));
        $this->assertEquals('images/joomla_black.png', $images['intro']['url']);
        $this->assertEquals('left', $images['intro']['float']);
        $this->assertEquals('Alt text', $images['intro']['alt']);
        $this->assertEquals('Caption text', $images['intro']['caption']);
    }

    /**
     * getIntroImage returns correct value.
     *
     * @return  void
     */
    public function testGetIntroImageReturnsCorrectValue()
    {
        $entity = $this->getEntity(['id' => 999, 'images' => '']);

        $this->assertEquals([], $entity->getIntroImage());

        $entity = $this->getEntity(['id' => 999, 'images' => '{"image_intro":"images\/joomla_black.png"}']);

        $this->assertEquals(['url' => 'images/joomla_black.png'], $entity->getIntroImage());

        $entity = $this->getEntity(['id' => 999, 'images' => '{"image_intro":"","float_intro":"","image_intro_alt":"","image_intro_caption":"","image_fulltext":"","float_fulltext":"","image_fulltext_alt":"","image_fulltext_caption":""}']);

        $this->assertEquals([], $entity->getIntroImage());
    }

    /**
     * getImages works with custom column.
     *
     * @return  void
     */
    public function testGetImagesWorksWithCustomColumn()
    {
        $phpUnitFrameworkMockObjectMockObject = $this->getMockBuilder(EntityWithImages::class)
            ->setMethods(['columnAlias'])
            ->getMock();

        $phpUnitFrameworkMockObjectMockObject->expects($this->once())
            ->method('columnAlias')
            ->willReturn('img');

        $reflectionClass = new \ReflectionClass($phpUnitFrameworkMockObjectMockObject);
        $reflectionProperty = $reflectionClass->getProperty('id');
        $reflectionProperty->setAccessible(true);
        $reflectionProperty->setValue($phpUnitFrameworkMockObjectMockObject, 999);

        $rowProperty = $reflectionClass->getProperty('row');
        $rowProperty->setAccessible(true);

        $rowProperty->setValue($phpUnitFrameworkMockObjectMockObject, ['id' => 999, 'img' => '{"image_intro":"images\/joomla_black.png","float_intro":"left","image_intro_alt":"Alt text","image_intro_caption":"Caption text"}']);

        $expected = [
            'intro' => [
                'url'     => 'images/joomla_black.png',
                'float'   => 'left',
                'alt'     => 'Alt text',
                'caption' => 'Caption text',
            ],
        ];

        $this->assertEquals($expected, $phpUnitFrameworkMockObjectMockObject->getImages());
    }

    /**
     * @test
     *
     * @return void
     */
    public function loadImagesReturnsEmptyArrayForNotLoadedEntity()
    {
        $entityWithImages = new EntityWithImages();

        $reflectionClass = new \ReflectionClass($entityWithImages);
        $reflectionMethod = $reflectionClass->getMethod('loadImages');
        $reflectionMethod->setAccessible(true);

        $this->assertSame([], $reflectionMethod->invoke($entityWithImages));
    }

    /**
     * Get a mocked entity with client.
     *
     * @param   array  $row  Row returned by the entity as data
     *
     * @return  \PHPUnit_Framework_MockObject_MockObject
     */
    private function getEntity($row = [])
    {
        $phpUnitFrameworkMockObjectMockObject = $this->getMockBuilder(EntityWithImages::class)
            ->setMethods(['columnAlias'])
            ->getMock();

        $phpUnitFrameworkMockObjectMockObject->method('columnAlias')
            ->willReturn('images');

        $phpUnitFrameworkMockObjectMockObject->bind($row);

        return $phpUnitFrameworkMockObjectMockObject;
    }
}
