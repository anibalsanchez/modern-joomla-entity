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

namespace Extly\Joomla\Entity\Tests\Content\Traits;

use Extly\Joomla\Entity\Collection;
use Extly\Joomla\Entity\Content\Article;
use Extly\Joomla\Entity\Tests\Content\Traits\Stubs\ClassWithArticles;

/**
 * HasArticles trait tests.
 *
 * @since   1.1.0
 */
class HasArticlesTest extends \PHPUnit\Framework\TestCase
{
    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     *
     * @return  void
     */
    protected function tearDown()
    {
        ClassWithArticles::clearAll();

        parent::tearDown();
    }

    /**
     * clearArticles clears articles property.
     *
     * @return  void
     */
    public function testClearArticlesClearsArticlesProperty()
    {
        $classWithArticles = new ClassWithArticles();

        $reflectionClass = new \ReflectionClass($classWithArticles);
        $reflectionProperty = $reflectionClass->getProperty('articles');
        $reflectionProperty->setAccessible(true);

        $this->assertEquals(null, $reflectionProperty->getValue($classWithArticles));

        $collection = new Collection(
            [
                new Article(23),
                new Article(24),
                new Article(25),
            ]
        );

        $reflectionProperty->setValue($classWithArticles, $collection);
        $this->assertEquals($collection, $reflectionProperty->getValue($classWithArticles));

        $classWithArticles->clearArticles();
        $this->assertEquals(null, $reflectionProperty->getValue($classWithArticles));
    }

    /**
     * clearArticles is chainable.
     *
     * @return  void
     */
    public function testClearArticlesIsChainable()
    {
        $classWithArticles = new ClassWithArticles();

        $this->assertTrue($classWithArticles->clearArticles() instanceof ClassWithArticles);
    }

    /**
     * articles returns correct data.
     *
     * @return  void
     */
    public function testArticlesReturnsCorrectData()
    {
        $classWithArticles = new ClassWithArticles();

        $this->assertEquals(new Collection(), $classWithArticles->articles());

        $classWithArticles->articlesIds = [999];

        // Previous data with no reload
        $this->assertEquals(new Collection(), $classWithArticles->articles());
        $this->assertEquals(new Collection([new Article(999)]), $classWithArticles->articles(true));
    }

    /**
     * hasArticle returns correct value.
     *
     * @return  void
     */
    public function testHasArticleReturnsCorrectValue()
    {
        $classWithArticles = new ClassWithArticles();

        $classWithArticles->articlesIds = [999, 1001, 1003];

        $this->assertFalse($classWithArticles->hasArticle(998));
        $this->assertTrue($classWithArticles->hasArticle(999));
        $this->assertFalse($classWithArticles->hasArticle(1000));
        $this->assertTrue($classWithArticles->hasArticle(1001));
        $this->assertFalse($classWithArticles->hasArticle(1002));
        $this->assertTrue($classWithArticles->hasArticle(1003));
    }

    /**
     * hasArticles returns correct value.
     *
     * @return  void
     */
    public function testHasArticlesReturnsCorrectValue()
    {
        $entity = new ClassWithArticles();

        $this->assertFalse($entity->hasArticles());

        $entity = new ClassWithArticles();
        $entity->articlesIds = [999, 1001, 1003];

        $this->assertTrue($entity->hasArticles());
    }
}
