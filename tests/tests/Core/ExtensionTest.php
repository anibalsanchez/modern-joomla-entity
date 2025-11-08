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

use Extly\Joomla\Entity\Core\Client\Administrator;
use Extly\Joomla\Entity\Core\Client\Site;
use Extly\Joomla\Entity\Core\Extension;

/**
 * Extension entity tests.
 *
 * @since   1.1.0
 */
class ExtensionTest extends \TestCaseDatabase
{
    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     *
     * @return  void
     */
    protected function tearDown()
    {
        Extension::clearAll();

        parent::tearDown();
    }

    /**
     * available states returns correct data.
     *
     * @return  void
     */
    public function testAvailableStatesReturnsCorrectData()
    {
        $extension = new Extension();

        $this->assertTrue(is_array($extension->availableStates()));
        $this->assertNotSame(0, count($extension->availableStates()));
    }

    /**
     * Client returns correct value.
     *
     * @return  void
     */
    public function testClientReturnsCorrectValue()
    {
        $extension = new Extension(999);

        $reflectionClass = new \ReflectionClass($extension);
        $reflectionProperty = $reflectionClass->getProperty('row');
        $reflectionProperty->setAccessible(true);

        $reflectionProperty->setValue($extension, ['extension_id' => 999, 'client_id' => 0]);

        $this->assertInstanceOf(Site::class, $extension->client());

        $reflectionProperty->setValue($extension, ['extension_id' => 999, 'client_id' => 1]);

        $this->assertInstanceOf(Administrator::class, $extension->client(true));
    }

    /**
     * instance loads an extension.
     *
     * @return  void
     */
    public function testInstanceLoadsAnExtension()
    {
        $data = [
            'extension_id' => 22,
            'package_id'   => 0,
            'name'         => 'com_content',
            'type'         => 'component',
        ];

        $phpUnitFrameworkMockObjectMockObject = $this->getExtensionMock(22, $data);

        $this->assertEquals(22, $phpUnitFrameworkMockObjectMockObject->id());
        $this->assertFalse($phpUnitFrameworkMockObjectMockObject->isLoaded());
        $this->assertEquals($data, $phpUnitFrameworkMockObjectMockObject->all());
        $this->assertTrue($phpUnitFrameworkMockObjectMockObject->isLoaded());
    }

    /**
     * isPublished returns correct value.
     *
     * @return  void
     */
    public function testIsPublishedReturnsCorrectValue()
    {
        $extension = new Extension(999);

        $reflectionClass = new \ReflectionClass($extension);
        $reflectionProperty = $reflectionClass->getProperty('row');
        $reflectionProperty->setAccessible(true);

        $reflectionProperty->setValue($extension, ['extension_id' => 999, 'enabled' => 0]);

        $this->assertFalse($extension->isPublished());
        $this->assertTrue($extension->isUnpublished());

        $reflectionProperty->setValue($extension, ['extension_id' => 999, 'enabled' => 1]);

        $this->assertTrue($extension->isPublished());
        $this->assertFalse($extension->isUnpublished());
    }

    /**
     * isComponent returns correct value.
     *
     * @return  void
     */
    public function testIsComponentReturnsCorrectValue()
    {
        $extension = new Extension(999);

        $reflectionClass = new \ReflectionClass($extension);
        $reflectionProperty = $reflectionClass->getProperty('row');
        $reflectionProperty->setAccessible(true);

        $reflectionProperty->setValue($extension, ['extension_id' => 999, 'type' => Extension::TYPE_MODULE]);

        $this->assertFalse($extension->isComponent());

        $reflectionProperty->setValue($extension, ['extension_id' => 999, 'type' => Extension::TYPE_COMPONENT]);

        $this->assertTrue($extension->isComponent());
    }

    /**
     * isFile returns correct value
     *
     * @return  void
     */
    public function testIsFileReturnsCorrectValue()
    {
        $extension = new Extension(999);

        $reflectionClass = new \ReflectionClass($extension);
        $reflectionProperty = $reflectionClass->getProperty('row');
        $reflectionProperty->setAccessible(true);

        $reflectionProperty->setValue($extension, ['extension_id' => 999, 'type' => Extension::TYPE_COMPONENT]);

        $this->assertFalse($extension->isFile());

        $reflectionProperty->setValue($extension, ['extension_id' => 999, 'type' => Extension::TYPE_FILE]);

        $this->assertTrue($extension->isFile());
    }

    /**
     * isLanguage returns correct value
     *
     * @return  void
     */
    public function testIsLanguageReturnsCorrectValue()
    {
        $extension = new Extension(999);

        $reflectionClass = new \ReflectionClass($extension);
        $reflectionProperty = $reflectionClass->getProperty('row');
        $reflectionProperty->setAccessible(true);

        $reflectionProperty->setValue($extension, ['extension_id' => 999, 'type' => Extension::TYPE_COMPONENT]);

        $this->assertFalse($extension->isLanguage());

        $reflectionProperty->setValue($extension, ['extension_id' => 999, 'type' => Extension::TYPE_LANGUAGE]);

        $this->assertTrue($extension->isLanguage());
    }

    /**
     * isLibrary returns correct value
     *
     * @return  void
     */
    public function testIsLibraryReturnsCorrectValue()
    {
        $extension = new Extension(999);

        $reflectionClass = new \ReflectionClass($extension);
        $reflectionProperty = $reflectionClass->getProperty('row');
        $reflectionProperty->setAccessible(true);

        $reflectionProperty->setValue($extension, ['extension_id' => 999, 'type' => Extension::TYPE_COMPONENT]);

        $this->assertFalse($extension->isLibrary());

        $reflectionProperty->setValue($extension, ['extension_id' => 999, 'type' => Extension::TYPE_LIBRARY]);

        $this->assertTrue($extension->isLibrary());
    }

    /**
     * isModule returns correct value
     *
     * @return  void
     */
    public function testIsModuleReturnsCorrectValue()
    {
        $extension = new Extension(999);

        $reflectionClass = new \ReflectionClass($extension);
        $reflectionProperty = $reflectionClass->getProperty('row');
        $reflectionProperty->setAccessible(true);

        $reflectionProperty->setValue($extension, ['extension_id' => 999, 'type' => Extension::TYPE_COMPONENT]);

        $this->assertFalse($extension->isModule());

        $reflectionProperty->setValue($extension, ['extension_id' => 999, 'type' => Extension::TYPE_MODULE]);

        $this->assertTrue($extension->isModule());
    }

    /**
     * isPackage returns correct value
     *
     * @return  void
     */
    public function testIsPackageReturnsCorrectValue()
    {
        $extension = new Extension(999);

        $reflectionClass = new \ReflectionClass($extension);
        $reflectionProperty = $reflectionClass->getProperty('row');
        $reflectionProperty->setAccessible(true);

        $reflectionProperty->setValue($extension, ['extension_id' => 999, 'type' => Extension::TYPE_COMPONENT]);

        $this->assertFalse($extension->isPackage());

        $reflectionProperty->setValue($extension, ['extension_id' => 999, 'type' => Extension::TYPE_PACKAGE]);

        $this->assertTrue($extension->isPackage());
    }

    /**
     * isPlugin returns correct value
     *
     * @return  void
     */
    public function testIsPluginReturnsCorrectValue()
    {
        $extension = new Extension(999);

        $reflectionClass = new \ReflectionClass($extension);
        $reflectionProperty = $reflectionClass->getProperty('row');
        $reflectionProperty->setAccessible(true);

        $reflectionProperty->setValue($extension, ['extension_id' => 999, 'type' => Extension::TYPE_COMPONENT]);

        $this->assertFalse($extension->isPlugin());

        $reflectionProperty->setValue($extension, ['extension_id' => 999, 'type' => Extension::TYPE_PLUGIN]);

        $this->assertTrue($extension->isPlugin());
    }

    /**
     * isTemplate returns correct value
     *
     * @return  void
     */
    public function testIsTemplateReturnsCorrectValue()
    {
        $extension = new Extension(999);

        $reflectionClass = new \ReflectionClass($extension);
        $reflectionProperty = $reflectionClass->getProperty('row');
        $reflectionProperty->setAccessible(true);

        $reflectionProperty->setValue($extension, ['extension_id' => 999, 'type' => Extension::TYPE_COMPONENT]);

        $this->assertFalse($extension->isTemplate());

        $reflectionProperty->setValue($extension, ['extension_id' => 999, 'type' => Extension::TYPE_TEMPLATE]);

        $this->assertTrue($extension->isTemplate());
    }

    /**
     * isType returns correct value.
     *
     * @return  void
     */
    public function testIsTypeReturnsCorrectValue()
    {
        $extension = new Extension(999);

        $reflectionClass = new \ReflectionClass($extension);
        $reflectionProperty = $reflectionClass->getProperty('row');
        $reflectionProperty->setAccessible(true);

        $reflectionProperty->setValue($extension, ['extension_id' => 999, 'type' => Extension::TYPE_COMPONENT]);

        $this->assertTrue($extension->isType(Extension::TYPE_COMPONENT));
        $this->assertFalse($extension->isType(Extension::TYPE_FILE));
        $this->assertFalse($extension->isType(Extension::TYPE_LANGUAGE));
        $this->assertFalse($extension->isType(Extension::TYPE_LIBRARY));
        $this->assertFalse($extension->isType(Extension::TYPE_MODULE));
        $this->assertFalse($extension->isType(Extension::TYPE_PACKAGE));
        $this->assertFalse($extension->isType(Extension::TYPE_PLUGIN));
        $this->assertFalse($extension->isType(Extension::TYPE_TEMPLATE));

        $reflectionProperty->setValue($extension, ['extension_id' => 999, 'type' => Extension::TYPE_PLUGIN]);

        $this->assertFalse($extension->isType(Extension::TYPE_COMPONENT));
        $this->assertFalse($extension->isType(Extension::TYPE_FILE));
        $this->assertFalse($extension->isType(Extension::TYPE_LANGUAGE));
        $this->assertFalse($extension->isType(Extension::TYPE_LIBRARY));
        $this->assertFalse($extension->isType(Extension::TYPE_MODULE));
        $this->assertFalse($extension->isType(Extension::TYPE_PACKAGE));
        $this->assertTrue($extension->isType(Extension::TYPE_PLUGIN));
        $this->assertFalse($extension->isType(Extension::TYPE_TEMPLATE));
    }

    /**
     * table returns JTableExtension instance.
     *
     * @return  void
     */
    public function testTableReturnsCorrectInstance()
    {
        $extension = new Extension();

        $this->assertInstanceOf(\JTableExtension::class, $extension->table());
    }

    /**
     * Get a table mock to simulate loading from table.
     *
     * @param   array   $data  Array of data that table should return in getProperties
     *
     * @return  \PHPUnit_Framework_MockObject_MockObject
     */
    private function getTableMock(array $data = [])
    {
        $tableMock = $this->getMockBuilder('FakeExtensionTable')
            ->disableOriginalConstructor()
            ->setMethods(['load', 'getProperties'])
            ->getMock();

        $tableMock->expects($this->at(0))
            ->method('load')
            ->willReturn(true);

        $tableMock->expects($this->at(1))
            ->method('getProperties')
            ->willReturn($data);

        return $tableMock;
    }

    /**
     * Get an extension mock to simulate data loading.
     *
     * @param   int  $id    Identifier to assign
     * @param   array    $data  Optional data to be returned as row
     *
     * @return  \PHPUnit_Framework_MockObject_MockObject
     */
    private function getExtensionMock($id = null, array $data = [])
    {
        $extension = $this->getMockBuilder(Extension::class)
            ->setMethods(['table'])
            ->getMock();

        $extension
            ->method('table')
            ->willReturn($this->getTableMock($data));

        if ($id) {
            $reflectionClass = new \ReflectionClass($extension);
            $idProperty = $reflectionClass->getProperty('id');
            $idProperty->setAccessible(true);
            $idProperty->setValue($extension, $id);
        }

        return $extension;
    }
}
