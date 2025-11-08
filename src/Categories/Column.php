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

namespace Extly\Joomla\Entity\Categories;

defined('_JEXEC') || die;

/**
 * Columns supported by categories.
 *
 * @since   1.7.2
 */
abstract class Column
{
    /**
     * Default column used to store category.
     *
     * @const
     */
    public const CATEGORY = 'category_id';
}
