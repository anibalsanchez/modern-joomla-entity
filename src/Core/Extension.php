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

namespace Extly\Joomla\Entity\Core;

defined('_JEXEC') || die;

use Extly\Joomla\Entity\Core\Contracts\Publishable;
use Extly\Joomla\Entity\Core\Traits as CoreTraits;
use Extly\Joomla\Entity\Entity;

/**
 * Extension entity.
 *
 * @since   1.0.0
 */
class Extension extends Entity implements Publishable
{
    use CoreTraits\HasAccess;
    use CoreTraits\HasClient;
    use CoreTraits\HasParams;
    use CoreTraits\HasState;

    /**
     * Component extension type
     *
     * @const
     */
    public const TYPE_COMPONENT = 'component';

    /**
     * File extension type
     *
     * @const
     */
    public const TYPE_FILE = 'file';

    /**
     * Language extension type
     *
     * @const
     */
    public const TYPE_LANGUAGE = 'language';

    /**
     * Library extension type
     *
     * @const
     */
    public const TYPE_LIBRARY = 'library';

    /**
     * Component extension type
     *
     * @const
     */
    public const TYPE_MODULE = 'module';

    /**
     * Package extension type
     *
     * @const
     */
    public const TYPE_PACKAGE = 'package';

    /**
     * Plugin extension type
     *
     * @const
     */
    public const TYPE_PLUGIN = 'plugin';

    /**
     * Template extension type
     *
     * @const
     */
    public const TYPE_TEMPLATE = 'template';

    /**
     * Get a list of available states.
     *
     * @return  string
     */
    public function availableStates()
    {
        return [
            self::STATE_PUBLISHED   => \Joomla\CMS\Language\Text::_('JENABLED'),
            self::STATE_UNPUBLISHED => \Joomla\CMS\Language\Text::_('JDISABLED'),
        ];
    }

    /**
     * Get the list of column aliases.
     *
     * @return  array
     */
    public function columnAliases()
    {
        return [
            'published' => 'enabled',
        ];
    }

    /**
     * Check if this extension is a component.
     *
     * @return  bool
     */
    public function isComponent()
    {
        return $this->isType(self::TYPE_COMPONENT);
    }

    /**
     * Check if this extension is a file.
     *
     * @return  bool
     */
    public function isFile()
    {
        return $this->isType(self::TYPE_FILE);
    }

    /**
     * Check if this extension is a language.
     *
     * @return  bool
     */
    public function isLanguage()
    {
        return $this->isType(self::TYPE_LANGUAGE);
    }

    /**
     * Check if this extension is a library.
     *
     * @return  bool
     */
    public function isLibrary()
    {
        return $this->isType(self::TYPE_LIBRARY);
    }

    /**
     * Check if this extension is a module.
     *
     * @return  bool
     */
    public function isModule()
    {
        return $this->isType(self::TYPE_MODULE);
    }

    /**
     * Check if this extension is a package.
     *
     * @return  bool
     */
    public function isPackage()
    {
        return $this->isType(self::TYPE_PACKAGE);
    }

    /**
     * Check if this extension is a plugin.
     *
     * @return  bool
     */
    public function isPlugin()
    {
        return $this->isType(self::TYPE_PLUGIN);
    }

    /**
     * Check if this extension is a template.
     *
     * @return  bool
     */
    public function isTemplate()
    {
        return $this->isType(self::TYPE_TEMPLATE);
    }

    /**
     * Check if this extension is of a specific type.
     *
     * @param   string   $type  Type to check
     *
     * @return  bool
     */
    public function isType($type)
    {
        return strtolower($this->get('type')) === strtolower($type);
    }

    /**
     * Get entity primary key column.
     *
     * @return  string
     */
    public function primaryKey()
    {
        return 'extension_id';
    }

    /**
     * Get a table.
     *
     * @param   string  $name     The table name. Optional.
     * @param   string  $prefix   The class prefix. Optional.
     * @param   array   $options  Configuration array for model. Optional.
     *
     * @return  \JTable
     *
     * @codeCoverageIgnore
     */
    public function table($name = '', $prefix = null, $options = [])
    {
        $name = $name ?: 'Extension';
        $prefix = $prefix ?: 'JTable';

        return parent::table($name, $prefix, $options);
    }
}
