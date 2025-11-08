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

use Extly\Joomla\Entity\Validation\Rule\IsNotEmptyString;

/**
 * IsNotEmptyString tests.
 *
 * @since   1.1.0
 */
class IsNotEmptyStringTest extends \TestCase
{
    /**
     * passes returns correct value.
     *
     * @return  void
     */
    public function testPassesReturnsCorrectValue()
    {
        $isNotEmptyString = new IsNotEmptyString();

        $this->assertTrue($isNotEmptyString->passes('mytest'));
        $this->assertFalse($isNotEmptyString->passes(''));
        $this->assertTrue($isNotEmptyString->passes('  my string'));
        $this->assertTrue($isNotEmptyString->passes(0));
        $this->assertFalse($isNotEmptyString->passes(null));
        $this->assertFalse($isNotEmptyString->passes(' '));
    }
}
