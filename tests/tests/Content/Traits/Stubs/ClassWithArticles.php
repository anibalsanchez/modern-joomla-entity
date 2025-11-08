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

namespace Extly\Joomla\Entity\Tests\Content\Traits\Stubs;

use Extly\Joomla\Entity\Collection;
use Extly\Joomla\Entity\Content\Article;
use Extly\Joomla\Entity\Content\Traits\HasArticles;
use Extly\Joomla\Entity\Entity;

/**
 * Sample class to test HasArticles trait.
 *
 * @since  1.1.0
 */
class ClassWithArticles extends Entity
{
    use HasArticles;

    /**
     * Expected articles ids for testing.
     *
     * @var  array
     */
    public $articlesIds = [];

    /**
     * Load associated articles from DB.
     *
     * @return  Collection
     */
    protected function loadArticles()
    {
        $collection = new Collection();

        foreach ($this->articlesIds as $articleId) {
            $collection->add(new Article($articleId));
        }

        return $collection;
    }
}
