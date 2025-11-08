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
 * Trait for entities with state.
 *
 * @since   1.0.0
 */
trait HasState
{
    /**
     * Entity state.
     *
     * @var  int
     */
    protected $state;

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
     * Get a list of available states.
     *
     * @return  string
     */
    public function availableStates()
    {
        return [
            self::STATE_PUBLISHED   => \Joomla\CMS\Language\Text::_('JPUBLISHED'),
            self::STATE_UNPUBLISHED => \Joomla\CMS\Language\Text::_('JUNPUBLISHED'),
            self::STATE_ARCHIVED    => \Joomla\CMS\Language\Text::_('JARCHIVEDSTATE_ARCHIVED'),
            self::STATE_TRASHED     => \Joomla\CMS\Language\Text::_('JTRASHED'),
        ];
    }

    /**
     * Check if this entity is archived.
     *
     * @return  bool
     */
    public function isArchived()
    {
        return $this->isOnState(self::STATE_ARCHIVED);
    }

    /**
     * Check if this entity is disabled.
     * This is just a proxy for entities that use enabled/disabled instead of published/unpublished.
     *
     * @return  bool
     */
    public function isDisabled()
    {
        return $this->isUnpublished();
    }

    /**
     * Check if this entity is enabled.
     * This is just a proxy for entities that use enabled/disabled instead of published/unpublished.
     *
     * @return  bool
     */
    public function isEnabled()
    {
        return $this->isPublished();
    }

    /**
     * Check if this entity is on a specific state.
     *
     * @param   int   $state  State to check.
     *
     * @return  bool
     */
    public function isOnState($state)
    {
        return $this->state() === (int) $state;
    }

    /**
     * Check if this entity is published.
     *
     * @return  bool
     */
    public function isPublished()
    {
        return $this->isOnState(self::STATE_PUBLISHED);
    }

    /**
     * Check if this entity is trashed.
     *
     * @return  bool
     */
    public function isTrashed()
    {
        return $this->isOnState(self::STATE_TRASHED);
    }

    /**
     * Check if this entity is unpublished.
     *
     * @return  bool
     */
    public function isUnpublished()
    {
        return $this->isOnState(self::STATE_UNPUBLISHED);
    }

    /**
     * Get state of this entity.
     *
     * @return  int
     */
    public function state()
    {
        return (int) $this->get($this->columnAlias(Column::STATE));
    }
}
