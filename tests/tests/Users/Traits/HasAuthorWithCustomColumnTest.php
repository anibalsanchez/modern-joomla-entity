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

namespace Extly\Joomla\Entity\Tests\Users\Traits;

use Extly\Joomla\Entity\Tests\Users\Traits\Stubs\EntityWithAuthorAndEditor;
use Extly\Joomla\Entity\Users\Traits\HasAuthor;
use Extly\Joomla\Entity\Users\User;

/**
 * HasAuthor trait tests for entities with custom author column.
 *
 * @since   1.1.0
 */
class HasAuthorWithCustomColumnTest extends HasAuthorTest
{
    /**
     * Name of the author column.
     *
     * @const
     */
    public const AUTHOR_COLUMN = 'created_user_id';
}
