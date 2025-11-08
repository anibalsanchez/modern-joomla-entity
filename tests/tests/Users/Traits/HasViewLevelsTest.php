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

namespace Extly\Joomla\Entity\Tests\Users\Traits;

use Extly\Joomla\Entity\Collection;
use Extly\Joomla\Entity\Tests\Users\Traits\Stubs\EntityWithViewLevels;
use Extly\Joomla\Entity\Users\ViewLevel;

/**
 * HasViewLevels trait tests.
 *
 * @since   1.2.0
 */
class HasViewLevelsTest extends \PHPUnit\Framework\TestCase
{
    /**
     * clearViewLevels clears viewLevels property.
     *
     * @return  void
     */
    public function testClearViewLevelsClearsViewLevelsProperty()
    {
        $entityWithViewLevels = new EntityWithViewLevels();

        $reflectionClass = new \ReflectionClass($entityWithViewLevels);

        $reflectionProperty = $reflectionClass->getProperty('viewLevels');
        $reflectionProperty->setAccessible(true);

        $this->assertSame(null, $reflectionProperty->getValue($entityWithViewLevels));

        $collection = new Collection([ViewLevel::find(333)]);

        $reflectionProperty->setValue($entityWithViewLevels, $collection);

        $this->assertSame($collection, $reflectionProperty->getValue($entityWithViewLevels));

        $entityWithViewLevels->clearViewLevels();

        $this->assertSame(null, $reflectionProperty->getValue($entityWithViewLevels));
    }

    /**
     * viewLevels returns cached data.
     *
     * @return  void
     */
    public function testviewLevelsReturnsCachedData()
    {
        $entityWithViewLevels = new EntityWithViewLevels();

        $reflectionClass = new \ReflectionClass($entityWithViewLevels);

        $reflectionProperty = $reflectionClass->getProperty('viewLevels');
        $reflectionProperty->setAccessible(true);

        $this->assertSame(null, $reflectionProperty->getValue($entityWithViewLevels));

        $collection = new Collection([ViewLevel::find(333)]);

        $reflectionProperty->setValue($entityWithViewLevels, $collection);

        $this->assertSame($collection, $entityWithViewLevels->viewLevels());
    }

    /**
     * viewLevels returns loadViewLevels result if not cached.
     *
     * @return  void
     */
    public function testViewLevelsReturnsLoadViewLevelsResultIfNotCached()
    {
        $collection = new Collection(
            [
                ViewLevel::find(333),
                ViewLevel::find(666),
            ]
        );

        $entityWithViewLevels = new EntityWithViewLevels();
        $entityWithViewLevels->loadableViewLevels = $collection;

        $this->assertSame($collection, $entityWithViewLevels->viewLevels());
    }

    /**
     * hasViewLevel returns correct value.
     *
     * @return  void
     */
    public function testHasViewLevelReturnsCorrectValue()
    {
        $entityWithViewLevels = new EntityWithViewLevels();
        $entityWithViewLevels->loadableViewLevels = new Collection(
            [
                ViewLevel::find(666),
                ViewLevel::find(999),
            ]
        );

        $this->assertTrue($entityWithViewLevels->hasViewLevel(666));
        $this->assertFalse($entityWithViewLevels->hasViewLevel(333));
        $this->assertTrue($entityWithViewLevels->hasViewLevel(999));
    }

    /**
     * hasViewLevels returns correct value.
     *
     * @return  void
     */
    public function testHasViewLevelsReturnsCorrectValue()
    {
        $entity = new EntityWithViewLevels();
        $entity->loadableViewLevels = new Collection(
            [
                ViewLevel::find(333),
                ViewLevel::find(666),
            ]
        );

        $this->assertTrue($entity->hasViewLevels());

        $entity = new EntityWithViewLevels();
        $entity->loadableViewLevels = new Collection();

        $this->assertFalse($entity->hasViewLevels());
    }
}
