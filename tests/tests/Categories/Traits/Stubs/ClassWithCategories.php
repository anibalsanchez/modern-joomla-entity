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

use Extly\Joomla\Entity\Categories\Category;
use Extly\Joomla\Entity\Categories\Traits\HasCategories;
use Extly\Joomla\Entity\Collection;
use Extly\Joomla\Entity\Entity;

/**
 * Sample class to test HasCategories trait.
 *
 * @since  1.1.0
 */
class ClassWithCategories extends Entity
{
    use HasCategories;

    /**
     * Expected categories ids for testing.
     *
     * @var  array
     */
    public $categoriesIds = [];

    /**
     * Load associated categories from DB.
     *
     * @return  Collection
     */
    protected function loadCategories()
    {
        $collection = new Collection();

        foreach ($this->categoriesIds as $categoryId) {
            $collection->add(new Category($categoryId));
        }

        return $collection;
    }
}
