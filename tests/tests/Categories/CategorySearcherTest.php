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

use Extly\Joomla\Entity\Categories\CategorySearcher;
use Extly\Joomla\Entity\Categories\Search\CategorySearch;

/**
 * Category searcher tests.
 *
 * @since   1.4.0
 */
class CategorySearcherTest extends \TestCaseDatabase
{
    /**
     * @test
     *
     * @return void
     */
    public function extendsCategorySearch()
    {
        $categorySearcher = new CategorySearcher();

        $this->assertInstanceOf(CategorySearch::class, $categorySearcher);
    }
}
