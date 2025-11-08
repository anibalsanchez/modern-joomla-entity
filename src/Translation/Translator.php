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

use Extly\Joomla\Entity\Contracts\EntityInterface;
use Extly\Joomla\Entity\Core\Column;
use Extly\Joomla\Entity\Decorator;
use Extly\Joomla\Entity\Translation\Contracts\Translatable;
use Extly\Joomla\Entity\Translation\Contracts\Translator as TranslatorContract;
use Extly\Joomla\Entity\Validation\Contracts\Validator as ValidatorContract;
use Extly\Joomla\Entity\Validation\Rule\IsNotNull;
use Extly\Joomla\Entity\Validation\Validator;

/**
 * Entity translation.
 *
 * @since   1.0.0
 */
class Translator extends Decorator implements TranslatorContract
{
    /**
     * Translation language tag.
     *
     * @var  string
     */
    protected $langTag;

    /**
     * Entity translation.
     *
     * @var  EntityInterface
     */
    protected $translation;

    /**
     * Translation validator.
     *
     * @var  Validator
     */
    protected $validator;

    /**
     * Constructor.
     *
     * @param Translatable $translatable Entity to decorate.
     * @param   string        $langTag  Language tag. Example: es-ES
     */
    public function __construct(Translatable $translatable, $langTag = null)
    {
        parent::__construct($translatable);

        $this->langTag = $langTag ?: $this->activeLanguage()->getTag();
    }

    /**
     * Get the active language.
     *
     * @return  \Joomla\CMS\Language\Language
     *
     * @codeCoverageIgnore
     */
    public function activeLanguage()
    {
        return \Joomla\CMS\Factory::getLanguage();
    }

    /**
     * Check if entity language is the translation language.
     *
     * @return  bool
     */
    public function isEntityLanguage()
    {
        $languageColumn = $this->entity->columnAlias(Column::LANGUAGE);

        return $this->entity->get($languageColumn) === $this->langTag;
    }

    /**
     * Set the active validator for the translations.
     *
     * @param ValidatorContract $validatorContract Desired validator
     *
     * @return  self
     */
    public function setValidator(ValidatorContract $validatorContract)
    {
        $this->validator = $validatorContract;

        return $this;
    }

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

        return $default;
    }

    /**
     * Retrieve the translation validator.
     *
     * @return  ValidatorContract
     */
    public function validator()
    {
        if (null === $this->validator) {
            $this->validator = new Validator($this->entity);
            $this->validator->addGlobalRule(new IsNotNull());
        }

        return $this->validator;
    }

    /**
     * Retrieve translation entity.
     *
     * @return  EntityInterface
     */
    protected function translation()
    {
        return $this->entity->translation($this->langTag);
    }
}
