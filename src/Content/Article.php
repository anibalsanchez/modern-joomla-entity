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
use Extly\Joomla\Entity\Categories\Traits\HasCategory;
use Extly\Joomla\Entity\Collection;
use Extly\Joomla\Entity\ComponentEntity;
use Extly\Joomla\Entity\Content\Category;
use Extly\Joomla\Entity\Content\Validation\ArticleValidator;
use Extly\Joomla\Entity\Core\Contracts\Publishable;
use Extly\Joomla\Entity\Core\Traits as CoreTraits;
use Extly\Joomla\Entity\Fields\Field;
use Extly\Joomla\Entity\Fields\Traits\HasFields;
use Extly\Joomla\Entity\Tags\Tag;
use Extly\Joomla\Entity\Tags\Traits\HasTags;
use Extly\Joomla\Entity\Translation\Contracts\Translatable;
use Extly\Joomla\Entity\Translation\Traits\HasTranslations;
use Extly\Joomla\Entity\Users\Contracts\Ownerable;
use Extly\Joomla\Entity\Users\Traits as UsersTraits;
use Extly\Joomla\Entity\Validation\Contracts\Validable;
use Extly\Joomla\Entity\Validation\Traits\HasValidation;
use Joomla\Registry\Registry;

/**
 * Article entity.
 *
 * @since   1.0.0
 */
class Article extends ComponentEntity implements Aclable, Ownerable, Publishable, Translatable, Validable
{
    use HasAcl;
    use HasCategory;
    use HasFields;
    use HasTags;
    use HasTranslations;
    use HasValidation;
    use CoreTraits\HasAccess;
    use CoreTraits\HasAsset;
    use CoreTraits\HasAssociations;
    use CoreTraits\HasFeatured;
    use CoreTraits\HasMetadata;
    use CoreTraits\HasImages;
    use CoreTraits\HasLink;
    use CoreTraits\HasParams;
    use CoreTraits\HasPublishDown;
    use CoreTraits\HasPublishUp;
    use CoreTraits\HasState;
    use CoreTraits\HasUrls;
    use UsersTraits\HasAuthor;
    use UsersTraits\HasEditor;
    use UsersTraits\HasOwner;

    /**
     * Get the list of column aliases.
     *
     * @return  array
     */
    public function columnAliases()
    {
        return [
            'category_id' => 'catid',
            'params'      => 'attribs',
        ];
    }

    /**
     * Retrieve the alias of content type associated with this entity.
     *
     * @return  string
     *
     * @since   1.6.0
     */
    public static function contentTypeAlias()
    {
        return 'com_content.article';
    }

    /**
     * Check if this entity is published.
     *
     * @return  bool
     */
    public function isPublished()
    {
        if (!$this->isOnState(self::STATE_PUBLISHED)) {
            return false;
        }

        if (!$this->isPublishedUp() || $this->isPublishedDown()) {
            return false;
        }

        return $this->category()->isPublished();
    }

    /**
     * Get a table instance. Defauts to \JTableContent.
     *
     * @param   string  $name     Table name. Optional.
     * @param   string  $prefix   Class prefix. Optional.
     * @param   array   $options  Configuration array for the table. Optional.
     *
     * @return  \JTable
     *
     * @throws  \InvalidArgumentException
     */
    public function table($name = '', $prefix = null, $options = [])
    {
        $name = $name ?: 'Content';
        $prefix = $prefix ?: 'JTable';

        return parent::table($name, $prefix, $options);
    }

    /**
     * Retrieve entity validator.
     *
     * @return  ArticleValidator
     */
    public function validator()
    {
        if (null === $this->validator) {
            $this->validator = new ArticleValidator($this);
        }

        return $this->validator;
    }

    /**
     * Get an instance of the articles model.
     *
     * @param   array  $state  State to populate in the model
     *
     * @return  \JModelList
     */
    protected function getArticlesModel(array $state = [])
    {
        \Joomla\CMS\MVC\Model\BaseDatabaseModel::addIncludePath(JPATH_SITE.'/components/com_content/models', 'ContentModel');

        $model = \Joomla\CMS\MVC\Model\BaseDatabaseModel::getInstance('Articles', 'ContentModel', ['ignore_request' => true]);

        $params = $state['params'] ?? new Registry();

        $model->setState('params', $params);

        foreach ($state as $key => $value) {
            $model->setState($key, $value);
        }

        return $model;
    }

    /**
     * Load associations from DB.
     *
     * @return  \stdClass[]
     *
     * @codeCoverageIgnore
     */
    protected function loadAssociations()
    {
        if (!$this->hasId()) {
            return [];
        }

        return \Joomla\CMS\Language\Associations::getAssociations('com_content', '#__content', 'com_content.item', $this->id());
    }

    /**
     * Load the category from the database.
     *
     * @return  Category
     */
    protected function loadCategory()
    {
        $column = $this->columnAlias('category_id');
        $data = $this->all();

        if (array_key_exists($column, $data)) {
            return Category::find($data[$column]);
        }

        return new Category();
    }

    /**
     * Load the link to this entity.
     *
     * @return  atring
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

        return \Joomla\CMS\Router\Route::_(\Joomla\Component\Content\Site\Helper\RouteHelper::getArticleRoute($slug, (int) $this->get('catid'), $this->get('language')));
    }

    /**
     * Load associated translations from DB.
     *
     * @return  Collection
     */
    protected function loadTranslations()
    {
        $ids = $this->associationsIds();

        if (empty($ids)) {
            return new Collection();
        }

        $state = [
            'filter.article_id' => array_values($ids),
        ];

        $articles = array_map(
            fn ($item) => static::find($item->id)->bind($item),
            $this->getArticlesModel($state)->getItems() ?: []
        );

        return new Collection($articles);
    }
}
