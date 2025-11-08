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

/**
 * Trait for linkable entities.
 *
 * @since   1.0.0
 */
trait HasLink
{
    /**
     * Link to this entity.
     *
     * @var  string
     */
    protected $link;

    /**
     * Gets the Identifier.
     *
     * @return  int
     */
    abstract public function id();

    /**
     * Get the link to this entity.
     *
     * @param   bool  $reload  Force reloading
     *
     * @return  string
     */
    public function link($reload = false)
    {
        if ($reload || null === $this->link) {
            $this->link = $this->loadLink();
        }

        return $this->link;
    }

    /**
     * Get the URL slug.
     *
     * @return  string
     */
    public function slug()
    {
        $slug = $this->id();

        if (!$slug) {
            return null;
        }

        if ($this->has('alias')) {
            $slug .= ':'.$this->get('alias');
        }

        return $slug;
    }

    /**
     * Load the link to this entity.
     *
     * @return  string
     */
    abstract protected function loadLink();
}
