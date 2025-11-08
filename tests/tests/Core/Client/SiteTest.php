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

use Extly\Joomla\Entity\Core\Client\Site;

/**
 * Tests for Site client.
 *
 * @since  1.1.0
 */
class SiteTest extends \TestCase
{
    /**
     * Test getFolder returns the correct folder.
     *
     * @return  void
     */
    public function testGetFolderReturnsCorrectFolder()
    {
        $site = new Site();
        $this->assertEquals(JPATH_SITE, $site->getFolder());
    }

    /**
     * Test getId returns the correct id.
     *
     * @return  void
     */
    public function testGetIdReturnsCorrectId()
    {
        $site = new Site();
        $this->assertEquals(Site::ID, $site->getId());
    }

    /**
     * Test getName returns correct name.
     *
     * @return  void
     */
    public function testGetNameRetursCorrectName()
    {
        $site = new Site();
        $this->assertEquals(Site::NAME, $site->getName());
    }

    /**
     * Test isAdmin returns false.
     *
     * @return  void
     */
    public function testIsAdminReturnsFalse()
    {
        $site = new Site();
        $this->assertFalse($site->IsAdmin());
    }

    /**
     * Test isSite returns true.
     *
     * @return  void
     */
    public function testIsSiteReturnsTrue()
    {
        $site = new Site();
        $this->assertTrue($site->IsSite());
    }
}
