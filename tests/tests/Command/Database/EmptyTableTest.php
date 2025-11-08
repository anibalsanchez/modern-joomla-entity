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
use Extly\Joomla\Entity\Command\Database\EmptyTable;
use Joomla\CMS\Factory;

/**
 * EmptyTable tests.
 *
 * @since   1.8
 */
class EmptyTableTest extends \TestCaseDatabase
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
        $emptyTable = new EmptyTable('test');

        $this->assertTrue($emptyTable instanceof CommandInterface);
    }

    /**
     * @test
     *
     * @return void
     */
    public function tableIsEmptied()
    {
        $db = static::$driver;
        $db->setQuery(
            'CREATE TABLE `emptytable_test` (
				`id` INTEGER PRIMARY KEY NOT NULL,
				`name` TEXT NOT NULL
			)'
        );

        $db->execute();

        $query = $db->getQuery(true)
            ->insert($db->qn('emptytable_test'))
            ->columns(['name'])
            ->values(
                [
                    $db->q('One item'),
                    $db->q('Another item'),
                    $db->q('Yet another item'),
                ]
            );

        $db->setQuery($query);
        $db->execute();

        Factory::$database = static::$driver;

        $db = Factory::getDbo();
        $query = $db->getQuery(true)
            ->select($db->qn('id'))
            ->from($db->qn('emptytable_test'));

        $db->setQuery($query);

        $this->assertSame(3, count($db->loadObjectList()));

        $emptyTable = new EmptyTable('emptytable_test');
        $emptyTable->execute();

        $this->assertSame(0, count($db->loadObjectList()));
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
            $emptyTable = new EmptyTable('inexistent', ['db' => $db2]);
            $emptyTable->execute();
        } catch (\RuntimeException $runtimeException) {
            $error = $runtimeException->getMessage();
        }

        $this->assertSame('Error emptying DB table `inexistent`: Could not connect to MySQL server.', $error);
    }

    /**
     * @test
     *
     * @return void
     */
    public function customDriverIsUsed()
    {
        @unlink(dirname(JPATH_TESTS_PHPROBERTO).'/:emptytable_test:');

        $db2 = \Joomla\Database\DatabaseDriver::getInstance(
            [
                'driver' => 'sqlite',
                'database' => ':emptytable_test:',
                'prefix' => 'ddd',
            ]
        );

        $db2->setQuery(
            'CREATE TABLE `emptytable_test2` (
				`id` INTEGER PRIMARY KEY NOT NULL,
				`name` TEXT NOT NULL
			)'
        );
        $db2->execute();

        $query = $db2->getQuery(true)
            ->insert($db2->qn('emptytable_test2'))
            ->columns(['name'])
            ->values(
                [
                    $db2->q('One item'),
                    $db2->q('Another item'),
                    $db2->q('Yet another item'),
                ]
            );

        $db2->setQuery($query);
        $db2->execute();

        $query = $db2->getQuery(true)
            ->select($db2->qn('id'))
            ->from($db2->qn('emptytable_test2'));

        $db2->setQuery($query);

        $this->assertSame(3, count($db2->loadObjectList()));

        $emptyTable = new EmptyTable('emptytable_test2', ['db' => $db2]);
        $emptyTable->execute();

        $this->assertSame(0, count($db2->loadObjectList()));

        $db2->disconnect();

        unlink(dirname(JPATH_TESTS_PHPROBERTO).'/:emptytable_test:');
    }
}
