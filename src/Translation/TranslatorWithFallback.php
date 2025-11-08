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

namespace Extly\Joomla\Entity\Translation;

defined('_JEXEC') || die;

/**
 * Represents a collection of entities.
 *
 * @since   1.0.0
 */
class TranslatorWithFallback extends Translator
{
    /**
     * Translate a column.
     *
     * @param   string  $column   Column to translate
     * @param   mixed   $default  Default value
     *
     * @return  mixed
     */
    public function translate($column, $default = null)
    {
        $value = $this->isEntityLanguage() ? $this->entity->get($column) : $this->translation()->get($column);

        if ($this->validator()->isValidColumnValue($column, $value)) {
            return $value;
        }

        if ($this->isEntityLanguage()) {
            return $default;
        }

        $value = $this->entity->get($column);

        $isValid = $this->validator()->isValidColumnValue($column, $value);

        return $isValid ? $value : $default;
    }
}
