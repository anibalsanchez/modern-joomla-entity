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
 * Client selector.
 *
 * @since  1.0.0
 */
abstract class Client
{
    /**
     * Retrieve the active client.
     *
     * @return  ClientInterface
     */
    public static function active()
    {
        return \Joomla\CMS\Factory::getApplication()->isAdmin() ? self::admin() : self::site();
    }

    /**
     * Retrieve admin client.
     *
     * @return  Admin
     */
    public static function admin()
    {
        return new Administrator();
    }

    /**
     * Retrieve site client.
     *
     * @return  Site
     */
    public static function site()
    {
        return new Site();
    }
}
