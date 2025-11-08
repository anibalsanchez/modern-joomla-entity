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

namespace Extly\Joomla\Entity\Fields;

defined('_JEXEC') || die;

use Extly\Joomla\Entity\Collection;
use Extly\Joomla\Entity\ComponentEntity;
use Extly\Joomla\Entity\Core\Contracts\Publishable;
use Extly\Joomla\Entity\Core\Traits as CoreTraits;
use Extly\Joomla\Entity\Fields\Column;
use Extly\Joomla\Entity\Fields\Traits\HasFields;

/**
 * Field Group entity.
 *
 * @since   1.2.0
 */
class FieldGroup extends ComponentEntity implements Publishable
{
    use CoreTraits\HasParams;
    use CoreTraits\HasState;
    use HasFields;

    /**
     * Get a table instance. Defauts to \JTableUser.
     *
     * @param   string  $name     Table name. Optional.
     * @param   string  $prefix   Class prefix. Optional.
     * @param   array   $options  Configuration array for the table. Optional.
     *
     * @return  \JTable
     *
     * @throws  \InvalidArgumentException
     */
    public function table($name = '', $prefix = null, $options = [])
    {
        $name = $name ?: 'Group';
        $prefix = $prefix ?: 'FieldsTable';

        if ($prefix === 'FieldsTable') {
            return $this->component()->table($name);
        }

        return parent::table($name, $prefix, $options);
    }

    /**
     * Load associated fields from DB.
     *
     * @return  Collection
     */
    protected function loadFields()
    {
        $collection = new Collection();

        if (!$this->hasId()) {
            return $collection;
        }

        $jDatabaseDriver = $this->getDbo();
        $query = $jDatabaseDriver->getQuery(true)
            ->select('f.*')
            ->from($jDatabaseDriver->qn('#__fields', 'f'))
            ->where($jDatabaseDriver->qn('f.group_id').' = '.(int) $this->id());

        $jDatabaseDriver->setQuery($query);

        $items = $jDatabaseDriver->loadObjectList() ?: [];

        foreach ($items as $item) {
            $field = Field::find($item->id)->bind($item);

            $collection->add($field);
        }

        return $collection;
    }
}
