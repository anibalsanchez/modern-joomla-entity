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

namespace Extly\Joomla\Entity\Tests\Stubs;

use Extly\Joomla\Entity\Entity as BaseEntity;

/**
 * Stub to test Entity class.
 *
 * @since   1.1.0
 *
 * @codeCoverageIgnore
 */
class Entity extends BaseEntity
{
    /**
     * Sample public property for tests.
     *
     * @var  mixed
     */
    public $publicProperty;

    /**
     * Allow to mock table returned by this entity.
     *
     * @var  \PHPUnit_Framework_MockObject_MockObject
     */
    public static $tableMock;

    /**
     * Get a table.
     *
     * @param   string  $name     Table name. Optional.
     * @param   string  $prefix   Class prefix. Optional.
     * @param   array   $options  Configuration array for the table. Optional.
     *
     * @return  \JTable
     *
     * @throws  \InvalidArgumentException
     */
    public function table($name = '', $prefix = null, $options = [])
    {
        if (null !== static::$tableMock) {
            return static::$tableMock;
        }

        return parent::table($name, $prefix, $options);
    }
}
