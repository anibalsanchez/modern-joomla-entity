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

use Extly\Joomla\Entity\Core\Extension\Component;

/**
 * Trait for entities with an associated component.
 *
 * @since   1.0.0
 */
trait HasComponent
{
    /**
     * Entity component
     *
     * @var  Component
     */
    protected $component;

    /**
     * Component option.
     *
     * @var string
     */
    protected $componentOption;

    /**
     * Retrieve the associated component.
     *
     * @return  Component
     */
    public function component()
    {
        if (null === $this->component) {
            $this->component = $this->loadComponent();
        }

        return $this->component;
    }

    /**
     * Try to guess component option from class prefix
     *
     * @return  mixed  null (not found) | string (found)
     */
    protected function componentOption()
    {
        if (null === $this->componentOption) {
            $this->componentOption = $this->componentOptionFromClass();
        }

        return $this->componentOption;
    }

    /**
     * Try to guess component option from class.
     *
     * @return  string
     */
    protected function componentOptionFromClass()
    {
        $class = get_class($this);

        if (str_contains($class, '\\')) {
            $suffix = rtrim(strstr($class, 'Entity'), '\\');
            $parts = explode('\\', $suffix);

            return array_key_exists(1, $parts) ? 'com_'.strtolower($parts[1]) : null;
        }

        return  'com_'.strtolower(strstr($class, 'Entity', true));
    }

    /**
     * Load associated component
     *
     * @return  Component
     *
     * @throws  \InvalidArgumentException  Wrong option received
     * @throws  \RuntimeException          Component not found
     */
    protected function loadComponent()
    {
        return Component::fromOption($this->componentOption());
    }
}
