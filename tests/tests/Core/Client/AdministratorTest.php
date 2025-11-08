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

namespace Extly\Joomla\Entity\Tests\Core\Client;

use Extly\Joomla\Entity\Core\Client\Administrator;

/**
 * Tests for Administrator client.
 *
 * @since  1.1.0
 */
class AdministratorTest extends \TestCase
{
    /**
     * Test getFolder returns the correct folder.
     *
     * @return  void
     */
    public function testGetFolderReturnsCorrectFolder()
    {
        $administrator = new Administrator();
        $this->assertEquals(JPATH_ADMINISTRATOR, $administrator->getFolder());
    }

    /**
     * Test getId returns the correct id.
     *
     * @return  void
     */
    public function testGetIdReturnsCorrectId()
    {
        $administrator = new Administrator();
        $this->assertEquals(Administrator::ID, $administrator->getId());
    }

    /**
     * Test getName returns correct name.
     *
     * @return  void
     */
    public function testGetNameRetursCorrectName()
    {
        $administrator = new Administrator();
        $this->assertEquals(Administrator::NAME, $administrator->getName());
    }

    /**
     * Test isAdmin returns true.
     *
     * @return  void
     */
    public function testIsAdminReturnsTrue()
    {
        $administrator = new Administrator();
        $this->assertTrue($administrator->IsAdmin());
    }

    /**
     * Test isAdmin returns false.
     *
     * @return  void
     */
    public function testIsSiteReturnsFalse()
    {
        $administrator = new Administrator();
        $this->assertFalse($administrator->IsSite());
    }
}
