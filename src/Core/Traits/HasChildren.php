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
 * Trait for entities with child entities.
 *
 * @since  1.4.0
 */
trait HasChildren
{
    /**
     * Get a specific child by its id.
     *
     * @param   int  $id  Ascendant identifier
     *
     * @return  mixed  null || static
     *
     * @throws  \InvalidArgumentException  Descendant not found
     */
    public function child($id)
    {
        return $this->children()->get($id);
    }

    /**
     * Get the children of this entity.
     *
     * @return  Collection
     */
    public function children()
    {
        return $this->searchChildren();
    }

    /**
     * Check if this entity has an specific child.
     *
     * @param   int  $id  Ascendant identifier
     *
     * @return  bool
     */
    public function hasChild($id)
    {
        return $this->children()->has($id);
    }

    /**
     * Check if this entity has children.
     *
     * @return  bool
     */
    public function hasChildren()
    {
        return !$this->children()->isEmpty();
    }

    /**
     * Search entity children.
     *
     * @param   array  $options  Search options. For filters, limit, ordering, etc.
     *
     * @return  Collection
     */
    abstract public function searchChildren(array $options = []);
}
