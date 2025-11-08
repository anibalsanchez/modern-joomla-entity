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
 * Invalid entity data errors.
 *
 * @since  1.0.0
 */
class LoadEntityDataError extends \RuntimeException implements ExceptionInterface
{
    /**
     * Data is empty.
     *
     * @param   EntityInterface  $entity  Entity with empty data
     * @param   string           $error   Error returned by the dable
     *
     * @return  static
     */
    public static function tableError(EntityInterface $entity, $error)
    {
        return new static('Table returned an error loading '.get_class($entity).' (id: `'.$entity->id().'`) data: '.$error, 500);
    }
}
