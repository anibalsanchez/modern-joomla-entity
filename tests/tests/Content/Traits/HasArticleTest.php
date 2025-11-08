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

use Extly\Joomla\Entity\Content\Article;
use Extly\Joomla\Entity\Tests\Content\Traits\Stubs\ClassWithArticle;

/**
 * HasArticle trait tests.
 *
 * @since   1.1.0
 */
class HasArticleTest extends \PHPUnit\Framework\TestCase
{
    /**
     * Column storign the article identifier.
     *
     * @const
     */
    public const ARTICLE_COLUMN = 'article_id';

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     *
     * @return  void
     */
    protected function tearDown()
    {
        ClassWithArticle::clearAll();

        parent::tearDown();
    }

    /**
     * getColumnArticle returns correct value.
     *
     * @return  void
     */
    public function testGetColumnArticleReturnsCorrectValue()
    {
        $classWithArticle = new ClassWithArticle();

        $reflectionClass = new \ReflectionClass($classWithArticle);
        $reflectionMethod = $reflectionClass->getMethod('getColumnArticle');
        $reflectionMethod->setAccessible(true);

        $this->assertEquals(static::ARTICLE_COLUMN, $reflectionMethod->invoke($classWithArticle));
    }

    /**
     * loadArticle loads correct article.
     *
     * @return  void
     */
    public function testLoadArticleLodsCorrectArticle()
    {
        $classWithArticle = new ClassWithArticle();

        $reflectionClass = new \ReflectionClass($classWithArticle);
        $reflectionMethod = $reflectionClass->getMethod('loadArticle');
        $reflectionMethod->setAccessible(true);

        $reflectionProperty = $reflectionClass->getProperty('row');
        $reflectionProperty->setAccessible(true);

        $reflectionProperty->setValue($classWithArticle, ['id' => 999]);

        $this->assertEquals(new Article(), $reflectionMethod->invoke($classWithArticle));

        $reflectionProperty->setValue($classWithArticle, ['id' => 999, static::ARTICLE_COLUMN => 666]);

        $this->assertEquals(new Article(666), $reflectionMethod->invoke($classWithArticle));
    }

    /**
     * loadArticle works with custom column.
     *
     * @return  void
     */
    public function testLoadArticleWorksWithCustomColumn()
    {
        $phpUnitFrameworkMockObjectMockObject = $this->getMockBuilder(ClassWithArticle::class)
            ->setMethods(['getColumnArticle'])
            ->getMock();

        $phpUnitFrameworkMockObjectMockObject->method('getColumnArticle')
            ->willReturn('custom_article_id');

        $reflectionClass = new \ReflectionClass($phpUnitFrameworkMockObjectMockObject);
        $reflectionMethod = $reflectionClass->getMethod('loadArticle');
        $reflectionMethod->setAccessible(true);

        $reflectionProperty = $reflectionClass->getProperty('row');
        $reflectionProperty->setAccessible(true);

        $reflectionProperty->setValue($phpUnitFrameworkMockObjectMockObject, ['id' => 999]);

        $this->assertEquals(new Article(), $reflectionMethod->invoke($phpUnitFrameworkMockObjectMockObject));

        $reflectionProperty->setValue($phpUnitFrameworkMockObjectMockObject, ['id' => 999, 'custom_article_id' => 666]);

        $this->assertEquals(new Article(666), $reflectionMethod->invoke($phpUnitFrameworkMockObjectMockObject));
    }

    /**
     * getArticle returns correct data.
     *
     * @return  void
     */
    public function testGetArticleReturnsCorrectData()
    {
        $classWithArticle = new ClassWithArticle();

        $reflectionClass = new \ReflectionClass($classWithArticle);
        $reflectionProperty = $reflectionClass->getProperty('row');
        $reflectionProperty->setAccessible(true);

        $reflectionProperty->setValue($classWithArticle, ['id' => 999]);

        $this->assertEquals(new Article(), $classWithArticle->getArticle());

        $reflectionProperty->setValue($classWithArticle, ['id' => 999, static::ARTICLE_COLUMN => 666]);

        $this->assertEquals(new Article(), $classWithArticle->getArticle());
        $this->assertEquals(new Article(666), $classWithArticle->getArticle(true));
    }

    /**
     * hasArticle returns correct value.
     *
     * @return  void
     */
    public function testHasArticleReturnsCorrectValue()
    {
        $classWithArticle = new ClassWithArticle();

        $reflectionClass = new \ReflectionClass($classWithArticle);
        $reflectionProperty = $reflectionClass->getProperty('article');
        $reflectionProperty->setAccessible(true);

        $rowProperty = $reflectionClass->getProperty('row');
        $rowProperty->setAccessible(true);

        $rowProperty->setValue($classWithArticle, ['id' => 999]);

        $this->assertFalse($classWithArticle->hasArticle());

        $rowProperty->setValue($classWithArticle, ['id' => 999, static::ARTICLE_COLUMN => 666]);

        // Cached data
        $this->assertFalse($classWithArticle->hasArticle());

        $reflectionProperty->setValue($classWithArticle, null);
        $this->assertTrue($classWithArticle->hasArticle());
    }
}
