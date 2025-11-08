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

namespace Extly\Joomla\Entity\Content;

defined('_JEXEC') || die;

use Extly\Joomla\Entity\Acl\Contracts\Aclable;
use Extly\Joomla\Entity\Acl\Traits\HasAcl;
use Extly\Joomla\Entity\Categories\Category as BaseCategory;
use Extly\Joomla\Entity\Collection;
use Extly\Joomla\Entity\Content\Article;
use Extly\Joomla\Entity\Content\Search\ArticleSearch;
use Extly\Joomla\Entity\Content\Traits\HasArticles;
use Extly\Joomla\Entity\Core\Traits as CoreTraits;
use Extly\Joomla\Entity\Core\Traits\HasLink;
use Extly\Joomla\Entity\Tags\Tag;
use Extly\Joomla\Entity\Tags\Traits\HasTags;
use Joomla\Registry\Registry;

/**
 * Content category entity.
 *
 * @since   1.0.0
 */
class Category extends BaseCategory implements Aclable
{
    use HasArticles;
    use HasAcl;
    use HasLink;
    use HasTags;

    /**
     * Retrieve the alias of content type associated with this entity.
     *
     * @return  string
     *
     * @since   1.6.0
     */
    public static function contentTypeAlias()
    {
        return 'com_content.category';
    }

    /**
     * Search within this entity tags.
     *
     * @param   array   $options  Search options
     *
     * @return  Collection
     *
     * @since   1.7.0
     */
    public function searchArticles(array $options = [])
    {
        if (!$this->hasId()) {
            return new Collection();
        }

        $options['filter.category_id'] = $this->id();

        return Collection::fromData(
            ArticleSearch::instance($options)->search(),
            Article::class
        );
    }

    /**
     * Load associated articles from DB.
     *
     * @return  Collection
     */
    protected function loadArticles()
    {
        if (!$this->hasId()) {
            return new Collection();
        }

        $articles = array_map(
            fn ($item) => Article::find($item->id)->bind($item),
            $this->getArticlesModel()->getItems() ?: []
        );

        return new Collection($articles);
    }

    /**
     * Load the link to this entity.
     *
     * @return  string
     *
     * @codeCoverageIgnore
     */
    protected function loadLink()
    {
        $slug = $this->slug();

        if (!$slug) {
            return null;
        }

        \JLoader::register('ContentHelperRoute', JPATH_SITE.'/components/com_content/helpers/route.php');

        return \Joomla\CMS\Router\Route::_(\Joomla\Component\Content\Site\Helper\RouteHelper::getCategoryRoute($slug));
    }

    /**
     * Get an instance of the articles model.
     *
     * @return  \JModelList
     */
    protected function getArticlesModel()
    {
        \Joomla\CMS\MVC\Model\BaseDatabaseModel::addIncludePath(JPATH_SITE.'/components/com_content/models', 'ContentModel');

        $model = \Joomla\CMS\MVC\Model\BaseDatabaseModel::getInstance('Articles', 'ContentModel', ['ignore_request' => true]);

        $model->setState('params', new Registry());

        if ($this->hasId()) {
            $model->setState('filter.category_id', $this->id());
        }

        return $model;
    }
}
