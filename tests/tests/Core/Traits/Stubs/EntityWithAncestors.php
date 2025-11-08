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
use Extly\Joomla\Entity\Core\Traits\HasAncestors;
use Extly\Joomla\Entity\Entity;

/**
 * Sample entity to test HasAncestors trait.
 *
 * @since  1.4.0
 */
class EntityWithAncestors extends Entity
{
    use HasAncestors;

    /**
     * Ancestors that will be returned by searchAncestors method.
     *
     * @var  Collection
     */
    public $loadableAncestors;

    /**
     * Search entity ancestors.
     *
     * @param   array  $options  Search options. For filters, limit, ordering, etc.
     *
     * @return  Collection
     */
    public function searchAncestors(array $options = [])
    {
        return $this->loadableAncestors ?: new Collection();
    }
}
