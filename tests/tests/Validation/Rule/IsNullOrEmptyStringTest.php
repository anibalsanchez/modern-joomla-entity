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

use Extly\Joomla\Entity\Validation\Rule\IsNullOrEmptyString;

/**
 * IsNullOrEmptyString tests.
 *
 * @since   1.1.0
 */
class IsNullOrEmptyStringTest extends \TestCase
{
    /**
     * passes returns correct value.
     *
     * @return  void
     */
    public function testPassesReturnsCorrectValue()
    {
        $isNullOrEmptyString = new IsNullOrEmptyString();

        $this->assertTrue($isNullOrEmptyString->passes(''));
        $this->assertFalse($isNullOrEmptyString->passes('#aa'));
        $this->assertFalse($isNullOrEmptyString->passes('null'));
        $this->assertFalse($isNullOrEmptyString->passes(0));
        $this->assertFalse($isNullOrEmptyString->passes(0.1));
        $this->assertFalse($isNullOrEmptyString->passes(1.1));
        $this->assertFalse($isNullOrEmptyString->passes('1.1'));
        $this->assertFalse($isNullOrEmptyString->passes('12,000'));

        $this->assertTrue($isNullOrEmptyString->passes(null));
    }
}
