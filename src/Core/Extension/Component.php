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

use Extly\Joomla\Entity\Acl\Contracts\Aclable;
use Extly\Joomla\Entity\Acl\Traits\HasAcl;
use Extly\Joomla\Entity\Core\Extension;

/**
 * Component entity.
 *
 * @since   1.0.0
 */
class Component extends Extension implements Aclable
{
    use HasAcl;

    /**
     * Component option.
     *
     * @var  string
     */
    protected $option;

    /**
     * Component prefix for classes, etc.
     *
     * @var  string
     */
    protected $prefix;

    /**
     * Option <-> id references to avoid duplicated loading from option.
     *
     * @var  array
     */
    protected static $optionIdXref = [];

    /**
     * Get the identifier of the asset asset
     *
     * @return  string
     */
    public function aclAssetName()
    {
        return $this->option();
    }

    /**
     * Get the active component.
     *
     * @return  static
     */
    public static function active()
    {
        return new ActiveComponent();
    }

    /**
     * Return folder where this component is.
     *
     * @return  string
     */
    public function folder()
    {
        return $this->client()->getFolder().'/components/'.$this->option();
    }

    /**
     * Load a component by its option
     *
     * @param   string  $option  Component option. Example: com_content
     *
     * @return  Component
     *
     * @throws  \InvalidArgumentException  Wrong option received
     * @throws  \RuntimeException          Component not found
     */
    public static function fromOption($option)
    {
        $option = trim(strtolower($option));

        if ($option === '' || $option === '0') {
            throw new \InvalidArgumentException('Cannot load component from empty option');
        }

        if (isset(self::$optionIdXref[$option])) {
            return self::find(static::$optionIdXref[$option]);
        }

        $static = new static();
        $table = $static->table();

        if (!$table->load(['element' => $option, 'type' => 'component'])) {
            throw new \RuntimeException(sprintf('Unable to load component from option `%s`', $option));
        }

        static::$optionIdXref[$option] = (int) $table->{'extension_id'};

        return self::find($table->{'extension_id'})->bind($table->getProperties(true));
    }

    /**
     * Get a model of this component.
     *
     * @param   string  $name    Name of the model.
     * @param   array   $config  Optional array of configuration for the model
     *
     * @return  \JModelLegacy
     *
     * @throws  \InvalidArgumentException  If not found
     */
    public function model($name, array $config = ['ignore_request' => true])
    {
        $prefix = $this->prefix().'Model';

        \Joomla\CMS\MVC\Model\BaseDatabaseModel::addIncludePath($this->modelsFolder(), $prefix);

        try {
            \Joomla\CMS\Table\Table::addIncludePath($this->tablesFolder());
        } catch (\Exception $exception) {
            // There are models with no associated tables
        }

        $model = \Joomla\CMS\MVC\Model\BaseDatabaseModel::getInstance($name, $prefix, $config);

        if (!$model instanceof \JModel && !$model instanceof \JModelLegacy) {
            throw new \InvalidArgumentException(
                sprintf("Cannot find the model `%s` in `%s` component's %s folder.", $name, $this->option(), $this->client()->getName())
            );
        }

        return $model;
    }

    /**
     * Get the folder where the models are.
     *
     * @return  string
     *
     * @throws  \RuntimeException  If not found
     */
    public function modelsFolder()
    {
        $folder = $this->folder().'/models';

        if (is_dir($folder)) {
            return $folder;
        }

        $folder = $this->folder().'/model';

        if (is_dir($folder)) {
            return $folder;
        }

        throw new \RuntimeException(
            sprintf('Cannot find the models folder for `%s` component in `%s` folder.', $this->option(), $this->client()->getName())
        );
    }

    /**
     * Get this component option.
     *
     * @return  string
     */
    public function option()
    {
        if (null === $this->option) {
            $this->option = $this->get('element');
        }

        return $this->option;
    }

    /**
     * Get the component prefix.
     *
     * @return  string
     */
    public function prefix()
    {
        if (null === $this->prefix) {
            $parts = array_map(
                fn ($part) => ucfirst(strtolower($part)),
                explode('_', substr($this->option(), 4))
            );

            $this->prefix = implode('_', $parts);
        }

        return $this->prefix;
    }

    /**
     * Get a table.
     *
     * @param   string  $name     Table name. Optional.
     * @param   string  $prefix   Class prefix. Optional.
     * @param   array   $options  Configuration array for the table. Optional.
     *
     * @return  \JTable
     *
     * @throws  \InvalidArgumentException
     *
     * @aacodeCoverageIgnore
     */
    public function table($name = '', $prefix = null, $options = [])
    {
        if (!empty($name) && null === $prefix) {
            $prefix = $this->prefix().'Table';

            \Joomla\CMS\Table\Table::addIncludePath($this->tablesFolder());
        }

        return parent::table($name, $prefix, $options);
    }

    /**
     * Get the folder where the tables are stored.
     *
     * @return  string
     *
     * @throws  \RuntimeException  If not found
     */
    public function tablesFolder()
    {
        $folder = $this->folder().'/tables';

        if (is_dir($folder)) {
            return $folder;
        }

        throw new \RuntimeException(
            sprintf('Cannot find the tables folder for `%s` component in `%s` folder.', $this->option(), $this->client()->getName())
        );
    }
}
