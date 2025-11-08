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

namespace Extly\Joomla\Entity\Core\Contracts;

defined('_JEXEC') || die;

/**
 * Publishable entities requirements.
 *
 * @since   1.0.0
 */
interface Publishable
{
    /**
     * Check if this entity is published.
     *
     * @return  bool
     */
    public function isPublished();
}
