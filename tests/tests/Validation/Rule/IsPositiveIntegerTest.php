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

use Extly\Joomla\Entity\Validation\Rule\IsPositiveInteger;

/**
 * IsPositiveInteger tests.
 *
 * @since   1.1.0
 */
class IsPositiveIntegerTest extends \TestCase
{
    /**
     * passes returns correct value.
     *
     * @return  void
     */
    public function testPassesReturnsCorrectValue()
    {
        $isPositiveInteger = new IsPositiveInteger();

        $this->assertFalse($isPositiveInteger->passes(''));
        $this->assertFalse($isPositiveInteger->passes('#aa'));
        $this->assertFalse($isPositiveInteger->passes(0));
        $this->assertFalse($isPositiveInteger->passes(-1));
        $this->assertFalse($isPositiveInteger->passes(0.1));
        $this->assertFalse($isPositiveInteger->passes(1.1));
        $this->assertFalse($isPositiveInteger->passes('1.1'));
        $this->assertFalse($isPositiveInteger->passes('12,000'));

        $this->assertTrue($isPositiveInteger->passes('12'));
        $this->assertTrue($isPositiveInteger->passes(1));
        $this->assertTrue($isPositiveInteger->passes(' 12'));
        $this->assertTrue($isPositiveInteger->passes(12));
        $this->assertTrue($isPositiveInteger->passes('12.000'));
        $this->assertTrue($isPositiveInteger->passes(12.000));
    }
}
