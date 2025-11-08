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

namespace Extly\Joomla\Entity\Content\Search;

defined('_JEXEC') || die;

use Extly\Joomla\Entity\Categories\Search\CategorySearch as BaseCategorySearch;
use Extly\Joomla\Entity\Content\Category;
use Joomla\Utilities\ArrayHelper;

/**
 * Category search.
 *
 * @since  1.6.0
 */
class CategorySearch extends BaseCategorySearch
{
    /**
     * Retrieve the search query.
     *
     * @return  \JDatabaseQuery
     */
    public function searchQuery()
    {
        $db = $this->db;

        $query = parent::searchQuery()
            ->where($db->qn('c.extension').' = '.$db->q('com_content'));

        // Filter: tag
        if (null !== $this->options->get('filter.tag_id')) {
            $tagIds = ArrayHelper::toInteger((array) $this->options->get('filter.tag_id'));

            $query->leftJoin(
                $db->quoteName('#__contentitem_tag_map', 'tagmap')
                .' ON '.$db->quoteName('tagmap.content_item_id').' = '.$db->quoteName('c.id')
                .' AND '.$db->quoteName('tagmap.type_alias').' = '.$db->quote(Category::contentTypeAlias())
            )->where($db->qn('tagmap.tag_id').' IN('.implode(',', $tagIds).')');
        }

        return $query;
    }
}
