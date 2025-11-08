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

use Extly\Joomla\Entity\Core\Client\Administrator;
use Extly\Joomla\Entity\Core\Client\Site;
use Extly\Joomla\Entity\Tests\Core\Traits\Stubs\ClassWithClient;

/**
 * HasClient trait tests.
 *
 * @since   1.1.0
 */
class HasClientTest extends \PHPUnit\Framework\TestCase
{
    /**
     * Column storing the client identifier.
     *
     * @const
     */
    public const CLIENT_COLUMN = 'client_id';

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     *
     * @return  void
     */
    protected function tearDown()
    {
        ClassWithClient::clearAll();

        parent::tearDown();
    }

    /**
     * admin changes active client.
     *
     * @return  void
     */
    public function testAdminChangesActiveClient()
    {
        $phpUnitFrameworkMockObjectMockObject = $this->getEntity(['id' => 999, 'client_id' => '0']);

        $this->assertInstanceOf(Site::class, $phpUnitFrameworkMockObjectMockObject->client());

        $phpUnitFrameworkMockObjectMockObject->admin();

        $this->assertInstanceOf(Administrator::class, $phpUnitFrameworkMockObjectMockObject->client());
    }

    /**
     * client returns correct data.
     *
     * @return  void
     */
    public function testClientReturnsCorrectData()
    {
        $entity = $this->getEntity(['id' => 999, static::CLIENT_COLUMN => 0]);

        $this->assertInstanceOf(Site::class, $entity->client());

        $entity = $this->getEntity(['id' => 999, static::CLIENT_COLUMN => 1]);

        $this->assertInstanceOf(Administrator::class, $entity->client(true));
    }

    /**
     * client throws an exception when client column is not found.
     *
     * @return  void
     *
     * @expectedException  \InvalidArgumentException
     */
    public function testLoadClientThrowsExceptionWhenClientColumnIsNotFound()
    {
        $phpUnitFrameworkMockObjectMockObject = $this->getEntity(['id' => 999]);

        $reflectionClass = new \ReflectionClass($phpUnitFrameworkMockObjectMockObject);

        $reflectionMethod = $reflectionClass->getMethod('loadClient');
        $reflectionMethod->setAccessible(true);

        $reflectionMethod->invoke($phpUnitFrameworkMockObjectMockObject);
    }

    /**
     * loadClient returns correct value.
     *
     * @return  void
     */
    public function testLoadClientReturnsCorrectValue()
    {
        $entity = $this->getEntity(['id' => 999, 'client_id' => 0]);

        $reflectionClass = new \ReflectionClass($entity);

        $reflectionMethod = $reflectionClass->getMethod('loadClient');
        $reflectionMethod->setAccessible(true);

        $this->assertInstanceOf(Site::class, $reflectionMethod->invoke($entity));

        $entity = $this->getEntity(['id' => 999, 'client_id' => '0']);

        $this->assertInstanceOf(Site::class, $reflectionMethod->invoke($entity));

        $entity = $this->getEntity(['id' => 999, 'client_id' => 'thiswillreturn0']);

        $this->assertInstanceOf(Site::class, $reflectionMethod->invoke($entity));

        $entity = $this->getEntity(['id' => 999, 'client_id' => 1]);

        $this->assertInstanceOf(Administrator::class, $reflectionMethod->invoke($entity));

        $entity = $this->getEntity(['id' => 999, 'client_id' => '1']);

        $this->assertInstanceOf(Administrator::class, $reflectionMethod->invoke($entity));
    }

    /**
     * site changes active client.
     *
     * @return  void
     */
    public function testSiteChangesActiveClient()
    {
        $phpUnitFrameworkMockObjectMockObject = $this->getEntity(['id' => 999, 'client_id' => '1']);

        $this->assertInstanceOf(Administrator::class, $phpUnitFrameworkMockObjectMockObject->client());

        $phpUnitFrameworkMockObjectMockObject->site();

        $this->assertInstanceOf(Site::class, $phpUnitFrameworkMockObjectMockObject->client());
    }

    /**
     * Get a mocked entity with client.
     *
     * @param   array  $row  Row returned by the entity as data
     *
     * @return  \PHPUnit_Framework_MockObject_MockObject
     */
    private function getEntity($row = [])
    {
        $phpUnitFrameworkMockObjectMockObject = $this->getMockBuilder(ClassWithClient::class)
            ->setMethods(['columnAlias'])
            ->getMock();

        $phpUnitFrameworkMockObjectMockObject->method('columnAlias')
            ->willReturn('client_id');

        $phpUnitFrameworkMockObjectMockObject->bind($row);

        return $phpUnitFrameworkMockObjectMockObject;
    }
}
