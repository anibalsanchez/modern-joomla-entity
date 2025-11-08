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

namespace Extly\Joomla\Entity\Tests\Command\Database;

defined('_JEXEC') || die;

use Extly\Joomla\Entity\Command\Contracts\CommandInterface;
use Extly\Joomla\Entity\Command\Database\DropTable;
use Joomla\CMS\Factory;

/**
 * DropTabletests.
 *
 * @since   1.8
 */
class DropTableTest extends \TestCaseDatabase
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

        Factory::$config = $this->getMockConfig();
        Factory::$application = $this->getMockCmsApp();
        Factory::$session = $this->getMockSession();
    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     *
     * @return  void
     */
    protected function tearDown()
    {
        $this->restoreFactoryState();

        parent::tearDown();
    }

    /**
     * @test
     *
     * @return void
     */
    public function implementsCommandInterface()
    {
        $dropTable = new DropTable('test');

        $this->assertTrue($dropTable instanceof CommandInterface);
    }

    /**
     * @test
     *
     * @return void
     */
    public function tableIsDropped()
    {
        static::$driver->setQuery(
            'CREATE TABLE `droptable_test` (
				`id` INTEGER PRIMARY KEY NOT NULL
			)'
        );

        static::$driver->execute();

        Factory::$database = static::$driver;
        $db = Factory::getDbo();

        $this->assertTrue(in_array('droptable_test', $db->getTableList(), true));

        $dropTable = new DropTable('droptable_test');
        $dropTable->execute();

        $this->assertFalse(in_array('droptable_test', $db->getTableList(), true));
    }

    /**
     * @test
     *
     * @return void
     */
    public function exceptionIsThrownOnError()
    {
        $db2 = \Joomla\Database\DatabaseDriver::getInstance(
            [
                'driver' => 'mysqli',
                'database' => 'test',
                'prefix' => 'ddd',
            ]
        );

        $error = '';

        try {
            $dropTable = new DropTable('inexistent', ['db' => $db2]);
            $dropTable->execute();
        } catch (\RuntimeException $runtimeException) {
            $error = $runtimeException->getMessage();
        }

        $this->assertSame('Error dropping DB table `inexistent`: Could not connect to MySQL server.', $error);
    }

    /**
     * @test
     *
     * @return void
     */
    public function customDriverIsUsed()
    {
        @unlink(dirname(JPATH_TESTS_PHPROBERTO).'/:droptable_test:');

        $db2 = \Joomla\Database\DatabaseDriver::getInstance(
            [
                'driver' => 'sqlite',
                'database' => ':droptable_test:',
                'prefix' => 'ddd',
            ]
        );

        $db2->setQuery(
            'CREATE TABLE `droptable_test2` (
				`id` INTEGER PRIMARY KEY NOT NULL
			)'
        );
        $db2->execute();

        $this->assertTrue(in_array('droptable_test2', $db2->getTableList(), true));

        $dropTable = new DropTable('droptable_test2', ['db' => $db2]);
        $dropTable->execute();

        $this->assertFalse(in_array('droptable_test2', $db2->getTableList(), true));

        $db2->disconnect();

        unlink(dirname(JPATH_TESTS_PHPROBERTO).'/:droptable_test:');
    }
}
