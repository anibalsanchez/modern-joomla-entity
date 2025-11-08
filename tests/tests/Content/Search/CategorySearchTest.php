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

namespace Extly\Joomla\Entity\Tests\Content\Search;

defined('_JEXEC') || die;

use Extly\Joomla\Entity\Content\Search\CategorySearch;
use Joomla\CMS\Factory;

/**
 * CategorySearch tests.
 *
 * @since   __DEPLOY_VERSION_
 */
class CategorySearchTest extends \TestCaseDatabase
{
    /**
     * This method is called before the first test of this test class is run.
     *
     * @return  void
     */
    public static function setUpBeforeClass()
    {
        parent::setUpBeforeClass();

        $files = [
            __DIR__.'/Stubs/Database/contentitem_tag_map.sql',
        ];

        foreach ($files as $file) {
            static::$driver->getConnection()->exec(file_get_contents($file));
        }

        Factory::$database = static::$driver;
    }

    /**
     * @test
     *
     * @return void
     */
    public function extensionIsAlwaysComContent()
    {
        $categories = CategorySearch::instance(
            [
                'list.limit' => 0,
            ]
        )->search();

        $this->assertNotSame(0, count($categories));

        foreach ($categories as $category) {
            $this->assertSame('com_content', $category['extension']);
        }
    }

    /**
     * @test
     *
     * @return void
     */
    public function tagFilterReturnsExpectedResults()
    {
        $categories = CategorySearch::instance(
            [
                'filter.tag_id' => 2,
                'list.limit'   => 0,
            ]
        )->search();

        $this->assertSame(1, count($categories));
        $this->assertSame(14, (int) $categories[0]['id']);
    }

    /**
     * Gets the data set to be loaded into the database during setup
     *
     * @return  \PHPUnit_Extensions_Database_DataSet_CsvDataSet
     */
    protected function getDataSet()
    {
        $phpUnitExtensionsDatabaseDataSetCsvDataSet = new \PHPUnit_Extensions_Database_DataSet_CsvDataSet(',', "'", '\\');
        $phpUnitExtensionsDatabaseDataSetCsvDataSet->addTable('jos_categories', __DIR__.'/Stubs/Database/categories.csv');
        $phpUnitExtensionsDatabaseDataSetCsvDataSet->addTable('jos_contentitem_tag_map', __DIR__.'/Stubs/Database/contentitem_tag_map.csv');

        return $phpUnitExtensionsDatabaseDataSetCsvDataSet;
    }
}
