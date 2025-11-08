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
 * Backend client.
 *
 * @since  1.0.0
 */
final class Administrator extends BaseClient implements ClientInterface
{
    /**
     * Client identifier.
     *
     * @const
     */
    public const ID = 1;

    /**
     * Client name.
     *
     * @const
     */
    public const NAME = 'Administrator';

    /**
     * Get the base folder of this client.
     *
     * @return  string
     */
    public function getFolder()
    {
        return JPATH_ADMINISTRATOR;
    }
}
