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
 * Trait for entities that have an associated publish up column.
 *
 * @since  1.0.0
 */
trait HasPublishUp
{
    /**
     * Get the publish up date.
     *
     * @return  string
     */
    public function getPublishUp()
    {
        return $this->get($this->columnAlias(Column::PUBLISH_UP));
    }

    /**
     * Has this entity a publish up date?
     *
     * @return  bool
     */
    public function hasPublishUp()
    {
        $publishUp = $this->getPublishUp();

        return !empty($publishUp) && $publishUp !== $this->nullDate();
    }

    /**
     * Check if this entity is published up.
     *
     * @return  bool
     */
    public function isPublishedUp()
    {
        if (!$this->hasPublishUp()) {
            return true;
        }

        return \Joomla\CMS\Factory::getDate($this->getPublishUp()) <= \Joomla\CMS\Factory::getDate();
    }

    /**
     * Get the empty date for the active DB driver.
     *
     * @return  string
     *
     * @codeCoverageIgnore
     */
    abstract protected function nullDate();
}
