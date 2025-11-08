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

namespace Extly\Joomla\Entity\Tests\Tags\Traits\Stubs;

use Extly\Joomla\Entity\Collection;
use Extly\Joomla\Entity\Entity;
use Extly\Joomla\Entity\Tags\Tag;
use Extly\Joomla\Entity\Tags\Traits\HasTags;

/**
 * Sample class to test HasTags trait.
 *
 * @since  1.1.0
 */
class ClassWithTags extends Entity
{
    use HasTags;

    /**
     * Expected tags ids for testing.
     *
     * @var  array
     */
    public $tagsIds = [];

    /**
     * Load associated tags from DB.
     *
     * @return  Collection
     */
    protected function loadTags()
    {
        $collection = new Collection();

        foreach ($this->tagsIds as $tagId) {
            $collection->add(new Tag($tagId));
        }

        return $collection;
    }
}
