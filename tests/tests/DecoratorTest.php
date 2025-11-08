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

use Extly\Joomla\Entity\Decorator;
use Extly\Joomla\Entity\Tests\Stubs\Entity;

/**
 * Base decorator tests.
 *
 * @since   1.1.0
 */
class DecoratorTest extends \TestCase
{
    /**
     * Constructor sets entity.
     *
     * @return  void
     */
    public function testConstructorSetsEntity()
    {
        $entity = new Entity();

        $decorator = $this->getMockBuilder(Decorator::class)
            ->setConstructorArgs([$entity])
            ->getMockForAbstractClass();

        $reflectionClass = new \ReflectionClass($decorator);
        $reflectionProperty = $reflectionClass->getProperty('entity');
        $reflectionProperty->setAccessible(true);

        $this->assertSame($entity, $reflectionProperty->getValue($decorator));
    }
}
