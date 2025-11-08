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

namespace Extly\Joomla\Entity\Core\Traits;

defined('_JEXEC') || die;

use Extly\Joomla\Entity\Collection;
use Extly\Joomla\Entity\Contracts\EntityInterface;

/**
 * Trait for entities with ancestors.
 *
 * @since  1.4.0
 */
trait HasAncestors
{
    /**
     * Get a specific ancestor by its id.
     *
     * @param   int  $id  Ancestor identifier
     *
     * @return  static
     *
     * @throws  \InvalidArgumentException  Ancestor not found
     */
    public function ancestor($id)
    {
        return $this->ancestors()->get($id);
    }

    /**
     * Get the Ascendants of an entity.
     *
     * @return  Collection
     */
    public function ancestors()
    {
        return $this->searchAncestors();
    }

    /**
     * Check if this entity has a specific ancestor.
     *
     * @param   int  $id  Ascendant identifier
     *
     * @return  bool
     */
    public function hasAncestor($id)
    {
        return $this->ancestors()->has($id);
    }

    /**
     * Check if this entity has ancestors.
     *
     * @return  bool
     */
    public function hasAncestors()
    {
        return !$this->ancestors()->isEmpty();
    }

    /**
     * Search entity ancestors.
     *
     * @param   array  $options  Search options. For filters, limit, ordering, etc.
     *
     * @return  Collection
     */
    abstract public function searchAncestors(array $options = []);
}
