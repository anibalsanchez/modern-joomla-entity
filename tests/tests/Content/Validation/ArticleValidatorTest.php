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

namespace Extly\Joomla\Entity\Tests\Content\Validation;

use Extly\Joomla\Entity\Content\Article;
use Extly\Joomla\Entity\Content\Validation\ArticleValidator;
use Extly\Joomla\Entity\Tests\Stubs\Entity;

/**
 * ArticleValidator tests.
 *
 * @since   1.1.0
 */
class ArticleValidatorTest extends \TestCase
{
    /**
     * constructor adds rules.
     *
     * @return  void
     */
    public function testConstructorAddsRules()
    {
        $article = new Article();

        $articleValidator = new ArticleValidator($article);

        $reflectionClass = new \ReflectionClass($articleValidator);

        $reflectionProperty = $reflectionClass->getProperty('rules');
        $reflectionProperty->setAccessible(true);

        $this->assertTrue(count($reflectionProperty->getValue($articleValidator)) > 0);
    }
}
