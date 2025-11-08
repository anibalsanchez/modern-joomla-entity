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

use Extly\Joomla\Entity\Collection;
use Extly\Joomla\Entity\Tests\Stubs\AnotherEntity;
use Extly\Joomla\Entity\Tests\Stubs\Entity;

/**
 * Entity collection tests.
 *
 * @since   1.1.0
 */
class CollectionTest extends \TestCase
{
    /**
     * Constructor sets entities.
     *
     * @return  void
     */
    public function testConstructorSetsEntities()
    {
        $collection = new Collection();

        $reflection = new \ReflectionClass($collection);
        $entitiesProperty = $reflection->getProperty('entities');
        $entitiesProperty->setAccessible(true);

        $this->assertSame([], $entitiesProperty->getValue($collection));

        $entities = [
            new Entity(1000),
            new Entity(1001),
        ];

        $collection = new Collection($entities);

        $reflection = new \ReflectionClass($collection);
        $entitiesProperty = $reflection->getProperty('entities');
        $entitiesProperty->setAccessible(true);

        $this->assertEquals(
            [
                1000 => new Entity(1000),
                1001 => new Entity(1001),
            ],
            $entitiesProperty->getValue($collection)
        );
    }

    /**
     * add adds a new entity.
     *
     * @return  void
     */
    public function testAddAddsNewEntity()
    {
        $collection = new Collection();

        $reflectionClass = new \ReflectionClass($collection);
        $reflectionProperty = $reflectionClass->getProperty('entities');
        $reflectionProperty->setAccessible(true);

        $this->assertSame([], $reflectionProperty->getValue($collection));

        $entity = new Entity(1000);

        $this->assertTrue($collection->add($entity));

        $this->assertSame([1000 => $entity], $reflectionProperty->getValue($collection));

        $entity1 = new Entity(1001);

        $this->assertTrue($collection->add($entity1));

        $this->assertSame([1000 => $entity, 1001 => $entity1], $reflectionProperty->getValue($collection));
    }

    /**
     * add does not overwrite entity.
     *
     * @return  void
     */
    public function testAddDoesNotOverwriteEntity()
    {
        $collection = new Collection();

        $reflection = new \ReflectionClass($collection);
        $reflectionProperty = $reflection->getProperty('entities');
        $reflectionProperty->setAccessible(true);

        $this->assertSame([], $reflectionProperty->getValue($collection));

        $entity = new Entity(1000);
        $entity2 = new Entity(1000);
        $entity3 = new Entity(1001);

        $reflection = new \ReflectionClass($entity);
        $rowProperty = $reflection->getProperty('row');
        $rowProperty->setAccessible(true);

        $expectedRow = ['id' => 1000, 'name' => 'Roberto Segura'];

        $rowProperty->setValue($entity, $expectedRow);

        $this->assertTrue($collection->add($entity));

        $this->assertSame([1000 => $entity], $reflectionProperty->getValue($collection));

        $this->assertFalse($collection->add($entity2));

        $this->assertNotSame([1000 => $entity2], $reflectionProperty->getValue($collection));
    }

    /**
     * arrayAccess implementation.
     *
     * @return  void
     */
    public function testArrayAccessImplementation()
    {
        $entities = [
            1000 => new Entity(1000),
            1001 => new Entity(1001),
            1002 => new Entity(1002),
        ];

        $collection = new Collection($entities);

        $this->assertTrue(isset($collection[1000]));
        $this->assertFalse(isset($collection[1003]));

        $collection[1003] = new Entity(1003);

        $this->assertTrue(isset($collection[1003]));
        $this->assertEquals(new Entity(1002), $collection[1002]);

        unset($collection[1003]);
        $this->assertFalse(isset($collection[1003]));
    }

    /**
     * clear empties entities array.
     *
     * @return  void
     */
    public function testClearEmptiesEntitiesArray()
    {
        $collection = new Collection([new Entity(1000), new Entity(1001), new Entity(1002)]);

        $reflectionClass = new \ReflectionClass($collection);
        $reflectionProperty = $reflectionClass->getProperty('entities');
        $reflectionProperty->setAccessible(true);

        $this->assertSame(3, count($reflectionProperty->getValue($collection)));

        $collection->clear();

        $this->assertSame([], $reflectionProperty->getValue($collection));
    }

    /**
     * count returns correct value.
     *
     * @return  void
     */
    public function testCountReturnsCorrectValue()
    {
        $collection = new Collection();

        $reflectionClass = new \ReflectionClass($collection);
        $reflectionProperty = $reflectionClass->getProperty('entities');
        $reflectionProperty->setAccessible(true);

        $this->assertSame(0, $collection->count());

        $collection = new Collection([new Entity(1000), new Entity(1001)]);

        $this->assertSame(2, $collection->count());

        $reflectionProperty->setValue(
            $collection,
            [
                1000 => new Entity(1000),
                1001 => new Entity(1001),
                1002 => new Entity(1002),
            ]
        );

        $this->assertSame(3, $collection->count());
    }

    /**
     * current returns correct value.
     *
     * @return  void
     */
    public function testCurrentReturnsCorrectValue()
    {
        $entities = [
            1000 => new Entity(1000),
            1001 => new Entity(1001),
            1002 => new Entity(1002),
        ];

        $collection = new Collection($entities);

        while ($collection->key()) {
            $this->assertEquals(current($entities), $collection->current());
            next($entities);
            $collection->next();
        }

        reset($entities);

        $reflectionClass = new \ReflectionClass($collection);
        $reflectionProperty = $reflectionClass->getProperty('entities');
        $reflectionProperty->setAccessible(true);

        $collection = new Collection([new Entity(1000), new Entity(1001), new Entity(1002)]);

        $reflectionProperty->setValue($collection, $entities);

        $this->assertEquals(new Entity(1000), $collection->current());

        while (key($entities) !== 1001) {
            next($entities);
        }

        $reflectionProperty->setValue($collection, $entities);

        $this->assertEquals(new Entity(1001), $collection->current());
    }

    /**
     * all returns correct value.
     *
     * @return  void
     */
    public function testAllReturnsCorrectValue()
    {
        $collection = new Collection();

        $this->assertSame([], $collection->all());

        $entities = [
            1000 => new Entity(1000),
            1001 => new Entity(1001),
        ];

        $collection = new Collection($entities);

        $returnedEntities = $collection->all();

        $this->assertSame($entities, $returnedEntities);

        // Test that writing does not modify source entities
        $returnedEntities[1000]->publicProperty = 'test me';

        $this->assertSame($entities, $returnedEntities);
    }

    /**
     * @test
     *
     * @return void
     *
     * @since  1.1.0
     */
    public function fitlerReturnsANewCollection()
    {
        $collection = new Collection();

        $entities = [
            1000 => new Entity(1000),
            1001 => new Entity(1001),
        ];

        $collection = new Collection($entities);

        $this->assertSame(2, $collection->count());

        $newCollection = $collection->filter(
            fn ($entity) => $entity->id() === 1000
        );

        $this->assertNotSame($newCollection, $collection);
        $this->assertSame(1, $newCollection->count());
        $this->assertSame(1000, $newCollection[1000]->id());
    }

    /**
     * @test
     *
     * @return void
     */
    public function fromDataReturnsExpectedCollection()
    {
        $data = [
            [
                'id' => 1,
                'name' => 'Entity',
            ],
            [
                'id' => 2,
                'name' => 'Another',
            ],
        ];

        $collection = Collection::fromData($data, Entity::class);

        $this->assertInstanceOf(Collection::class, $collection);
        $this->assertSame([1, 2], $collection->ids());

        foreach ($collection as $entity) {
            $this->assertInstanceOf(Entity::class, $entity);
        }
    }

    /**
     * getIterator returns correct iterator.
     *
     * @return  void
     */
    public function testGetIteratorReturnsCorrectIterator()
    {
        $entities = [
            1000 => new Entity(1000),
            1001 => new Entity(1001),
            1002 => new Entity(1002),
        ];

        $collection = new Collection($entities);

        $this->assertEquals(new \ArrayIterator($entities), $collection->getIterator());
    }

    /**
     * get retrieves correct entity.
     *
     * @return  void
     */
    public function testGetRetrievesCorrectEntity()
    {
        $collection = new Collection([new Entity(1000), new Entity(1001)]);

        $this->assertEquals(new Entity(1000), $collection->get(1000));
        $this->assertEquals(new Entity(1001), $collection->get(1001));
    }

    /**
     * get throws exception when element is not present.
     *
     * @return  void
     *
     * @expectedException  \InvalidArgumentException
     */
    public function testGetThrowsExceptionForMissingElement()
    {
        $collection = new Collection([new Entity(1000), new Entity(1001)]);

        $collection->get(1002);
    }

    /**
     * has returns correct vlaue.
     *
     * @return  void
     */
    public function testHasReturnsCorrectValue()
    {
        $collection = new Collection();

        $this->assertFalse($collection->has(1000));
        $this->assertFalse($collection->has(1001));
        $this->assertFalse($collection->has(1002));

        $collection = new Collection([new Entity(1000), new Entity(1001)]);

        $this->assertTrue($collection->has(1000));
        $this->assertFalse($collection->has(1002));
        $this->assertTrue($collection->has(1001));
    }

    /**
     * ids returns correct identifiers.
     *
     * @return  void
     */
    public function testIdsReturnsCorrectIdentifiers()
    {
        $collection = new Collection();

        $reflectionClass = new \ReflectionClass($collection);
        $reflectionProperty = $reflectionClass->getProperty('entities');
        $reflectionProperty->setAccessible(true);

        $this->assertSame([], $collection->ids());

        $collection = new Collection([new Entity(1000), new Entity(1001)]);

        $this->assertSame([1000, 1001], $collection->ids());

        $reflectionProperty->setValue(
            $collection,
            [
                1000 => new Entity(1000),
                1002 => new Entity(1002),
                1001 => new Entity(1001),
            ]
        );

        $this->assertSame([1000, 1002, 1001], $collection->ids());
    }

    /**
     * intersect returns correct value.
     *
     * @return  void
     */
    public function testIntersectReturnsCorrectValue()
    {
        $collection1 = new Collection();
        $collection2 = new Collection();

        $result = $collection1->intersect($collection2);

        $reflectionClass = new \ReflectionClass($result);
        $reflectionProperty = $reflectionClass->getProperty('entities');
        $reflectionProperty->setAccessible(true);

        $this->assertEquals([], $reflectionProperty->getValue($result));

        $collection1 = new Collection([new Entity(1000), new Entity(1001)]);
        $collection2 = new Collection([new Entity(1002), new Entity(1000)]);

        $result = $collection1->intersect($collection2);

        $expectetdEntities = [
            1000 => new Entity(1000),
        ];

        $this->assertEquals($expectetdEntities, $reflectionProperty->getValue($result));
        $this->assertSame([1000], array_keys($reflectionProperty->getValue($result)));

        // Ensure that source entities aren't modified
        $this->assertSame([1000, 1001], array_keys($reflectionProperty->getValue($collection1)));
        $this->assertSame([1002, 1000], array_keys($reflectionProperty->getValue($collection2)));

        $collection1 = new Collection([new Entity(999), new Entity(1000), new Entity(1001)]);
        $collection2 = new Collection([new Entity(1001), new Entity(1000), new Entity(1002)]);

        $result = $collection1->intersect($collection2);

        $expectetdEntities = [
            1000 => new Entity(1000),
            1001 => new Entity(1001),
        ];

        $this->assertEquals($expectetdEntities, $reflectionProperty->getValue($result));
        $this->assertSame([1000, 1001], array_keys($reflectionProperty->getValue($result)));

        // Ensure that source entities aren't modified
        $this->assertSame([999, 1000, 1001], array_keys($reflectionProperty->getValue($collection1)));
        $this->assertSame([1001, 1000, 1002], array_keys($reflectionProperty->getValue($collection2)));
    }

    /**
     * isEmpty returns correct value.
     *
     * @return  void
     */
    public function testIsEmptyReturnsCorrectValue()
    {
        $collection = new Collection();

        $reflectionClass = new \ReflectionClass($collection);
        $reflectionProperty = $reflectionClass->getProperty('entities');
        $reflectionProperty->setAccessible(true);

        $this->assertTrue($collection->isEmpty());

        $collection = new Collection([new Entity(1000), new Entity(1001)]);

        $this->assertFalse($collection->isEmpty());

        $reflectionProperty->setValue($collection, []);

        $this->assertTrue($collection->isEmpty());

        $reflectionProperty->setValue(
            $collection,
            [
                1000 => new Entity(1000),
                1002 => new Entity(1002),
                1001 => new Entity(1001),
            ]
        );

        $this->assertFalse($collection->isEmpty());
    }

    /**
     * key returns correct value.
     *
     * @return  void
     */
    public function testKeyReturnsCorrectValue()
    {
        $collection = new Collection();

        $this->assertSame(null, $collection->key());

        $entities = [
            1000 => new Entity(1000),
            1001 => new Entity(1001),
            1002 => new Entity(1002),
        ];

        $collection = new Collection($entities);

        while ($collection->key()) {
            $this->assertEquals(key($entities), $collection->key());
            next($entities);
            $collection->next();
        }

        reset($entities);

        $reflectionClass = new \ReflectionClass($collection);
        $reflectionProperty = $reflectionClass->getProperty('entities');
        $reflectionProperty->setAccessible(true);

        $collection = new Collection($entities);

        $reflectionProperty->setValue($collection, $entities);

        $this->assertSame(1000, $collection->key());

        while (key($entities) !== 1001) {
            next($entities);
        }

        $reflectionProperty->setValue($collection, $entities);

        $this->assertSame(1001, $collection->key());
    }

    /**
     * ksort orders entities.
     *
     * @return  void
     */
    public function testKsortOrdersEntities()
    {
        $entities = [1001 => new Entity(1001), 1000 => new Entity(1000), 1002 => new Entity(1002)];

        $collection = new Collection($entities);

        $reflectionClass = new \ReflectionClass($collection);
        $reflectionProperty = $reflectionClass->getProperty('entities');
        $reflectionProperty->setAccessible(true);

        $this->assertSame([1001, 1000, 1002], array_keys($reflectionProperty->getValue($collection)));

        $newCollection = $collection->ksort();

        $this->assertSame([1000, 1001, 1002], array_keys($reflectionProperty->getValue($newCollection)));

        // Ensure source collection integrity
        $this->assertSame([1001, 1000, 1002], array_keys($reflectionProperty->getValue($collection)));
    }

    /**
     * krsort orders entities.
     *
     * @return  void
     */
    public function testKrsortOrdersEntities()
    {
        $entities = [1001 => new Entity(1001), 1000 => new Entity(1000), 1002 => new Entity(1002)];

        $collection = new Collection($entities);

        $reflectionClass = new \ReflectionClass($collection);
        $reflectionProperty = $reflectionClass->getProperty('entities');
        $reflectionProperty->setAccessible(true);

        $this->assertSame([1001, 1000, 1002], array_keys($reflectionProperty->getValue($collection)));

        $newCollection = $collection->krsort();

        $this->assertSame([1002, 1001, 1000], array_keys($reflectionProperty->getValue($newCollection)));

        // Ensure source collection integrity
        $this->assertSame([1001, 1000, 1002], array_keys($reflectionProperty->getValue($collection)));
    }

    /**
     * last returns correct value.
     *
     * @return  void
     */
    public function testLastReturnsCorrectValue()
    {
        $collection = new Collection();

        $this->assertSame(false, $collection->last());

        $entities = [1000 => new Entity(1000), 1001 => new Entity(1001), 1002 => new Entity(1002)];

        $collection = new Collection($entities);

        $this->assertSame($entities[1002], $collection->last());

        $reflectionClass = new \ReflectionClass($collection);
        $reflectionProperty = $reflectionClass->getProperty('entities');
        $reflectionProperty->setAccessible(true);

        while (key($entities) !== 1001) {
            next($entities);
        }

        $reflectionProperty->setValue($collection, $entities);

        $this->assertSame(1001, key($reflectionProperty->getValue($collection)));
        $this->assertEquals(new Entity(1002), $collection->last());
    }

    /**
     * map processes all the entities.
     *
     * @return  void
     */
    public function testMapProcessesAllTheEntities()
    {
        $entity1 = new Entity(1000);
        $entity2 = new Entity(1001);

        $reflectionClass = new \ReflectionClass($entity1);
        $reflectionProperty = $reflectionClass->getProperty('row');
        $reflectionProperty->setAccessible(true);

        $row1 = ['id' => 1000, 'name' => 'Vicente Monroig', 'foo' => 'foo'];
        $row2 = ['id' => 1001, 'name' => 'Jorge Pomer', 'foo' => 'foo'];

        $reflectionProperty->setValue($entity1, $row1);
        $reflectionProperty->setValue($entity2, $row2);

        $entities = [$entity1, $entity2];

        $collection = new Collection($entities);

        $function = function ($entity) {
            $this->assertSame(null, $entity->publicProperty);
            $entity->assign('foo', 'bar');

            return $entity;
        };

        $newCollection = $collection->map($function);

        foreach ($newCollection as $entity) {
            $this->assertSame('bar', $entity->get('foo'));
        }

        $this->assertNotEquals($newCollection, $collection);

        // Original collection not modified
        foreach ($collection as $entity) {
            $this->assertSame('foo', $entity->get('foo'));
        }
    }

    /**
     * Merge returns correct collection.
     *
     * @return  void
     */
    public function testMergeReturnsCorrectCollection()
    {
        $collection1 = new Collection();
        $collection2 = new Collection();

        $mergedCollection = $collection1->merge($collection2);

        $reflectionClass = new \ReflectionClass($mergedCollection);
        $reflectionProperty = $reflectionClass->getProperty('entities');
        $reflectionProperty->setAccessible(true);

        $this->assertEquals([], $reflectionProperty->getValue($mergedCollection));

        $collection1 = new Collection([new Entity(1000), new Entity(1001)]);
        $collection2 = new Collection([new Entity(1002), new Entity(1003)]);

        $mergedCollection = $collection1->merge($collection2);

        $expectetdEntities = [
            1000 => new Entity(1000),
            1001 => new Entity(1001),
            1002 => new Entity(1002),
            1003 => new Entity(1003),
        ];

        $this->assertEquals($expectetdEntities, $reflectionProperty->getValue($mergedCollection));
        $this->assertSame([1000, 1001, 1002, 1003], array_keys($reflectionProperty->getValue($mergedCollection)));

        // Ensure that source entities aren't modified
        $this->assertSame([1000, 1001], array_keys($reflectionProperty->getValue($collection1)));
        $this->assertSame([1002, 1003], array_keys($reflectionProperty->getValue($collection2)));

        $collection1 = new Collection([new Entity(1000), new Entity(1001)]);
        $collection2 = new Collection([new Entity(1002), new Entity(1003)]);

        $mergedCollection = $collection2->merge($collection1);

        $expectetdEntities = [
            1002 => new Entity(1002),
            1003 => new Entity(1003),
            1000 => new Entity(1000),
            1001 => new Entity(1001),
        ];

        $this->assertEquals($expectetdEntities, $reflectionProperty->getValue($mergedCollection));
        $this->assertSame([1002, 1003, 1000, 1001], array_keys($reflectionProperty->getValue($mergedCollection)));

        // Ensure that source entities aren't modified
        $this->assertSame([1000, 1001], array_keys($reflectionProperty->getValue($collection1)));
        $this->assertSame([1002, 1003], array_keys($reflectionProperty->getValue($collection2)));
    }

    /**
     * next returns correct value.
     *
     * @return  void
     */
    public function testNextReturnsCorrectValue()
    {
        $collection = new Collection();

        $this->assertSame(false, $collection->next());

        $entities = [1000 => new Entity(1000), 1001 => new Entity(1001), 1002 => new Entity(1002)];

        $collection = new Collection($entities);

        foreach ($collection as $entity) {
            if ($entity->id() !== 1002) {
                $this->assertSame($collection->next(), $entities[$entity->id() + 1]);
            }
        }

        $reflectionClass = new \ReflectionClass($collection);
        $reflectionProperty = $reflectionClass->getProperty('entities');
        $reflectionProperty->setAccessible(true);

        $collection = new Collection([new Entity(1000), new Entity(1001), new Entity(1002)]);

        $reflectionProperty->setValue($collection, $entities);

        $this->assertSame($entities[1001], $collection->next());

        while (key($entities) !== 1001) {
            next($entities);
        }

        $reflectionProperty->setValue($collection, $entities);

        $this->assertSame($entities[1002], $collection->next());
    }

    /**
     * offsetExists returns correct value.
     *
     * @return  void
     */
    public function testOffsetExistsReturnsCorrectValue()
    {
        $entities = [
            1000 => new Entity(1000),
            1001 => new Entity(1001),
            1002 => new Entity(1002),
        ];

        $collection = new Collection($entities);

        $this->assertFalse($collection->offsetExists(1003));
        $this->assertTrue($collection->offsetExists(1000));
        $this->assertFalse($collection->offsetExists(999));
        $this->assertTrue($collection->offsetExists(1001));
        $this->assertFalse($collection->offsetExists(1004));
        $this->assertTrue($collection->offsetExists(1002));
    }

    /**
     * offsetGet returns correct value.
     *
     * @return  void
     */
    public function testOffsetGetReturnsCorrectValue()
    {
        $entities = [
            1000 => new Entity(1000),
            1001 => new Entity(1001),
            1002 => new Entity(1002),
        ];

        $collection = new Collection($entities);

        $this->assertInstanceOf(Entity::class, $collection->offsetGet(1000));
        $this->assertInstanceOf(Entity::class, $collection->offsetGet(1002));
        $this->assertInstanceOf(Entity::class, $collection->offsetGet(1001));
    }

    /**
     * offsetSet sets correct value.
     *
     * @return  void
     */
    public function testOffsetSetSetsCorrectValue()
    {
        $collection = new Collection();

        $entities = [
            1000 => new Entity(1000),
            1001 => new Entity(1001),
            1002 => new Entity(1002),
        ];

        foreach ($entities as $id => $entity) {
            $collection->offsetSet($id, $entity);
            $this->assertSame($entity, $collection[$id]);
        }
    }

    /**
     * ofssetUnset unsets entity.
     *
     * @return  void
     */
    public function testOffsetUnsetUnsetsEntity()
    {
        $entities = [
            1000 => new Entity(1000),
            1001 => new Entity(1001),
            1002 => new Entity(1002),
        ];

        $collection = new Collection($entities);

        $collection->offsetUnset(1001);
        $this->assertEquals($collection, new Collection([$entities[1000], $entities[1002]]));

        $collection->offsetUnset(1002);
        $this->assertEquals($collection, new Collection([$entities[1000]]));
    }

    /**
     * remove removes entity.
     *
     * @return  void
     */
    public function testRemoveRemovesEntity()
    {
        $collection = new Collection();

        $this->assertSame(false, $collection->remove(1000));

        $entities = [1000 => new Entity(1000), 1001 => new Entity(1001), 1002 => new Entity(1002)];

        $collection = new Collection($entities);

        $this->assertSame(false, $collection->remove(1005));

        $reflectionClass = new \ReflectionClass($collection);
        $reflectionProperty = $reflectionClass->getProperty('entities');
        $reflectionProperty->setAccessible(true);

        $this->assertSame(true, $collection->remove(1001));
        $this->assertEquals([1000 => new Entity(1000), 1002 => new Entity(1002)], $reflectionProperty->getValue($collection));

        $this->assertSame(true, $collection->remove(1000));
        $this->assertEquals([1002 => new Entity(1002)], $reflectionProperty->getValue($collection));

        $this->assertSame(true, $collection->remove(1002));
        $this->assertEquals([], $reflectionProperty->getValue($collection));
    }

    /**
     * rewind returns correct value.
     *
     * @return  void
     */
    public function testRewindReturnsCorrectValue()
    {
        $collection = new Collection();

        $this->assertSame(false, $collection->rewind());

        $entities = [1000 => new Entity(1000), 1001 => new Entity(1001), 1002 => new Entity(1002)];

        $collection = new Collection($entities);

        $this->assertSame($entities[1000], $collection->rewind());

        $reflectionClass = new \ReflectionClass($collection);
        $reflectionProperty = $reflectionClass->getProperty('entities');
        $reflectionProperty->setAccessible(true);

        while (key($entities) !== 1001) {
            next($entities);
        }

        $reflectionProperty->setValue($collection, $entities);

        $this->assertSame(1001, key($reflectionProperty->getValue($collection)));
        $this->assertEquals(new Entity(1000), $collection->rewind());
    }

    /**
     * toArray returns correct data.
     *
     * @return  void
     */
    public function testToArrayReturnsCorrectData()
    {
        $collection = new Collection();

        $this->assertEquals([], $collection->toObjects());

        $entity1 = new Entity(1000);
        $entity2 = new Entity(1001);

        $reflectionClass = new \ReflectionClass($entity1);
        $reflectionProperty = $reflectionClass->getProperty('row');
        $reflectionProperty->setAccessible(true);

        $row1 = ['id' => 1000, 'name' => 'Vicente Monroig'];
        $row2 = ['id' => 1001, 'name' => 'Jorge Pomer'];

        $reflectionProperty->setValue($entity1, $row1);
        $reflectionProperty->setValue($entity2, $row2);

        $entities = [$entity1, $entity2];

        $collection = new Collection($entities);

        $expected = [
            $row1['id'] => $row1,
            $row2['id'] => $row2,
        ];

        $this->assertEquals($expected, $collection->toArray());
    }

    /**
     * toObjects returns correct data.
     *
     * @return  void
     */
    public function testToObjectsReturnsCorrectData()
    {
        $collection = new Collection();

        $this->assertEquals([], $collection->toObjects());

        $entity1 = new Entity(1000);
        $entity2 = new Entity(1001);

        $reflectionClass = new \ReflectionClass($entity1);
        $reflectionProperty = $reflectionClass->getProperty('row');
        $reflectionProperty->setAccessible(true);

        $row1 = ['id' => 1000, 'name' => 'Vicente Monroig'];
        $row2 = ['id' => 1001, 'name' => 'Jorge Pomer'];

        $reflectionProperty->setValue($entity1, $row1);
        $reflectionProperty->setValue($entity2, $row2);

        $entities = [$entity1, $entity2];

        $collection = new Collection($entities);

        $expected = [
            $row1['id'] => (object) $row1,
            $row2['id'] => (object) $row2,
        ];

        $this->assertEquals($expected, $collection->toObjects());
    }

    /**
     * sortyBy does not modify source collection.
     *
     * @return  void
     */
    public function testSortByDoesNotModifySourceCollection()
    {
        $entity1 = new Entity(1000);
        $entity2 = new Entity(1001);

        $reflection = new \ReflectionClass($entity1);
        $reflectionProperty = $reflection->getProperty('row');
        $reflectionProperty->setAccessible(true);

        $row1 = ['id' => 1000, 'test_integer' => '4'];
        $row2 = ['id' => 1001, 'test_integer' => '3'];

        $reflectionProperty->setValue($entity1, $row1);
        $reflectionProperty->setValue($entity2, $row2);

        $collection = new Collection([$entity1, $entity2]);

        $reflection = new \ReflectionClass($collection);
        $entitiesProperty = $reflection->getProperty('entities');
        $entitiesProperty->setAccessible(true);

        $this->assertSame([1000, 1001], array_keys($entitiesProperty->getValue($collection)));

        $newCollection = $collection->sortBy('test_integer');
        $this->assertSame([1000, 1001], array_keys($entitiesProperty->getValue($collection)));

        $newCollection = $collection->sortByDesc('test_integer');
        $this->assertSame([1000, 1001], array_keys($entitiesProperty->getValue($collection)));
    }

    /**
     * sortBy orders entities for integer properties.
     *
     * @return  void
     */
    public function testSortByIntegerOrdering()
    {
        $entity1 = new Entity(1000);
        $entity2 = new Entity(1001);

        $reflection = new \ReflectionClass($entity1);
        $reflectionProperty = $reflection->getProperty('row');
        $reflectionProperty->setAccessible(true);

        $row1 = ['id' => 1000, 'test_integer' => '4'];
        $row2 = ['id' => 1001, 'test_integer' => '3'];

        $reflectionProperty->setValue($entity1, $row1);
        $reflectionProperty->setValue($entity2, $row2);

        $collection = new Collection([$entity1, $entity2]);

        $reflection = new \ReflectionClass($collection);
        $entitiesProperty = $reflection->getProperty('entities');
        $entitiesProperty->setAccessible(true);

        $this->assertSame([1000, 1001], array_keys($entitiesProperty->getValue($collection)));

        $newCollection = $collection->sortBy('test_integer');
        $this->assertSame([1001, 1000], array_keys($entitiesProperty->getValue($newCollection)));

        $newCollection = $collection->sortByDesc('test_integer');
        $this->assertSame([1000, 1001], array_keys($entitiesProperty->getValue($newCollection)));

        $row1 = ['id' => 1000, 'test_integer' => '0'];
        $row2 = ['id' => 1001, 'test_integer' => 400];

        $reflectionProperty->setValue($entity1, $row1);
        $reflectionProperty->setValue($entity2, $row2);

        $collection = new Collection([$entity1, $entity2]);

        $newCollection = $collection->sortBy('test_integer');
        $this->assertSame([1000, 1001], array_keys($entitiesProperty->getValue($newCollection)));

        $newCollection = $collection->sortByDesc('test_integer');
        $this->assertSame([1001, 1000], array_keys($entitiesProperty->getValue($newCollection)));

        $row1 = ['id' => 1000, 'test_integer' => 34];
        $row2 = ['id' => 1001, 'test_integer' => 44];

        $reflectionProperty->setValue($entity1, $row1);
        $reflectionProperty->setValue($entity2, $row2);

        $collection = new Collection([$entity1, $entity2]);

        $this->assertSame([1000, 1001], array_keys($entitiesProperty->getValue($collection)));

        $newCollection = $collection->sortByDesc('test_integer');
        $this->assertSame([1001, 1000], array_keys($entitiesProperty->getValue($newCollection)));

        $newCollection = $collection->sortBy('test_integer');
        $this->assertSame([1000, 1001], array_keys($entitiesProperty->getValue($newCollection)));
    }

    /**
     * sortBy orders entities for text fields.
     *
     * @return  void
     */
    public function testSortByTextOrdering()
    {
        $entity1 = new Entity(1000);
        $entity2 = new Entity(1001);

        $reflection = new \ReflectionClass($entity1);
        $reflectionProperty = $reflection->getProperty('row');
        $reflectionProperty->setAccessible(true);

        $row1 = ['id' => 1000, 'test_text' => 'Camióna'];
        $row2 = ['id' => 1001, 'test_text' => 'Camión'];

        $reflectionProperty->setValue($entity1, $row1);
        $reflectionProperty->setValue($entity2, $row2);

        $collection = new Collection([$entity1, $entity2]);

        $reflection = new \ReflectionClass($collection);
        $entitiesProperty = $reflection->getProperty('entities');
        $entitiesProperty->setAccessible(true);

        $this->assertSame([1000, 1001], array_keys($entitiesProperty->getValue($collection)));

        $newCollection = $collection->sortBy('test_text');
        $this->assertSame([1001, 1000], array_keys($entitiesProperty->getValue($newCollection)));

        $newCollection = $collection->sortByDesc('test_text');
        $this->assertSame([1000, 1001], array_keys($entitiesProperty->getValue($newCollection)));

        $row1 = ['id' => 1000, 'test_text' => 'Turrón'];
        $row2 = ['id' => 1001, 'test_text' => 'tºurrón'];

        $reflectionProperty->setValue($entity1, $row1);
        $reflectionProperty->setValue($entity2, $row2);

        $collection = new Collection([$entity1, $entity2]);

        $newCollection = $collection->sortBy('test_text');
        $this->assertSame([1000, 1001], array_keys($entitiesProperty->getValue($newCollection)));

        $newCollection = $collection->sortByDesc('test_text');
        $this->assertSame([1001, 1000], array_keys($entitiesProperty->getValue($newCollection)));
    }

    /**
     * sort orders entities.
     *
     * @return  void
     */
    public function testSortOrdersEntities()
    {
        $entities = [1000 => new Entity(1000), 1001 => new Entity(1001), 1002 => new Entity(1002)];

        $collection = new Collection($entities);

        $reflectionClass = new \ReflectionClass($collection);
        $reflectionProperty = $reflectionClass->getProperty('entities');
        $reflectionProperty->setAccessible(true);

        $this->assertSame([1000, 1001, 1002], array_keys($reflectionProperty->getValue($collection)));

        $newCollection = $collection->sort(
            fn ($entity1, $entity2) => ($entity2->id() < $entity1->id()) ? -1 : 1
        );

        $this->assertSame([1002, 1001, 1000], array_keys($reflectionProperty->getValue($newCollection)));

        // Ensure integrity of source collection
        $this->assertSame([1000, 1001, 1002], array_keys($reflectionProperty->getValue($collection)));
    }

    /**
     * valid returns correct value.
     *
     * @return  void
     */
    public function testValidReturnsCorrectValue()
    {
        $collection = new Collection();

        $this->assertFalse($collection->valid());

        $entities = [1000 => new Entity(1000), 1001 => new Entity(1001), 1002 => new Entity(1002)];

        $collection = new Collection($entities);

        $this->assertTrue($collection->valid());
    }

    /**
     * set sets correct value.
     *
     * @return  void
     */
    public function testWriteOverwritesValue()
    {
        $collection = new Collection();

        $this->assertTrue($collection->write(new Entity(1000)));

        $reflection = new \ReflectionClass($collection);
        $reflectionProperty = $reflection->getProperty('entities');
        $reflectionProperty->setAccessible(true);

        $this->assertEquals([1000 => new Entity(1000)], $reflectionProperty->getValue($collection));

        $entities = [1000 => new Entity(1000), 1001 => new Entity(1001), 1002 => new Entity(1002)];

        $entity = new Entity(1000);
        $entity2 = new Entity(1000);
        $entity3 = new Entity(1001);

        $reflection = new \ReflectionClass($entity);
        $rowProperty = $reflection->getProperty('row');
        $rowProperty->setAccessible(true);

        $expectedRow = ['id' => 1000, 'name' => 'Roberto Segura'];

        $rowProperty->setValue($entity, $expectedRow);

        $this->assertTrue($collection->write($entity));

        $this->assertSame([1000 => $entity], $reflectionProperty->getValue($collection));

        $this->assertTrue($collection->write($entity2));

        $this->assertSame([1000 => $entity2], $reflectionProperty->getValue($collection));
    }

    /**
     * write throws exception when entity has no id.
     *
     * @return  void
     *
     * @expectedException  \InvalidArgumentException
     */
    public function testWriteThrowsExceptionWhenEntityHasNoId()
    {
        $collection = new Collection();

        $collection->add(new Entity());
    }

    /**
     * write throws exception when entity has a different class of the collection.
     *
     * @return  void
     *
     * @expectedException  \InvalidArgumentException
     */
    public function testWriteThrowsExceptionWhenEntityHasWrongClass()
    {
        $collection = new Collection();

        $collection->add(new Entity(24));
        $collection->add(new AnotherEntity(23));
    }
}
