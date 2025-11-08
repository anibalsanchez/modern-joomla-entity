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

use Extly\Joomla\Entity\Acl\Acl;
use Extly\Joomla\Entity\Core\Extension\Component;
use Extly\Joomla\Entity\Users\User;

/**
 * Component entity tests.
 *
 * @since   1.1.0
 */
class ComponentTest extends \TestCaseDatabase
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
        Component::clearAll();

        $this->restoreFactoryState();

        parent::tearDown();
    }

    /**
     * aclAssetName returns option.
     *
     * @return  void
     */
    public function testAclAssetNameReturnsOption()
    {
        $component = $this->getMockBuilder(Component::class)
            ->setMethods(['option'])
            ->getMock();

        $component->expects($this->once())
            ->method('option')
            ->willReturn('com_phproberto');

        $this->assertSame('com_phproberto', $component->aclAssetName());
    }

    /**
     * Acl can be retrieved.
     *
     * @return  void
     */
    public function testAclCanBeRetrieved()
    {
        $component = new Component(666);
        $user = new User(999);

        $acl = $component->acl($user);

        $reflectionClass = new \ReflectionClass($acl);
        $reflectionProperty = $reflectionClass->getProperty('entity');
        $reflectionProperty->setAccessible(true);

        $userProperty = $reflectionClass->getProperty('user');
        $userProperty->setAccessible(true);

        $this->assertInstanceOf(Acl::class, $acl);
        $this->assertSame($user, $userProperty->getValue($acl));
        $this->assertSame($component, $reflectionProperty->getValue($acl));
    }

    /**
     * active returns correct instance.
     *
     * @return  void
     */
    public function testActiveReturnsCorrectInstance()
    {
        \Joomla\CMS\Factory::getApplication()->input->set('option', 'com_content');

        $this->assertInstanceOf(Component::class, Component::active());

        \Joomla\CMS\Factory::getApplication()->input->set('option', 'com_phproberto');

        $this->assertSame('com_phproberto', Component::active()->option());

        \Joomla\CMS\Factory::getApplication()->input->set('option', null);
    }

    /**
     * fromOption loads cached id.
     *
     * @return  void
     */
    public function testFromOptionCachesId()
    {
        $component = new Component();

        $reflectionClass = new \ReflectionClass($component);
        $reflectionProperty = $reflectionClass->getProperty('optionIdXref');
        $reflectionProperty->setAccessible(true);

        $this->assertSame([], $reflectionProperty->getValue($component));

        $component = Component::fromOption('com_content');

        $this->assertSame(['com_content' => 22], $reflectionProperty->getValue($component));
    }

    /**
     * fromOption throws exception for wrong option.
     *
     * @return  void
     *
     * @expectedException \InvalidArgumentException
     */
    public function testFromOptionThrowsExceptionForWrongOption()
    {
        $component = Component::fromOption(' ');
    }

    /**
     * fromOption throws exception when component not found.
     *
     * @return  void
     *
     * @expectedException \RuntimeException
     */
    public function testFromOptionThrowsExceptionWhenComponentNotFound()
    {
        $component = Component::fromOption('com_phproberto');
    }

    /**
     * model returns admin model.
     *
     * @return  void
     */
    public function testModelReturnsAdministratorModel()
    {
        $component = Component::fromOption('com_admin');

        $this->assertEquals('AdminModelProfile', get_class($component->model('Profile')));
    }

    /**
     * model returns site model.
     *
     * @return  void
     */
    public function testModelReturnsSiteModel()
    {
        $extension = Component::fromOption('com_content')->site();

        $this->assertInstanceOf('ContentModelArticles', $extension->model('Articles'));
    }

    /**
     * Test model returns a backend model when backend app is active.
     *
     * @return  void
     */
    public function testModelReturnsBackendModelWhenBackendAppIsActive()
    {
        $component = Component::fromOption('com_admin');

        $this->assertEquals('AdminModelProfile', get_class($component->model('Profile')));
    }

    /**
     * model throws exception when model not found.
     *
     * @return  void
     *
     * @expectedException \InvalidArgumentException
     */
    public function testModelThrowsExceptionWhenModelNotFound()
    {
        $component = Component::fromOption('com_admin');

        $component->model('UnexistingModel');
    }

    /**
     * modelsFolder returns correct value.
     *
     * @return  void
     */
    public function testModelsFolderReturnsCorrectValue()
    {
        $component = Component::fromOption('com_admin');

        $this->assertSame(JPATH_SITE.'/administrator/components/com_admin/models', $component->modelsFolder());

        $component = Component::fromOption('com_config')->site();

        $this->assertSame(JPATH_SITE.'/components/com_config/model', $component->modelsFolder());
    }

    /**
     * modelsFolder throws exception when folder not found.
     *
     * @return  void
     *
     * @expectedException \RuntimeException
     */
    public function testModelsFolderThrowsExceptionWhenFolderNotFound()
    {
        $component = Component::fromOption('com_cpanel');

        $component->modelsFolder();
    }

    /**
     * option returns correct string.
     *
     * @return  void
     */
    public function testOptionReturnsCorrectString()
    {
        $component = new Component(999);

        $reflectionClass = new \ReflectionClass($component);
        $reflectionProperty = $reflectionClass->getProperty('row');
        $reflectionProperty->setAccessible(true);

        $reflectionProperty->setValue($component, ['extension_id' => 999, 'element' => 'com_phproberto']);

        $this->assertSame('com_phproberto', $component->option());

        $component = new Component(999);
        $reflectionProperty->setValue($component, ['extension_id' => 999, 'element' => 'com_contact']);

        $this->assertSame('com_contact', $component->option());
    }

    /**
     * prefix returns correct value.
     *
     * @return  void
     */
    public function testPrefixReturnsCorrectValue()
    {
        $component = Component::fromOption('com_cpanel');

        $this->assertSame('Cpanel', $component->prefix());

        $component = Component::fromOption('com_categories');

        $this->assertSame('Categories', $component->prefix());
    }

    /**
     * table returns correct table.
     *
     * @return  void
     */
    public function testTableReturnsCorrectTable()
    {
        $component = Component::fromOption('com_cpanel');

        $this->assertInstanceOf('JTableExtension', $component->table());

        $component = Component::fromOption('com_categories');

        require_once JPATH_ADMINISTRATOR.'/components/com_categories/tables/category.php';

        $this->assertInstanceOf('CategoriesTableCategory', $component->table('Category'));
        $this->assertInstanceOf('JTableContent', $component->table('Content', 'JTable'));
    }

    /**
     * tablesFolder returns correct value.
     *
     * @return  void
     */
    public function testTablesFolderReturnsCorrectValue()
    {
        $component = Component::fromOption('com_categories');

        $this->assertSame(JPATH_ADMINISTRATOR.'/components/com_categories/tables', $component->tablesFolder());
    }

    /**
     * tablesFolder throws exception when folder does not exist.
     *
     * @return  void
     *
     * @expectedException \RuntimeException
     */
    public function testTablesFolderThrowsExceptionWhenFolderDoesNotExist()
    {
        $component = Component::fromOption('com_cpanel');

        $component->tablesFolder();
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
