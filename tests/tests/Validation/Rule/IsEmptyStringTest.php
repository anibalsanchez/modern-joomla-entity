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

use Extly\Joomla\Entity\Validation\Rule\IsEmptyString;

/**
 * IsEmptyString tests.
 *
 * @since   1.1.0
 */
class IsEmptyStringTest extends \TestCase
{
    /**
     * passes returns correct value.
     *
     * @return  void
     */
    public function testPassesReturnsCorrectValue()
    {
        $isEmptyString = new IsEmptyString();

        $this->assertFalse($isEmptyString->passes('mytest'));
        $this->assertTrue($isEmptyString->passes(''));
        $this->assertFalse($isEmptyString->passes('  my string'));
        $this->assertFalse($isEmptyString->passes(0));
        $this->assertTrue($isEmptyString->passes(null));
        $this->assertTrue($isEmptyString->passes(' '));
    }
}
