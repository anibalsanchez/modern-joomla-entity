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

namespace Extly\Joomla\Entity\Core\Traits;

defined('_JEXEC') || die;

/**
 * Classes using multiple singleton instances.
 *
 * @since  1.0.0
 */
trait HasInstances
{
    /**
     * Cached instances
     *
     * @var  array
     */
    protected static $instances = [];

    /**
     * Remove an instance from cache.
     *
     * @param   int  $id  Class identifier
     *
     * @return  void
     */
    public static function clear($id)
    {
        unset(static::$instances[static::class][$id]);
    }

    /**
     * Clear all instances from cache
     *
     * @return  void
     */
    public static function clearAll()
    {
        unset(static::$instances[static::class]);
    }

    /**
     * Ensure that we retrieve a non-statically-cached instance.
     *
     * @param   int  $id   Identifier of the instance
     *
     * @return  $this
     */
    public static function fresh($id)
    {
        static::clear($id);

        return static::find($id);
    }

    /**
     * Create and return a cached instance
     *
     * @param   int  $id  Identifier of the instance
     *
     * @return  $this
     */
    public static function find($id)
    {
        $class = static::class;

        if (empty(static::$instances[$class][$id])) {
            static::$instances[$class][$id] = new static($id);
        }

        return static::$instances[$class][$id];
    }
}
