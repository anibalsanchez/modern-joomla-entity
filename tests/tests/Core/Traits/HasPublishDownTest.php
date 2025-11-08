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

use Extly\Joomla\Entity\Tests\Core\Traits\Stubs\EntityWithPublishDown;

/**
 * HasPublishDown trait tests.
 *
 * @since   1.1.0
 */
class HasPublishDownTest extends \PHPUnit\Framework\TestCase
{
    /**
     * Column to use to load/store publish down date.
     *
     * @const
     */
    public const COLUMN_PUBLISH_DOWN = 'publish_down';

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     *
     * @return  void
     */
    protected function tearDown()
    {
        EntityWithPublishDown::clearAll();

        parent::tearDown();
    }

    /**
     * getPublishDown returns expected value.
     *
     * @return  void
     */
    public function testGetPublishDownReturnsExpectedValue()
    {
        $phpUnitFrameworkMockObjectMockObject = $this->getEntity([self::COLUMN_PUBLISH_DOWN => '2017-09-23 16:49:00']);

        $this->assertSame('2017-09-23 16:49:00', $phpUnitFrameworkMockObjectMockObject->getPublishDown());
    }

    /**
     * hasPublishDownReturnsExpectedValue.
     *
     * @return  void
     */
    public function testHasPublishDownReturnsExpectedValue()
    {
        $entity = $this->getEntity([self::COLUMN_PUBLISH_DOWN => null]);

        $this->assertFalse($entity->hasPublishDown());

        $entity = $this->getEntity([self::COLUMN_PUBLISH_DOWN => '2017-09-23 16:49:00']);

        $this->assertTrue($entity->hasPublishDown());

        $entity = $this->getEntity([self::COLUMN_PUBLISH_DOWN => '0000-00-00 00:00:00']);

        $this->assertFalse($entity->hasPublishDown());
    }

    /**
     * isPublishedDown returns correct value.
     *
     * @return  void
     */
    public function testIsPublishedDownReturnsCorrectValue()
    {
        $entity = $this->getEntity([self::COLUMN_PUBLISH_DOWN => null]);

        $this->assertFalse($entity->isPublishedDown());

        // Remove 1h to current time to force past date
        $date = new \DateTime();
        $date->sub(new \DateInterval('PT1H'));

        $entity = $this->getEntity([self::COLUMN_PUBLISH_DOWN => $date->format('Y-m-d H:i:s')]);

        $this->assertTrue($entity->isPublishedDown());

        $entity = $this->getEntity([self::COLUMN_PUBLISH_DOWN => '0000-00-00 00:00:00']);

        $this->assertFalse($entity->isPublishedDown());

        // Add 1h to current time to force future date
        $date = new \DateTime();
        $date->add(new \DateInterval('PT1H'));

        $entity = $this->getEntity([self::COLUMN_PUBLISH_DOWN => $date->format('Y-m-d H:i:s')]);

        $this->assertFalse($entity->isPublishedDown());
    }

    /**
     * Get a mocked entity.
     *
     * @param   array  $row  Row returned by the entity as data
     *
     * @return  \PHPUnit_Framework_MockObject_MockObject
     */
    private function getEntity($row = [])
    {
        $phpUnitFrameworkMockObjectMockObject = $this->getMockBuilder(EntityWithPublishDown::class)
            ->setMethods(['columnAlias', 'nullDate'])
            ->getMock();

        $phpUnitFrameworkMockObjectMockObject->method('columnAlias')
            ->willReturn(static::COLUMN_PUBLISH_DOWN);

        $phpUnitFrameworkMockObjectMockObject->method('nullDate')
            ->willReturn('0000-00-00 00:00:00');

        $phpUnitFrameworkMockObjectMockObject->bind($row);

        return $phpUnitFrameworkMockObjectMockObject;
    }
}
