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

namespace Extly\Joomla\Entity\Users\Traits;

defined('_JEXEC') || die;

use Extly\Joomla\Entity\Users\Column;
use Extly\Joomla\Entity\Users\User;

/**
 * Trait for entities that have an associated author.
 *
 * @since  1.0.0
 */
trait HasAuthor
{
    /**
     * Entity author.
     *
     * @var  User
     */
    protected $author;

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
     * @param   mixed   $default   Value to use as default if property is null
     *
     * @return  mixed
     *
     * @throws  \InvalidArgumentException  Property does not exist
     */
    abstract public function get($property, $default = null);

    /**
     * Get this entity author.
     *
     * @param   bool  $reload  Force data reloading
     *
     * @return  User
     */
    public function author($reload = false)
    {
        if ($reload || null === $this->author) {
            $this->author = $this->loadAuthor();
        }

        return $this->author;
    }

    /**
     * Retrieve the associated author ID.
     *
     * @return  int
     *
     * @since   1.3.0
     */
    public function authorId()
    {
        if (!$this->has($this->columnAlias(Column::AUTHOR))) {
            return 0;
        }

        return (int) $this->get($this->columnAlias(Column::AUTHOR));
    }

    /**
     * Check if this entity has an associated author.
     *
     * @return  bool
     */
    public function hasAuthor()
    {
        return 0 !== $this->authorId();
    }

    /**
     * Load entity's author.
     *
     * @return  User
     *
     * @throws  \InvalidArgumentException  Author property not found
     */
    protected function loadAuthor()
    {
        $authorId = (int) $this->get($this->columnAlias(Column::AUTHOR));

        return User::find($authorId);
    }
}
