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

use Extly\Joomla\Entity\Core\Column;

/**
 * Trait for entities with featured column.
 *
 * @since   1.0.0
 */
trait HasFeatured
{
    /**
     * Is this entity featured.
     *
     * @var  bool
     */
    protected $featured;

    /**
     * Get the alias for a specific DB column.
     *
     * @param   string  $column  Name of the DB column. Example: created_by
     *
     * @return  string
     */
    abstract public function columnAlias($column);

    /**
     * Get a property of this entity.
     *
     * @param   string  $property  Name of the property to get
     * @param   mixed   $default   Value to use as default if property is not set or is null
     *
     * @return  mixed
     */
    abstract public function get($property, $default = null);

    /**
     * Is this article featured?
     *
     * @return  bool
     */
    public function isFeatured()
    {
        $featured = (int) $this->get($this->columnAlias(Column::FEATURED));

        return (bool) $featured;
    }
}
