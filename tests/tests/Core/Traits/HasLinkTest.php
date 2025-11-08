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

use Extly\Joomla\Entity\Tests\Core\Traits\Stubs\EntityWithLink;

/**
 * HasLink trait tests.
 *
 * @since   1.1.0
 */
class HasLinkTest extends \PHPUnit\Framework\TestCase
{
    /**
     * getLink returns correct value.
     *
     * @return  void
     */
    public function testGetLinkReturnsCorrectValue()
    {
        $_SERVER['HTTP_HOST'] = 'joomla-entity.test.com';
        $_SERVER['SCRIPT_NAME'] = '/index.php';

        $entity = new EntityWithLink();
        $this->assertSame(null, $entity->link());

        $entity = new EntityWithLink(999);

        $reflectionClass = new \ReflectionClass($entity);

        $reflectionProperty = $reflectionClass->getProperty('row');
        $reflectionProperty->setAccessible(true);

        $reflectionProperty->setValue($entity, ['id' => 999]);

        $this->assertSame('/999', $entity->link());

        $reflectionProperty->setValue($entity, ['id' => 999, 'alias' => 'sample-alias']);

        // Without reload returns old link
        $this->assertSame('/999', $entity->link());
        $this->assertSame('/999:sample-alias', $entity->link(true));
    }
}
