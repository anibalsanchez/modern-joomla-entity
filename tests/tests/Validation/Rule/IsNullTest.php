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

use Extly\Joomla\Entity\Validation\Rule\IsNull;

/**
 * IsNull tests.
 *
 * @since   1.1.0
 */
class IsNullTest extends \TestCase
{
    /**
     * passes returns correct value.
     *
     * @return  void
     */
    public function testPassesReturnsCorrectValue()
    {
        $isNull = new IsNull();

        $this->assertFalse($isNull->passes(''));
        $this->assertFalse($isNull->passes('#aa'));
        $this->assertFalse($isNull->passes('null'));
        $this->assertFalse($isNull->passes(0));
        $this->assertFalse($isNull->passes(0.1));
        $this->assertFalse($isNull->passes(1.1));
        $this->assertFalse($isNull->passes('1.1'));
        $this->assertFalse($isNull->passes('12,000'));

        $this->assertTrue($isNull->passes(null));
    }
}
