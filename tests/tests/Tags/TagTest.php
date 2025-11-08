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

namespace Extly\Joomla\Entity\Tests\Tags;

use Extly\Joomla\Entity\Acl\Acl;
use Extly\Joomla\Entity\Tags\Tag;
use Extly\Joomla\Entity\Users\User;
use Joomla\Registry\Registry;

/**
 * Tag entity tests.
 *
 * @since   1.1.0
 */
class TagTest extends \TestCaseDatabase
{
    /**
     * Acl can be retrieved.
     *
     * @return  void
     */
    public function testAclCanBeRetrieved()
    {
        $tag = new Tag(666);
        $user = new User(999);

        $acl = $tag->acl($user);

        $reflectionClass = new \ReflectionClass($acl);
        $reflectionProperty = $reflectionClass->getProperty('entity');
        $reflectionProperty->setAccessible(true);

        $userProperty = $reflectionClass->getProperty('user');
        $userProperty->setAccessible(true);

        $this->assertInstanceOf(Acl::class, $acl);
        $this->assertSame($user, $userProperty->getValue($acl));
        $this->assertSame($tag, $reflectionProperty->getValue($acl));
    }

    /**
     * getImages returns correct value.
     *
     * @return  void
     */
    public function testGetImagesReturnsCorrectValue()
    {
        $_SERVER['HTTP_HOST'] = 'joomla-entity.test.com';
        $_SERVER['SCRIPT_NAME'] = '/index.php';

        $tag = new Tag(999);

        $reflectionClass = new \ReflectionClass($tag);
        $reflectionProperty = $reflectionClass->getProperty('row');
        $reflectionProperty->setAccessible(true);

        $reflectionProperty->setValue($tag, ['id' => 999, 'images' => '']);

        $this->assertSame([], $tag->getImages(true));

        $reflectionProperty->setValue($tag, ['id' => 999, 'images' => '{"image_intro":"images\/joomla_black.png","float_intro":"left","image_intro_alt":"Alt text","image_intro_caption":"Caption text","image_fulltext":"images\/fulltext.png","float_fulltext":"right","image_fulltext_alt":"Alt fulltext","image_fulltext_caption":"Caption fulltext"}']);

        $this->assertSame([], $tag->getImages());

        $expected = [
            'intro' => [
                'url'     => 'images/joomla_black.png',
                'float'   => 'left',
                'alt'     => 'Alt text',
                'caption' => 'Caption text',
            ],
            'full' => [
                'url'     => 'images/fulltext.png',
                'float'   => 'right',
                'alt'     => 'Alt fulltext',
                'caption' => 'Caption fulltext',
            ],
        ];
        $images = $tag->getImages(true);

        $this->assertEquals($expected, $tag->getImages(true));
    }

    /**
     * getMetadata returns data.
     *
     * @return  void
     */
    public function testGetMetadataReturnsData()
    {
        $tag = new Tag(999);

        $reflectionClass = new \ReflectionClass($tag);
        $reflectionProperty = $reflectionClass->getProperty('row');
        $reflectionProperty->setAccessible(true);

        $reflectionProperty->setValue($tag, ['id' => 999, 'metadata' => '{"foo":"bar"}']);

        $expected = [
            'foo' => 'bar',
        ];

        $this->assertEquals($expected, $tag->metadata());
    }

    /**
     * params returns parameters.
     *
     * @return  void
     */
    public function testParamsReturnsParameters()
    {
        $tag = new Tag(999);

        $reflectionClass = new \ReflectionClass($tag);
        $reflectionProperty = $reflectionClass->getProperty('row');
        $reflectionProperty->setAccessible(true);

        $reflectionProperty->setValue($tag, ['id' => 999, 'params' => '{"foo":"var"}']);

        $this->assertEquals(new Registry(['foo' => 'var']), $tag->params());
    }

    /**
     * getState returns correct value.
     *
     * @return  void
     */
    public function testGetStateReturnsCorrectValue()
    {
        $tag = new Tag(999);

        $reflectionClass = new \ReflectionClass($tag);
        $reflectionProperty = $reflectionClass->getProperty('row');
        $reflectionProperty->setAccessible(true);

        $reflectionProperty->setValue($tag, ['id' => 999, 'published' => '0']);

        $this->assertEquals(0, $tag->state());

        $reflectionProperty->setValue($tag, ['id' => 999, 'published' => '1']);

        $this->assertEquals(1, $tag->state());
    }
}
