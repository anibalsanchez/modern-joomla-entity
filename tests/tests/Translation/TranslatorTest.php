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

namespace Extly\Joomla\Entity\Tests\Translation;

use Extly\Joomla\Entity\Tests\Stubs\Entity;
use Extly\Joomla\Entity\Tests\Translation\Stubs\TranslatableEntity;
use Extly\Joomla\Entity\Translation\Translator;

/**
 * Translator decorator tests.
 *
 * @since   1.1.0
 */
class TranslatorTest extends \TestCase
{
    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     *
     * @return  void
     */
    protected function tearDown()
    {
        Entity::clearAll();

        parent::tearDown();
    }

    /**
     * Constructor sets entity and language tag.
     *
     * @return  void
     */
    public function testConstructorSetsEntityAndLanguageTag()
    {
        $translatableEntity = new TranslatableEntity();
        $langTag = 'es-ES';

        $translator = new Translator($translatableEntity, $langTag);

        $this->assertInstanceOf(Translator::class, $translator);

        $reflectionClass = new \ReflectionClass($translator);

        $reflectionProperty = $reflectionClass->getProperty('entity');
        $reflectionProperty->setAccessible(true);

        $langTagProperty = $reflectionClass->getProperty('langTag');
        $langTagProperty->setAccessible(true);

        $this->assertSame($translatableEntity, $reflectionProperty->getValue($translator));
        $this->assertSame($langTag, $langTagProperty->getValue($translator));
    }

    /**
     * isEntityLanguage returns true when translator uses entity language.
     *
     * @return  void
     */
    public function testIsEntityLanguageReturnsTrueWhenTranslatorUsesEntityLanguage()
    {
        $entity = $this->getMockBuilder(TranslatableEntity::class)
            ->disableOriginalConstructor()
            ->setMethods(['columnAlias', 'get'])
            ->getMock();

        $entity->method('columnAlias')
            ->willReturn('language');

        $entity->method('get')
            ->with('language')
            ->willReturn('es-ES');

        $translator = new Translator($entity, 'es-ES');

        $reflectionClass = new \ReflectionClass($translator);

        $reflectionMethod = $reflectionClass->getMethod('isEntityLanguage');
        $reflectionMethod->setAccessible(true);

        $this->assertTrue($reflectionMethod->invoke($translator));

        $translator = new Translator($entity, 'en-GB');

        $this->assertFalse($reflectionMethod->invoke($translator));
    }

    /**
     * translate returns correct value.
     *
     * @return  void
     */
    public function testTranslateReturnsCorrectValuesWithNoEmptyValues()
    {
        $nullDate = '1976-11-16 16:00:00';
        $emptyValues = [null, '', $nullDate];

        $spanishTranslation = $this->getMockBuilder('MockedTranslation')
            ->setMethods(['get'])
            ->getMock();

        $spanishTranslation->method('get')
            ->with($this->equalTo('property'))
            ->will($this->onConsecutiveCalls('translatedValue', null, 'anotherValue', '', 'yetAnotherValue', $nullDate));

        $translator = $this->getMockBuilder(Translator::class)
            ->disableOriginalConstructor()
            ->setMethods(['translation', 'isEntityLanguage'])
            ->getMock();

        $translator->method('translation')
            ->willReturn($spanishTranslation);

        $translator->method('isEntityLanguage')
            ->willReturn(false);

        $reflectionClass = new \ReflectionClass($translator);

        $reflectionProperty = $reflectionClass->getProperty('entity');
        $reflectionProperty->setAccessible(true);
        $reflectionProperty->setValue($translator, new Entity(999));

        $this->assertSame('translatedValue', $translator->translate('property', 'defaultValue'));
        $this->assertSame('defaultValue', $translator->translate('property', 'defaultValue'));
        $this->assertSame('anotherValue', $translator->translate('property', 'defaultValue'));
        $this->assertSame('', $translator->translate('property', 'defaultValue'));
        $this->assertSame('yetAnotherValue', $translator->translate('property', 'defaultValue'));
        $this->assertSame($nullDate, $translator->translate('property', '0000-00-00 00:00:00'));
    }

    /**
     * translate returns correct value.
     *
     * @return  void
     */
    public function testTranslateReturnsCorrectValuesWithNoEmptyColumnValues()
    {
        $nullDate = '1976-11-16 16:00:00';
        $emptyValues = [null, '', $nullDate];

        $spanishTranslation = $this->getMockBuilder(Entity::class)
            ->setMethods(['get'])
            ->getMock();

        $spanishTranslation->method('get')
            ->with('property')
            ->will($this->onConsecutiveCalls('translatedValue', null, 'anotherValue', '', 'yetAnotherValue', $nullDate));

        $translator = $this->getMockBuilder(Translator::class)
            ->disableOriginalConstructor()
            ->setMethods(['translation', 'isEntityLanguage'])
            ->getMock();

        $translator->method('translation')
            ->willReturn($spanishTranslation);

        $translator->method('isEntityLanguage')
            ->willReturn(false);

        $reflection = new \ReflectionClass($translator);

        $entityProperty = $reflection->getProperty('entity');
        $entityProperty->setAccessible(true);
        $entityProperty->setValue($translator, new Entity(999));

        $this->assertSame('translatedValue', $translator->translate('property', 'defaultValue'));
        $this->assertSame('defaultValue', $translator->translate('property', 'defaultValue'));
        $this->assertSame('anotherValue', $translator->translate('property', 'defaultValue'));
        $this->assertSame('', $translator->translate('property', 'defaultValue'));
        $this->assertSame('yetAnotherValue', $translator->translate('property', 'defaultValue'));
        $this->assertSame($nullDate, $translator->translate('property', 'defaultValue'));

        $spanishTranslation = $this->getMockBuilder('MockedTranslation')
            ->setMethods(['get'])
            ->getMock();

        $spanishTranslation->method('get')
            ->with('property2')
            ->will($this->onConsecutiveCalls('translatedValue', null, 'anotherValue', '', 'yetAnotherValue', $nullDate));

        $translator = $this->getMockBuilder(Translator::class)
            ->disableOriginalConstructor()
            ->setMethods(['translation', 'isEntityLanguage'])
            ->getMock();

        $translator->method('translation')
            ->willReturn($spanishTranslation);

        $translator->method('isEntityLanguage')
            ->willReturn(false);

        $reflection = new \ReflectionClass($translator);

        $entityProperty = $reflection->getProperty('entity');
        $entityProperty->setAccessible(true);
        $entityProperty->setValue($translator, new Entity(999));

        $this->assertSame('translatedValue', $translator->translate('property2', 'defaultValue'));
        $this->assertSame('defaultValue', $translator->translate('property2', 'defaultValue'));
        $this->assertSame('anotherValue', $translator->translate('property2', 'defaultValue'));
        $this->assertSame('', $translator->translate('property2', 'defaultValue'));
        $this->assertSame('yetAnotherValue', $translator->translate('property2', 'defaultValue'));
        $this->assertSame('1976-11-16 16:00:00', $translator->translate('property2', 'defaultValue'));
    }

    /**
     * translation returns entity translation.
     *
     * @return  void
     */
    public function testTranslationReturnsEntityTranslation()
    {
        $nullDate = '1976-11-16 16:00:00';
        $emptyValues = [null, '', $nullDate];

        $entity = $this->getMockBuilder('MockedTranslation')
            ->setMethods(['translation'])
            ->getMock();

        $entity->method('translation')
            ->with($this->equalTo('es-ES'))
            ->willReturn(new TranslatableEntity(999));

        $translator = $this->getMockBuilder(Translator::class)
            ->disableOriginalConstructor()
            ->getMock();

        $reflectionClass = new \ReflectionClass($translator);

        $reflectionProperty = $reflectionClass->getProperty('entity');
        $reflectionProperty->setAccessible(true);
        $reflectionProperty->setValue($translator, $entity);

        $langTagProperty = $reflectionClass->getProperty('langTag');
        $langTagProperty->setAccessible(true);
        $langTagProperty->setValue($translator, 'es-ES');

        $reflectionMethod = $reflectionClass->getMethod('translation');
        $reflectionMethod->setAccessible(true);

        $this->assertEquals(new TranslatableEntity(999), $reflectionMethod->invoke($translator));
    }
}
