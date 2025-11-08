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

namespace Extly\Joomla\Entity\Messages;

defined('_JEXEC') || die;

use Extly\Joomla\Entity\ComponentEntity;

/**
 * Message entity.
 *
 * @since   1.6.0
 */
class Message extends ComponentEntity
{
    /**
     * Get entity primary key column.
     *
     * @return  string
     */
    public function primaryKey()
    {
        return 'message_id';
    }

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
        \Joomla\CMS\Table\Table::addIncludePath(JPATH_ADMINISTRATOR.'/components/com_messages/tables');

        $name = $name ?: 'Message';
        $prefix = $prefix ?: 'MessagesTable';

        return parent::table($name, $prefix, $options);
    }
}
