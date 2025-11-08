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

namespace Extly\Joomla\Entity\Core\Client;

defined('_JEXEC') || die;

/**
 * Base client.
 *
 * @since  1.0.0
 */
abstract class BaseClient
{
    /**
     * Client identifier.
     *
     * @var  int
     */
    protected $id;

    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->id = static::ID;
    }

    /**
     * Get client identifier.
     *
     * @return  int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Get client name
     *
     * @return  string
     */
    public function getName()
    {
        return static::NAME;
    }

    /**
     * Is this admin client?
     *
     * @return  bool
     */
    public function isAdmin()
    {
        return $this->id === Administrator::ID;
    }

    /**
     * Is this site client?
     *
     * @return  bool
     */
    public function isSite()
    {
        return $this->id === Site::ID;
    }
}
