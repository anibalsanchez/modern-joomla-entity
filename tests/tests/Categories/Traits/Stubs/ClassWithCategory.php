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

namespace Extly\Joomla\Entity\Tests\Categories\Traits\Stubs;

use Extly\Joomla\Entity\Categories\Traits\HasCategory;
use Extly\Joomla\Entity\Entity;

/**
 * Sample class to test HasCategory trait.
 *
 * @since  1.1.0
 */
class ClassWithCategory extends Entity
{
    use HasCategory;

    /**
     * Get the list of column aliases.
     *
     * @return  array
     */
    public function columnAliases()
    {
        return [
            'category_id' => 'category_id',
        ];
    }
}
