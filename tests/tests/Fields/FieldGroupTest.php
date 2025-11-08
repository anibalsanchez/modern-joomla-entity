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

namespace Extly\Joomla\Entity\Tests\Fields;

use Extly\Joomla\Entity\Collection;
use Extly\Joomla\Entity\Fields\FieldGroup;
use Joomla\CMS\Factory;

/**
 * Field group entity tests.
 *
 * @since   1.2.0
 */
class FieldGroupTest extends \TestCaseDatabase
{
    /**
     * Preloaded field group for tests.
     *
     * @var  FieldGroup
     */
    protected $fieldGroup;

    /**
     * This method is called before the first test of this test class is run.
     *
     * @return  void
     */
    public static function setUpBeforeClass()
    {
        parent::setUpBeforeClass();

        $files = [
            JPATH_TESTS_PHPROBERTO.'/db/schema/fields.sql',
            JPATH_TESTS_PHPROBERTO.'/db/schema/fields_groups.sql',
        ];

        foreach ($files as $file) {
            static::$driver->getConnection()->exec(file_get_contents($file));
        }

        Factory::$database = static::$driver;
    }

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

        $this->fieldGroup = FieldGroup::find(1);
    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     *
     * @return  void
     */
    protected function tearDown()
    {
        FieldGroup::clearAll();

        $this->restoreFactoryState();

        parent::tearDown();
    }

    /**
     * @test
     *
     * @return void
     */
    public function fieldsReturnsFields()
    {
        $fields = $this->fieldGroup->fields();

        $this->assertInstanceOf(Collection::class, $fields);
        $this->assertNotSame(0, count($fields));
    }

    /**
     * @test
     *
     * @return void
     */
    public function fieldsReturnsEmptyCollectionForNonLoadedGroup()
    {
        $fieldGroup = new FieldGroup();
        $fields = $fieldGroup->fields();

        $this->assertInstanceOf(Collection::class, $fields);
        $this->assertSame(0, count($fields));
    }

    /**
     * @test
     *
     * @return void
     */
    public function loadWorks()
    {
        $data = $this->fieldGroup->all();

        $this->assertTrue(is_array($data));
        $this->assertNotSame(0, count($data));
    }

    /**
     * @test
     *
     * @return  void
     */
    public function tableReturnsSpecificTableInstance()
    {
        $this->assertInstanceOf('JTableUser', $this->fieldGroup->table('User', 'JTable'));
    }

    /**
     * Gets the data set to be loaded into the database during setup
     *
     * @return  \PHPUnit_Extensions_Database_DataSet_CsvDataSet
     */
    protected function getDataSet()
    {
        $phpUnitExtensionsDatabaseDataSetCsvDataSet = new \PHPUnit_Extensions_Database_DataSet_CsvDataSet(',', "'", '\\');
        $phpUnitExtensionsDatabaseDataSetCsvDataSet->addTable('jos_extensions', JPATH_TESTS_PHPROBERTO.'/db/data/extensions.csv');
        $phpUnitExtensionsDatabaseDataSetCsvDataSet->addTable('jos_fields', JPATH_TESTS_PHPROBERTO.'/db/data/fields.csv');
        $phpUnitExtensionsDatabaseDataSetCsvDataSet->addTable('jos_fields_groups', JPATH_TESTS_PHPROBERTO.'/db/data/fields_groups.csv');

        return $phpUnitExtensionsDatabaseDataSetCsvDataSet;
    }
}
