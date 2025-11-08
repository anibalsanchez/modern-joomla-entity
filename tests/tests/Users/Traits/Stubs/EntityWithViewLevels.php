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

namespace Extly\Joomla\Entity\Tests\Users\Traits\Stubs;

use Extly\Joomla\Entity\Collection;
use Extly\Joomla\Entity\Entity;
use Extly\Joomla\Entity\Users\Traits\HasViewLevels;

/**
 * Sample class to test HasViewLevels trait.
 *
 * @since  1.2.0
 *
 * @codeCoverageIgnore
 */
class EntityWithViewLevels extends Entity
{
    use HasViewLevels;

    /**
     * Expected loadViewLevels result.
     *
     * @var  Collection
     */
    public $loadableViewLevels;

    /**
     * Load associated view levels.
     *
     * @return  Collection
     */
    protected function loadViewLevels()
    {
        return $this->loadableViewLevels ?? new Collection();
    }
}
