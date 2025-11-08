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
 * Trait for entities that have an associated publish down column.
 *
 * @since  1.0.0
 */
trait HasPublishDown
{
    /**
     * Get the publish down date.
     *
     * @return  string
     */
    public function getPublishDown()
    {
        return $this->get($this->columnAlias(Column::PUBLISH_DOWN));
    }

    /**
     * Has this entity a publish down date?
     *
     * @return  bool
     */
    public function hasPublishDown()
    {
        $publishDown = $this->getPublishDown();

        return !empty($publishDown) && $publishDown !== $this->nullDate();
    }

    /**
     * Check if this entity is published down.
     *
     * @return  bool
     */
    public function isPublishedDown()
    {
        if (!$this->hasPublishDown()) {
            return false;
        }

        return \Joomla\CMS\Factory::getDate($this->getPublishDown()) <= \Joomla\CMS\Factory::getDate();
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
