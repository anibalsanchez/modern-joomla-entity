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

namespace Extly\Joomla\Entity\Core;

defined('_JEXEC') || die;

use Extly\Joomla\Entity\Entity;

/**
 * Asset entity.
 *
 * @since   1.0.0
 */
class Asset extends Entity
{
    /**
     * Get a table.
     *
     * @param   string  $name     The table name. Optional.
     * @param   string  $prefix   The class prefix. Optional.
     * @param   array   $options  Configuration array for model. Optional.
     *
     * @return  \JTable
     *
     * @codeCoverageIgnore
     */
    public function table($name = '', $prefix = null, $options = [])
    {
        $name = $name ?: 'Asset';
        $prefix = $prefix ?: 'JTable';

        return parent::table($name, $prefix, $options);
    }
}
