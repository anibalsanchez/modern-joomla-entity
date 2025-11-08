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
use Extly\Joomla\Entity\Core\Client\Client;
use Extly\Joomla\Entity\Core\Client\Site;

/**
 * Tests for HasExtension trait.
 *
 * @since  1.1.0
 */
class ClientTest extends \TestCase
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

        $app = $this->getMockCmsApp();

        \Joomla\CMS\Factory::$application = $app;
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
     * Test administrator client can be retrieved.
     *
     * @return  void
     */
    public function testAdminReturnsAdminClient()
    {
        $admin = Client::admin();
        $this->assertEquals(get_class($admin), get_class(new Administrator()));
    }

    /**
     * Test getActive return site client when active application is site.
     *
     * @return  void
     */
    public function testGetActiveReturnsSiteClient()
    {
        $this->getMockCmsApp()
            ->method('isAdmin')
            ->willReturn(false);

        $client = Client::active();
        $this->assertEquals(get_class($client), get_class(new Site()));
    }

    /**
     * Test getActive return site client when active application is site.
     *
     * @return  void
     */
    public function testGetActiveReturnsAdminClient()
    {
        $this->getMockCmsApp()
            ->method('isAdmin')
            ->willReturn(true);

        $client = Client::active();
        $this->assertEquals(get_class($client), get_class(new Site()));
    }

    /**
     * Test site client can be retrieved.
     *
     * @return  void
     */
    public function testSiteReturnsSiteClient()
    {
        $site = Client::site();
        $this->assertEquals(get_class($site), get_class(new Site()));
    }
}
