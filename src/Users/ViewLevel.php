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

namespace Extly\Joomla\Entity\Users;

defined('_JEXEC') || die;

use Extly\Joomla\Entity\Collection;
use Extly\Joomla\Entity\ComponentEntity;
use Extly\Joomla\Entity\Users\Traits\HasUserGroups;

/**
 * ViewLevel entity.
 *
 * @since   1.2.0
 */
class ViewLevel extends ComponentEntity
{
    use HasUserGroups;

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
        $name = $name ?: 'ViewLevel';
        $prefix = $prefix ?: 'JTable';

        return parent::table($name, $prefix, $options);
    }

    /**
     * Load associated user groups from DB.
     *
     * @return  Collection
     */
    protected function loadUserGroups()
    {
        $collection = new Collection();

        if (!$this->has('rules')) {
            return $collection;
        }

        $rules = $this->get('rules');
        $ids = array_unique(
            array_filter(
                empty($rules) ? [] : json_decode($rules)
            )
        );

        foreach ($ids as $id) {
            $collection->add(UserGroup::find($id));
        }

        return $collection;
    }
}
