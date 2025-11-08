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

namespace Extly\Joomla\Entity\Fields;

defined('_JEXEC') || die;

/**
 * Columns supported by fields.
 *
 * @since   1.2.0
 */
abstract class Column
{
    /**
     * Default column used to store field group.
     *
     * @const
     */
    public const FIELD_GROUP = 'group_id';
}
