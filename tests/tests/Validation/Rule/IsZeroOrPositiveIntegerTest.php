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

use Extly\Joomla\Entity\Validation\Rule\IsZeroOrPositiveInteger;

/**
 * IsZeroOrPositiveInteger tests.
 *
 * @since   1.7.0
 */
class IsZeroOrPositiveIntegerTest extends \TestCase
{
    /**
     * Data provider for tests.
     *
     * @return  array
     */
    public function dataProvider()
    {
        return [
            ['', false],
            ['0', true],
            ['aa', false],
            [' 0', true],
            [null, false],
            [0, true],
            [0.5, false],
            [1.0, true],
            [1.1, false],
            ['1.1', false],
            ['1.0', true],
            ['1,0', false],
        ];
    }

    /**
     * @test
     *
     * @dataProvider  dataProvider
     *
     * @param   mixed    $value           Value to test
     * @param   bool  $expectedResult  Expected reponse from validator
     *
     * @return  void
     */
    public function passesReturnsCorrectValue($value, $expectedResult)
    {
        $isZeroOrPositiveInteger = new IsZeroOrPositiveInteger();

        $this->assertSame($expectedResult, $isZeroOrPositiveInteger->passes($value));
    }
}
