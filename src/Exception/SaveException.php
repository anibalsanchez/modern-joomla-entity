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
use Extly\Joomla\Entity\Validation\Exception\ValidationException;

/**
 * Errors saving entity.
 *
 * @since  1.0.0
 */
class SaveException extends \RuntimeException implements ExceptionInterface
{
    /**
     * Entity cannot be saved.
     *
     * @param   EntityInterface  $entity  Entity with empty data
     * @param \JTable $jTable Table containing the entity data
     *
     * @return  static
     */
    public static function table(EntityInterface $entity, \JTable $jTable)
    {
        if (!$entity->hasId()) {
            $msg = sprintf('Save failed trying to create `%s`:</br> %s', $entity->name(), $jTable->getError());

            return new static($msg, 500);
        }

        $msg = sprintf('Save failed trying to save `%s`:</br> %s', $entity->name().'::'.$entity->id(), $jTable->getError());

        return new static($msg, 500);
    }

    /**
     * Entity did not pass validation.
     *
     * @param   EntityInterface      $entity     Entity with empty data
     * @param ValidationException $validationException Validation exception
     *
     * @return  static
     */
    public static function validation(EntityInterface $entity, ValidationException $validationException)
    {
        if (!$entity->hasId()) {
            $msg = sprintf('Validation failed trying to create `%s`:</br> %s', $entity->name(), $validationException->getMessage());

            return new static($msg, 500);
        }

        $msg = sprintf('Validation failed trying to save `%s`:</br> %s', $entity->name().'::'.$entity->id(), $validationException->getMessage());

        return new static($msg, 500);
    }
}
