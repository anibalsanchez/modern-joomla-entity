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

namespace Extly\Joomla\Entity\Tests\Command\FileSystem;

defined('_JEXEC') || die;

use Extly\Joomla\Entity\Command\Contracts\CommandInterface;
use Extly\Joomla\Entity\Command\FileSystem\DeleteFolderRecursively;
use Joomla\CMS\Factory;

/**
 * DeleteFolderRecursivelyTest tests.
 *
 * @since   1.8
 */
class DeleteFolderRecursivelyTestTest extends \TestCase
{
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
    public function deletingUnexistingFolderReturnsTrue()
    {
        $this->assertTrue(
            DeleteFolderRecursively::instance([__DIR__.'/does-not-exist'])->execute()
        );
    }

    /**
     * @test
     *
     * @return void
     */
    public function folderIsDeleted()
    {
        $this->createTestFolder();

        $tmpFolder = $this->tmpFolder();

        $this->assertTrue(is_dir($tmpFolder));

        $deleteFolderRecursively = new DeleteFolderRecursively($tmpFolder);
        $deleteFolderRecursively->execute();

        $this->assertFalse(is_dir($tmpFolder));
    }

    /**
     * Create a test folder structure.
     *
     * @return  void
     */
    private function createTestFolder()
    {
        $tmpFolder = $this->tmpFolder();

        if (is_dir($tmpFolder)) {
            $this->deleteTestFolder();
        }

        $childFolder = $tmpFolder.'/child-folder';

        mkdir($tmpFolder);
        touch($tmpFolder.'/delete-me.txt');
        mkdir($childFolder);
        touch($childFolder.'/delete-me-too.txt');
    }

    /**
     * Delete the test folder.
     *
     * @return  void
     */
    private function deleteTestFolder()
    {
        $tmpFolder = $this->tmpFolder();
        $childFolder = $tmpFolder.'/child-folder';

        @unlink($tmpFolder.'/delete-me.txt');
        @unlink($childFolder.'/delete-me-too.txt');
        @rmdir($childFolder);
        @rmdir($tmpFolder);
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
