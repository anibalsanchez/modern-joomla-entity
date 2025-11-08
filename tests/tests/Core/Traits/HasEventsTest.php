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

use Extly\Joomla\Entity\Tests\Stubs\Entity;

/**
 * HasEvents trait tests.
 *
 * @since   1.1.0
 */
class HasEventsTest extends \PHPUnit\Framework\TestCase
{
    /**
     * getDispatcher returns correct class.
     *
     * @return  void
     */
    public function testGetDispatcherReturnsCorrectClass()
    {
        $entity = new Entity();

        $reflectionClass = new \ReflectionClass($entity);
        $reflectionMethod = $reflectionClass->getMethod('dispatcher');
        $reflectionMethod->setAccessible(true);

        $this->assertInstanceOf(\JEventDispatcher::class, $reflectionMethod->invoke($entity));
    }

    /**
     * importPlugin imports plugin.
     *
     * @return  void
     */
    public function testImportPluginImportsPlugin()
    {
        $phpUnitFrameworkMockObjectMockObject = $this->getMockedEntity();

        $reflectionClass = new \ReflectionClass($phpUnitFrameworkMockObjectMockObject);

        $reflectionProperty = $reflectionClass->getProperty('eventsPluginsImported');
        $reflectionProperty->setAccessible(true);

        $this->assertSame([], $reflectionProperty->getValue($phpUnitFrameworkMockObjectMockObject));

        $phpUnitFrameworkMockObjectMockObject->importPlugin('my_folder');

        $this->assertSame(['my_folder'], $reflectionProperty->getValue($phpUnitFrameworkMockObjectMockObject));
    }

    /**
     * trigger runs trigger method on dispatcher.
     *
     * @return  void
     */
    public function testTriggerExecutesTriggerMethodOnDispatcher()
    {
        $response = ['result 1', 'result 2'];

        $phpUnitFrameworkMockObjectMockObject = $this->getMockedEntity(1, $response);

        $this->assertSame($response, $phpUnitFrameworkMockObjectMockObject->trigger('event_name', ['param1', 'param2']));
    }

    /**
     * trigger imports default plugins.
     *
     * @return  void
     */
    public function testTriggerImportsDefaultPlugins()
    {
        $phpUnitFrameworkMockObjectMockObject = $this->getMockedEntity();

        $reflectionClass = new \ReflectionClass($phpUnitFrameworkMockObjectMockObject);

        $reflectionProperty = $reflectionClass->getProperty('eventsPluginsImported');
        $reflectionProperty->setAccessible(true);

        $phpUnitFrameworkMockObjectMockObject->trigger('sample_event');

        $this->assertSame(['joomla_entity'], $reflectionProperty->getValue($phpUnitFrameworkMockObjectMockObject));
    }

    /**
     * Get a mocked entity with bypassed importJoomlaPlugin method to avoid testing issues.
     *
     * @param   int  $id        Identifier to assign
     * @param   array    $response  Expected response from the dispatcher
     *
     * @return  \PHPUnit_Framework_MockObject_MockObject
     */
    private function getMockedEntity($id = null, $response = [])
    {
        $phpUnitFrameworkMockObjectMockObject = $this->getMockBuilder(\JEventDispatcher::class)
            ->setMethods(['trigger'])
            ->getMock();

        $phpUnitFrameworkMockObjectMockObject->method('trigger')
            ->willReturn($response);

        $entity = $this->getMockBuilder(Entity::class)
            ->setMethods(['importJoomlaPlugin', 'dispatcher'])
            ->getMock();

        $entity->method('importJoomlaPlugin')
            ->willReturn(true);

        $entity->method('dispatcher')
            ->willReturn($phpUnitFrameworkMockObjectMockObject);

        if ($id) {
            $reflectionClass = new \ReflectionClass($entity);
            $idProperty = $reflectionClass->getProperty('id');
            $idProperty->setAccessible(true);
            $idProperty->setValue($entity, $id);
        }

        return $entity;
    }
}
