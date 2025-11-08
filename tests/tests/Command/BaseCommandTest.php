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

namespace Extly\Joomla\Entity\Tests\Command;

use Extly\Joomla\Entity\Decorator;
use Extly\Joomla\Entity\Tests\Command\Stubs\SampleCommand;
use Joomla\Registry\Registry;

/**
 * Base command tests.
 *
 * @since   1.8
 */
class BaseCommandTest extends \TestCase
{
    /**
     * @test
     *
     * @return void
     */
    public function constructorSetsConfig()
    {
        $sampleCommand = new SampleCommand(['my-setting' => 'value']);

        $reflectionClass = new \ReflectionClass($sampleCommand);
        $reflectionProperty = $reflectionClass->getProperty('config');
        $reflectionProperty->setAccessible(true);

        $config = $reflectionProperty->getValue($sampleCommand);

        $this->assertInstanceOf(Registry::class, $config);
        $this->assertSame('value', $config->get('my-setting'));
    }

    /**
     * @test
     *
     * @return void
     */
    public function instanceReturnsExpectedInstance()
    {
        $sampleCommand = SampleCommand::instance([['test' => 'avalue']]);

        $this->assertInstanceOf(SampleCommand::class, $sampleCommand);

        $reflectionClass = new \ReflectionClass($sampleCommand);
        $reflectionProperty = $reflectionClass->getProperty('config');
        $reflectionProperty->setAccessible(true);

        $config = $reflectionProperty->getValue($sampleCommand);

        $this->assertInstanceOf(Registry::class, $config);
        $this->assertSame('avalue', $config->get('test'));
    }
}
