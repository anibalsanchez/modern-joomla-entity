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

namespace Extly\Joomla\Entity\Tests\Fields\Traits;

use Extly\Joomla\Entity\Collection;
use Extly\Joomla\Entity\Core\Extension\Component;
use Extly\Joomla\Entity\Fields\Field;
use Extly\Joomla\Entity\Tests\Fields\Traits\Stubs\EntityWithFields;

/**
 * HasTags trait tests.
 *
 * @since   1.1.0
 */
class HasFieldsTest extends \PHPUnit\Framework\TestCase
{
    /**
     * Data provider for tests that required fields data.
     *
     * @return  array
     */
    public function fieldProvider()
    {
        return [
            [
                [
                    [
                        'id'       => 123,
                        'context'  => 'com_content.article',
                        'title'    => 'Field test',
                        'name'     => 'field-test',
                        'state'    => 1,
                        'required' => 1,
                    ],
                    [
                        'id'       => 124,
                        'context'  => 'com_content.article',
                        'title'    => 'Another field test',
                        'name'     => 'another-field-test',
                        'state'    => 0,
                        'required' => 1,
                    ],
                    [
                        'id'       => 164,
                        'context'  => 'com_content.article',
                        'title'    => 'Yet another field test',
                        'name'     => 'yet-another-field-test',
                        'state'    => 0,
                        'required' => 0,
                    ],
                ],
            ],
        ];
    }

    /**
     * field returns correct field.
     *
     * @return  void
     */
    public function testFieldReturnsCorrectField()
    {
        $collection = new Collection([Field::find(999), Field::find(1000)]);

        $phpUnitFrameworkMockObjectMockObject = $this->getMockBuilder(EntityWithFields::class)
            ->setMethods(['fields'])
            ->getMock();

        $phpUnitFrameworkMockObjectMockObject
            ->method('fields')
            ->willReturn($collection);

        $this->assertInstanceOf(Field::class, $phpUnitFrameworkMockObjectMockObject->field(1000));
        $this->assertInstanceOf(Field::class, $phpUnitFrameworkMockObjectMockObject->field(999));
    }

    /**
     * field throws exception for missing field.
     *
     * @return  void
     *
     * @expectedException  \InvalidArgumentException
     */
    public function testFieldThrowsExceptionForMissingField()
    {
        $collection = new Collection([Field::find(999), Field::find(1000)]);

        $phpUnitFrameworkMockObjectMockObject = $this->getMockBuilder(EntityWithFields::class)
            ->setMethods(['fields'])
            ->getMock();

        $phpUnitFrameworkMockObjectMockObject
            ->method('fields')
            ->willReturn($collection);

        $phpUnitFrameworkMockObjectMockObject->field(767);
    }

    /**
     * fieldsContext returns correct value.
     *
     * @return  void
     */
    public function testFieldsContextReturnsCorrectValue()
    {
        $phpUnitFrameworkMockObjectMockObject = $this->getMockBuilder(Component::class)
            ->setMethods(['option'])
            ->getMock();

        $phpUnitFrameworkMockObjectMockObject->expects($this->once())
            ->method('option')
            ->willReturn('com_phproberto');

        $entity = $this->getMockBuilder(EntityWithFields::class)
            ->setMethods(['component', 'name'])
            ->getMock();

        $entity->expects($this->once())
            ->method('component')
            ->willReturn($phpUnitFrameworkMockObjectMockObject);

        $entity->expects($this->once())
            ->method('name')
            ->willReturn('sample');

        $reflectionClass = new \ReflectionClass($entity);
        $reflectionMethod = $reflectionClass->getMethod('fieldsContext');
        $reflectionMethod->setAccessible(true);

        $this->assertSame('com_phproberto.sample', $reflectionMethod->invoke($entity));
    }

    /**
     * fields loadFields.
     *
     * @return  void
     */
    public function testFieldsLoadFields()
    {
        $reloadCollection = new Collection([Field::find(999), Field::find(1000)]);

        $phpUnitFrameworkMockObjectMockObject = $this->getMockBuilder(EntityWithFields::class)
            ->setMethods(['loadFields'])
            ->getMock();

        $phpUnitFrameworkMockObjectMockObject->expects($this->at(0))
            ->method('loadFields')
            ->willReturn(new Collection());

        $phpUnitFrameworkMockObjectMockObject->expects($this->at(1))
            ->method('loadFields')
            ->willReturn($reloadCollection);

        $this->assertEquals(new Collection(), $phpUnitFrameworkMockObjectMockObject->fields());
        $this->assertEquals(new Collection(), $phpUnitFrameworkMockObjectMockObject->fields());
        $this->assertEquals($reloadCollection, $phpUnitFrameworkMockObjectMockObject->fields(true));
    }

    /**
     * @test
     *
     * @dataProvider  fieldProvider
     *
     * @return void
     */
    public function fieldByNameReturnsFieldIfFound(array $fieldsData)
    {
        $collection = new Collection(
            array_map(
                fn ($fieldData) => (new Field($fieldData['id']))->bind($fieldData),
                $fieldsData
            )
        );

        $phpUnitFrameworkMockObjectMockObject = $this->getMockBuilder(EntityWithFields::class)
            ->setMethods(['loadFields'])
            ->getMock();

        $phpUnitFrameworkMockObjectMockObject->expects($this->at(0))
            ->method('loadFields')
            ->willReturn($collection);

        $this->assertSame('another-field-test', $phpUnitFrameworkMockObjectMockObject->fieldByName('another-field-test')->get('name'));
    }

    /**
     * @test
     *
     * @return void
     *
     * @expectedException  \InvalidArgumentException
     */
    public function fieldByNameThrowsExceptionIfNotFound()
    {
        $collection = new Collection();

        $phpUnitFrameworkMockObjectMockObject = $this->getMockBuilder(EntityWithFields::class)
            ->setMethods(['fields'])
            ->getMock();

        $phpUnitFrameworkMockObjectMockObject
            ->method('fields')
            ->willReturn($collection);

        $phpUnitFrameworkMockObjectMockObject->fieldByName('my-name');
    }

    /**
     * fieldValue returns correct value.
     *
     * @return  void
     */
    public function testFieldValueReturnsCorrectValue()
    {
        $field = new Field(666);
        $field->bind(
            [
                'id'       => 666,
                'title'    => 'Sample field',
                'value'    => 'Sample field value',
                'rawvalue' => 'Sample field raw value',
            ]
        );

        $field2 = new Field(999);
        $field2->bind(
            [
                'id'       => 999,
                'title'    => 'Sample field 2',
                'value'    => 'Sample field 2 value',
                'rawvalue' => 'Sample field 2 raw value',
            ]
        );

        $field3 = new Field(1002);
        $field3->bind(
            [
                'id'       => 1002,
                'title'    => 'Sample field 3',
                'value'    => null,
                'rawvalue' => null,
            ]
        );

        $collection = new Collection([$field, $field2, $field3]);

        $phpUnitFrameworkMockObjectMockObject = $this->getMockBuilder(EntityWithFields::class)
            ->setMethods(['fields'])
            ->getMock();

        $phpUnitFrameworkMockObjectMockObject
            ->method('fields')
            ->willReturn($collection);

        $this->assertSame('default value', $phpUnitFrameworkMockObjectMockObject->fieldValue(1002, 'default value'));
        $this->assertSame('Sample field value', $phpUnitFrameworkMockObjectMockObject->fieldValue(666));
        $this->assertSame('Sample field 2 value', $phpUnitFrameworkMockObjectMockObject->fieldValue(999));
    }

    /**
     * fieldValue throws exception for missing field.
     *
     * @return  void
     *
     * @expectedException  \InvalidArgumentException
     */
    public function testFieldValueThrowsExceptionForMissingField()
    {
        $phpUnitFrameworkMockObjectMockObject = $this->getMockBuilder(EntityWithFields::class)
            ->setMethods(['fields'])
            ->getMock();

        $phpUnitFrameworkMockObjectMockObject->expects($this->once())
            ->method('fields')
            ->willReturn(new Collection());

        $phpUnitFrameworkMockObjectMockObject->fieldValue(999);
    }

    /**
     * fieldValues returns an array with values.
     *
     * @return  void
     */
    public function testFieldValuesReturnsAnArrayWithValues()
    {
        $field = new Field(666);
        $field->bind(
            [
                'id'       => 666,
                'title'    => 'Sample field',
                'value'    => 'Sample field value',
                'rawvalue' => 'Sample field raw value',
            ]
        );

        $field2 = new Field(999);
        $field2->bind(
            [
                'id'       => 999,
                'title'    => 'Sample field 2',
                'value'    => 'Sample field 2 value',
                'rawvalue' => 'Sample field 2 raw value',
            ]
        );

        $field3 = new Field(1002);
        $field3->bind(
            [
                'id'       => 1002,
                'title'    => 'Sample field 3',
                'value'    => 'Sample field 3 value',
                'rawvalue' => 'Sample field 3 raw value',
            ]
        );

        $collection = new Collection([$field, $field2, $field3]);

        $phpUnitFrameworkMockObjectMockObject = $this->getMockBuilder(EntityWithFields::class)
            ->setMethods(['fields'])
            ->getMock();

        $phpUnitFrameworkMockObjectMockObject->expects($this->at(0))
            ->method('fields')
            ->willReturn(new Collection());

        $phpUnitFrameworkMockObjectMockObject->expects($this->at(1))
            ->method('fields')
            ->willReturn($collection);

        $phpUnitFrameworkMockObjectMockObject->expects($this->at(2))
            ->method('fields')
            ->willReturn($collection);

        $this->assertSame([], $phpUnitFrameworkMockObjectMockObject->fieldValues());

        $expectedValues = [
            666  => 'Sample field value',
            999  => 'Sample field 2 value',
            1002 => 'Sample field 3 value',
        ];

        $this->assertSame($expectedValues, $phpUnitFrameworkMockObjectMockObject->fieldValues());

        $expectedRawValues = [
            666  => 'Sample field raw value',
            999  => 'Sample field 2 raw value',
            1002 => 'Sample field 3 raw value',
        ];

        $this->assertSame($expectedRawValues, $phpUnitFrameworkMockObjectMockObject->fieldValues(true));
    }

    /**
     * hasField returns correct value.
     *
     * @return  void
     */
    public function testHasFieldReturnsCorrectValue()
    {
        $collection = new Collection([Field::find(999), Field::find(1000)]);

        $phpUnitFrameworkMockObjectMockObject = $this->getMockBuilder(EntityWithFields::class)
            ->setMethods(['fields'])
            ->getMock();

        $phpUnitFrameworkMockObjectMockObject
            ->method('fields')
            ->willReturn($collection);

        $this->assertTrue($phpUnitFrameworkMockObjectMockObject->hasField(1000));
        $this->assertFalse($phpUnitFrameworkMockObjectMockObject->hasField(998));
        $this->assertTrue($phpUnitFrameworkMockObjectMockObject->hasField(1000));
    }

    /**
     * @test
     *
     * @return void
     */
    public function hasFieldsReturnsCorrectValue()
    {
        $collection = new Collection([Field::find(999), Field::find(1000)]);

        $phpUnitFrameworkMockObjectMockObject = $this->getMockBuilder(EntityWithFields::class)
            ->setMethods(['fields'])
            ->getMock();

        $phpUnitFrameworkMockObjectMockObject
            ->method('fields')
            ->will($this->onConsecutiveCalls(new Collection(), $collection));

        $this->assertFalse($phpUnitFrameworkMockObjectMockObject->hasFields());
        $this->assertTrue($phpUnitFrameworkMockObjectMockObject->hasFields());
    }

    /**
     * loadFields returns correct value.
     *
     * @return  void
     */
    public function testLoadFieldsReturnsCorrectValue()
    {
        $helperData = [
            (object) [
                'id'       => 666,
                'title'    => 'Sample field',
                'value'    => 'Sample field value',
                'rawvalue' => 'Sample raw field value',
            ],
        ];

        $phpUnitFrameworkMockObjectMockObject = $this->getMockBuilder(EntityWithFields::class)
            ->setMethods(['component', 'fieldsContext', 'getFieldsThroughHelper'])
            ->getMock();

        $phpUnitFrameworkMockObjectMockObject->expects($this->once())
            ->method('fieldsContext')
            ->willReturn('com_phproberto.sample');

        $phpUnitFrameworkMockObjectMockObject->expects($this->once())
            ->method('getFieldsThroughHelper')
            ->with($this->equalTo('com_phproberto.sample'))
            ->willReturn($helperData);

        $reflectionClass = new \ReflectionClass($phpUnitFrameworkMockObjectMockObject);
        $reflectionMethod = $reflectionClass->getMethod('loadFields');
        $reflectionMethod->setAccessible(true);

        $reflectionProperty = $reflectionClass->getProperty('id');
        $reflectionProperty->setAccessible(true);
        $reflectionProperty->setValue($phpUnitFrameworkMockObjectMockObject, 444);

        $this->assertEquals(new Collection([Field::find(666)]), $reflectionMethod->invoke($phpUnitFrameworkMockObjectMockObject));
    }
}
