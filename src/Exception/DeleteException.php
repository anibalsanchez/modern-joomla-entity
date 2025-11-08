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

namespace Extly\Joomla\Entity\Exception;

defined('_JEXEC') || die;

use Extly\Joomla\Entity\Contracts\EntityInterface;
use Extly\Joomla\Entity\Contracts\ExceptionInterface;

/**
 * Errors deleting an entity.
 *
 * @since  1.2.0
 */
class DeleteException extends \RuntimeException implements ExceptionInterface
{
    /**
     * Entity cannot be saved.
     *
     * @param   EntityInterface  $entity  Entity with empty data
     * @param \JTable $jTable Table containing the entity data
     *
     * @return  static
     */
    public static function fromTable(EntityInterface $entity, \JTable $jTable)
    {
        $msg = sprintf('Delete failed trying to delete `%s`:</br> %s', $entity->name().'::'.$entity->id(), $jTable->getError());

        return new static($msg, 500);
    }
}
