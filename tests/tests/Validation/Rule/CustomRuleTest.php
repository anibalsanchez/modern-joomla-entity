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

namespace Extly\Joomla\Entity\Tests\Validation\Rule;

use Extly\Joomla\Entity\Validation\Rule\CustomRule;

/**
 * CustomRule tests.
 *
 * @since   1.1.0
 */
class CustomRuleTest extends \TestCase
{
    /**
     * passes returns correct value.
     *
     * @return  void
     */
    public function testPassesReturnsCorrectValue()
    {
        $customRule = new CustomRule(
            fn ($value) => in_array($value, ['validValue1', 'validValue2'])
        );

        $this->assertTrue($customRule->passes('validValue1'));
        $this->assertTrue($customRule->passes('validValue2'));
        $this->assertFalse($customRule->passes('invalidValue'));
        $this->assertFalse($customRule->passes(''));
        $this->assertFalse($customRule->passes(null));
    }
}
