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

defined('_JEXEC') || die;

use Extly\Joomla\Entity\Core\Asset;
use Extly\Joomla\Entity\Core\Client\Administrator;
use Extly\Joomla\Entity\Core\Client\Site;
use Extly\Joomla\Entity\Core\Column;
use Extly\Joomla\Entity\Core\Module;
use Joomla\CMS\Factory;
use Joomla\Registry\Registry;

/**
 * Module tests.
 *
 * @since   1.4.0
 */
class ModuleTest extends \TestCaseDatabase
{
    private $module;

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

        $this->module = Module::find(1);
    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     *
     * @return  void
     */
    protected function tearDown()
    {
        Module::clearAll();

        parent::tearDown();
    }

    /**
     * @test
     *
     * @return void
     */
    public function accessReturnsExpectedValue()
    {
        $this->assertSame(1, $this->module->access());
        $this->assertSame(3, Module::find(3)->access());
    }

    /**
     * @test
     *
     * @return void
     */
    public function assetReturnsCorrectAsset()
    {
        $this->module->assign($this->module->columnAlias(Column::ASSET), 2);

        $asset = $this->module->asset();

        $this->assertInstanceOf(Asset::class, $asset);
        $this->assertSame('com_admin', $asset->get('title'));
    }

    /**
     * @test
     *
     * @return void
     */
    public function clientReturnsExpectedClient()
    {
        $client = $this->module->client();

        $this->assertInstanceOf(Site::class, $client);

        $this->assertInstanceOf(Administrator::class, Module::find(2)->client());
    }

    /**
     * @test
     *
     * @return void
     */
    public function isPublishedInMenuReturnsTrueForNoMenuIdIfModuleIsShownOnAllPages()
    {
        $module = new Module();
        $reflectionClass = new \ReflectionClass($module);
        $reflectionProperty = $reflectionClass->getProperty('menusIds');
        $reflectionProperty->setAccessible(true);

        $reflectionProperty->setValue($module, [0]);

        $this->assertTrue($module->isPublishedInMenu(0));
        $this->assertTrue($module->isPublishedInMenu(null));
    }

    /**
     * @test
     *
     * @return void
     */
    public function isPublishedInMenuReturnsFalseForNoMenuIdIfModuleIsNotShownOnAllPages()
    {
        $module = new Module();
        $reflectionClass = new \ReflectionClass($module);
        $reflectionProperty = $reflectionClass->getProperty('menusIds');
        $reflectionProperty->setAccessible(true);

        $reflectionProperty->setValue($module, [222]);

        $this->assertFalse($module->isPublishedInMenu(0));
    }

    /**
     * @test
     *
     * @return void
     */
    public function isPublishedInMenuReturnsFalseForNoMenuIdInMenusIds()
    {
        $module = new Module();
        $reflectionClass = new \ReflectionClass($module);
        $reflectionProperty = $reflectionClass->getProperty('menusIds');
        $reflectionProperty->setAccessible(true);

        $reflectionProperty->setValue($module, [222, 444]);

        $this->assertTrue($module->isPublishedInMenu(222));
        $this->assertFalse($module->isPublishedInMenu(333));
        $this->assertTrue($module->isPublishedInMenu(444));
    }

    /**
     * @test
     *
     * @return void
     */
    public function isPublishedInMenuReturnsFalseForNoMenusIds()
    {
        $module = new Module();
        $reflectionClass = new \ReflectionClass($module);
        $reflectionProperty = $reflectionClass->getProperty('menusIds');
        $reflectionProperty->setAccessible(true);

        $reflectionProperty->setValue($module, []);

        $this->assertFalse($module->isPublishedInMenu(222));
        $this->assertFalse($module->isPublishedInMenu(333));
        $this->assertFalse($module->isPublishedInMenu(444));
    }

    /**
     * @test
     *
     * @return void
     */
    public function isPublishedInMenuReturnsTrueForExcludedMenuId()
    {
        $module = new Module();
        $reflectionClass = new \ReflectionClass($module);
        $reflectionProperty = $reflectionClass->getProperty('menusIds');
        $reflectionProperty->setAccessible(true);

        $reflectionProperty->setValue($module, [-222, -555]);

        $this->assertFalse($module->isPublishedInMenu(222));
        $this->assertTrue($module->isPublishedInMenu(333));
        $this->assertFalse($module->isPublishedInMenu(555));
    }

    /**
     * @test
     *
     * @return void
     */
    public function isPublishedReturnsExpectedValue()
    {
        $this->assertTrue($this->module->isPublished());
        $this->assertFalse($this->module->isUnpublished());

        $this->assertFalse(Module::find(79)->isPublished());
        $this->assertTrue(Module::find(79)->isUnpublished());

        // Not published up
        $date = new \DateTime();
        $date->modify('+1 hour');

        // Future publish_up date
        $this->module->assign($this->module->columnAlias(Column::PUBLISH_UP), $date->format('Y-m-d H:i:s'));

        $this->assertFalse($this->module->isPublished());
        $this->assertTrue($this->module->isUnpublished());

        $this->module->assign($this->module->columnAlias(Column::PUBLISH_UP), null);

        $this->assertTrue($this->module->isPublished());
        $this->assertFalse($this->module->isUnpublished());

        // Past publish_down date
        $date = new \DateTime();
        $date->modify('-1 hour');

        $this->module->assign($this->module->columnAlias(Column::PUBLISH_DOWN), $date->format('Y-m-d H:i:s'));

        $this->assertFalse($this->module->isPublished());
        $this->assertTrue($this->module->isUnpublished());
    }

    /**
     * @test
     *
     * @return void
     */
    public function menusIdsReturnsExpectedVale()
    {
        $module = new Module();
        $this->assertEquals([], $module->menusIds());
        $this->assertEquals([101], $this->module->menusIds());
        $this->assertEquals([0], Module::find(2)->menusIds());
    }

    /**
     * @test
     *
     * @return void
     */
    public function menusIdsReturnsCachedInstanceAndReloads()
    {
        $reflectionClass = new \ReflectionClass($this->module);
        $reflectionProperty = $reflectionClass->getProperty('menusIds');
        $reflectionProperty->setAccessible(true);

        $reflectionProperty->setValue($this->module, [999, 666]);

        $this->assertEquals([999, 666], $this->module->menusIds());
        $this->assertEquals([101], $this->module->menusIds(true));
    }

    /**
     * @test
     *
     * @return void
     */
    public function loadWorks()
    {
        $this->assertSame('Main Menu', $this->module->get('title'));
    }

    /**
     * @test
     *
     * @return void
     */
    public function paramsReturnsModuleParameters()
    {
        $params = $this->module->params();

        $this->assertInstanceOf(Registry::class, $params);
        $this->assertSame('mainmenu', $params->get('menutype'));
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
        $phpUnitExtensionsDatabaseDataSetCsvDataSet->addTable('jos_modules', JPATH_TEST_DATABASE.'/jos_modules.csv');
        $phpUnitExtensionsDatabaseDataSetCsvDataSet->addTable('jos_modules_menu', JPATH_TEST_DATABASE.'/jos_modules_menu.csv');

        return $phpUnitExtensionsDatabaseDataSetCsvDataSet;
    }
}
