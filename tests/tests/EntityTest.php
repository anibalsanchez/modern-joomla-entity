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

namespace Extly\Joomla\Entity\Tests;

use Extly\Joomla\Entity\Exception\DeleteException;
use Extly\Joomla\Entity\Exception\SaveException;
use Extly\Joomla\Entity\Tests\Stubs\Entity;
use Extly\Joomla\Entity\Tests\Stubs\EntityWithFakeSave;
use Extly\Joomla\Entity\Tests\Validation\Traits\Stubs\EntityWithValidation;
use Extly\Joomla\Entity\Validation\Exception\ValidationException;
use Joomla\CMS\Factory;
use Joomla\CMS\Table\Table;
use Joomla\Registry\Registry;

/**
 * Base entity tests.
 *
 * @since   1.1.0
 */
class EntityTest extends \TestCaseDatabase
{
    /**
     * Name of the primary key
     *
     * @const
     */
    public const PRIMARY_KEY = 'id';

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

        Factory::$session = $this->getMockSession();
        Factory::$config = $this->getMockConfig();
        Factory::$application = $this->getMockCmsApp();
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

        Entity::clearAll();
        Entity::$tableMock = null;

        parent::tearDown();
    }

    /**
     * @test
     *
     * @return void
     */
    public function deleteReturnsTrueForNoIds()
    {
        $this->assertTrue(Entity::delete([]));
        $this->assertTrue(Entity::delete(['', 0, null]));
    }

    /**
     * @test
     *
     * @return void
     */
    public function deleteRemovesSpecifiedId()
    {
        $tableMock = $this->getMockBuilder('MockedTable')
            ->disableOriginalConstructor()
            ->setMethods(['delete'])
            ->getMock();

        $tableMock->expects($this->once())
            ->method('delete')
            ->with($this->equalTo(45))
            ->willReturn(true);

        Entity::$tableMock = $tableMock;

        $this->assertTrue(Entity::delete(45));
    }

    /**
     * @test
     *
     * @return void
     */
    public function deleteRemovesSpecifiedIds()
    {
        $tableMock = $this->getMockBuilder('MockedTable')
            ->disableOriginalConstructor()
            ->setMethods(['delete'])
            ->getMock();

        $tableMock->expects($this->at(0))
            ->method('delete')
            ->with($this->equalTo(23))
            ->willReturn(true);

        $tableMock->expects($this->at(1))
            ->method('delete')
            ->with($this->equalTo(32))
            ->willReturn(true);

        Entity::$tableMock = $tableMock;

        $this->assertTrue(Entity::delete([0, '', null, ' ', 23, 32]));
    }

    /**
     * @test
     *
     * @return void
     */
    public function deleteThrowsDeleteExceptionOnError()
    {
        $tableMock = $this->getMockBuilder(Table::class)
            ->disableOriginalConstructor()
            ->setMethods(['delete', 'getError'])
            ->getMock();

        $tableMock->expects($this->at(0))
            ->method('delete')
            ->with($this->equalTo(23))
            ->willReturn(true);

        $tableMock->expects($this->at(1))
            ->method('delete')
            ->with($this->equalTo(32))
            ->willReturn(false);

        $tableMock->expects($this->once())
            ->method('getError')
            ->willReturn('Roberto broke it');

        Entity::$tableMock = $tableMock;

        $error = null;

        try {
            Entity::delete([23, 32]);
        } catch (DeleteException $deleteException) {
            $error = $deleteException->getMessage();
        }

        $this->assertContains('Roberto broke it', $error);
    }

    /**
     * Dates provider.
     *
     * @return  array
     *
     * @since   1.8
     */
    public function hasEmptyDateProvider()
    {
        return [
            // Date, expectedResult
            [null, true],
            ['1976-11-16', false],
            ['', true],
            ['1976-11-16 00:00:00', false],
            [' ', true],
            ['1989-5-20 12:00:01', false],
            ['0', true],
            ['0000-00-00', true],
            ['2000-00-00', false],
            ['0000-00-00 00:00:00', true],
            ['1971-01-01', true],
            ['1971-01-01 00:00:00', true],
            ['1971-01-01 00:00:00 ', true],
        ];
    }

    /**
     * @test
     *
     * @dataProvider  hasEmptyDateProvider
     *
     * @param   string   $date            Date to test
     * @param   bool  $expectedResult  Expected result for hasEmptyDate()
     *
     * @return void
     *
     * @since   1.8
     */
    public function hasEmptyDateReturnExpectedValue($date, bool $expectedResult)
    {
        $phpUnitFrameworkMockObjectMockObject = $this->getEntity();
        $phpUnitFrameworkMockObjectMockObject->assign('test-date', $date);

        $this->assertSame($expectedResult, $phpUnitFrameworkMockObjectMockObject->hasEmptyDate('test-date'));
    }

    /**
     * @test
     *
     * @return void
     */
    public function loadFromDataReturnsUnloadedEntityOnLoadFailure()
    {
        $tableMock = $this->getMockBuilder('MockedTable')
            ->disableOriginalConstructor()
            ->setMethods(['load'])
            ->getMock();

        $tableMock->expects($this->once())
            ->method('load')
            ->willReturn(false);

        Entity::$tableMock = $tableMock;

        $entity = Entity::loadFromData([]);

        $this->assertInstanceOf(Entity::class, $entity);
        $this->assertFalse($entity->isLoaded());

        $entity2 = Entity::loadFromData(['id' => 23]);

        $this->assertInstanceOf(Entity::class, $entity2);
        $this->assertFalse($entity2->isLoaded());
        $this->assertNotSame($entity, $entity2);
    }

    /**
     * @test
     *
     * @return void
     */
    public function loadFromDataReturnsLoadedEntity()
    {
        $data = [
            'name' => 'Grijander',
        ];

        $tableMock = $this->getMockBuilder('MockedTable')
            ->disableOriginalConstructor()
            ->setMethods(['getProperties', 'load'])
            ->getMock();

        $tableMock->expects($this->once())
            ->method('load')
            ->with($this->equalTo($data))
            ->willReturn(true);

        $tableMock->expects($this->once())
            ->method('getProperties')
            ->willReturn(['id' => 999, 'name' => 'Grijander']);

        Entity::$tableMock = $tableMock;

        $entity = Entity::loadFromData($data);

        $this->assertTrue($entity->isLoaded());
        $this->assertSame(999, $entity->id());
    }

    /**
     * Test magic __get method calls get.
     *
     * @return  void
     */
    public function testMagicGetReturnsGet()
    {
        $entity = $this->getMockBuilder(Entity::class)
            ->disableOriginalConstructor()
            ->setMethods(['get'])
            ->getMock();

        $entity->expects($this->once())
            ->method('get')
            ->with($this->equalTo('property'))
            ->willReturn('fake value');

        $this->assertSame('fake value', $entity->property);
    }

    /**
     * Assign sets the correct value.
     *
     * @return  void
     */
    public function testAsssignSetsCorrectValue()
    {
        $entity = new Entity();

        $reflectionClass = new \ReflectionClass($entity);
        $reflectionProperty = $reflectionClass->getProperty('row');
        $reflectionProperty->setAccessible(true);

        $this->assertSame(null, $reflectionProperty->getValue($entity));

        $entity->assign('name', 'Sample name');

        $this->assertSame(['name' => 'Sample name'], $reflectionProperty->getValue($entity));
    }

    /**
     * Assign sets correct id.
     *
     * @return  void
     */
    public function testAssignSetsCorrectId()
    {
        $phpUnitFrameworkMockObjectMockObject = $this->getEntity([static::PRIMARY_KEY => '666']);

        $reflectionClass = new \ReflectionClass($phpUnitFrameworkMockObjectMockObject);
        $reflectionProperty = $reflectionClass->getProperty('id');
        $reflectionProperty->setAccessible(true);

        $this->assertSame(666, $reflectionProperty->getValue($phpUnitFrameworkMockObjectMockObject));

        $phpUnitFrameworkMockObjectMockObject->assign(static::PRIMARY_KEY, '999');

        $this->assertSame(999, $reflectionProperty->getValue($phpUnitFrameworkMockObjectMockObject));
    }

    /**
     * bind sets correct object data.
     *
     * @return  void
     */
    public function testBindSetsCorrectData()
    {
        $phpUnitFrameworkMockObjectMockObject = $this->getEntity();

        $reflectionClass = new \ReflectionClass($phpUnitFrameworkMockObjectMockObject);
        $reflectionProperty = $reflectionClass->getProperty('row');
        $reflectionProperty->setAccessible(true);

        $this->assertSame([], $reflectionProperty->getValue($phpUnitFrameworkMockObjectMockObject));

        $data = [
            static::PRIMARY_KEY => 999,
            'name' => 'Roberto Segura',
        ];

        $phpUnitFrameworkMockObjectMockObject->bind($data);

        $this->assertSame($data, $reflectionProperty->getValue($phpUnitFrameworkMockObjectMockObject));

        $data = (object) [
            static::PRIMARY_KEY => 999,
            'name' => 'Sample Name',
        ];

        $phpUnitFrameworkMockObjectMockObject->bind($data);

        $this->assertSame((array) $data, $reflectionProperty->getValue($phpUnitFrameworkMockObjectMockObject));
    }

    /**
     * bind throws exception with wrong data.
     *
     * @return  void
     */
    public function testBindThrowsExceptionWithWrongData()
    {
        $entity = new Entity();

        $exception = false;

        try {
            $entity->bind('test');
        } catch (\InvalidArgumentException $invalidArgumentException) {
            $exception = true;
        }

        $this->assertTrue($exception);

        $exception = false;

        try {
            $entity->bind(111);
        } catch (\InvalidArgumentException $invalidArgumentException) {
            $exception = true;
        }

        $this->assertTrue($exception);

        $exception = false;

        try {
            $entity->bind(null);
        } catch (\InvalidArgumentException $invalidArgumentException) {
            $exception = true;
        }

        $this->assertTrue($exception);

        $exception = false;

        try {
            $entity->bind(true);
        } catch (\InvalidArgumentException $invalidArgumentException) {
            $exception = true;
        }

        $this->assertTrue($exception);
    }

    /**
     * bind sets correct id.
     *
     * @return  void
     */
    public function testBindSetsCorrectId()
    {
        $phpUnitFrameworkMockObjectMockObject = $this->getEntity();

        $reflectionClass = new \ReflectionClass($phpUnitFrameworkMockObjectMockObject);
        $reflectionProperty = $reflectionClass->getProperty('id');
        $reflectionProperty->setAccessible(true);

        $this->assertSame(0, $reflectionProperty->getValue($phpUnitFrameworkMockObjectMockObject));

        $data = [
            static::PRIMARY_KEY => '999',
            'name' => 'Roberto Segura',
        ];

        $phpUnitFrameworkMockObjectMockObject->bind($data);

        $this->assertSame(999, $reflectionProperty->getValue($phpUnitFrameworkMockObjectMockObject));
    }

    /**
     * columnAlias returns sent column if not alias is set.
     *
     * @return  void
     */
    public function testColumnAliasReturnsSentColumnIfNoAliasIsSet()
    {
        $tableMock = $this->getMockBuilder('MockedTable')
            ->disableOriginalConstructor()
            ->setMethods(['getColumnAlias'])
            ->getMock();

        $tableMock->expects($this->once())
            ->method('getColumnAlias')
            ->willReturn('published');

        $entity = $this->getMockBuilder(Entity::class)
            ->disableOriginalConstructor()
            ->setMethods(['table'])
            ->getMock();

        $entity
            ->method('table')
            ->willReturn($tableMock);

        $this->assertSame('published', $entity->columnAlias('published'));
    }

    /**
     * columnAlias returns entity alias if set.
     *
     * @return  void
     */
    public function testColumnAliasReturnsEntityAliasIfSet()
    {
        $entity = $this->getMockBuilder(Entity::class)
            ->disableOriginalConstructor()
            ->setMethods(['columnAliases'])
            ->getMock();

        $entity->expects($this->once())
            ->method('columnAliases')
            ->willReturn(['published' => 'entityValue']);

        $this->assertSame('entityValue', $entity->columnAlias('published'));
    }

    /**
     * columnAliases returns array.
     *
     * @return  void
     */
    public function testColumnAliasesReturnsAnArray()
    {
        $entity = new Entity();

        $this->assertSame(true, is_array($entity->columnAliases()));
    }

    /**
     * Constructor.
     *
     * @return  void
     */
    public function testConstructor()
    {
        $entity = new Entity();
        $entity2 = new Entity('999');
        $entity3 = new Entity(9999);
    }

    /**
     * Constructor sets correct id.
     *
     * @return  void
     */
    public function testConstructorSetsCorrectId()
    {
        $entity = new Entity();

        $reflectionClass = new \ReflectionClass($entity);
        $reflectionProperty = $reflectionClass->getProperty('id');
        $reflectionProperty->setAccessible(true);

        $this->assertSame(0, $reflectionProperty->getValue($entity));

        $entity = new Entity('999');

        $this->assertSame(999, $reflectionProperty->getValue($entity));
    }

    /**
     * create returns saved instance.
     *
     * @return  void
     */
    public function testCreateReturnsSavedInstance()
    {
        $data = [
            self::PRIMARY_KEY => 34,
            'name'            => 'My name',
            'age'             => 25,
        ];

        $entityWithFakeSave = EntityWithFakeSave::create($data);

        $this->assertTrue($entityWithFakeSave->saved);
        $this->assertNotSame($data, $entityWithFakeSave->savedData);

        unset($data[self::PRIMARY_KEY]);

        $this->assertSame($data, $entityWithFakeSave->savedData);
    }

    /**
     * date returns correct value.
     *
     * @return  void
     */
    public function testDateReturnsCorrectValue()
    {
        $user = $this->getMockBuilder('UserMock')
            ->setMethods(['getTimezone'])
            ->getMock();

        $user->expects($this->once())
            ->method('getTimezone')
            ->willReturn(new \DateTimeZone('GMT'));

        $entity = $this->getMockBuilder(Entity::class)
            ->setMethods(['juser'])
            ->getMock();

        $entity
            ->method('juser')
            ->willReturn($user);

        $reflectionClass = new \ReflectionClass($entity);

        $reflectionProperty = $reflectionClass->getProperty('row');
        $reflectionProperty->setAccessible(true);

        $data = [
            'id' => 999,
            'date' => '1976-11-16 16:00:00',
        ];

        $reflectionProperty->setValue($entity, $data);

        $this->assertInstanceOf(\Joomla\CMS\Date\Date::class, $entity->date('date', true));

        Factory::$config = new Registry(['offset' => '+0600']);

        $this->assertInstanceOf(\Joomla\CMS\Date\Date::class, $entity->date('date', false));
        $this->assertInstanceOf(\Joomla\CMS\Date\Date::class, $entity->date('date', null));
        $this->assertInstanceOf(\Joomla\CMS\Date\Date::class, $entity->date('date', 'GMT'));
    }

    /**
     * date throws exception when date property is empty.
     *
     * @return  void
     *
     * @expectedException  \RuntimeException
     */
    public function testDateThrowsExceptionWhenDatePropertyIsEmpty()
    {
        $entity = new Entity();

        $reflectionClass = new \ReflectionClass($entity);
        $reflectionProperty = $reflectionClass->getProperty('row');
        $reflectionProperty->setAccessible(true);

        $data = ['id' => 999, 'date' => null];

        $reflectionProperty->setValue($entity, $data);

        $entity->date('date');
    }

    /**
     * fetch preserves previously assigned data.
     *
     * @return  void
     */
    public function testFetchPreservesPreviouslyAssignedData()
    {
        $dataFromDb = [
            static::PRIMARY_KEY => 999,
            'name' => 'Sample name',
        ];

        $assignedData = [
            'foo' => 'bar',
            'name' => 'Modified name',
        ];

        $phpUnitFrameworkMockObjectMockObject = $this->getLoadableEntityMock(999, $dataFromDb);

        $reflectionClass = new \ReflectionClass($phpUnitFrameworkMockObjectMockObject);

        $reflectionProperty = $reflectionClass->getProperty('id');
        $reflectionProperty->setAccessible(true);
        $reflectionProperty->setValue($phpUnitFrameworkMockObjectMockObject, 43);

        $rowProperty = $reflectionClass->getProperty('row');
        $rowProperty->setAccessible(true);
        $rowProperty->setValue($phpUnitFrameworkMockObjectMockObject, $assignedData);

        $phpUnitFrameworkMockObjectMockObject->fetch();

        $this->assertEquals(array_merge($dataFromDb, $assignedData), $rowProperty->getValue($phpUnitFrameworkMockObjectMockObject));
        $this->assertSame($assignedData['name'], $phpUnitFrameworkMockObjectMockObject->get('name'));
    }

    /**
     * load loads correct data.
     *
     * @return  void
     */
    public function testLoadLoadsCorrectData()
    {
        $reflection = new \ReflectionClass(Entity::class);
        $reflectionProperty = $reflection->getProperty('instances');
        $reflectionProperty->setAccessible(true);

        $row = [
            static::PRIMARY_KEY => 999,
            'name' => 'Sample name',
        ];

        $instances = [
            Entity::class => [
                999 => $this->getLoadableEntityMock(999, $row),
            ],
        ];

        $reflectionProperty->setValue(Entity::class, $instances);

        $entity = Entity::load(999);

        $reflection = new \ReflectionClass($entity);
        $rowProperty = $reflection->getProperty('row');
        $rowProperty->setAccessible(true);

        $this->assertSame($row, $rowProperty->getValue($entity));
    }

    /**
     * fetch throws InvalidEntityData exception for empty data.
     *
     * @return  void
     *
     * @expectedException \Extly\Joomla\Entity\Exception\InvalidEntityData
     */
    public function testFetchRowThrowsExceptionForEmptyData()
    {
        $phpUnitFrameworkMockObjectMockObject = $this->getLoadableEntityMock(999, []);

        $reflectionClass = new \ReflectionClass(Entity::class);

        $reflectionMethod = $reflectionClass->getMethod('fetchRow');
        $reflectionMethod->setAccessible(true);

        $reflectionMethod->invoke($phpUnitFrameworkMockObjectMockObject);
    }

    /**
     * fetchRow throws InvalidEntityData exception for missing primary key.
     *
     * @return  void
     *
     * @expectedException \Extly\Joomla\Entity\Exception\InvalidEntityData
     */
    public function testFetchRowThrowsExceptionForMissingPrimaryKey()
    {
        $entity = new Entity();

        $reflectionClass = new \ReflectionClass($entity);
        $reflectionMethod = $reflectionClass->getMethod('fetchRow');
        $reflectionMethod->setAccessible(true);

        $reflectionMethod->invoke($entity);
    }

    /**
     * fetchRow throws exception when table load fails.
     *
     * @return  void
     *
     * @expectedException \Extly\Joomla\Entity\Exception\LoadEntityDataError
     */
    public function testFetchRowThrowsExceptionWhenTableLoadFails()
    {
        $tableMock = $this->getMockBuilder('MockedTable')
            ->disableOriginalConstructor()
            ->setMethods(['load', 'getError'])
            ->getMock();

        $tableMock->expects($this->at(0))
            ->method('load')
            ->willReturn(false);

        $tableMock->expects($this->at(1))
            ->method('getError')
            ->willReturn('En un lugar de la mancha de cuyo nombre no quiero acordarme');

        $entity = $this->getMockBuilder(Entity::class)
            ->disableOriginalConstructor()
            ->setMethods(['table'])
            ->getMock();

        $entity
            ->method('table')
            ->willReturn($tableMock);

        $reflectionClass = new \ReflectionClass($entity);

        $reflectionProperty = $reflectionClass->getProperty('id');
        $reflectionProperty->setAccessible(true);
        $reflectionProperty->setValue($entity, 999);

        $reflectionMethod = $reflectionClass->getMethod('fetchRow');
        $reflectionMethod->setAccessible(true);

        $reflectionMethod->invoke($entity);
    }

    /**
     * fetchRow throws exception when primary key is not in the loaded data.
     *
     * @return  void
     *
     * @expectedException \Extly\Joomla\Entity\Exception\InvalidEntityData
     */
    public function testFetchRowThrowsExceptionWhenPrimaryKeyIsNotInTheLoadedData()
    {
        $phpUnitFrameworkMockObjectMockObject = $this->getLoadableEntityMock(999, ['title' => 'Sample title']);

        $reflectionClass = new \ReflectionClass(Entity::class);

        $reflectionMethod = $reflectionClass->getMethod('fetchRow');
        $reflectionMethod->setAccessible(true);

        $reflectionMethod->invoke($phpUnitFrameworkMockObjectMockObject);
    }

    /**
     * fromData returns entity with data binded.
     *
     * @return  void
     */
    public function testFromDataReturnsEntityWithDataBinded()
    {
        $data = [
            self::PRIMARY_KEY => 333,
            'name' => 'Hello world',
        ];

        $entity = Entity::fromData($data);

        $this->assertInstanceOf(Entity::class, $entity);
        $this->assertSame($data, $entity->all());
    }

    /**
     * load sets the correct id.
     *
     * @return  void
     */
    public function testLoadSetsCorrectId()
    {
        $reflection = new \ReflectionClass(Entity::class);
        $reflectionProperty = $reflection->getProperty('instances');
        $reflectionProperty->setAccessible(true);

        $instances = [
            Entity::class => [
                999 => $this->getLoadableEntityMock(999, [static::PRIMARY_KEY => 999]),
            ],
        ];

        $reflectionProperty->setValue(Entity::class, $instances);

        $entity = Entity::load(999);

        $entityReflection = new \ReflectionClass($entity);
        $idProperty = $reflection->getProperty('id');
        $idProperty->setAccessible(true);

        $this->assertSame(999, $idProperty->getValue($entity));
    }

    /**
     * id returns the correct identifier.
     *
     * @return  void
     */
    public function testGetIdReturnsCorrectIdentifier()
    {
        $entity = new Entity();

        $reflectionClass = new \ReflectionClass($entity);
        $reflectionProperty = $reflectionClass->getProperty('id');
        $reflectionProperty->setAccessible(true);

        $this->assertSame(0, $entity->id());

        $reflectionProperty->setValue($entity, 999);

        $this->assertSame(999, $entity->id());
    }

    /**
     * get returns correct value.
     *
     * @return  void
     */
    public function testGetReturnsCorrectValue()
    {
        $row = [
            static::PRIMARY_KEY => 999,
            'name' => 'Roberto Segura',
            'age' => null,
        ];

        $phpUnitFrameworkMockObjectMockObject = $this->getEntity($row);

        $this->assertSame(999, $phpUnitFrameworkMockObjectMockObject->get(static::PRIMARY_KEY));
        $this->assertSame('Roberto Segura', $phpUnitFrameworkMockObjectMockObject->get('name'));
        $this->assertSame('Roberto Segura', $phpUnitFrameworkMockObjectMockObject->get('name', 'Isidro Baquero'));
        $this->assertSame(null, $phpUnitFrameworkMockObjectMockObject->get('age'));
        $this->assertSame(33, $phpUnitFrameworkMockObjectMockObject->get('age', 33));
    }

    /**
     * get throws exception for missing property.
     *
     * @return  void
     *
     * @expectedException  \InvalidArgumentException
     */
    public function testGetThrowsExceptionForMissingProperty()
    {
        $entity = new Entity(999);

        $reflectionClass = new \ReflectionClass($entity);
        $reflectionProperty = $reflectionClass->getProperty('row');
        $reflectionProperty->setAccessible(true);

        $row = [
            static::PRIMARY_KEY => 999,
            'name' => 'Roberto Segura',
        ];

        $reflectionProperty->setValue($entity, $row);

        $entity->get('age');
    }

    /**
     * getRow forces fetchRow.
     *
     * @return  void
     */
    public function testGetAllForcesFetchRow()
    {
        $row = [
            static::PRIMARY_KEY => 999,
            'name' => 'Roberto Segura',
        ];

        $entity = $this->getMockBuilder(Entity::class)
            ->setMethods(['fetchRow'])
            ->getMock();

        $entity->expects($this->once())
            ->method('fetchRow')
            ->willReturn($row);

        $reflectionClass = new \ReflectionClass($entity);
        $reflectionProperty = $reflectionClass->getProperty('id');
        $reflectionProperty->setAccessible(true);

        $reflectionProperty->setValue($entity, 999);

        $this->assertSame($row, $entity->all());
    }

    /**
     * has returns correct value.
     *
     * @return  void
     */
    public function testHasReturnsCorrectValue()
    {
        $entity = $this->getEntity([static::PRIMARY_KEY => 999]);

        $this->assertTrue($entity->has(static::PRIMARY_KEY));
        $this->assertFalse($entity->has('name'));
        $this->assertFalse($entity->has('age'));

        $entity = $this->getEntity([static::PRIMARY_KEY => 999, 'name' => 'Roberto Segura']);

        $this->assertTrue($entity->has(static::PRIMARY_KEY));
        $this->assertTrue($entity->has('name'));
        $this->assertFalse($entity->has('age'));
    }

    /**
     * hasEmpty returns correct value.
     *
     * @return  void
     */
    public function testHasEmptyReturnsCorrectValue()
    {
        $phpUnitFrameworkMockObjectMockObject = $this->getEntity(
            [
                static::PRIMARY_KEY => 999,
                'name' => 'Roberto Segura',
                'empty_int' => 0,
                'empty_array' => [],
                'empty_null' => null,
                'empty_false' => false,
                'empty_string' => '',
                'not_empty_int' => 1,
                'not_empty_array' => [0],
                'not_empty_boolean' => true,
                'not_empty_float' => 0.123,
            ]
        );

        $this->assertFalse($phpUnitFrameworkMockObjectMockObject->hasEmpty('name'));
        $this->assertFalse($phpUnitFrameworkMockObjectMockObject->hasEmpty('unexistent'));
        $this->assertTrue($phpUnitFrameworkMockObjectMockObject->hasEmpty('empty_int'));
        $this->assertFalse($phpUnitFrameworkMockObjectMockObject->hasEmpty('not_empty_int'));
        $this->assertTrue($phpUnitFrameworkMockObjectMockObject->hasEmpty('empty_array'));
        $this->assertFalse($phpUnitFrameworkMockObjectMockObject->hasEmpty('not_empty_array'));
        $this->assertTrue($phpUnitFrameworkMockObjectMockObject->hasEmpty('empty_null'));
        $this->assertFalse($phpUnitFrameworkMockObjectMockObject->hasEmpty('not_empty_boolean'));
        $this->assertTrue($phpUnitFrameworkMockObjectMockObject->hasEmpty('empty_false'));
        $this->assertFalse($phpUnitFrameworkMockObjectMockObject->hasEmpty('not_empty_float'));
        $this->assertTrue($phpUnitFrameworkMockObjectMockObject->hasEmpty('empty_string'));
    }

    /**
     * hasNotEmpty returns correct value.
     *
     * @return  void
     */
    public function testHasNotEmptyReturnsCorrectValue()
    {
        $phpUnitFrameworkMockObjectMockObject = $this->getEntity(
            [
                static::PRIMARY_KEY => 999,
                'name' => 'Roberto Segura',
                'empty_int' => 0,
                'empty_array' => [],
                'empty_null' => null,
                'empty_false' => false,
                'empty_string' => '',
                'not_empty_int' => 1,
                'not_empty_array' => [0],
                'not_empty_boolean' => true,
                'not_empty_float' => 0.123,
            ]
        );

        $this->assertTrue($phpUnitFrameworkMockObjectMockObject->hasNotEmpty('name'));
        $this->assertFalse($phpUnitFrameworkMockObjectMockObject->hasNotEmpty('unexistent'));
        $this->assertFalse($phpUnitFrameworkMockObjectMockObject->hasNotEmpty('empty_int'));
        $this->assertTrue($phpUnitFrameworkMockObjectMockObject->hasNotEmpty('not_empty_int'));
        $this->assertFalse($phpUnitFrameworkMockObjectMockObject->hasNotEmpty('empty_array'));
        $this->assertTrue($phpUnitFrameworkMockObjectMockObject->hasNotEmpty('not_empty_array'));
        $this->assertFalse($phpUnitFrameworkMockObjectMockObject->hasNotEmpty('empty_null'));
        $this->assertTrue($phpUnitFrameworkMockObjectMockObject->hasNotEmpty('not_empty_boolean'));
        $this->assertFalse($phpUnitFrameworkMockObjectMockObject->hasNotEmpty('empty_false'));
        $this->assertTrue($phpUnitFrameworkMockObjectMockObject->hasNotEmpty('not_empty_float'));
        $this->assertFalse($phpUnitFrameworkMockObjectMockObject->hasNotEmpty('empty_string'));
    }

    /**
     * isLoaded returns correct value.
     *
     * @return  void
     */
    public function testIsLoadedReturnsCorrectValue()
    {
        $entity = new Entity();

        $this->assertFalse($entity->isLoaded());

        $reflection = new \ReflectionClass($entity);
        $reflectionProperty = $reflection->getProperty('id');
        $reflectionProperty->setAccessible(true);

        $reflectionProperty->setValue($entity, 999);

        $this->assertFalse($entity->isLoaded());

        $reflection = new \ReflectionClass($entity);
        $rowProperty = $reflection->getProperty('row');
        $rowProperty->setAccessible(true);

        $rowProperty->setValue($entity, []);

        $this->assertFalse($entity->isLoaded());

        $rowProperty->setValue($entity, [static::PRIMARY_KEY => 999, 'name' => 'Roberto Segura']);

        $this->assertTrue($entity->isLoaded());
    }

    /**
     * json returns correct data.
     *
     * @return  void
     */
    public function testJsonReturnsCorrectData()
    {
        $entity = $this->getEntity([static::PRIMARY_KEY => 999, 'json_column' => '']);

        $this->assertEquals([], $entity->json('json_column'));

        $entity = $this->getEntity([static::PRIMARY_KEY => 999, 'json_column' => '{"foo":""}']);

        $this->assertEquals([], $entity->json('json_column'));

        $entity = $this->getEntity([static::PRIMARY_KEY => 999, 'json_column' => '{"foo":"0"}']);

        $this->assertEquals(['foo' => '0'], $entity->json('json_column'));

        $entity = $this->getEntity([static::PRIMARY_KEY => 999, 'json_column' => '{"foo":"bar"}']);

        $this->assertEquals(['foo' => 'bar'], $entity->json('json_column'));
    }

    /**
     * name returns correct value.
     *
     * @return  void
     */
    public function testNameReturnsCorrectValue()
    {
        $entity = new Entity();

        $this->assertSame('entity', $entity->name());

        require_once __DIR__.'/Stubs/TestsEntityEntity.php';

        $entity = new \TestsEntityEntity();

        $this->assertSame('entity', $entity->name());
    }

    /**
     * registry returns correct data.
     *
     * @return  void
     */
    public function testRegistryReturnsCorrectData()
    {
        $entity = new Entity();

        $reflectionClass = new \ReflectionClass($entity);
        $reflectionProperty = $reflectionClass->getProperty('row');
        $reflectionProperty->setAccessible(true);

        $data = ['id' => 999, 'registry' => '{"foo":"bar"}'];

        $reflectionProperty->setValue($entity, $data);

        $this->assertEquals(new Registry('{"foo":"bar"}'), $entity->registry('registry'));
    }

    /**
     * save returns saved instance.
     *
     * @return  void
     */
    public function testSaveReturnsSavedInstance()
    {
        $data = [
            self::PRIMARY_KEY => 999,
            'name'            => 'Anibal Sánchez',
        ];

        $tableMock = $this->getMockBuilder(\Joomla\CMS\Table\Table::class)
            ->disableOriginalConstructor()
            ->setMethods(['save'])
            ->getMock();

        $tableMock->expects($this->at(0))
            ->method('save')
            ->willReturn(true);

        foreach ($data as $property => $value) {
            $tableMock->{$property} = $value;
        }

        $entity = $this->getMockBuilder(Entity::class)
            ->setMethods(['table'])
            ->getMock();

        $entity
            ->method('table')
            ->willReturn($tableMock);

        $reflectionClass = new \ReflectionClass($entity);
        $reflectionProperty = $reflectionClass->getProperty('row');
        $reflectionProperty->setAccessible(true);
        $reflectionProperty->setValue($entity, $data);

        $newEntity = $entity->save();

        $this->assertInstanceOf(get_class($entity), $newEntity);

        foreach (array_keys($data) as $property) {
            $this->assertSame($data[$property], $newEntity->{$property});
        }
    }

    /**
     * save throws RuntimeException when errors happen.
     *
     * @return  void
     *
     * @expectedException \Extly\Joomla\Entity\Exception\SaveException
     */
    public function testSaveThrowsExceptionWhenTableErrorsHappen()
    {
        $tableMock = $this->getMockBuilder(\Joomla\CMS\Table\Table::class)
            ->disableOriginalConstructor()
            ->setMethods(['save', 'getError'])
            ->getMock();

        $tableMock->expects($this->at(0))
            ->method('save')
            ->willReturn(false);

        $tableMock->expects($this->at(1))
            ->method('getError')
            ->willReturn('En un lugar de La Mancha de cuyo nombre no quiero acordarme');

        $mock = $this->getMockBuilder(Entity::class)
            ->setMethods(['table'])
            ->getMock();

        $mock
            ->method('table')
            ->willReturn($tableMock);

        $mock->save();
    }

    /**
     * save throws exception when validable entities throw validation exceptions.
     *
     * @return  void
     */
    public function testSaveThrowsExceptionWhenValidableEntitiesThrowValidationExceptions()
    {
        $entity = $this->getMockBuilder(EntityWithValidation::class)
            ->disableOriginalConstructor()
            ->setMethods(['validate'])
            ->getMock();

        $entity->expects($this->once())
            ->method('validate')
            ->will($this->throwException(new ValidationException('Validation error happened')));

        try {
            $entity->save();
        } catch (SaveException $saveException) {
        }

        $this->assertInstanceOf(SaveException::class, $saveException);
        $this->assertTrue(strlen($saveException->getMessage()) > 0);
    }

    /**
     * showDate returns correct value.
     *
     * @return  void
     */
    public function testShowDateReturnsCorrectValue()
    {
        $user = $this->getMockBuilder('UserMock')
            ->setMethods(['getTimezone'])
            ->getMock();

        $user->expects($this->exactly(2))
            ->method('getTimezone')
            ->willReturn(new \DateTimeZone('GMT'));

        $entity = $this->getMockBuilder(Entity::class)
            ->setMethods(['juser'])
            ->getMock();

        $entity
            ->method('juser')
            ->willReturn($user);

        $reflectionClass = new \ReflectionClass($entity);
        $reflectionProperty = $reflectionClass->getProperty('row');
        $reflectionProperty->setAccessible(true);

        $data = ['id' => 999, 'date' => '1976-11-16 16:05:00'];

        $reflectionProperty->setValue($entity, $data);

        $this->assertSame('Tuesday, 16 November 1976', $entity->showDate('date'));
        $this->assertSame('1976-11-16 16:05:00', $entity->showDate('date', 'DATE_FORMAT_FILTER_DATETIME'));
    }

    /**
     * Dates provider.
     *
     * @return  array
     *
     * @since   1.8
     */
    public function showNotEmptyDateProvider()
    {
        return [
            // Date, expectedResult
            [null, 'DATE_FORMAT_LC1', ''],
            ['1976-11-16', 'DATE_FORMAT_LC1', 'Tuesday, 16 November 1976'],
            ['1989-5-20 12:00:01', 'd-m-Y', '20-05-1989'],
        ];
    }

    /**
     * @test
     *
     * @dataProvider  showNotEmptyDateProvider
     *
     * @param   string  $date      Date to test
     * @param   string  $format    Desired date format
     * @param   string  $expected  Expected formated date
     *
     * @return void
     *
     * @since   1.8
     */
    public function showNotEmptyDateReturnsExpectedValue($date, $format, $expected)
    {
        $user = $this->getMockBuilder('UserMock')
            ->setMethods(['getTimezone'])
            ->getMock();

        $user->method('getTimezone')
            ->willReturn(new \DateTimeZone('GMT'));

        $entity = $this->getMockBuilder(Entity::class)
            ->setMethods(['juser'])
            ->getMock();

        $entity
            ->method('juser')
            ->willReturn($user);

        $entity->assign('test-date', $date);

        $this->assertSame($expected, $entity->showNotEmptyDate('test-date', $format));
    }

    /**
     * @test
     *
     * @return void
     */
    public function tableReturnsExpectedTable()
    {
        $customDb = clone Factory::getDbo();

        $this->assertNotSame($customDb, Factory::getDbo());

        $entity = new Entity();

        $config = [
            'dbo' => $customDb,
        ];

        $table = $entity->table('Content', 'JTable', $config);

        $reflectionClass = new \ReflectionClass($table);
        $reflectionProperty = $reflectionClass->getProperty('_db');
        $reflectionProperty->setAccessible(true);

        $this->assertInstanceOf(Table::class, $table);
        $this->assertSame($customDb, $reflectionProperty->getValue($table));
    }

    /**
     * unassign unsets row property.
     *
     * @return  void
     */
    public function testUnassignUnsetsRowProperty()
    {
        $entity = new Entity();

        $reflectionClass = new \ReflectionClass($entity);
        $reflectionProperty = $reflectionClass->getProperty('row');
        $reflectionProperty->setAccessible(true);

        $row = [
            static::PRIMARY_KEY => 999,
            'name' => 'Isidro Baquero',
        ];

        $reflectionProperty->setValue($entity, $row);

        $this->assertSame($row, $reflectionProperty->getValue($entity));

        $entity->unassign(static::PRIMARY_KEY);

        $this->assertSame(['name' => 'Isidro Baquero'], $reflectionProperty->getValue($entity));

        $entity->unassign('name');

        $this->assertSame([], $reflectionProperty->getValue($entity));
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
        $entity = $this->getMockBuilder(Entity::class)
            ->setMethods(['primaryKey'])
            ->getMock();

        $entity->method('primaryKey')
            ->willReturn(static::PRIMARY_KEY);

        $entity->bind($row);

        return $entity;
    }

    /**
     * Get an entity that simulates data loading.
     *
     * @param   int  $id    Identifier to assign
     * @param   array    $data  Expected data loaded
     *
     * @return  PHPUnit_Framework_MockObject_MockObject
     */
    private function getLoadableEntityMock($id = null, array $data = [])
    {
        $tableMock = $this->getMockBuilder('MockedTable')
            ->disableOriginalConstructor()
            ->setMethods(['load', 'getProperties'])
            ->getMock();

        $tableMock->expects($this->at(0))
            ->method('load')
            ->willReturn(true);

        $tableMock->expects($this->at(1))
            ->method('getProperties')
            ->willReturn($data);

        $mock = $this->getMockBuilder(Entity::class)
            ->disableOriginalConstructor()
            ->setMethods(['table', 'primaryKey'])
            ->getMock();

        $mock
            ->method('table')
            ->willReturn($tableMock);

        $mock->method('primaryKey')
            ->willReturn(static::PRIMARY_KEY);

        if ($id) {
            $reflectionClass = new \ReflectionClass($mock);
            $idProperty = $reflectionClass->getProperty('id');
            $idProperty->setAccessible(true);
            $idProperty->setValue($mock, $id);
        }

        return $mock;
    }
}
