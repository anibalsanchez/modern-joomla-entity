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

namespace Extly\Joomla\Entity\Searcher;

defined('_JEXEC') || die;

use Joomla\Registry\Registry;

/**
 * Base searcher.
 *
 * @since  1.4.0
 */
abstract class BaseSearcher
{
    /**
     * Find options.
     *
     * @var  Registry
     */
    protected $options;

    /**
     * Constructor
     *
     * @param   array  $options  Find options
     */
    public function __construct(array $options = [])
    {
        $this->options = new Registry(array_merge($this->defaultOptions(), $options));
        $this->options->separator = '|';
    }

    /**
     * Default options to initialise searcher.
     *
     * @return  array
     */
    public function defaultOptions()
    {
        return [];
    }

    /**
     * Factory method.
     *
     * @param   array   $options  Options for the searcher.
     *
     * @return  static
     */
    public static function instance(array $options = [])
    {
        return new static($options);
    }

    /**
     * Retrieve searcher options.
     *
     * @return  Registry
     */
    public function options()
    {
        return $this->options;
    }
}
