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

use Extly\Joomla\Entity\Validation\Rule\IsNotNull;

/**
 * IsNotNull tests.
 *
 * @since   1.1.0
 */
class IsNotNullTest extends \TestCase
{
    /**
     * passes returns correct value.
     *
     * @return  void
     */
    public function testPassesReturnsCorrectValue()
    {
        $isNotNull = new IsNotNull();

        $this->assertTrue($isNotNull->passes(''));
        $this->assertTrue($isNotNull->passes('#aa'));
        $this->assertTrue($isNotNull->passes('null'));
        $this->assertTrue($isNotNull->passes(0));
        $this->assertTrue($isNotNull->passes(0.1));
        $this->assertTrue($isNotNull->passes(1.1));
        $this->assertTrue($isNotNull->passes('1.1'));
        $this->assertTrue($isNotNull->passes('12,000'));

        $this->assertFalse($isNotNull->passes(null));
    }
}
