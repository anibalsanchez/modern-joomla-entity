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

use Extly\Joomla\Entity\Tests\Core\Traits\Stubs\EntityWithParams;
use Joomla\Registry\Registry;

/**
 * HasParams trait tests.
 *
 * @since   1.1.0
 */
class HasParamsTest extends \PHPUnit\Framework\TestCase
{
    /**
     * clearParmas sets params property to null.
     *
     * @return  void
     */
    public function testClearParamsSetsParamsPropertyToNull()
    {
        $phpUnitFrameworkMockObjectMockObject = $this->getEntity(['id' => 999, 'params' => '{"foo":"bar"}']);

        $reflectionClass = new \ReflectionClass($phpUnitFrameworkMockObjectMockObject);

        $reflectionProperty = $reflectionClass->getProperty('params');
        $reflectionProperty->setAccessible(true);

        $this->assertSame(null, $reflectionProperty->getValue($phpUnitFrameworkMockObjectMockObject));

        $registry = new Registry('{"foo":"bar-modified"}');
        $reflectionProperty->setValue($phpUnitFrameworkMockObjectMockObject, $registry);

        $this->assertSame($registry, $reflectionProperty->getValue($phpUnitFrameworkMockObjectMockObject));

        $phpUnitFrameworkMockObjectMockObject->clearParams();

        $this->assertSame(null, $reflectionProperty->getValue($phpUnitFrameworkMockObjectMockObject));
    }

    /**
     * loadParams returns row params if they are already there as string.
     *
     * @return  void
     */
    public function testLoadParamsReturnsRowParamsIfTheyAreAlreadyThereAsString()
    {
        $row = ['id' => 999, 'title' => 'test entity', 'attribs' => '{"foo":"var"}', 'params' => '{"bar":"foo"}'];

        $phpUnitFrameworkMockObjectMockObject = $this->getMockBuilder(EntityWithParams::class)
            ->setMethods(['columnAlias'])
            ->getMock();

        $phpUnitFrameworkMockObjectMockObject->method('columnAlias')
            ->willReturn('attribs');

        $phpUnitFrameworkMockObjectMockObject->bind($row);

        $reflectionClass = new \ReflectionClass($phpUnitFrameworkMockObjectMockObject);
        $reflectionMethod = $reflectionClass->getMethod('loadParams');
        $reflectionMethod->setAccessible(true);

        $this->assertSame('foo', $reflectionMethod->invoke($phpUnitFrameworkMockObjectMockObject)->get('bar'));
    }

    /**
     * loadParams returns row params if they are already there as Registry.
     *
     * @return  void
     */
    public function testLoadParamsReturnsRowParamsIfTheyAreAlreadyThereAsRegistry()
    {
        $registry = new Registry('{"bar":"foo"}');

        $row = ['id' => 999, 'title' => 'test entity', 'params' => $registry];

        $phpUnitFrameworkMockObjectMockObject = $this->getMockBuilder(EntityWithParams::class)
            ->setMethods(['columnAlias'])
            ->getMock();

        $phpUnitFrameworkMockObjectMockObject->method('columnAlias')
            ->willReturn('params');

        $phpUnitFrameworkMockObjectMockObject->bind($row);

        $reflectionClass = new \ReflectionClass($phpUnitFrameworkMockObjectMockObject);
        $reflectionMethod = $reflectionClass->getMethod('loadParams');
        $reflectionMethod->setAccessible(true);

        $this->assertSame($registry, $reflectionMethod->invoke($phpUnitFrameworkMockObjectMockObject));
    }

    /**
     * loadParams returns Registry if table loads params as Registry.
     *
     * @return  void
     */
    public function testLoadParamsReturnsRegistryIfTableLoadsParamsAsRegistry()
    {
        $registry = new Registry('{"bar":"foo"}');

        $phpUnitFrameworkMockObjectMockObject = $this->getMockBuilder(EntityWithParams::class)
            ->setMethods(['columnAlias', 'get'])
            ->getMock();

        $phpUnitFrameworkMockObjectMockObject->method('columnAlias')
            ->willReturn('params');

        $phpUnitFrameworkMockObjectMockObject->method('get')
            ->with('params')
            ->willReturn($registry);

        $reflectionClass = new \ReflectionClass($phpUnitFrameworkMockObjectMockObject);
        $reflectionMethod = $reflectionClass->getMethod('loadParams');
        $reflectionMethod->setAccessible(true);

        $this->assertSame($registry, $reflectionMethod->invoke($phpUnitFrameworkMockObjectMockObject));
    }

    /**
     * @return  void
     */
    public function testLoadParamsReturnsRegistryIfTableReturnsParamsAsStringWithSpaces()
    {
        $params = '        {"bar":"foo"}     ';

        $phpUnitFrameworkMockObjectMockObject = $this->getMockBuilder(EntityWithParams::class)
            ->setMethods(['columnAlias', 'get'])
            ->getMock();

        $phpUnitFrameworkMockObjectMockObject->method('columnAlias')
            ->willReturn('params');

        $phpUnitFrameworkMockObjectMockObject->method('get')
            ->with('params')
            ->willReturn($params);

        $reflectionClass = new \ReflectionClass($phpUnitFrameworkMockObjectMockObject);
        $reflectionMethod = $reflectionClass->getMethod('loadParams');
        $reflectionMethod->setAccessible(true);

        $this->assertSame('foo', $reflectionMethod->invoke($phpUnitFrameworkMockObjectMockObject)->get('bar'));
    }

    /**
     * @return  void
     */
    public function testLoadParamsReturnsRegistryIfTableReturnsParamsAsArray()
    {
        $params = ['bar' => 'fromArray'];

        $phpUnitFrameworkMockObjectMockObject = $this->getMockBuilder(EntityWithParams::class)
            ->setMethods(['columnAlias', 'get'])
            ->getMock();

        $phpUnitFrameworkMockObjectMockObject->method('columnAlias')
            ->willReturn('params');

        $phpUnitFrameworkMockObjectMockObject->method('get')
            ->with('params')
            ->willReturn($params);

        $reflectionClass = new \ReflectionClass($phpUnitFrameworkMockObjectMockObject);
        $reflectionMethod = $reflectionClass->getMethod('loadParams');
        $reflectionMethod->setAccessible(true);

        $this->assertSame('fromArray', $reflectionMethod->invoke($phpUnitFrameworkMockObjectMockObject)->get('bar'));
    }

    /**
     * param returns correct value.
     *
     * @return  void
     */
    public function testParamReturnsCorrectValue()
    {
        $phpUnitFrameworkMockObjectMockObject = $this->getEntity(['id' => 999, 'params' => '{"foo":"var"}']);

        $this->assertSame('var', $phpUnitFrameworkMockObjectMockObject->param('foo'));
        $this->assertSame(null, $phpUnitFrameworkMockObjectMockObject->param('unknown'));
        $this->assertSame('default', $phpUnitFrameworkMockObjectMockObject->param('use-default', 'default'));
    }

    /**
     * params works with attribs column.
     *
     * @return  void
     */
    public function testParamsWorksWithAttribsColumn()
    {
        $phpUnitFrameworkMockObjectMockObject = $this->getMockBuilder(EntityWithParams::class)
            ->setMethods(['columnAlias'])
            ->getMock();

        $phpUnitFrameworkMockObjectMockObject->expects($this->once())
            ->method('columnAlias')
            ->willReturn('attribs');

        $reflectionClass = new \ReflectionClass($phpUnitFrameworkMockObjectMockObject);
        $reflectionProperty = $reflectionClass->getProperty('row');
        $reflectionProperty->setAccessible(true);

        $reflectionProperty->setValue($phpUnitFrameworkMockObjectMockObject, ['id' => 999, 'attribs' => '{"foo":"var"}']);

        $this->assertEquals(new Registry(['foo' => 'var']), $phpUnitFrameworkMockObjectMockObject->params());
    }

    /**
     * params works for unset params.
     *
     * @return  void
     */
    public function testParamsWorksForUnsetParams()
    {
        $phpUnitFrameworkMockObjectMockObject = $this->getEntity(['id' => 999, 'name' => 'Roberto Segura', 'params' => '']);

        $this->assertEquals(new Registry(), $phpUnitFrameworkMockObjectMockObject->params());
    }

    /**
     * params works with params column.
     *
     * @return  void
     */
    public function testParamsWorksWithParamsColumn()
    {
        $phpUnitFrameworkMockObjectMockObject = $this->getEntity(['id' => 999, 'params' => '{"foo":"bar"}']);

        $this->assertEquals(new Registry(['foo' => 'bar']), $phpUnitFrameworkMockObjectMockObject->params());
    }

    /**
     * saveParams throws an exception when column is not present in database row.
     *
     * @return  void
     *
     * @expectedException \InvalidArgumentException
     */
    public function testSaveParamsThrowsExceptionIfParamsColumnIsNotPresentInRow()
    {
        $phpUnitFrameworkMockObjectMockObject = $this->getMockBuilder(EntityWithParams::class)
            ->setMethods(['columnAlias'])
            ->getMock();

        $phpUnitFrameworkMockObjectMockObject->method('columnAlias')
            ->willReturn('attribs');

        $reflectionClass = new \ReflectionClass($phpUnitFrameworkMockObjectMockObject);
        $reflectionProperty = $reflectionClass->getProperty('row');
        $reflectionProperty->setAccessible(true);

        $reflectionProperty->setValue($phpUnitFrameworkMockObjectMockObject, ['id' => 999, 'params' => '{"test":"var"}']);

        $phpUnitFrameworkMockObjectMockObject->saveParams();
    }

    /**
     * saveParams stores correct value.
     *
     * @return  void
     *
     * @expectedException \RuntimeException
     */
    public function testSaveParamsThrowsExceptionIfTableSaveFails()
    {
        $phpUnitFrameworkMockObjectMockObject = $this->getMockBuilder(\JTable::class)
            ->disableOriginalConstructor()
            ->setMethods(['save', 'getError'])
            ->getMock();

        $phpUnitFrameworkMockObjectMockObject->expects($this->at(0))
            ->method('save')
            ->willReturn(false);

        $phpUnitFrameworkMockObjectMockObject->expects($this->at(1))
            ->method('getError')
            ->willReturn('En un lugar de La Mancha de cuyo nombre no quiero acordarme');

        $entity = $this->getMockBuilder(EntityWithParams::class)
            ->setMethods(['table'])
            ->getMock();

        $entity->method('table')
            ->willReturn($phpUnitFrameworkMockObjectMockObject);

        $reflectionClass = new \ReflectionClass($entity);
        $reflectionProperty = $reflectionClass->getProperty('row');
        $reflectionProperty->setAccessible(true);

        $reflectionProperty->setValue($entity, ['id' => 999, 'params' => '{"test":"var"}']);

        $entity->saveParams();
    }

    /**
     * saveParams returns true when table saves data.
     *
     * @return  void
     */
    public function testSaveParamsReturnsTrueWhenTableSavesData()
    {
        $phpUnitFrameworkMockObjectMockObject = $this->getMockBuilder(\JTable::class)
            ->disableOriginalConstructor()
            ->setMethods(['save', 'load'])
            ->getMock();

        $phpUnitFrameworkMockObjectMockObject
            ->method('load')
            ->willReturn(true);

        $phpUnitFrameworkMockObjectMockObject
            ->method('save')
            ->willReturn(true);

        $entity = $this->getMockBuilder(EntityWithParams::class)
            ->setMethods(['table'])
            ->getMock();

        $entity->method('table')
            ->willReturn($phpUnitFrameworkMockObjectMockObject);

        $reflectionClass = new \ReflectionClass($entity);
        $reflectionProperty = $reflectionClass->getProperty('id');
        $reflectionProperty->setAccessible(true);
        $reflectionProperty->setValue($entity, 999);

        $rowProperty = $reflectionClass->getProperty('row');
        $rowProperty->setAccessible(true);

        $rowProperty->setValue($entity, ['id' => 999, 'params' => '{"test":"var"}']);

        $this->assertTrue($entity->saveParams());
    }

    /**
     * setParam sets the correct param value.
     *
     * @return  void
     */
    public function testSetParamSetsCorrectParamValue()
    {
        $phpUnitFrameworkMockObjectMockObject = $this->getEntity(['id' => 999, 'params' => '{"test":"var"}']);

        $reflectionClass = new \ReflectionClass($phpUnitFrameworkMockObjectMockObject);

        $reflectionProperty = $reflectionClass->getProperty('row');
        $reflectionProperty->setAccessible(true);

        $paramsProperty = $reflectionClass->getProperty('params');
        $paramsProperty->setAccessible(true);

        $this->assertSame(null, $paramsProperty->getValue($phpUnitFrameworkMockObjectMockObject));

        $phpUnitFrameworkMockObjectMockObject->setParam('foo', 'foobar');

        $this->assertEquals(new Registry(['test' => 'var', 'foo' => 'foobar']), $phpUnitFrameworkMockObjectMockObject->params());

        $phpUnitFrameworkMockObjectMockObject->setParam('test', 'modified-var');

        $registry = new Registry(['test' => 'modified-var', 'foo' => 'foobar']);

        $this->assertEquals($registry, $phpUnitFrameworkMockObjectMockObject->params());
        $this->assertEquals($registry->toString(), $reflectionProperty->getValue($phpUnitFrameworkMockObjectMockObject)['params']);
    }

    /**
     * setParam updates row parameters.
     *
     * @return  void
     */
    public function testSetParamSetsCorrectParamValueWithCustomParamsColumn()
    {
        $phpUnitFrameworkMockObjectMockObject = $this->getMockBuilder(EntityWithParams::class)
            ->setMethods(['columnAlias'])
            ->getMock();

        $phpUnitFrameworkMockObjectMockObject->method('columnAlias')
            ->willReturn('attribs');

        $reflection = new \ReflectionClass($phpUnitFrameworkMockObjectMockObject);
        $reflectionProperty = $reflection->getProperty('row');
        $reflectionProperty->setAccessible(true);

        $reflectionProperty->setValue($phpUnitFrameworkMockObjectMockObject, ['id' => 999, 'attribs' => '{"test":"var"}']);

        $reflection = new \ReflectionClass($phpUnitFrameworkMockObjectMockObject);
        $paramsProperty = $reflection->getProperty('params');
        $paramsProperty->setAccessible(true);

        $this->assertSame(null, $paramsProperty->getValue($phpUnitFrameworkMockObjectMockObject));

        $phpUnitFrameworkMockObjectMockObject->setParam('foo', 'foobar');

        $this->assertEquals(new Registry(['test' => 'var', 'foo' => 'foobar']), $paramsProperty->getValue($phpUnitFrameworkMockObjectMockObject));

        $phpUnitFrameworkMockObjectMockObject->setParam('test', 'modified-var');

        $registry = new Registry(['test' => 'modified-var', 'foo' => 'foobar']);

        $this->assertEquals($registry, $paramsProperty->getValue($phpUnitFrameworkMockObjectMockObject));
        $this->assertEquals($registry->toString(), $reflectionProperty->getValue($phpUnitFrameworkMockObjectMockObject)['attribs']);
    }

    /**
     * setParam sets the correct param value.
     *
     * @return  void
     */
    public function testSetParamsSetsCorrectValue()
    {
        $phpUnitFrameworkMockObjectMockObject = $this->getEntity(['id' => 999, 'params' => '{"test":"var"}']);

        $reflectionClass = new \ReflectionClass($phpUnitFrameworkMockObjectMockObject);

        $reflectionProperty = $reflectionClass->getProperty('row');
        $reflectionProperty->setAccessible(true);

        $paramsProperty = $reflectionClass->getProperty('params');
        $paramsProperty->setAccessible(true);

        $this->assertSame(null, $paramsProperty->getValue($phpUnitFrameworkMockObjectMockObject));

        $registry = new Registry(['test' => 'modified-var', 'foo' => 'foobar']);
        $phpUnitFrameworkMockObjectMockObject->setParams($registry);

        $this->assertEquals($registry, $paramsProperty->getValue($phpUnitFrameworkMockObjectMockObject));
        $this->assertEquals($registry->toString(), $reflectionProperty->getValue($phpUnitFrameworkMockObjectMockObject)['params']);
    }

    /**
     * setParam updates row parameters.
     *
     * @return  void
     */
    public function testSetParamsSetsCorrectValueWithCustomParamsColumn()
    {
        $phpUnitFrameworkMockObjectMockObject = $this->getMockBuilder(EntityWithParams::class)
            ->setMethods(['columnAlias'])
            ->getMock();

        $phpUnitFrameworkMockObjectMockObject->method('columnAlias')
            ->willReturn('attribs');

        $reflection = new \ReflectionClass($phpUnitFrameworkMockObjectMockObject);
        $reflectionProperty = $reflection->getProperty('row');
        $reflectionProperty->setAccessible(true);

        $reflectionProperty->setValue($phpUnitFrameworkMockObjectMockObject, ['id' => 999, 'attribs' => '{"test":"var"}']);

        $reflection = new \ReflectionClass($phpUnitFrameworkMockObjectMockObject);
        $paramsProperty = $reflection->getProperty('params');
        $paramsProperty->setAccessible(true);

        $this->assertSame(null, $paramsProperty->getValue($phpUnitFrameworkMockObjectMockObject));

        $registry = new Registry(['test' => 'modified-var', 'foo' => 'foobar']);

        $phpUnitFrameworkMockObjectMockObject->setParams($registry);

        $this->assertEquals($registry, $paramsProperty->getValue($phpUnitFrameworkMockObjectMockObject));
        $this->assertEquals($registry->toString(), $reflectionProperty->getValue($phpUnitFrameworkMockObjectMockObject)['attribs']);
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
        $phpUnitFrameworkMockObjectMockObject = $this->getMockBuilder(EntityWithParams::class)
            ->setMethods(['columnAlias'])
            ->getMock();

        $phpUnitFrameworkMockObjectMockObject->method('columnAlias')
            ->willReturn('params');

        $phpUnitFrameworkMockObjectMockObject->bind($row);

        return $phpUnitFrameworkMockObjectMockObject;
    }
}
