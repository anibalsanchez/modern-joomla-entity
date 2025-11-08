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

namespace Extly\Joomla\Entity\Users;

defined('_JEXEC') || die;

/**
 * Columns supported by users.
 *
 * @since   1.0.0
 */
abstract class Column
{
    /**
     * Default column used to store author.
     *
     * @const
     */
    public const AUTHOR = 'created_by';

    /**
     * Default column used to store editor.
     *
     * @const
     */
    public const EDITOR = 'modified_by';

    /**
     * Default column used to store owner.
     *
     * @const
     */
    public const OWNER = 'created_by';

    /**
     * Default column used to store user.
     *
     * @const
     */
    public const USER = 'user_id';
}
