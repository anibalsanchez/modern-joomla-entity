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

namespace Extly\Joomla\Entity\Tests\Core\Traits;

use Extly\Joomla\Client\Administrator;
use Extly\Joomla\Client\Site;
use Extly\Joomla\Entity\Core\Extension\Component;
use Extly\Joomla\Entity\Tests\Core\Traits\Stubs\ClassWithComponent;

/**
 * HasComponent trait tests.
 *
 * @since   1.1.0
 */
class HasComponentTest extends \TestCaseDatabase
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
        ClassWithComponent::clearAll();

        $this->restoreFactoryState();

        parent::tearDown();
    }

    /**
     * component calls loadComponent.
     *
     * @return  void
     */
    public function testComponentClassLoadComponent()
    {
        $dummyComponent = new Component(999);

        $class = $this->getMockBuilder(ClassWithComponent::class)
            ->setMethods(['loadComponent'])
            ->getMock();

        $class->expects($this->once())
            ->method('loadComponent')
            ->willReturn($dummyComponent);

        $this->assertSame($dummyComponent, $class->component());
    }

    /**
     * component returns cached component.
     *
     * @return  void
     */
    public function testComponentReturnsCachedComponent()
    {
        $class = $this->getMockBuilder(ClassWithComponent::class)
            ->setMethods(['loadComponent'])
            ->getMock();

        $class->expects($this->never())
            ->method('loadComponent');

        $reflectionClass = new \ReflectionClass($class);

        $reflectionProperty = $reflectionClass->getProperty('component');
        $reflectionProperty->setAccessible(true);

        $dummyComponent = new Component(999);

        $reflectionProperty->setValue($class, $dummyComponent);

        $this->assertSame($dummyComponent, $class->component());
    }

    /**
     * componentOption returns correct value.
     *
     * @return  void
     */
    public function testComponentOptionFromClass()
    {
        $class = new ClassWithComponent();

        $reflection = new \ReflectionClass($class);
        $method = $reflection->getMethod('componentOption');
        $method->setAccessible(true);

        $this->assertSame('com_tests', $method->invoke($class));

        require_once __DIR__.'/Stubs/ContentEntityComponent.php';

        $class = new \ContentEntityComponent();

        $reflection = new \ReflectionClass($class);
        $method = $reflection->getMethod('componentOption');
        $method->setAccessible(true);

        $this->assertSame('com_content', $method->invoke($class));
    }

    /**
     * loadComponent returns correct value.
     *
     * @return  void
     */
    public function testLoadComponentReturnsCorrectValue()
    {
        $class = $this->getMockBuilder(ClassWithComponent::class)
            ->setMethods(['componentOption'])
            ->getMock();

        $class->expects($this->once())
            ->method('componentOption')
            ->willReturn('com_content');

        $reflectionClass = new \ReflectionClass($class);

        $reflectionProperty = $reflectionClass->getProperty('row');
        $reflectionProperty->setAccessible(true);

        $reflectionMethod = $reflectionClass->getMethod('loadComponent');
        $reflectionMethod->setAccessible(true);

        $this->assertInstanceOf(Component::class, $reflectionMethod->invoke($class));
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
