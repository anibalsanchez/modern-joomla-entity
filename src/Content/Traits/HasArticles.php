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

namespace Extly\Joomla\Entity\Content\Traits;

defined('_JEXEC') || die;

use Extly\Joomla\Entity\Content\Article;

/**
 * Trait for entities that have associated articles.
 *
 * @since  1.0.0
 */
trait HasArticles
{
    /**
     * Associated articles.
     *
     * @var  Collection
     */
    protected $articles;

    /**
     * Clear already loaded articles.
     *
     * @return  self
     */
    public function clearArticles()
    {
        $this->articles = null;

        return $this;
    }

    /**
     * Get the associated articles.
     *
     * @param   bool  $reload  Force data reloading
     *
     * @return  Collection
     */
    public function articles($reload = false)
    {
        if ($reload || null === $this->articles) {
            $this->articles = $this->loadArticles();
        }

        return $this->articles;
    }

    /**
     * Check if this entity has an associated article.
     *
     * @param   int   $id  Article identifier
     *
     * @return  bool
     */
    public function hasArticle($id)
    {
        return $this->articles()->has($id);
    }

    /**
     * Check if this entity has associated articles.
     *
     * @return  bool
     */
    public function hasArticles()
    {
        return !$this->articles()->isEmpty();
    }

    /**
     * Load associated articles from DB.
     *
     * @return  Collection
     */
    abstract protected function loadArticles();
}
