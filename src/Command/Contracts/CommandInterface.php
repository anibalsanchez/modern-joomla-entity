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

namespace Extly\Joomla\Entity\Command\Contracts;

defined('_JEXEC') || die;

/**
 * Describes methods required by commands.
 *
 * @since  1.8
 */
interface CommandInterface
{
    /**
     * Execute the command.
     *
     * @return  mixed
     */
    public function execute();
}
