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
 * Describes methods required by modules.
 *
 * @since  0.0.1
 */
interface ClientInterface
{
    /**
     * Get the base folder of this client.
     *
     * @return  string
     */
    public function getFolder();

    /**
     * Get the identifier.
     *
     * @return  int
     */
    public function getId();

    /**
     * Get the name
     *
     * @return  string
     */
    public function getName();

    /**
     * Is this admin client?
     *
     * @return  bool
     */
    public function isAdmin();

    /**
     * Is this site client?
     *
     * @return  bool
     */
    public function isSite();
}
