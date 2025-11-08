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

use Extly\Joomla\Entity\Validation\Rule\IsEmptyDate;

/**
 * IsEmptyDate tests.
 *
 * @since   1.1.0
 */
class IsEmptyDateTest extends \TestCase
{
    /**
     * passes returns correct value.
     *
     * @return  void
     */
    public function testPassesReturnsCorrectValue()
    {
        $rule = $this->getMockBuilder(IsEmptyDate::class)
            ->setMethods(['nullDate'])
            ->getMock();

        $rule->method('nullDate')
            ->will($this->onConsecutiveCalls('0000-00-00 00:00:00', '1976-11-16 16:00:00', '0000-00-00 00:00:00', '1976-11-16 16:00:00'));

        $this->assertTrue($rule->passes(''));
        $this->assertTrue($rule->passes(null));
        $this->assertTrue($rule->passes('0000-00-00 00:00:00'));
        $this->assertTrue($rule->passes('1976-11-16 16:00:00'));
        $this->assertFalse($rule->passes('1976-11-16 16:00:00'));
        $this->assertFalse($rule->passes('0000-00-00 00:00:00'));
    }
}
