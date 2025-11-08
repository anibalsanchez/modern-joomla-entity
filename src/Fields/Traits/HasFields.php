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

namespace Extly\Joomla\Entity\Fields\Traits;

defined('_JEXEC') || die;

use Extly\Joomla\Entity\Collection;
use Extly\Joomla\Entity\Core\Extension\Component;
use Extly\Joomla\Entity\Fields\Field;

/**
 * Trait for entities that have associated fields.
 *
 * @since  1.0.0
 */
trait HasFields
{
    /**
     * Associated fields
     *
     * @var  Collection
     */
    protected $fields;

    /**
     * Retrieve the associated component.
     *
     * @return  Component
     */
    abstract public function component();

    /**
     * Get a specfic entity field.
     *
     * @param   int  $id  Field identifier
     *
     * @return  Field
     *
     * @throws  \InvalidArgumentException  Entity does not have specified field
     */
    public function field($id)
    {
        $fields = $this->fields();

        if (!$fields->has($id)) {
            $msg = sprintf('Entity %s does not have field %s', get_class($this), $id);

            throw new \InvalidArgumentException($msg);
        }

        return $fields->get($id);
    }

    /**
     * Get a specific entity field by its name.
     *
     * @param   string  $name  Field name
     *
     * @return  Field
     *
     * @throws  \InvalidArgumentException  Entity does not have specified field
     *
     * @since   1.1.0
     */
    public function fieldByName($name)
    {
        foreach ($this->fields() as $field) {
            if ($name === $field->get('name')) {
                return $field;
            }
        }

        $msg = sprintf('Entity %s does not have field %s', get_class($this), $name);

        throw new \InvalidArgumentException($msg);
    }

    /**
     * Deprecated function for getting a single field value
     *
     * @param   int  $id       Field which value we want to retrieve.
     * @param   mixed    $default  Value to use as default if value is null
     * @param   bool  $raw      Return raw field value
     *
     * @return  mixed
     *
     * @deprecated   Use field($id)->value() or field($id)->rawValue()
     */
    public function fieldValue($id, $default = null, $raw = false)
    {
        $value = $raw ? $this->field($id)->rawValue() : $this->field($id)->value();

        return is_null($value) ? $default : $value;
    }

    /**
     * Get all the field values for this entity.
     *
     * @param   bool  $raw  Return raw field values
     *
     * @return  array
     */
    public function fieldValues($raw = false)
    {
        $values = [];

        foreach ($this->fields() as $field) {
            $values[$field->id()] = $raw ? $field->rawValue() : $field->value();
        }

        return $values;
    }

    /**
     * Get associated fields.
     *
     * @param   bool  $reload  Force data reloading
     *
     * @return  Collection
     */
    public function fields($reload = false)
    {
        if ($reload || null === $this->fields) {
            $this->fields = $this->loadFields();
        }

        return $this->fields;
    }

    /**
     * Check if this entity has a field.
     *
     * @param   int  $id  Field identifier
     *
     * @return  bool
     */
    public function hasField($id)
    {
        return $this->fields()->has($id);
    }

    /**
     * Check if this entity has fields.
     *
     * @return  bool
     *
     * @since   1.2.0
     */
    public function hasFields()
    {
        return !$this->fields()->isEmpty();
    }

    /**
     * Get the applicate context to load fields for this entity.
     *
     * @return  string
     */
    protected function fieldsContext()
    {
        return $this->component()->option().'.'.$this->name();
    }

    /**
     * Load associated fields from DB.
     *
     * @return  Collection
     */
    protected function loadFields()
    {
        $fields = array_values(
            array_map(
                fn ($field) => Field::find($field->id)->bind($field),
                $this->getFieldsThroughHelper($this->fieldsContext())
            )
        );

        return new Collection($fields);
    }

    /**
     * Get fields using the fields helper
     *
     * @param   string   $context  Example: com_content.article
     *
     * @return  array
     *
     * @codeCoverageIgnore
     */
    protected function getFieldsThroughHelper($context)
    {
        \JLoader::register('FieldsHelper', JPATH_ADMINISTRATOR.'/components/com_fields/helpers/fields.php');

        return \FieldsHelper::getFields($context, (object) $this->all(), true);
    }
}
