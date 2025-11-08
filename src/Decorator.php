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

namespace Extly\Joomla\Entity;

defined('_JEXEC') || die;

use Extly\Joomla\Entity\Contracts\EntityInterface;

/**
 * Represents a collection of entities.
 *
 * @since   1.0.0
 */
abstract class Decorator
{
    /**
     * Decorated entity.
     *
     * @var  EntityInterface
     */
    protected $entity;

    /**
     * Constructor.
     *
     * @param   EntityInterface  $entity  Entity to decorate.
     */
    public function __construct(EntityInterface $entity)
    {
        $this->entity = $entity;
    }
}
