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

namespace Extly\Joomla\Entity\Content;

defined('_JEXEC') || die;

use Extly\Joomla\Entity\Entity;

/**
 * ContentType entity.
 *
 * @since   1.6.0
 */
class ContentType extends Entity
{
    /**
     * Get the alias of this content type.
     *
     * @return  string
     */
    public function alias()
    {
        return $this->get('type_alias');
    }

    /**
     * Retrieve content history options.
     *
     * @return  null|\stdClass
     */
    public function contentHistoryOptions()
    {
        return json_decode($this->get('content_history_options'));
    }

    /**
     * Get field mappings.
     *
     * @return  null|\stdClass
     */
    public function fieldMappings()
    {
        return json_decode($this->get('field_mappings'));
    }

    /**
     * Load a content type from its alias.
     *
     * @param   string  $alias  Content type alias
     *
     * @return  static
     */
    public static function fromAlias($alias)
    {
        return static::loadFromData(['type_alias' => $alias]);
    }

    /**
     * Get entity primary key column.
     *
     * @return  string
     */
    public function primaryKey()
    {
        return 'type_id';
    }

    /**
     * Get a table instance. Defauts to \JTableContent.
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
        $name = $name ?: 'Contenttype';
        $prefix = $prefix ?: 'JTable';

        return parent::table($name, $prefix, $options);
    }

    /**
     * Get table settings.
     *
     * @return  null|\stdClass
     */
    public function tableSettings()
    {
        return json_decode($this->get('table'));
    }

    /**
     * Get the title of this content type.
     *
     * @return  string
     */
    public function title()
    {
        return $this->get('type_title');
    }
}
