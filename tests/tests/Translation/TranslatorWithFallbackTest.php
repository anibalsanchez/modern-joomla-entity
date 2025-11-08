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

namespace Extly\Joomla\Entity\Tests\Core\Decorator;

use Extly\Joomla\Entity\Tests\Stubs\Entity;
use Extly\Joomla\Entity\Tests\Translation\Stubs\TranslatableEntity;
use Extly\Joomla\Entity\Translation\TranslatorWithFallback;
use Extly\Joomla\Entity\Validation\Rule\CustomRule;
use Extly\Joomla\Entity\Validation\Validator;

/**
 * TranslatorWithFallback decorator tests.
 *
 * @since   1.1.0
 */
class TranslatorWithFallbackTest extends \TestCase
{
    /**
     * translateIf returns correct value.
     *
     * @return  void
     */
    public function testTranslateReturnsCorrectValue()
    {
        $spanishTranslation = $this->getMockBuilder('MockedTranslation')
            ->setMethods(['get'])
            ->getMock();

        $spanishTranslation->method('get')
            ->with($this->equalTo('property'))
            ->will($this->onConsecutiveCalls('translatedValue', 'validValue', 'invalidValue', 'validValue'));

        $translatableEntity = new TranslatableEntity();
        $translatableEntity->bind(['id' => 999, 'language' => 'en-GB', 'property' => 'entityValue']);

        $translator = $this->getMockBuilder(TranslatorWithFallback::class)
            ->disableOriginalConstructor()
            ->setMethods(['translation', 'isEntityLanguage'])
            ->getMock();

        $translator->method('translation')
            ->willReturn($spanishTranslation);

        $translator->method('isEntityLanguage')
            ->willReturn(false);

        $reflection = new \ReflectionClass($translator);

        $reflectionProperty = $reflection->getProperty('entity');
        $reflectionProperty->setAccessible(true);
        $reflectionProperty->setValue($translator, $translatableEntity);

        $langTagProperty = $reflection->getProperty('langTag');
        $langTagProperty->setAccessible(true);
        $langTagProperty->setValue($translator, 'es-ES');

        $validator = new Validator($translatableEntity);

        $validator->addRule(
            new CustomRule(
                fn ($value) => $value === 'validValue'
            ),
            'property'
        );

        $translator->setValidator($validator);

        $this->assertSame('defaultValue', $translator->translate('property', 'defaultValue'));
        $this->assertSame('validValue', $translator->translate('property', 'defaultValue'));

        $validatorReflection = new \ReflectionClass($validator);
        $rulesProperty = $validatorReflection->getProperty('rules');
        $rulesProperty->setAccessible(true);
        $rulesProperty->setValue($validator, []);

        $validator->addRule(
            new CustomRule(
                fn ($value) => in_array($value, ['validValue', 'entityValue'], true)
            ),
            'property'
        );

        $translator->setValidator($validator);

        $this->assertSame('entityValue', $translator->translate('property', 'defaultValue'));
        $this->assertSame('validValue', $translator->translate('property', 'defaultValue'));
    }

    /**
     * translate returns entity value if is entity language.
     *
     * @return  void
     */
    public function testTranslateReturnsEntityValueIfIsEntityLanguage()
    {
        $entity = $this->getMockBuilder(Entity::class)
            ->setMethods(['get'])
            ->getMock();

        $entity->method('get')
            ->with($this->equalTo('property'))
            ->will($this->onConsecutiveCalls('value', '', null, 'anotherValue', '0000-00-00 00:00:00'));

        $translator = $this->getMockBuilder(TranslatorWithFallback::class)
            ->disableOriginalConstructor()
            ->setMethods(['isEntityLanguage'])
            ->getMock();

        $translator->method('isEntityLanguage')
            ->willReturn(true);

        $reflectionClass = new \ReflectionClass($translator);

        $reflectionProperty = $reflectionClass->getProperty('entity');
        $reflectionProperty->setAccessible(true);
        $reflectionProperty->setValue($translator, $entity);

        $this->assertSame('value', $translator->translate('property', 'default'));
        $this->assertSame('', $translator->translate('property', 'default'));
        $this->assertSame('default', $translator->translate('property', 'default'));
        $this->assertSame('anotherValue', $translator->translate('property', 'default'));
        $this->assertSame('0000-00-00 00:00:00', $translator->translate('property', 'default'));
    }

    /**
     * translate returns translation value if not entity language.
     *
     * @return  void
     */
    public function testTranslateReturnsTranslationValueIfNotEntityLanguage()
    {
        $translation = $this->getMockBuilder(Entity::class)
            ->setMethods(['get'])
            ->getMock();

        $translation->method('get')
            ->with($this->equalTo('property'))
            ->will($this->onConsecutiveCalls('value', '', null, 'anotherValue', '0000-00-00 00:00:00'));

        $translator = $this->getMockBuilder(TranslatorWithFallback::class)
            ->disableOriginalConstructor()
            ->setMethods(['isEntityLanguage', 'translation'])
            ->getMock();

        $translator->method('isEntityLanguage')
            ->willReturn(false);

        $translator->method('translation')
            ->willReturn($translation);

        $entity = new Entity(999);
        $entity->bind(['id' => 999, 'property' => 'entityValue']);

        $reflectionClass = new \ReflectionClass($translator);

        $reflectionProperty = $reflectionClass->getProperty('entity');
        $reflectionProperty->setAccessible(true);
        $reflectionProperty->setValue($translator, $entity);

        $this->assertSame('value', $translator->translate('property', 'default'));
        $this->assertSame('', $translator->translate('property', 'default'));
        $this->assertSame('entityValue', $translator->translate('property', 'default'));
        $this->assertSame('anotherValue', $translator->translate('property', 'default'));
        $this->assertSame('0000-00-00 00:00:00', $translator->translate('property', 'default'));
    }
}
