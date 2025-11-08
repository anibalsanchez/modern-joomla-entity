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

namespace Extly\Joomla\Entity\Tests\Translation\Traits\Stubs;

use Extly\Joomla\Entity\Collection;
use Extly\Joomla\Entity\Entity;
use Extly\Joomla\Entity\Translation\Traits\HasTranslations;

/**
 * Sample entity to test HasTranslations trait.
 *
 * @since  1.1.0
 */
class EntityWithTranslations extends Entity
{
    use HasTranslations;

    /**
     * Expected translations ids for testing.
     *
     * @var  array
     */
    public $translationsIds = [];

    /**
     * Load associated translations from DB.
     *
     * @return  Collection
     */
    protected function loadTranslations()
    {
        $collection = new Collection();

        foreach ($this->translationsIds as $translationId) {
            $collection->add(new static($translationId));
        }

        return $collection;
    }
}
