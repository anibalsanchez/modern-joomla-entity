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

namespace Extly\Joomla\Entity\Tests\Core\Extension;

use Extly\Joomla\Entity\Core\Extension\ActiveComponent;
use Extly\Joomla\Entity\Exception\InvalidEntityData;
use Extly\Joomla\Entity\Exception\LoadEntityDataError;

/**
 * Component entity tests.
 *
 * @since   1.1.0
 */
class ActiveComponentTest extends \TestCaseDatabase
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

        \Joomla\CMS\Factory::$session = $this->getMockSession();
        \Joomla\CMS\Factory::$config = $this->getMockConfig();
        \Joomla\CMS\Factory::$application = $this->getMockCmsApp();
    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     *
     * @return  void
     */
    protected function tearDown()
    {
        ActiveComponent::clearAll();

        $this->restoreFactoryState();

        parent::tearDown();
    }

    /**
     * fetchRow returns correct value.
     *
     * @return  void
     */
    public function testFetchRowReturnsCorrectValue()
    {
        $class = $this->getMockBuilder(ActiveComponent::class)
            ->setMethods(['option'])
            ->getMock();

        $class->expects($this->once())
            ->method('option')
            ->willReturn('com_content');

        $reflectionClass = new \ReflectionClass($class);
        $reflectionMethod = $reflectionClass->getMethod('fetchRow');
        $reflectionMethod->setAccessible(true);

        $this->assertSame(22, (int) $reflectionMethod->invoke($class)['extension_id']);
    }

    /**
     * fetchRow throws an exception when no active option is detected.
     *
     * @return  void
     *
     * @expectedException \RuntimeException
     */
    public function testFetchRowThrowsAnExceptionWhenNoActiveOptionIsDetected()
    {
        $class = $this->getMockBuilder(ActiveComponent::class)
            ->setMethods(['option'])
            ->getMock();

        $class->expects($this->once())
            ->method('option')
            ->willReturn(null);

        $reflectionClass = new \ReflectionClass($class);
        $reflectionMethod = $reflectionClass->getMethod('fetchRow');
        $reflectionMethod->setAccessible(true);

        $reflectionMethod->invoke($class);
    }

    /**
     * fetchRow throws an exception when component not found.
     *
     * @return  void
     *
     * @expectedException Extly\Joomla\Entity\Exception\InvalidEntityData
     */
    public function testFetchRowThrowsAnExceptionWhenComponentNotFound()
    {
        $table = $this->getMockBuilder('MockTable')
            ->setMethods(['load', 'getProperties'])
            ->getMock();

        $table->expects($this->once())
            ->method('load')
            ->willReturn(true);

        $table->expects($this->once())
            ->method('getProperties')
            ->willReturn([]);

        $class = $this->getMockBuilder(ActiveComponent::class)
            ->setMethods(['option', 'table'])
            ->getMock();

        $class->expects($this->once())
            ->method('option')
            ->willReturn('com_phproberto');

        $class->expects($this->once())
            ->method('table')
            ->willReturn($table);

        $reflectionClass = new \ReflectionClass($class);
        $reflectionMethod = $reflectionClass->getMethod('fetchRow');
        $reflectionMethod->setAccessible(true);

        $reflectionMethod->invoke($class);
    }

    /**
     * fetchRow throws an exception when component not found.
     *
     * @return  void
     *
     * @expectedException Extly\Joomla\Entity\Exception\LoadEntityDataError
     */
    public function testFetchRowThrowsAnExceptionWhenNoPrimaryKeyReturned()
    {
        $class = $this->getMockBuilder(ActiveComponent::class)
            ->setMethods(['option'])
            ->getMock();

        $class->expects($this->once())
            ->method('option')
            ->willReturn('com_phproberto');

        $reflectionClass = new \ReflectionClass($class);
        $reflectionMethod = $reflectionClass->getMethod('fetchRow');
        $reflectionMethod->setAccessible(true);

        $reflectionMethod->invoke($class);
    }

    /**
     * option returns active input option.
     *
     * @return  void
     */
    public function testOptionReturnsActiveInputOption()
    {
        $activeComponent = new ActiveComponent();

        \Joomla\CMS\Factory::getApplication()->input->set('option', 'com_phproberto');

        $this->assertSame('com_phproberto', $activeComponent->option());

        \Joomla\CMS\Factory::getApplication()->input->set('option', null);
    }

    /**
     * Gets the data set to be loaded into the database during setup
     *
     * @return  \PHPUnit_Extensions_Database_DataSet_CsvDataSet
     */
    protected function getDataSet()
    {
        $phpUnitExtensionsDatabaseDataSetCsvDataSet = new \PHPUnit_Extensions_Database_DataSet_CsvDataSet(',', "'", '\\');
        $phpUnitExtensionsDatabaseDataSetCsvDataSet->addTable('jos_extensions', JPATH_TEST_DATABASE.'/jos_extensions.csv');

        return $phpUnitExtensionsDatabaseDataSetCsvDataSet;
    }
}
