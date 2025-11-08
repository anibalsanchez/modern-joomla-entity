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

namespace Extly\Joomla\Entity\Tests\Core\Traits\Stubs;

use Extly\Joomla\Entity\Collection;
use Extly\Joomla\Entity\Core\Traits\HasAssociations;
use Extly\Joomla\Entity\Entity;

/**
 * Sample entity to test HasAssociations trait.
 *
 * @since  1.1.0
 *
 * @codeCoverageIgnore
 */
class EntityWithAssociations extends Entity
{
    use HasAssociations;

    /**
     * Expected translations ids for testing.
     *
     * @var  array
     */
    public $associationsIds = [];

    /**
     * Load associations from DB.
     *
     * @return  static[]
     */
    protected function loadAssociations()
    {
        $associations = [];

        foreach ($this->associationsIds as $langTag => $id) {
            $associations[$langTag] = new static($id);
        }

        return $associations;
    }
}
