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
use Extly\Joomla\Entity\Core\Traits\HasDescendants;
use Extly\Joomla\Entity\Entity;

/**
 * Sample entity to test HasDescendants trait.
 *
 * @since  1.4.0
 */
class EntityWithDescendants extends Entity
{
    use HasDescendants;

    /**
     * Descendants that will be returned by searchDescendants method.
     *
     * @var  Collection
     */
    public $loadableDescendants;

    /**
     * Search entity descendants.
     *
     * @param   array  $options  Search options. For filters, limit, ordering, etc.
     *
     * @return  Collection
     */
    public function searchDescendants(array $options = [])
    {
        return $this->loadableDescendants ?: new Collection();
    }
}
