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

use Extly\Joomla\Entity\Tests\Core\Traits\Stubs\EntityWithUrls;

/**
 * HasUrls trait tests.
 *
 * @since   1.1.0
 */
class HasUrlsTest extends \PHPUnit\Framework\TestCase
{
    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     *
     * @return  void
     */
    protected function tearDown()
    {
        EntityWithUrls::clearAll();

        parent::tearDown();
    }

    /**
     * getUrls gets correct data.
     *
     * @return  void
     */
    public function testGetUrlsGetsCorrectData()
    {
        $entityWithUrls = new EntityWithUrls(999);

        $reflectionClass = new \ReflectionClass($entityWithUrls);
        $reflectionProperty = $reflectionClass->getProperty('row');
        $reflectionProperty->setAccessible(true);

        $reflectionProperty->setValue($entityWithUrls, ['id' => 999, 'urls' => '']);

        $this->assertEquals([], $entityWithUrls->getUrls(true));

        $reflectionProperty->setValue($entityWithUrls, ['id' => 999, 'urls' => '{}']);

        $this->assertEquals([], $entityWithUrls->getUrls(true));

        $reflectionProperty->setValue($entityWithUrls, ['id' => 999, 'urls' => '{"urla":"","urlatext":"","targeta":"","urlb":"","urlbtext":"","targetb":"","urlc":"","urlctext":"","targetc":""}']);

        $this->assertEquals([], $entityWithUrls->getUrls(true));

        $reflectionProperty->setValue($entityWithUrls, ['id' => 999, 'urls' => '{"urla":"http://google.com","urlatext":"Google","targeta":"0"}']);

        // With no reload returns old data
        $this->assertEquals([], $entityWithUrls->getUrls());

        $expected = [
            'a' => [
                'url'    => 'http://google.com',
                'text'   => 'Google',
                'target' => '0',
            ],
        ];

        $this->assertEquals($expected, $entityWithUrls->getUrls(true));

        $reflectionProperty->setValue($entityWithUrls, ['id' => 999, 'urls' => '{"urla":"http:\/\/google.es","urlatext":"Google","targeta":"1","urlb":"http:\/\/yahoo.com","urlbtext":"Yahoo","targetb":"0","urlc":"http://www.phproberto.com","urlctext":"Phproberto","targetc":""}']);

        $expected = [
            'a' => [
                'url'    => 'http://google.es',
                'text'   => 'Google',
                'target' => '1',
            ],
            'b' => [
                'url'    => 'http://yahoo.com',
                'text'   => 'Yahoo',
                'target' => '0',
            ],
            'c' => [
                'url'    => 'http://www.phproberto.com',
                'text'   => 'Phproberto',
            ],
        ];

        $this->assertEquals($expected, $entityWithUrls->getUrls(true));
    }

    /**
     * getUrls works with custom column.
     *
     * @return  void
     */
    public function testGetUrlsWorksWithCustomColumn()
    {
        $phpUnitFrameworkMockObjectMockObject = $this->getMockBuilder(EntityWithUrls::class)
            ->setMethods(['getColumnUrls'])
            ->getMock();

        $phpUnitFrameworkMockObjectMockObject->method('getColumnUrls')
            ->willReturn('links');

        $reflectionClass = new \ReflectionClass($phpUnitFrameworkMockObjectMockObject);
        $reflectionProperty = $reflectionClass->getProperty('row');
        $reflectionProperty->setAccessible(true);

        $reflectionProperty->setValue($phpUnitFrameworkMockObjectMockObject, ['id' => 999, 'links' => '']);

        $this->assertEquals([], $phpUnitFrameworkMockObjectMockObject->getUrls());

        $reflectionProperty->setValue($phpUnitFrameworkMockObjectMockObject, ['id' => 999, 'links' => '{"urla":"http:\/\/google.es","urlatext":"Google","targeta":"1","urlb":"http:\/\/yahoo.com","urlbtext":"Yahoo","targetb":"0","urlc":"http://www.phproberto.com","urlctext":"Phproberto","targetc":""}']);

        $this->assertEquals([], $phpUnitFrameworkMockObjectMockObject->getUrls());

        $expected = [
            'a' => [
                'url'    => 'http://google.es',
                'text'   => 'Google',
                'target' => '1',
            ],
            'b' => [
                'url'    => 'http://yahoo.com',
                'text'   => 'Yahoo',
                'target' => '0',
            ],
            'c' => [
                'url'    => 'http://www.phproberto.com',
                'text'   => 'Phproberto',
            ],
        ];

        $this->assertEquals($expected, $phpUnitFrameworkMockObjectMockObject->getUrls(true));
    }
}
