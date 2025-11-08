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

use Extly\Joomla\Entity\Validation\Rule\SubstrCount;

/**
 * SubstrCount tests.
 *
 * @since   1.1.0
 */
class SubstrCountTest extends \TestCase
{
    /**
     * passes returns correct value.
     *
     * @return  void
     */
    public function testPassesReturnsCorrectValue()
    {
        $substrCount = new SubstrCount('test');

        $this->assertTrue($substrCount->passes('mytest'));
        $this->assertFalse($substrCount->passes(''));
        $this->assertFalse($substrCount->passes('my string'));
        $this->assertFalse($substrCount->passes(0));
        $this->assertFalse($substrCount->passes(null));
        $this->assertTrue($substrCount->passes('testing substr_count'));
    }
}
