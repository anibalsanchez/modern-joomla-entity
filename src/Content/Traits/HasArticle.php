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
 * Trait for entities that have an associated article.
 *
 * @since  1.0.0
 */
trait HasArticle
{
    /**
     * Associated article.
     *
     * @var  Article
     */
    protected $article;

    /**
     * Get the attached database row.
     *
     * @return  array
     */
    abstract public function all();

    /**
     * Get the associated article.
     *
     * @param   bool  $reload  Force reloading
     *
     * @return  Article
     */
    public function getArticle($reload = false)
    {
        if ($reload || null === $this->article) {
            $this->article = $this->loadArticle();
        }

        return $this->article;
    }

    /**
     * Check if this entity has an associated article.
     *
     * @return  bool
     */
    public function hasArticle()
    {
        return $this->getArticle()->hasId();
    }

    /**
     * Get the name of the column that stores article.
     *
     * @return  string
     */
    protected function getColumnArticle()
    {
        return 'article_id';
    }

    /**
     * Load the article from the database.
     *
     * @return  Article
     */
    protected function loadArticle()
    {
        $column = $this->getColumnArticle();
        $data = $this->all();

        if (array_key_exists($column, $data)) {
            return Article::find($data[$column]);
        }

        return new Article();
    }
}
