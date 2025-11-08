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

use Extly\Joomla\Entity\Validation\Rule\NoSubstrCount;

/**
 * NoSubstrCount tests.
 *
 * @since   1.1.0
 */
class NoSubstrCountTest extends \TestCase
{
    /**
     * passes returns correct value.
     *
     * @return  void
     */
    public function testPassesReturnsCorrectValue()
    {
        $noSubstrCount = new NoSubstrCount('test');

        $this->assertFalse($noSubstrCount->passes('mytest'));
        $this->assertTrue($noSubstrCount->passes(''));
        $this->assertTrue($noSubstrCount->passes('my string'));
        $this->assertTrue($noSubstrCount->passes(0));
        $this->assertTrue($noSubstrCount->passes(null));
        $this->assertFalse($noSubstrCount->passes('testing substr_count'));
    }
}
