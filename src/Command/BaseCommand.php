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

namespace Extly\Joomla\Entity\Command;

defined('_JEXEC') || die;

use Joomla\Registry\Registry;

/**
 * Base Command.
 *
 * @since  1.8
 */
abstract class BaseCommand
{
    /**
     * Command extra configuration.
     *
     * @var  Registry
     */
    protected $config;

    /**
     * Constructor.
     *
     * @param   array  $options  Array with command options
     */
    public function __construct(array $options = [])
    {
        $this->config = new Registry($options);
    }

    /**
     * Factory method.
     *
     * @param   array   $arguments  Arguments for the instance.
     *
     * @return  static
     */
    public static function instance(array $arguments = [])
    {
        return (new \ReflectionClass(static::class))->newInstanceArgs($arguments);
    }
}
