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

namespace Extly\Joomla\Entity\Validation;

defined('_JEXEC') || die;

use Extly\Joomla\Entity\Decorator;
use Extly\Joomla\Entity\Validation\Contracts\Rule as RuleContract;
use Extly\Joomla\Entity\Validation\Contracts\Validator as ValidatorContract;
use Extly\Joomla\Entity\Validation\Exception\ValidationException;

/**
 * Entity validator.
 *
 * @since   1.0.0
 */
class Validator extends Decorator implements ValidatorContract
{
    /**
     * Validation rules applicable to all columns.
     *
     * @var  RuleContract[]
     */
    protected $globalRules = [];

    /**
     * Column specific validation rules.
     *
     * @var  array
     */
    protected $rules = [];

    /**
     * Fast proxy to
     *
     * @param RuleContract $ruleContract Translation rule
     *
     * @return  self
     */
    public function addGlobalRule(RuleContract $ruleContract)
    {
        $this->globalRules[$ruleContract->id()] = $ruleContract;

        return $this;
    }

    /**
     * Add an array of global rules.
     *
     * @param   array  $rules  Rules to add
     *
     * @return  self
     */
    public function addGlobalRules(array $rules)
    {
        foreach ($rules as $rule) {
            $this->addGlobalRule($rule);
        }

        return $this;
    }

    /**
     * Add a validation rule for a column.
     *
     * @param RuleContract $ruleContract Rule
     * @param   mixed         $columns  String | Array. Columns to apply rule
     *
     * @return  self
     */
    public function addRule(RuleContract $ruleContract, $columns)
    {
        $columns = (array) $columns;

        foreach ($columns as $column) {
            if (!isset($this->rules[$column])) {
                $this->rules[$column] = [];
            }

            $this->rules[$column][$ruleContract->id()] = $ruleContract;
        }

        return $this;
    }

    /**
     * Add an array of rules.
     *
     * @param   array  $rules  Rules to add
     *
     * @return  self
     */
    public function addRules(array $rules)
    {
        foreach ($rules as $column => $columnRules) {
            $columnRules = is_array($columnRules) ? $columnRules : [$columnRules];

            foreach ($columnRules as $columnRule) {
                $this->addRule($columnRule, [$column]);
            }
        }

        return $this;
    }

    /**
     * Retrieve global translation rules.
     *
     * @return  RuleContract[]
     */
    public function globalRules()
    {
        return $this->globalRules;
    }

    /**
     * Check if there is a global translation rule with a specific name.
     *
     * @param   string  $name  Name of the rule
     *
     * @return  bool
     */
    public function hasGlobalRule($name)
    {
        return isset($this->globalRules[$name]);
    }

    /**
     * Check if there are global rules.
     *
     * @return  bool
     */
    public function hasGlobalRules()
    {
        return $this->globalRules !== [];
    }

    /**
     * Check if column has a validation rule.
     *
     * @param   string  $name    Name of the rule
     * @param   string  $column  Column to check for rule
     *
     * @return  bool
     */
    public function hasRule($name, $column)
    {
        return !empty($this->rules[$column][$name]);
    }

    /**
     * Check if there are validation rules set.
     *
     * @return  bool
     */
    public function hasRules()
    {
        return $this->rules !== [];
    }

    /**
     * Check if the entity is valid.
     *
     * @return  bool
     */
    public function isValid()
    {
        try {
            $this->validate();
        } catch (ValidationException $validationException) {
            return false;
        }

        return true;
    }

    /**
     * Check if a value is valid for a specific column.
     *
     * @param   string  $column  Column to validate against
     * @param   mixed   $value   Value to check
     *
     * @return  bool
     */
    public function isValidColumnValue($column, $value)
    {
        try {
            $this->validateColumnValue($column, $value);
        } catch (\Exception $exception) {
            return false;
        }

        return true;
    }

    /**
     * Remove a global translation rule by its name.
     *
     * @param   string  $name  Name of the rule to unset
     *
     * @return  self
     */
    public function removeGlobalRule($name)
    {
        unset($this->globalRules[$name]);

        return $this;
    }

    /**
     * Remove all the global rules.
     *
     * @return  self
     */
    public function removeGlobalRules()
    {
        $this->globalRules = [];

        return $this;
    }

    /**
     * Unset a rule by its name.
     *
     * @param   string  $column  Specific column to unset rules
     * @param   string  $name    Name of the rule to unset
     *
     * @return  self
     */
    public function removeRule($column, $name)
    {
        unset($this->rules[$column][$name]);

        return $this;
    }

    /**
     * Remove all the column translation rules.
     *
     * @return  self
     */
    public function removeRules()
    {
        $this->rules = [];

        return $this;
    }

    /**
     * Retrieve translation rules.
     *
     * @param   string  $column  [optional] Only retrieve rules for specified column
     *
     * @return  array
     */
    public function rules($column = null)
    {
        if (!$column) {
            return $this->rules;
        }

        return $this->rules[$column] ?? [];
    }

    /**
     * Validate entity.
     *
     * @return  bool
     *
     * @throws  \Exception
     */
    public function validate()
    {
        $errors = [];

        $data = $this->entity->all();

        $validableColumns = array_unique(
            array_merge(array_keys($data), array_keys($this->rules))
        );

        sort($validableColumns);

        foreach ($validableColumns as $validableColumn) {
            $value = $data[$validableColumn] ?? null;

            try {
                $this->validateColumnValue($validableColumn, $value);
            } catch (ValidationException $e) {
                $errors[] = $e->getMessage();
            }
        }

        if ($errors !== []) {
            throw ValidationException::invalidEntity($this->entity, $errors);
        }

        return $errors === [];
    }

    /**
     * Validate a column value.
     *
     * @param   string  $column  Column to check value against
     * @param   mixed   $value   Value for the column. Null to use current column value.
     *
     * @return  bool
     *
     * @throws  ValidationException
     */
    public function validateColumnValue($column, $value)
    {
        $failedRules = [];
        $rules = array_merge($this->globalRules(), $this->rules($column));

        foreach ($rules as $rule) {
            if (!$rule->passes($value)) {
                $failedRules[] = $rule;
            }
        }

        if ($failedRules !== []) {
            throw ValidationException::invalidColumn($column, $failedRules);
        }

        return true;
    }
}
