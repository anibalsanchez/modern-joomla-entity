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

use Extly\Joomla\Entity\Validation\Rule\IsString;

/**
 * IsString tests.
 *
 * @since   1.1.0
 */
class IsStringTest extends \TestCase
{
    /**
     * passes returns correct value.
     *
     * @return  void
     */
    public function testPassesReturnsCorrectValue()
    {
        $isString = new IsString();

        $this->assertTrue($isString->passes('mytest'));
        $this->assertTrue($isString->passes(''));
        $this->assertTrue($isString->passes('  my string'));
        $this->assertFalse($isString->passes(0));
        $this->assertFalse($isString->passes(0.11));
        $this->assertFalse($isString->passes(null));
        $this->assertTrue($isString->passes(' '));
    }
}
