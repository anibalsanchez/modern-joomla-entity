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
use Extly\Joomla\Entity\Validation\Rule;
use Extly\Joomla\Entity\Validation\Validator;

/**
 * Base rule tests.
 *
 * @since   1.1.0
 */
class RuleTest extends \TestCase
{
    /**
     * fails returns true when passes returns false.
     *
     * @return  void
     */
    public function testFailsReturnsTruenWhenPassesReturnsFalse()
    {
        $rule = $this->getMockBuilder(Rule::class)
            ->setMethods(['passes'])
            ->getMockForAbstractClass();

        $rule->expects($this->exactly(2))
            ->method('passes')
            ->with('value')
            ->will($this->onConsecutiveCalls(false, true));

        $this->assertTrue($rule->fails('value'));
        $this->assertFalse($rule->fails('value'));
    }
}
