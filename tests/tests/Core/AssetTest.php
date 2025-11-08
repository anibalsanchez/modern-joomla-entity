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

namespace Extly\Joomla\Entity\Tests\Core;

use Extly\Joomla\Entity\Core\Asset;
use Extly\Joomla\Entity\Exception\LoadEntityDataError;
use Joomla\CMS\Factory;

/**
 * Asset entity tests.
 *
 * @since   1.1.0
 */
class AssetTest extends \TestCaseDatabase
{
    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     *
     * @return  void
     */
    protected function setUp()
    {
        parent::setUp();

        $this->saveFactoryState();

        Factory::$session = $this->getMockSession();
        Factory::$config = $this->getMockConfig();
        Factory::$application = $this->getMockCmsApp();
    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     *
     * @return  void
     */
    protected function tearDown()
    {
        Asset::clearAll();

        $this->restoreFactoryState();

        parent::tearDown();
    }

    /**
     * instance loads an asset.
     *
     * @return  void
     */
    public function testInstanceLoadsAnAsset()
    {
        $asset = Asset::find(1);

        $this->assertEquals(1, $asset->id());
    }

    /**
     * @test
     *
     * @return void
     */
    public function crudWorks()
    {
        $asset = Asset::create(['name' => 'joomla.entity', 'title' => 'createWorks']);

        $this->assertTrue($asset->hasId());

        $asset->assign('name', 'joomla.entity.edited');
        $asset->save();

        $id = $asset->id();
        Asset::clear($id);
        $reloadedAsset = Asset::load($id);

        $this->assertSame('joomla.entity.edited', $reloadedAsset->get('name'));

        Asset::clear($id);
        Asset::delete($id);

        $error = '';

        try {
            $reloadedAsset = Asset::load($id);
        } catch (LoadEntityDataError $loadEntityDataError) {
            $error = $loadEntityDataError->getMessage();
        }

        $this->assertNotEmpty($error);
    }

    /**
     * Gets the data set to be loaded into the database during setup
     *
     * @return  \PHPUnit_Extensions_Database_DataSet_CsvDataSet
     */
    protected function getDataSet()
    {
        $phpUnitExtensionsDatabaseDataSetCsvDataSet = new \PHPUnit_Extensions_Database_DataSet_CsvDataSet(',', "'", '\\');
        $phpUnitExtensionsDatabaseDataSetCsvDataSet->addTable('jos_assets', JPATH_TEST_DATABASE.'/jos_assets.csv');

        return $phpUnitExtensionsDatabaseDataSetCsvDataSet;
    }
}
