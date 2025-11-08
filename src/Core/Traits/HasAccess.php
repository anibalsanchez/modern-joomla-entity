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
 * Trait for entities with access column.
 *
 * @since   1.0.0
 */
trait HasAccess
{
    /**
     * Can current user access this entity?
     *
     * @var  bool
     */
    protected $access;

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
     * Check if this entity has an id.
     *
     * @return  bool
     */
    abstract public function hasId();

    /**
     * Get access level required for this entity.
     *
     * @return  int
     */
    public function access()
    {
        return (int) $this->get($this->columnAlias(Column::ACCESS));
    }

    /**
     * Can current user access this entity?
     *
     * @param   bool  $reload  Force reloading
     *
     * @return  bool
     */
    public function canAccess($reload = false)
    {
        if ($reload || null === $this->access) {
            $this->access = $this->checkAccess();
        }

        return $this->access;
    }

    /**
     * Check access to this entity.
     *
     * @return  bool
     *
     * @codeCoverageIgnore
     */
    protected function checkAccess()
    {
        if (!$this->hasId()) {
            return false;
        }

        $authorised = \Joomla\CMS\Access\Access::getAuthorisedViewLevels(\Joomla\CMS\Factory::getUser()->get('id'));

        return in_array($this->access(), $authorised);
    }
}
