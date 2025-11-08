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

namespace Extly\Joomla\Entity\Core\Traits;

defined('_JEXEC') || die;

/**
 * Methods for entities that have events.
 *
 * @since  1.0.0
 */
trait HasEvents
{
    /**
     * Check if
     *
     * @var  bool
     */
    protected $eventsPluginsImported = [];

    /**
     * Import a plugin type for triggered events.
     *
     * @param   string  $pluginType  Folder of the plugin
     *
     * @return  self
     */
    public function importPlugin($pluginType)
    {
        if (!in_array($pluginType, $this->eventsPluginsImported)) {
            $this->eventsPluginsImported[] = $pluginType;

            $this->importJoomlaPlugin($pluginType);
        }

        return $this;
    }

    /**
     * Trigger an entity event.
     *
     * @param   string  $event   Event to trigger
     * @param   array   $params  Optional parameters for the event
     *
     * @return  array
     */
    public function trigger($event, $params = [])
    {
        $this->importPlugins();

        array_unshift($params, $this);

        return $this->dispatcher()->trigger($event, $params);
    }

    /**
     * Get the event dispatcher.
     *
     * @return  \JEventDispatcher
     */
    protected function dispatcher()
    {
        return \JEventDispatcher::getInstance();
    }

    /**
     * Get the plugin types that will be used by this entity.
     *
     * @return  array
     */
    protected function eventsPlugins()
    {
        return ['joomla_entity'];
    }

    /**
     * Import Joomla plugin. Isolated for tests.
     *
     * @param   string  $pluginType  Plugin type to import
     *
     * @return  bool
     *
     * @codeCoverageIgnore
     */
    protected function importJoomlaPlugin($pluginType)
    {
        return \Joomla\CMS\Plugin\PluginHelper::importPlugin($pluginType);
    }

    /**
     * Import available plugins.
     *
     * @return  void
     */
    protected function importPlugins()
    {
        foreach ($this->eventsPlugins() as $plugin) {
            $this->importPlugin($plugin);
        }
    }
}
