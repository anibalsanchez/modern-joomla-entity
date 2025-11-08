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

namespace Extly\Joomla\Entity\Core\Extension;

defined('_JEXEC') || die;

use Extly\Joomla\Entity\Exception\InvalidEntityData;
use Extly\Joomla\Entity\Exception\LoadEntityDataError;

/**
 * Component entity.
 *
 * @since   1.0.0
 */
class ActiveComponent extends Component
{
    /**
     * Get the active option.
     *
     * @return  string
     */
    public function option()
    {
        return \Joomla\CMS\Application\ApplicationHelper::getComponentName();
    }

    /**
     * Load the entity from the database.
     *
     * @return  array
     *
     * @throws  LoadEntityDataError  Table error loading row
     * @throws  InvalidEntityData    Incorrect data received
     */
    protected function fetchRow()
    {
        $option = $this->option();

        if (!$option) {
            throw new \RuntimeException('Unable to detect active component option');
        }

        $table = $this->table();

        if (!$table->load(['element' => $option, 'type' => 'component'])) {
            throw LoadEntityDataError::tableError($this, $table->getError());
        }

        $data = $table->getProperties(true);

        if (!array_key_exists($this->primaryKey(), $data)) {
            throw InvalidEntityData::missingPrimaryKey($this);
        }

        $this->id = (int) $data[$this->primaryKey()];

        return $data;
    }
}
