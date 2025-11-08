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

use Extly\Joomla\Entity\Core\Column;
use Extly\Joomla\Entity\Core\Extension\Component;
use Extly\Joomla\Entity\Fields\Column as FieldsColumn;
use Extly\Joomla\Entity\Fields\Field;
use Extly\Joomla\Entity\Fields\FieldGroup;
use Joomla\CMS\Factory;
use Joomla\Registry\Registry;

/**
 * Field entity tests.
 *
 * @since   1.1.0
 */
class FieldTest extends \TestCaseDatabase
{
    /**
     * This method is called before the first test of this test class is run.
     *
     * @return  void
     */
    public static function setUpBeforeClass()
    {
        parent::setUpBeforeClass();

        $test = static::$driver->getConnection()->exec(file_get_contents(JPATH_TESTS_PHPROBERTO.'/db/schema/fields.sql'));

        Factory::$database = static::$driver;
    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     *
     * @return  void
     */
    protected function tearDown()
    {
        Field::clearAll();

        parent::tearDown();
    }

    /**
     * @test
     *
     * @return void
     */
    public function fieldGroupReturnsCorrectFieldGroup()
    {
        $field = new Field(12);
        $field->bind(['id' => 12, 'title' => 'Test field', FieldsColumn::FIELD_GROUP => 66]);

        $fieldGroup = $field->fieldGroup();

        $this->assertInstanceOf(FieldGroup::class, $fieldGroup);
        $this->assertSame(66, $fieldGroup->id());
    }

    /**
     * @test
     *
     * @return void
     */
    public function hasFieldGroupReturnsCorrectValue()
    {
        $field = new Field(12);
        $field->bind(['id' => 12, 'title' => 'Test field']);

        $this->assertFalse($field->hasFieldGroup());

        $field = new Field(12);
        $field->bind(['id' => 12, 'title' => 'Test field', FieldsColumn::FIELD_GROUP => 66]);

        $this->assertTrue($field->hasFieldGroup());

        $field = new Field(12);
        $field->bind(['id' => 12, 'title' => 'Test field', FieldsColumn::FIELD_GROUP => '']);

        $this->assertFalse($field->hasFieldGroup());
    }

    /**
     * @test
     *
     * @return void
     */
    public function hasRawValueReturnsCorrectValue()
    {
        $field = new Field(12);
        $field->bind(['id' => 12, 'title' => 'Test field']);

        $this->assertFalse($field->hasRawValue());

        $field = new Field(14);
        $field->bind(['id' => 14, 'title' => 'Another field', 'rawvalue' => 'My value']);

        $this->assertTrue($field->hasRawValue());
    }

    /**
     * @test
     *
     * @return void
     */
    public function hasValueReturnsCorrectValue()
    {
        $field = new Field(12);
        $field->bind(['id' => 12, 'title' => 'Test field']);

        $this->assertFalse($field->hasValue());

        $field = new Field(14);
        $field->bind(['id' => 14, 'title' => 'Another field', 'value' => 'My value']);

        $this->assertTrue($field->hasValue());
    }

    /**
     * @test
     *
     * @return  void
     */
    public function paramsReturnsParameters()
    {
        $field = $this->getMockBuilder(Field::class)
            ->setMethods(['columnAlias'])
            ->getMock();

        $field->method('columnAlias')
            ->willReturn(Column::PARAMS);

        $field->bind(['id' => 999, 'params' => '{"foo":"var"}']);

        $this->assertEquals(new Registry(['foo' => 'var']), $field->params());
    }

    /**
     * @test
     *
     * @return  void
     */
    public function stateReturnsCorrectValue()
    {
        $field = $this->getMockBuilder(Field::class)
            ->setMethods(['columnAlias'])
            ->getMock();

        $field->method('columnAlias')
            ->willReturn(Column::STATE);

        $field->bind(['id' => 999, 'published' => '0']);

        $this->assertEquals(0, $field->state());

        $field->bind(['id' => 999, 'published' => '1']);

        $this->assertEquals(1, $field->state());
    }

    /**
     * @test
     *
     * @return  void
     */
    public function tableReturnsCorrectInstance()
    {
        $component = $this->getMockBuilder(Component::class)
            ->setMethods(['table'])
            ->getMock();

        $component->expects($this->once())
            ->method('table')
            ->with($this->equalTo('Field'))
            ->willReturn('componentTable');

        $field = $this->getMockBuilder(Field::class)
            ->setMethods(['component'])
            ->getMock();

        $field->method('component')
            ->willReturn($component);

        $this->assertSame('componentTable', $field->table());
    }

    /**
     * @test
     *
     * @return  void
     */
    public function tableReturnsSpecificTableInstance()
    {
        $field = new Field();

        $this->assertInstanceOf('JTableUser', $field->table('User', 'JTable'));
    }

    /**
     * @test
     *
     * @return  void
     */
    public function nameRetrieved()
    {
        $field = new Field(999);

        $reflectionClass = new \ReflectionClass($field);
        $reflectionProperty = $reflectionClass->getProperty('row');
        $reflectionProperty->setAccessible(true);

        $reflectionProperty->setValue($field, ['id' => 999, 'name' => 'field_name']);

        $this->assertSame('field_name', $field->fieldName());
    }

    /**
     * @test
     *
     * @return  void
     */
    public function valueRetrieved()
    {
        $field = new Field(999);

        $reflectionClass = new \ReflectionClass($field);
        $reflectionProperty = $reflectionClass->getProperty('row');
        $reflectionProperty->setAccessible(true);

        $reflectionProperty->setValue($field, ['id' => 999, 'value' => 100]);

        $this->assertSame(100, $field->value());
    }

    /**
     * @test
     *
     * @return  void
     */
    public function rawValueRetrieved()
    {
        $field = new Field(999);

        $reflectionClass = new \ReflectionClass($field);
        $reflectionProperty = $reflectionClass->getProperty('row');
        $reflectionProperty->setAccessible(true);

        $reflectionProperty->setValue($field, ['id' => 999, 'rawvalue' => ['x' => 'dummy']]);

        $expected = ['x' => 'dummy'];

        $this->assertEquals($expected, $field->rawValue());
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

        return $phpUnitExtensionsDatabaseDataSetCsvDataSet;
    }
}
