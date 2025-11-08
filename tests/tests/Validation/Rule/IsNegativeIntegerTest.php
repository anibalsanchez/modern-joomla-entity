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

use Extly\Joomla\Entity\Validation\Rule\IsNegativeInteger;

/**
 * IsNegativeInteger tests.
 *
 * @since   1.1.0
 */
class IsNegativeIntegerTest extends \TestCase
{
    /**
     * passes returns correct value.
     *
     * @return  void
     */
    public function testPassesReturnsCorrectValue()
    {
        $isNegativeInteger = new IsNegativeInteger();

        $this->assertFalse($isNegativeInteger->passes(''));
        $this->assertFalse($isNegativeInteger->passes('#aa'));
        $this->assertFalse($isNegativeInteger->passes(0));
        $this->assertFalse($isNegativeInteger->passes(0.1));
        $this->assertFalse($isNegativeInteger->passes(1.1));
        $this->assertFalse($isNegativeInteger->passes('1.1'));
        $this->assertFalse($isNegativeInteger->passes('12,000'));

        $this->assertTrue($isNegativeInteger->passes('-12'));
        $this->assertTrue($isNegativeInteger->passes(-1));
        $this->assertTrue($isNegativeInteger->passes(' -12'));
        $this->assertTrue($isNegativeInteger->passes(-12));
        $this->assertTrue($isNegativeInteger->passes('-12.000'));
        $this->assertTrue($isNegativeInteger->passes(-12.000));
    }
}
