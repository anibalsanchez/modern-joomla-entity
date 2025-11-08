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

/**
 * Trait for entities that have associated categories.
 *
 * @since  1.0.0
 */
trait HasCategories
{
    /**
     * Associated categories.
     *
     * @var  Collection
     */
    protected $categories;

    /**
     * Get the associated categories.
     *
     * @param   bool  $reload  Force data reloading
     *
     * @return  Collection
     */
    public function categories($reload = false)
    {
        if ($reload || null === $this->categories) {
            $this->categories = $this->loadCategories();
        }

        return $this->categories;
    }

    /**
     * Clear already loaded categories.
     *
     * @return  self
     */
    public function clearCategories()
    {
        $this->categories = null;

        return $this;
    }

    /**
     * Check if this entity has an associated category.
     *
     * @param   int   $id  Category identifier
     *
     * @return  bool
     */
    public function hasCategory($id)
    {
        return $this->categories()->has($id);
    }

    /**
     * Check if this entity has associated categories.
     *
     * @return  bool
     */
    public function hasCategories()
    {
        return !$this->categories()->isEmpty();
    }

    /**
     * Load associated categories from DB.
     *
     * @return  Collection
     */
    abstract protected function loadCategories();
}
