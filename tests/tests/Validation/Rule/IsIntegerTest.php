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

use Extly\Joomla\Entity\Validation\Rule\IsInteger;

/**
 * IsInteger tests.
 *
 * @since   1.1.0
 */
class IsIntegerTest extends \TestCase
{
    /**
     * passes returns correct value.
     *
     * @return  void
     */
    public function testPassesReturnsCorrectValue()
    {
        $isInteger = new IsInteger();

        $this->assertFalse($isInteger->passes('mytest'));
        $this->assertFalse($isInteger->passes(''));
        $this->assertFalse($isInteger->passes('  my string'));
        $this->assertTrue($isInteger->passes(0));
        $this->assertFalse($isInteger->passes(null));
        $this->assertTrue($isInteger->passes('0'));
        $this->assertTrue($isInteger->passes('1111'));
        $this->assertTrue($isInteger->passes('1111'));
        $this->assertFalse($isInteger->passes(' '));
    }
}
