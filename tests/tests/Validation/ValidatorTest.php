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

namespace Extly\Joomla\Entity\Tests\Validation;

use Extly\Joomla\Entity\Tests\Stubs\Entity;
use Extly\Joomla\Entity\Validation\Exception\ValidationException;
use Extly\Joomla\Entity\Validation\Rule\CustomRule;
use Extly\Joomla\Entity\Validation\Validator;

/**
 * Validator tests.
 *
 * @since   1.1.0
 */
class ValidatorTest extends \TestCase
{
    /**
     * addGlobalRule adds rule.
     *
     * @return  void
     */
    public function testAddGlobalRuleAddsRule()
    {
        $validator = new Validator(new Entity());

        $rule = new CustomRule(
            fn ($value) => $value !== 'test',
            'Custom rule test'
        );

        $validator->addGlobalRule($rule);

        $reflectionClass = new \ReflectionClass($validator);

        $reflectionProperty = $reflectionClass->getProperty('globalRules');
        $reflectionProperty->setAccessible(true);

        $expected = [
            $rule->id() => $rule,
        ];

        $this->assertSame($expected, $reflectionProperty->getValue($validator));

        $rule2 = new CustomRule(
            fn ($value) => $value !== 'test2'
        );

        $validator->addGlobalRule($rule2);

        $expected = [
            $rule->id()  => $rule,
            $rule2->id() => $rule2,
        ];

        $this->assertSame($expected, $reflectionProperty->getValue($validator));
    }

    /**
     * addGlobalRules adds rules.
     *
     * @return  void
     */
    public function testAddGlobalRulesAddsRules()
    {
        $validator = new Validator(new Entity());

        $rules = [
            new CustomRule(
                fn ($value) => $value !== 'test',
                'Custom rule test'
            ),
            new CustomRule(
                fn ($value) => $value !== 'test2',
                'Custom rule test2'
            ),
        ];

        $validator->addGlobalRules($rules);

        $reflectionClass = new \ReflectionClass($validator);

        $reflectionProperty = $reflectionClass->getProperty('globalRules');
        $reflectionProperty->setAccessible(true);

        $expected = [
            $rules[0]->id() => $rules[0],
            $rules[1]->id() => $rules[1],
        ];

        $this->assertSame($expected, $reflectionProperty->getValue($validator));
    }

    /**
     * addRule adds column rule.
     *
     * @return  void
     */
    public function testAddsRuleAddsColumnRule()
    {
        $validator = new Validator(new Entity());

        $rule = new CustomRule(
            fn ($value) => $value !== 'test'
        );

        $validator->addRule($rule, 'sample_column');

        $reflectionClass = new \ReflectionClass($validator);

        $reflectionProperty = $reflectionClass->getProperty('rules');
        $reflectionProperty->setAccessible(true);

        $expected = [
            'sample_column' => [
                $rule->id() => $rule,
            ],
        ];

        $this->assertSame($expected, $reflectionProperty->getValue($validator));

        $rule2 = new CustomRule(
            fn ($value) => $value !== 'test2'
        );

        $validator->addRule($rule2, 'sample_column2');

        $expected = [
            'sample_column' => [
                $rule->id() => $rule,
            ],
            'sample_column2' => [
                $rule2->id() => $rule2,
            ],
        ];

        $this->assertSame($expected, $reflectionProperty->getValue($validator));
    }

    /**
     * addRules adds rules.
     *
     * @return  void
     */
    public function testAddRulesAddsRules()
    {
        $validator = new Validator(new Entity());

        $rules = [
            'sample_column' => new CustomRule(
                fn ($value) => $value !== 'test'
            ),
            'sample_column2' => [
                new CustomRule(
                    fn ($value) => $value !== 'test2'
                ),
                new CustomRule(
                    fn ($value) => $value !== 'test3'
                ),
            ],
        ];

        $validator->addRules($rules);

        $reflectionClass = new \ReflectionClass($validator);

        $reflectionProperty = $reflectionClass->getProperty('rules');
        $reflectionProperty->setAccessible(true);

        $expected = [
            'sample_column' => [
                $rules['sample_column']->id() => $rules['sample_column'],
            ],
            'sample_column2' => [
                $rules['sample_column2'][0]->id() => $rules['sample_column2'][0],
                $rules['sample_column2'][1]->id() => $rules['sample_column2'][1],
            ],
        ];

        $this->assertSame($expected, $reflectionProperty->getValue($validator));
    }

    /**
     * globalRules returns property value.
     *
     * @return  void
     */
    public function testGlobalRulesReturnsPropertyValue()
    {
        $validator = new Validator(new Entity());

        $reflectionClass = new \ReflectionClass($validator);
        $rules = [
            CustomRule::class => new CustomRule(
                fn ($value) => $value === 'test1'
            ),
            CustomRule::class => new CustomRule(
                fn ($value) => $value === 'test2'
            ),
        ];

        $reflectionProperty = $reflectionClass->getProperty('globalRules');
        $reflectionProperty->setAccessible(true);
        $reflectionProperty->setValue($validator, $rules);

        $this->assertSame($rules, $validator->globalRules());
    }

    /**
     * hasGlobalRule returns correct value.
     *
     * @return  void
     */
    public function testHasGlobalRuleReturnsCorrectValue()
    {
        $validator = new Validator(new Entity());

        $reflectionClass = new \ReflectionClass($validator);
        $rules = [
            'test' => new CustomRule(
                fn ($value) => $value === 'test1'
            ),
            'test two' => new CustomRule(
                fn ($value) => $value === 'test2'
            ),
        ];

        $reflectionProperty = $reflectionClass->getProperty('globalRules');
        $reflectionProperty->setAccessible(true);
        $reflectionProperty->setValue($validator, $rules);

        $this->assertTrue($validator->hasGlobalRule('test'));
        $this->assertFalse($validator->hasGlobalRule('test1'));
        $this->assertTrue($validator->hasGlobalRule('test two'));
    }

    /**
     * hasGlobalRules returns correct value.
     *
     * @return  void
     */
    public function testHasGlobalRulesReturnsCorrectValue()
    {
        $validator = new Validator(new Entity());

        $this->assertFalse($validator->hasGlobalRules());

        $reflectionClass = new \ReflectionClass($validator);
        $rules = [
            'test' => new CustomRule(
                fn ($value) => $value === 'test1'
            ),
            'test two' => new CustomRule(
                fn ($value) => $value === 'test2'
            ),
        ];

        $reflectionProperty = $reflectionClass->getProperty('globalRules');
        $reflectionProperty->setAccessible(true);
        $reflectionProperty->setValue($validator, $rules);

        $this->assertTrue($validator->hasGlobalRules());

        $reflectionProperty->setValue($validator, []);

        $this->assertFalse($validator->hasGlobalRules());
    }

    /**
     * hasRule returns correct value.
     *
     * @return  void
     */
    public function testHasRuleReturnsCorrectValue()
    {
        $validator = new Validator(new Entity());

        $this->assertFalse($validator->hasRule('sample rule', 'sample_column'));

        $reflectionClass = new \ReflectionClass($validator);
        $rules = [
            'sample_column' => [
                'test' => new CustomRule(
                    fn ($value) => $value === 'test1'
                ),
            ],
            'sample_column2' => [
                'test two' => new CustomRule(
                    fn ($value) => $value === 'test2'
                ),
            ],
        ];

        $reflectionProperty = $reflectionClass->getProperty('rules');
        $reflectionProperty->setAccessible(true);
        $reflectionProperty->setValue($validator, $rules);

        $this->assertTrue($validator->hasRule('test', 'sample_column'));
        $this->assertFalse($validator->hasRule('test two', 'sample_column'));
        $this->assertTrue($validator->hasRule('test two', 'sample_column2'));
    }

    /**
     * hasRules returns correct value.
     *
     * @return  void
     */
    public function testHasRulesReturnsCorrectValue()
    {
        $validator = new Validator(new Entity());

        $this->assertFalse($validator->hasRules());

        $reflectionClass = new \ReflectionClass($validator);
        $rules = [
            'sample_column' => [
                'test' => new CustomRule(
                    fn ($value) => $value === 'test1'
                ),
            ],
            'sample_column2' => [
                'test two' => new CustomRule(
                    fn ($value) => $value === 'test2'
                ),
            ],
        ];

        $reflectionProperty = $reflectionClass->getProperty('rules');
        $reflectionProperty->setAccessible(true);
        $reflectionProperty->setValue($validator, $rules);

        $this->assertTrue($validator->hasRules());

        $reflectionProperty->setValue($validator, []);

        $this->assertFalse($validator->hasRules());
    }

    /**
     * isValid returns false when exception happens.
     *
     * @return  void
     */
    public function testIsValidReturnsFalseWhenExceptionHappens()
    {
        $validator = $this->getMockBuilder(Validator::class)
            ->disableOriginalConstructor()
            ->setMethods(['validate'])
            ->getMock();

        $validator->expects($this->once())
            ->method('validate')
            ->will($this->throwException(new ValidationException('Validation failure')));

        $this->assertFalse($validator->isValid());
    }

    /**
     * isValid returns true when no exception happens.
     *
     * @return  void
     */
    public function testIsValidReturnsTrueWhenNoExceptionHappens()
    {
        $validator = $this->getMockBuilder(Validator::class)
            ->disableOriginalConstructor()
            ->setMethods(['validate'])
            ->getMock();

        $validator->expects($this->once())
            ->method('validate')
            ->willReturn(true);

        $this->assertTrue($validator->isValid());
    }

    /**
     * isValidColumnValue returns false when global rule returns false.
     *
     * @return  void
     */
    public function testIsValidColumnValueReturnsFalseWhenGlobalRuleReturnsFalse()
    {
        $validator = new Validator(new Entity());

        $reflectionClass = new \ReflectionClass($validator);
        $rules = [
            'test' => new CustomRule(
                fn ($value) => $value !== 'test1'
            ),
            'test two' => new CustomRule(
                fn ($value) => $value !== 'test2'
            ),
        ];

        $reflectionProperty = $reflectionClass->getProperty('globalRules');
        $reflectionProperty->setAccessible(true);
        $reflectionProperty->setValue($validator, $rules);

        $this->assertFalse($validator->isValidColumnValue('property', 'test1'));
        $this->assertFalse($validator->isValidColumnValue('property', 'test2'));
        $this->assertTrue($validator->isValidColumnValue('property', 'test3'));
    }

    /**
     * isValidColumnValue returns false when column rule fails.
     *
     * @return  void
     */
    public function testIsValidColumnValueReturnsFalseWhenColumnRuleFails()
    {
        $validator = new Validator(new Entity());

        $reflectionClass = new \ReflectionClass($validator);
        $rules = [
            'sample_column' => [
                'test' => new CustomRule(
                    fn ($value) => $value !== 'test1'
                ),
            ],
            'sample_column2' => [
                'test two' => new CustomRule(
                    fn ($value) => $value !== 'test2'
                ),
            ],
        ];

        $reflectionProperty = $reflectionClass->getProperty('rules');
        $reflectionProperty->setAccessible(true);
        $reflectionProperty->setValue($validator, $rules);

        $this->assertFalse($validator->isValidColumnValue('sample_column', 'test1'));
        $this->assertFalse($validator->isValidColumnValue('sample_column2', 'test2'));
        $this->assertTrue($validator->isValidColumnValue('sample_column', 'test3'));
        $this->assertTrue($validator->isValidColumnValue('sample_column2', 'test3'));
    }

    /**
     * removeGlobalRule removes rule.
     *
     * @return  void
     */
    public function testRemoveGlobalRuleRemovesRule()
    {
        $validator = new Validator(new Entity());

        $reflectionClass = new \ReflectionClass($validator);
        $rules = [
            'test' => new CustomRule(
                fn ($value) => $value === 'test1'
            ),
            'test two' => new CustomRule(
                fn ($value) => $value === 'test2'
            ),
        ];

        $reflectionProperty = $reflectionClass->getProperty('globalRules');
        $reflectionProperty->setAccessible(true);
        $reflectionProperty->setValue($validator, $rules);

        $this->assertSame($rules, $reflectionProperty->getValue($validator));

        $validator->removeGlobalRule('test');
        unset($rules['test']);

        $this->assertSame($rules, $reflectionProperty->getValue($validator));

        $validator->removeGlobalRule('test two');

        $this->assertSame([], $reflectionProperty->getValue($validator));
    }

    /**
     * removeGlobalRules removes all the rules.
     *
     * @return  void
     */
    public function testRemoveGlobalRulesRemovesAllTheRules()
    {
        $validator = new Validator(new Entity());

        $reflectionClass = new \ReflectionClass($validator);
        $rules = [
            'test' => new CustomRule(
                fn ($value) => $value === 'test1'
            ),
            'test two' => new CustomRule(
                fn ($value) => $value === 'test2'
            ),
        ];

        $reflectionProperty = $reflectionClass->getProperty('globalRules');
        $reflectionProperty->setAccessible(true);
        $reflectionProperty->setValue($validator, $rules);

        $this->assertSame($rules, $reflectionProperty->getValue($validator));

        $validator->removeGlobalRules();

        $this->assertSame([], $reflectionProperty->getValue($validator));
    }

    /**
     * removeRule removes rule.
     *
     * @return  void
     */
    public function testRemoveRuleRemovesRule()
    {
        $validator = new Validator(new Entity());

        $reflectionClass = new \ReflectionClass($validator);
        $rules = [
            'sample_column' => [
                'test' => new CustomRule(
                    fn ($value) => $value === 'test1'
                ),
            ],
            'sample_column2' => [
                'test two' => new CustomRule(
                    fn ($value) => $value === 'test2'
                ),
            ],
        ];

        $reflectionProperty = $reflectionClass->getProperty('rules');
        $reflectionProperty->setAccessible(true);
        $reflectionProperty->setValue($validator, $rules);

        $this->assertSame($rules, $reflectionProperty->getValue($validator));

        $validator->removeRule('sample_column', 'test');
        unset($rules['sample_column']['test']);

        $this->assertSame($rules, $reflectionProperty->getValue($validator));

        $validator->removeRule('sample_column2', 'test two');

        $this->assertSame(['sample_column' => [], 'sample_column2' => []], $reflectionProperty->getValue($validator));
    }

    /**
     * removeRules removes all the rules.
     *
     * @return  void
     */
    public function testRemoveRulesRemovesAllTheRules()
    {
        $validator = new Validator(new Entity());

        $reflectionClass = new \ReflectionClass($validator);
        $rules = [
            'sample_column' => [
                'test' => new CustomRule(
                    fn ($value) => $value === 'test1'
                ),
            ],
            'sample_column2' => [
                'test two' => new CustomRule(
                    fn ($value) => $value === 'test2'
                ),
            ],
        ];

        $reflectionProperty = $reflectionClass->getProperty('rules');
        $reflectionProperty->setAccessible(true);
        $reflectionProperty->setValue($validator, $rules);

        $this->assertSame($rules, $reflectionProperty->getValue($validator));

        $validator->removeRules();

        $this->assertSame([], $reflectionProperty->getValue($validator));
    }

    /**
     * rules returns all rules if no column is specified.
     *
     * @return  void
     */
    public function testRulesReturnsAllRulesIfNoColumnIsSpecified()
    {
        $validator = new Validator(new Entity());

        $reflectionClass = new \ReflectionClass($validator);
        $rules = [
            'sample_column' => [
                'test' => new CustomRule(
                    fn ($value) => $value === 'test1'
                ),
            ],
            'sample_column2' => [
                'test two' => new CustomRule(
                    fn ($value) => $value === 'test2'
                ),
            ],
        ];

        $reflectionProperty = $reflectionClass->getProperty('rules');
        $reflectionProperty->setAccessible(true);
        $reflectionProperty->setValue($validator, $rules);

        $this->assertSame($rules, $validator->rules());
    }

    /**
     * rules returns only column rules if column is specified.
     *
     * @return  void
     */
    public function testRulesReturnsOnlyColumnRulesIfColumnIsSpecified()
    {
        $validator = new Validator(new Entity());

        $reflectionClass = new \ReflectionClass($validator);
        $rules = [
            'sample_column' => [
                'test' => new CustomRule(
                    fn ($value) => $value === 'test1'
                ),
            ],
            'sample_column2' => [
                'test two' => new CustomRule(
                    fn ($value) => $value === 'test2'
                ),
            ],
        ];

        $reflectionProperty = $reflectionClass->getProperty('rules');
        $reflectionProperty->setAccessible(true);
        $reflectionProperty->setValue($validator, $rules);

        $this->assertSame($rules['sample_column'], $validator->rules('sample_column'));
        $this->assertSame($rules['sample_column2'], $validator->rules('sample_column2'));
        $this->assertSame([], $validator->rules('non_existing_column'));
    }

    /**
     * validate throws exception for invalid column value.
     *
     * @return  void
     */
    public function testValidateThrowsExceptionForInvalidColumnValue()
    {
        $entity = new Entity(999);
        $entity->bind(['id' => 999, 'sample_column' => 'test1']);

        $validator = new Validator($entity);
        $validator->addRule(
            new CustomRule(
                fn ($value) => $value !== 'test1'
            ),
            ['sample_column']
        );

        try {
            $validator->validate();
        } catch (ValidationException $validationException) {
        }

        $this->assertInstanceOf(ValidationException::class, $validationException);
        $this->assertTrue(strlen($validationException->getMessage()) > 0);
    }

    /**
     * validate returns true for valid column values.
     *
     * @return  void
     */
    public function testValidateReturnsTrueForValidColumnValues()
    {
        $entity = new Entity(999);
        $entity->bind(['id' => 999, 'sample_column' => 'test1', 'sample_column2' => 1]);

        $validator = new Validator($entity);
        $validator
            ->addGlobalRule(
                new CustomRule(
                    fn ($value) => is_int($value) || $value === 'test1'
                )
            )
            ->addRule(
                new CustomRule(
                    fn ($value) => $value === 'test1'
                ),
                ['sample_column']
            );

        $this->assertTrue($validator->validate());
    }
}
