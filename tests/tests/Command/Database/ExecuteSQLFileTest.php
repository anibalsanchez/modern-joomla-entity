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
use Extly\Joomla\Entity\Command\Database\ExecuteSQLFile;
use Extly\Joomla\Entity\Command\FileSystem\DeleteFolderRecursively;
use Joomla\CMS\Factory;

/**
 * ExecuteSQLFile tests.
 *
 * @since   1.8
 */
class ExecuteSQLFileTest extends \TestCaseDatabase
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
        $deleteFolderRecursively = new DeleteFolderRecursively('test');

        $this->assertTrue($deleteFolderRecursively instanceof CommandInterface);
    }

    /**
     * @test
     *
     * @return void
     */
    public function fileIsExecuted()
    {
        $file = $this->createTestSQLFile();

        $this->assertTrue(file_exists($file));

        $db = Factory::getDbo();

        $this->assertFalse(in_array('execute-sql-test', $db->getTableList(), true));

        $executeSQLFile = new ExecuteSQLFile($file);
        $executeSQLFile->execute();

        $this->assertTrue(in_array('execute-sql-test', $db->getTableList(), true));

        $this->deleteTmpFolder();
    }

    /**
     * Create the test SQL file.
     *
     * @return  string
     */
    private function createTestSQLFile()
    {
        $tmpFolder = $this->tmpFolder();

        if (is_dir($tmpFolder)) {
            $this->deleteTmpFolder();
        }

        mkdir($tmpFolder);
        $file = $tmpFolder.'/execute-sql-test.sql';
        touch($file);
        $sql = '-- This is a comment'
            ."\n"
            .'CREATE TABLE `execute-sql-test` (`id` INTEGER PRIMARY KEY NOT NULL, `name` TEXT NOT NULL)'
            ."\n"
            .'/* And another comment */';

        $handle = fopen($file, 'w+');
        fwrite($handle, $sql);
        fclose($handle);

        return $file;
    }

    /**
     * Delete the test folder.
     *
     * @return  void
     */
    private function deleteTmpFolder()
    {
        DeleteFolderRecursively::instance([$this->tmpFolder()])->execute();
    }

    /**
     * Route to the temporary folder used to test this command.
     *
     * @return  string
     */
    private function tmpFolder()
    {
        return __DIR__.'/tmp';
    }
}
