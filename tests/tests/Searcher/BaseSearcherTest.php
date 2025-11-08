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

namespace Extly\Joomla\Entity\Tests\Searcher;

defined('_JEXEC') || die;

use Extly\Joomla\Entity\Collection;
use Extly\Joomla\Entity\Searcher\BaseSearcher;
use Extly\Joomla\Entity\Tests\Searcher\Stubs\Searcher;
use Joomla\CMS\Factory;

/**
 * Category searcher tests.
 *
 * @since   1.4.0
 */
class BaseSearcherTest extends \TestCaseDatabase
{
    /**
     * @test
     *
     * @return void
     */
    public function constructorSetsOptions()
    {
        $searcher = $this->getMockForAbstractClass(
            BaseSearcher::class,
            [
                ['test' => 'my-value'],
            ]
        );

        $this->assertSame('my-value', $searcher->options()->get('test'));
    }

    /**
     * @test
     *
     * @return void
     */
    public function constructorAppliesDefaultOptions()
    {
        $searcher = new Searcher(['option' => 'overriden-value', 'custom' => 'custom-value']);

        $this->assertSame('overriden-value', $searcher->options()->get('option'));
        $this->assertSame('another-default-value', $searcher->options()->get('another-option'));
        $this->assertSame('custom-value', $searcher->options()->get('custom'));
    }

    /**
     * @test
     *
     * @return void
     */
    public function instanceReturnsInitialisedSearcher()
    {
        $searcher = Searcher::instance(['my-option' => 'my-value']);

        $this->assertSame('another-default-value', $searcher->options()->get('another-option'));
        $this->assertSame('my-value', $searcher->options()->get('my-option'));
    }
}
