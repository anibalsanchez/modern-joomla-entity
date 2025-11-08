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

namespace Extly\Joomla\Entity\Validation\Exception;

defined('_JEXEC') || die;

use Extly\Joomla\Entity\Contracts\EntityInterface;
use Extly\Joomla\Entity\Contracts\ExceptionInterface;
use Extly\Joomla\Entity\Validation\Contracts\Rule as RuleContract;

/**
 * Validation errors.
 *
 * @since  1.0.0
 */
class ValidationException extends \RuntimeException implements ExceptionInterface
{
    /**
     * Entity did not pass validation.
     *
     * @param   EntityInterface  $entity  Entity with empty data
     * @param   array            $errors  Validation errors
     *
     * @return  static
     */
    public static function invalidEntity(EntityInterface $entity, array $errors = [])
    {
        $entityName = $entity->name().($entity->hasId() ? '::'.$entity->id() : null);
        $msg = sprintf('`%s` is not valid:</br>* ', $entityName);

        if ($errors !== []) {
            $msg .= implode('</br>* ', $errors);
        }

        return new static($msg, 500);
    }

    /**
     * Entity did not pass validation.
     *
     * @param   string          $column       Entity with empty data
     * @param   RuleContract[]  $failedRules  Rule failed
     *
     * @return  static
     */
    public static function invalidColumn($column, array $failedRules)
    {
        $errors = [];

        foreach ($failedRules as $failedRule) {
            $errors[] = sprintf('`%s` does not pass `%s` validation rule', $column, $failedRule->name());
        }

        return new static(implode('</br>* ', $errors), 500);
    }
}
