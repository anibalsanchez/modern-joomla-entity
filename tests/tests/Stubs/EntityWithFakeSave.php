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

namespace Extly\Joomla\Entity\Tests\Stubs;

use Extly\Joomla\Entity\Entity as BaseEntity;

/**
 * Stub to test Entity class.
 *
 * @since   1.1.0
 */
class EntityWithFakeSave extends BaseEntity
{
    /**
     * Save method executed?
     *
     * @var  bool
     */
    public $saved = false;

    /**
     * Data saved in save method.
     *
     * @var  array
     */
    public $savedData = [];

    /**
     * Save entity to the database.
     *
     * @return  self
     *
     * @throws  SaveException
     */
    public function save()
    {
        $this->savedData = $this->row;
        $this->saved = true;

        return $this;
    }
}
