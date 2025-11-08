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

namespace Extly\Joomla\Entity\Categories;

defined('_JEXEC') || die;

use Extly\Joomla\Entity\Categories\Validation\CategoryValidator;
use Extly\Joomla\Entity\Collection;
use Extly\Joomla\Entity\ComponentEntity;
use Extly\Joomla\Entity\Core\Contracts\Publishable;
use Extly\Joomla\Entity\Core\Traits as CoreTraits;
use Extly\Joomla\Entity\Traits as EntityTraits;
use Extly\Joomla\Entity\Translation\Contracts\Translatable;
use Extly\Joomla\Entity\Translation\Traits\HasTranslations;
use Extly\Joomla\Entity\Users\Traits as UsersTraits;
use Extly\Joomla\Entity\Validation\Contracts\Validable;
use Extly\Joomla\Entity\Validation\Traits\HasValidation;
use Joomla\CMS\Factory;
use Joomla\Utilities\ArrayHelper;

/**
 * Stub to test Entity class.
 *
 * @since   1.0.0
 */
class Category extends ComponentEntity implements Publishable, Translatable, Validable
{
    use CoreTraits\HasAccess;
    use CoreTraits\HasAncestors;
    use CoreTraits\HasAsset;
    use CoreTraits\HasAssociations;
    use CoreTraits\HasChildren;
    use CoreTraits\HasDescendants;
    use CoreTraits\HasLevel;
    use CoreTraits\HasMetadata;
    use CoreTraits\HasParams;
    use CoreTraits\HasParent;
    use CoreTraits\HasState;
    use HasTranslations;
    use HasValidation;
    use UsersTraits\HasAuthor;
    use UsersTraits\HasEditor;

    /**
     * Get the list of column aliases.
     *
     * @return  array
     */
    public function columnAliases()
    {
        return [
            'created_by'  => 'created_user_id',
            'modified_by' => 'modified_user_id',
        ];
    }

    /**
     * Get a table.
     *
     * @param   string  $name     The table name. Optional.
     * @param   string  $prefix   The class prefix. Optional.
     * @param   array   $options  Configuration array for model. Optional.
     *
     * @return  \JTable
     */
    public function table($name = '', $prefix = null, $options = [])
    {
        \Joomla\CMS\Table\Table::addIncludePath(JPATH_ADMINISTRATOR.'/components/com_categories/tables');

        $name = $name ?: 'Category';
        $prefix = $prefix ?: 'CategoriesTable';

        return parent::table($name, $prefix, $options);
    }

    /**
     * Search entity ancestors.
     *
     * @param   array  $options  Search options. For filters, limit, ordering, etc.
     *
     * @return  Collection
     */
    public function searchAncestors(array $options = [])
    {
        if (!$this->hasId()) {
            return new Collection();
        }

        $options = array_merge(['list.limit' => 0], $options);
        $options['filter.descendant_id'] = $this->id();

        return Collection::fromData(CategorySearcher::instance($options)->search(), self::class);
    }

    /**
     * Search entity children.
     *
     * @param   array  $options  Search options. For filters, limit, ordering, etc.
     *
     * @return  Collection
     */
    public function searchChildren(array $options = [])
    {
        if (!$this->hasId()) {
            return new Collection();
        }

        $options = array_merge(['list.limit' => 0], $options);
        $options['filter.parent_id'] = $this->id();

        return Collection::fromData(CategorySearcher::instance($options)->search(), self::class);
    }

    /**
     * Search entity descendants.
     *
     * @param   array  $options  Search options. For filters, limit, ordering, etc.
     *
     * @return  Collection
     */
    public function searchDescendants(array $options = [])
    {
        if (!$this->hasId()) {
            return new Collection();
        }

        $options = array_merge(['list.limit' => 0], $options);
        $options['filter.ancestor_id'] = $this->id();

        return Collection::fromData(CategorySearcher::instance($options)->search(), self::class);
    }

    /**
     * Retrieve entity validator.
     *
     * @return  CategoryValidator
     *
     * @since   1.7.0
     */
    public function validator()
    {
        if (null === $this->validator) {
            $this->validator = new CategoryValidator($this);
        }

        return $this->validator;
    }

    /**
     * Load associations from DB.
     *
     * @return  array
     *
     * @codeCoverageIgnore
     */
    protected function loadAssociations()
    {
        if (!$this->hasId()) {
            return [];
        }

        return \Joomla\CMS\Language\Associations::getAssociations(
            $this->get('extension'),
            '#__categories',
            'com_categories.item',
            $this->id(),
            'id',
            'alias',
            ''
        );
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

        $jDatabaseDriver = $this->getDbo();

        $query = $jDatabaseDriver->getQuery(true)
            ->select('c.*')
            ->from($jDatabaseDriver->qn('#__categories', 'c'))
            ->where('c.id IN ('.implode(',', ArrayHelper::toInteger($ids)).')');

        $jDatabaseDriver->setQuery($query);

        $categories = array_map(
            fn ($item) => static::find($item->id)->bind($item),
            $jDatabaseDriver->loadObjectList() ?: []
        );

        return new Collection($categories);
    }
}
