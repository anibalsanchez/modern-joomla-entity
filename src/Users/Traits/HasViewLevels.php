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

/**
 * Trait for entities that have associated view levels.
 *
 * @since  1.2.0
 */
trait HasViewLevels
{
    /**
     * Associated view levels.
     *
     * @var  Collection
     */
    protected $viewLevels;

    /**
     * Clear already loaded view levels.
     *
     * @return  self
     */
    public function clearViewLevels()
    {
        $this->viewLevels = null;

        return $this;
    }

    /**
     * Get the associated view levels.
     *
     * @return  Collection
     */
    public function viewLevels()
    {
        if (null === $this->viewLevels) {
            $this->viewLevels = $this->loadViewLevels();
        }

        return $this->viewLevels;
    }

    /**
     * Check if this entity has an associated view level.
     *
     * @param   int   $id  View level identifier
     *
     * @return  bool
     */
    public function hasViewLevel($id)
    {
        return $this->viewLevels()->has($id);
    }

    /**
     * Check if this entity has associated view levels.
     *
     * @return  bool
     */
    public function hasViewLevels()
    {
        return !$this->viewLevels()->isEmpty();
    }

    /**
     * Load associated view levels.
     *
     * @return  Collection
     */
    abstract protected function loadViewLevels();
}
