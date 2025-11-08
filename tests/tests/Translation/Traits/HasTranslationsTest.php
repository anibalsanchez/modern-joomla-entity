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

namespace Extly\Joomla\Entity\Tests\Translation\Traits;

use Extly\Joomla\Entity\Collection;
use Extly\Joomla\Entity\Tests\Translation\Traits\Stubs\EntityWithTranslations;

/**
 * HasTranslations trait tests.
 *
 * @since   1.1.0
 */
class HasTranslationsTest extends \PHPUnit\Framework\TestCase
{
    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     *
     * @return  void
     */
    protected function tearDown()
    {
        EntityWithTranslations::clearAll();

        parent::tearDown();
    }

    /**
     * hasTranslation returns correct value.
     *
     * @return  void
     */
    public function testHastranslationReturnsCorrectValue()
    {
        $entity = new EntityWithTranslations();

        $this->assertSame(false, $entity->hasTranslation('es-ES'));
        $this->assertSame(false, $entity->hasTranslation('es-AR'));
        $this->assertSame(false, $entity->hasTranslation('pt-BR'));

        $translations = [
            'es-ES' => EntityWithTranslations::find(666),
            'pt-BR' => EntityWithTranslations::find(999),
        ];

        $entity = $this->getMockBuilder(EntityWithTranslations::class)
            ->setMethods(['translationsByTag'])
            ->getMock();

        $entity->expects($this->exactly(3))
            ->method('translationsByTag')
            ->willReturn($translations);

        $this->assertSame(true, $entity->hasTranslation('es-ES'));
        $this->assertSame(false, $entity->hasTranslation('es-AR'));
        $this->assertSame(true, $entity->hasTranslation('pt-BR'));
    }

    /**
     * hasTranslations returns correct value.
     *
     * @return  void
     */
    public function testHasTranslationsReturnsCorrectValue()
    {
        $entity = new EntityWithTranslations();

        $this->assertSame(false, $entity->hasTranslations());

        $collection = new Collection(
            [
                EntityWithTranslations::find(666),
                EntityWithTranslations::find(999),
            ]
        );

        $entity = $this->getMockBuilder(EntityWithTranslations::class)
            ->setMethods(['translations'])
            ->getMock();

        $entity->expects($this->once())
            ->method('translations')
            ->willReturn($collection);

        $this->assertSame(true, $entity->hasTranslations());
    }

    /**
     * translation returns correct value.
     *
     * @return  void
     */
    public function testTranslationRetursnCorrectValue()
    {
        $translations = [
            'es-ES' => EntityWithTranslations::find(666),
            'pt-BR' => EntityWithTranslations::find(999),
        ];

        $entityWithTranslations = new EntityWithTranslations();
        $reflectionClass = new \ReflectionClass($entityWithTranslations);

        $reflectionProperty = $reflectionClass->getProperty('translationsByTag');
        $reflectionProperty->setAccessible(true);
        $reflectionProperty->setValue($entityWithTranslations, $translations);

        $this->assertSame(EntityWithTranslations::find(666), $entityWithTranslations->translation('es-ES'));
    }

    /**
     * translation throws an exception trying to retrieve a missing translation.
     *
     * @return  void
     *
     * @expectedException  \InvalidArgumentException
     */
    public function testTranslationThrowsExceptionForMissingTranslation()
    {
        $entityWithTranslations = new EntityWithTranslations();

        $entityWithTranslations->translation('es-ES');
    }

    /**
     * translationsByTag returns correct data.
     *
     * @return  void
     */
    public function testTranslationsByTagReturnsCorrectData()
    {
        $entity = new EntityWithTranslations();

        $reflectionClass = new \ReflectionClass($entity);

        $reflectionProperty = $reflectionClass->getProperty('id');
        $reflectionProperty->setAccessible(true);

        $rowProperty = $reflectionClass->getProperty('row');
        $rowProperty->setAccessible(true);

        $this->assertEquals([], $entity->translationsByTag());

        $entities = [
            666 => ['id' => 666, 'title' => 'Spanish translation', 'lang' => 'es-ES'],
            999 => ['id' => 999, 'title' => 'Brasialian translation', 'lang' => 'pt-BR'],
        ];

        $spanish = new EntityWithTranslations(666);
        $rowProperty->setValue($spanish, $entities[666]);

        $brasilian = new EntityWithTranslations(999);
        $rowProperty->setValue($brasilian, $entities[999]);

        $phpUnitFrameworkMockObjectMockObject = $this->getMockBuilder('TableMock')
            ->disableOriginalConstructor()
            ->setMethods(['getColumnAlias'])
            ->getMock();

        $phpUnitFrameworkMockObjectMockObject->expects($this->exactly(2))
            ->method('getColumnAlias')
            ->willReturn('lang');

        $entity = $this->getMockBuilder(EntityWithTranslations::class)
            ->setMethods(['table', 'translations'])
            ->getMock();

        $entity->expects($this->exactly(2))
            ->method('table')
            ->willReturn($phpUnitFrameworkMockObjectMockObject);

        $entity->expects($this->once())
            ->method('translations')
            ->willReturn(new Collection([$spanish, $brasilian]));

        $expected = [
            'es-ES' => $spanish,
            'pt-BR' => $brasilian,
        ];

        $this->assertSame($expected, $entity->translationsByTag());
    }

    /**
     * translations returns expected translations.
     *
     * @return  void
     */
    public function testTranslationsReturnsExpectedTranslatons()
    {
        $entityWithTranslations = new EntityWithTranslations();

        $this->assertEquals(new Collection(), $entityWithTranslations->translations());

        $entityWithTranslations->translationsIds = [666, 999];

        $collection = new Collection(
            [
                EntityWithTranslations::find(666),
                EntityWithTranslations::find(999),
            ]
        );

        // No reload = same data
        $this->assertEquals(new Collection(), $entityWithTranslations->translations());
        $this->assertEquals($collection, $entityWithTranslations->translations(true));
    }
}
