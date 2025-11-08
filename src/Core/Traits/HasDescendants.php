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
 * Trait for entities with descendants.
 *
 * @since  1.4.0
 */
trait HasDescendants
{
    /**
     * Get a specific descendant by its id.
     *
     * @param   int  $id  Ascendant identifier
     *
     * @return  mixed  null || static
     *
     * @throws  \InvalidArgumentException  Descendant not found
     */
    public function descendant($id)
    {
        return $this->descendants()->get($id);
    }

    /**
     * Get the descendants of this entity.
     *
     * @return  Collection
     */
    public function descendants()
    {
        return $this->searchDescendants();
    }

    /**
     * Check if this entity has an specific descendant.
     *
     * @param   int  $id  Ascendant identifier
     *
     * @return  bool
     */
    public function hasDescendant($id)
    {
        return $this->descendants()->has($id);
    }

    /**
     * Check if this entity has descendants.
     *
     * @return  bool
     */
    public function hasDescendants()
    {
        return !$this->descendants()->isEmpty();
    }

    /**
     * Search entity descendants.
     *
     * @param   array  $options  Search options. For filters, limit, ordering, etc.
     *
     * @return  Collection
     */
    abstract public function searchDescendants(array $options = []);
}
