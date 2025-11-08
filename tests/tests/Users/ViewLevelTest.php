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

namespace Extly\Joomla\Entity\Tests\Users;

use Extly\Joomla\Entity\Collection;
use Extly\Joomla\Entity\Users\ViewLevel;
use Joomla\CMS\Factory;

/**
 * ViewLevel entity tests.
 *
 * @since   1.2.0
 */
class ViewLevelTest extends \TestCaseDatabase
{
    /**
     * Preloaded entity for tests.
     *
     * @var  ViewLevel
     */
    private $viewLevel;

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

        $this->viewLevel = ViewLevel::find(3);
    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     *
     * @return  void
     */
    protected function tearDown()
    {
        ViewLevel::clearAll();

        $this->restoreFactoryState();

        parent::tearDown();
    }

    /**
     * @test
     *
     * @return void
     */
    public function loadWorks()
    {
        $data = $this->viewLevel->all();

        $this->assertTrue(is_array($data));
        $this->assertTrue($this->viewLevel->isLoaded());
        $this->assertNotSame(0, count($data));
    }

    /**
     * @test
     *
     * @return  void
     */
    public function tableReturnsExpectedInstances()
    {
        $this->assertInstanceOf(\Joomla\CMS\Table\ViewLevel::class, $this->viewLevel->table());
        $this->assertInstanceOf(\Joomla\CMS\Table\User::class, $this->viewLevel->table('User', 'JTable'));
    }

    /**
     * @test
     *
     * @return void
     */
    public function userGroupsReturnsEmptyCollectionsForEmptyRules()
    {
        $entity = new ViewLevel();
        $userGroups = $entity->userGroups();

        $this->assertInstanceOf(Collection::class, $userGroups);

        $entity = new ViewLevel();
        $entity->bind(['id' => 333, 'title' => 'Unexisting1', 'rules' => '']);

        $this->assertSame([], $entity->userGroups()->ids());

        $entity = new ViewLevel();
        $entity->bind(['id' => 333, 'title' => 'Unexisting2', 'rules' => '[2,4]']);

        $this->assertSame([2, 4], $entity->userGroups()->ids());

        $entity = new ViewLevel();
        $entity->bind(['id' => 222, 'title' => 'Unexisting2', 'rules' => null]);

        $this->assertSame([], $entity->userGroups()->ids());
    }

    /**
     * @test
     *
     * @return void
     */
    public function userGroupsReturnsExpectedUserGroups()
    {
        $userGroups = $this->viewLevel->userGroups();

        $this->assertInstanceOf(Collection::class, $userGroups);
        $this->assertSame(3, $userGroups->count());
    }

    /**
     * Gets the data set to be loaded into the database during setup
     *
     * @return  \PHPUnit_Extensions_Database_DataSet_CsvDataSet
     */
    protected function getDataSet()
    {
        $phpUnitExtensionsDatabaseDataSetCsvDataSet = new \PHPUnit_Extensions_Database_DataSet_CsvDataSet(',', "'", '\\');
        $phpUnitExtensionsDatabaseDataSetCsvDataSet->addTable('jos_viewlevels', JPATH_TEST_DATABASE.'/jos_viewlevels.csv');

        return $phpUnitExtensionsDatabaseDataSetCsvDataSet;
    }
}
