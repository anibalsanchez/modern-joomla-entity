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

use Extly\Joomla\Entity\Contracts\EntityInterface;
use Extly\Joomla\Entity\Core\Column;

/**
 * Trait for entities with a parent entity.
 *
 * @since  1.4.0
 */
trait HasParent
{
    /**
     * Entitiy parent.
     *
     * @var  EntityInterface
     */
    protected $parent;

    /**
     * Check if this entity has an assigned parent.
     *
     * @return  bool
     *
     * @since   1.8
     */
    public function hasParent()
    {
        return $this->parentId() > 0;
    }

    /**
     * Retrieve the parent entity.
     *
     * @return  EntityInterface
     */
    public function parent()
    {
        if (null === $this->parent) {
            $this->parent = $this->loadParent();
        }

        return $this->parent;
    }

    /**
     * Retrieve parent identifier.
     *
     * @return  int
     */
    public function parentId()
    {
        $column = $this->parentColumn();

        if (!$this->has($column)) {
            return 0;
        }

        return (int) $this->get($column);
    }

    /**
     * Column used to store the parent identifier.
     *
     * @return  string
     */
    public function parentColumn()
    {
        return $this->columnAlias(Column::PARENT);
    }

    /**
     * Load the parent entity.
     *
     * @return  EntityInterface
     */
    protected function loadParent()
    {
        $column = $this->parentColumn();
        $data = $this->all();

        if (empty($data[$column])) {
            return new static();
        }

        return static::find($data[$column]);
    }
}
