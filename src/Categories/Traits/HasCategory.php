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

namespace Extly\Joomla\Entity\Categories\Traits;

defined('_JEXEC') || die;

use Extly\Joomla\Entity\Categories\Category;
use Extly\Joomla\Entity\Categories\Column;

/**
 * Trait for entities that have an asset. Based on category_id|catid column.
 *
 * @since  1.0.0
 */
trait HasCategory
{
    /**
     * Associated category.
     *
     * @var  Category
     */
    protected $category;

    /**
     * Get the attached database row.
     *
     * @return  array
     */
    abstract public function all();

    /**
     * Get the associated category.
     *
     * @param   bool  $reload  Force reloading
     *
     * @return  Category
     */
    public function category($reload = false)
    {
        if ($reload || null === $this->category) {
            $this->category = $this->loadCategory();
        }

        return $this->category;
    }

    /**
     * Get associated category identifier.
     *
     * @return  int
     *
     * @since   1.7.2
     */
    public function categoryId()
    {
        $column = $this->getColumnCategory();

        if (!$this->has($column)) {
            return 0;
        }

        return (int) $this->get($column);
    }

    /**
     * Get the name of the column that stores category.
     *
     * @return  string
     *
     * @deprecated  1.7.2  Use column aliases
     */
    protected function getColumnCategory()
    {
        return $this->columnAlias(Column::CATEGORY);
    }

    /**
     * Load the category from the database.
     *
     * @return  Category
     */
    protected function loadCategory()
    {
        $id = $this->categoryId();

        return $id ? Category::find($id) : new Category();
    }
}
