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

namespace Extly\Joomla\Entity\Tests\Core\Traits;

use Extly\Joomla\Entity\Collection;
use Extly\Joomla\Entity\Tests\Core\Traits\Stubs\EntityWithAssociations;

/**
 * HasAssociations trait tests.
 *
 * @since   1.1.0
 */
class HasAssociationsTest extends \PHPUnit\Framework\TestCase
{
    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     *
     * @return  void
     */
    protected function tearDown()
    {
        EntityWithAssociations::clearAll();

        parent::tearDown();
    }

    /**
     * Association throws exception when association does not exist.
     *
     * @return  void
     *
     * @expectedException  \InvalidArgumentException
     */
    public function testAssociationThrowsExceptionWhenAssociationDoesNotExist()
    {
        $entityWithAssociations = new EntityWithAssociations();

        $reflectionClass = new \ReflectionClass($entityWithAssociations);

        $reflectionProperty = $reflectionClass->getProperty('associations');
        $reflectionProperty->setAccessible(true);

        $reflectionProperty->setValue($entityWithAssociations, []);

        $entityWithAssociations->association('es-ES');
    }

    /**
     * association returns correct association.
     *
     * @return  void
     */
    public function testAssociationReturnsCorrectAssociation()
    {
        $entityWithAssociations = new EntityWithAssociations();

        $cachedAssociations = [
            'es-ES' => new EntityWithAssociations(666),
            'pt-BR' => new EntityWithAssociations(999),
        ];

        $reflectionClass = new \ReflectionClass($entityWithAssociations);

        $reflectionProperty = $reflectionClass->getProperty('associations');
        $reflectionProperty->setAccessible(true);

        $reflectionProperty->setValue($entityWithAssociations, $cachedAssociations);

        $this->assertSame($cachedAssociations['es-ES'], $entityWithAssociations->association('es-ES'));
        $this->assertSame($cachedAssociations['pt-BR'], $entityWithAssociations->association('pt-BR'));
    }

    /**
     * Associations returns empty array for no associations.
     *
     * @return  void
     */
    public function testAssociationsReturnsEmptyArrayForNoAssociations()
    {
        $entityWithAssociations = new EntityWithAssociations();

        $this->assertSame([], $entityWithAssociations->associations());
    }

    /**
     * Associations returns cached data.
     *
     * @return  void
     */
    public function testAssociationsReturnsCachedData()
    {
        $entityWithAssociations = new EntityWithAssociations();

        $cachedAssociations = [
            'es-ES' => new EntityWithAssociations(666),
            'pt-BR' => new EntityWithAssociations(999),
        ];

        $reflectionClass = new \ReflectionClass($entityWithAssociations);

        $reflectionProperty = $reflectionClass->getProperty('associations');
        $reflectionProperty->setAccessible(true);

        $reflectionProperty->setValue($entityWithAssociations, $cachedAssociations);

        $this->assertSame($cachedAssociations, $entityWithAssociations->associations());
    }

    /**
     * associations calls loadAssociations when no cached associations.
     *
     * @return  void
     */
    public function testAssociationsCallsLoadAssociationsWhenNoCachedAssociations()
    {
        $associations = [
            'es-ES' => new EntityWithAssociations(666),
            'pt-BR' => new EntityWithAssociations(999),
        ];

        $phpUnitFrameworkMockObjectMockObject = $this->getMockBuilder(EntityWithAssociations::class)
            ->setMethods(['loadAssociations'])
            ->getMock();

        $phpUnitFrameworkMockObjectMockObject->expects($this->once())
            ->method('loadAssociations')
            ->willReturn($associations);

        $this->assertSame($associations, $phpUnitFrameworkMockObjectMockObject->associations());
    }

    /**
     * Associations reloads data when reload is true.
     *
     * @return  void
     */
    public function testAssociationsReloadsDataWhenReloadIsTrue()
    {
        $associations = [
            'es-ES' => new EntityWithAssociations(666),
            'pt-BR' => new EntityWithAssociations(999),
        ];

        $phpUnitFrameworkMockObjectMockObject = $this->getMockBuilder(EntityWithAssociations::class)
            ->setMethods(['loadAssociations'])
            ->getMock();

        $phpUnitFrameworkMockObjectMockObject->expects($this->once())
            ->method('loadAssociations')
            ->willReturn($associations);

        $reflectionClass = new \ReflectionClass($phpUnitFrameworkMockObjectMockObject);

        $reflectionProperty = $reflectionClass->getProperty('associations');
        $reflectionProperty->setAccessible(true);

        $reflectionProperty->setValue($phpUnitFrameworkMockObjectMockObject, []);

        $this->assertSame([], $phpUnitFrameworkMockObjectMockObject->associations());
        $this->assertSame($associations, $phpUnitFrameworkMockObjectMockObject->associations(true));
    }

    /**
     * associationsIds returns empty array for no associations.
     *
     * @return  void
     */
    public function testAssociationsIdsReturnsEmptyArrayForNoAssociations()
    {
        $entityWithAssociations = new EntityWithAssociations();

        $this->assertSame([], $entityWithAssociations->associationsIds());
    }

    /**
     * associationsIdsReturnsCorrectData.
     *
     * @return  void
     */
    public function testAssociationsIdsReturnsCorrectData()
    {
        $entityWithAssociations = new EntityWithAssociations();

        $cachedAssociations = [
            'es-ES' => new EntityWithAssociations(666),
            'pt-BR' => new EntityWithAssociations(999),
        ];

        $reflectionClass = new \ReflectionClass($entityWithAssociations);

        $reflectionProperty = $reflectionClass->getProperty('associations');
        $reflectionProperty->setAccessible(true);

        $reflectionProperty->setValue($entityWithAssociations, $cachedAssociations);

        $this->assertSame(['es-ES' => 666, 'pt-BR' => 999], $entityWithAssociations->associationsIds());
    }

    /**
     * hasAssociation returns correct value.
     *
     * @return  void
     */
    public function testHasAssociationReturnsCorrectValue()
    {
        $entityWithAssociations = new EntityWithAssociations();

        $reflectionClass = new \ReflectionClass($entityWithAssociations);

        $reflectionProperty = $reflectionClass->getProperty('associations');
        $reflectionProperty->setAccessible(true);

        $reflectionProperty->setValue($entityWithAssociations, []);

        $this->assertSame(false, $entityWithAssociations->hasAssociation('es-ES'));
        $this->assertSame(false, $entityWithAssociations->hasAssociation('pt-BR'));
        $this->assertSame(false, $entityWithAssociations->hasAssociation('es-AR'));

        $associations = [
            'es-ES' => new EntityWithAssociations(666),
            'pt-BR' => new EntityWithAssociations(999),
        ];

        $reflectionProperty->setValue($entityWithAssociations, $associations);

        $this->assertSame(true, $entityWithAssociations->hasAssociation('es-ES'));
        $this->assertSame(true, $entityWithAssociations->hasAssociation('pt-BR'));
        $this->assertSame(false, $entityWithAssociations->hasAssociation('es-AR'));
    }

    /**
     * hasAssociationById returns correct value.
     *
     * @return  void
     */
    public function testHasAssociationByIdReturnsCorrectValue()
    {
        $entityWithAssociations = new EntityWithAssociations();

        $reflectionClass = new \ReflectionClass($entityWithAssociations);

        $reflectionProperty = $reflectionClass->getProperty('associations');
        $reflectionProperty->setAccessible(true);

        $reflectionProperty->setValue($entityWithAssociations, []);

        $this->assertSame(false, $entityWithAssociations->hasAssociationById(333));
        $this->assertSame(false, $entityWithAssociations->hasAssociationById(666));
        $this->assertSame(false, $entityWithAssociations->hasAssociationById(999));

        $associations = [
            'es-ES' => new EntityWithAssociations(666),
            'pt-BR' => new EntityWithAssociations(999),
        ];

        $reflectionProperty->setValue($entityWithAssociations, $associations);

        $this->assertSame(false, $entityWithAssociations->hasAssociationById(333));
        $this->assertSame(true, $entityWithAssociations->hasAssociationById(666));
        $this->assertSame(true, $entityWithAssociations->hasAssociationById(999));
    }

    /**
     * hasAssociations returns correct value.
     *
     * @return  void
     */
    public function testHasAssociationsReturnsCorrectValue()
    {
        $entityWithAssociations = new EntityWithAssociations();

        $reflectionClass = new \ReflectionClass($entityWithAssociations);

        $reflectionProperty = $reflectionClass->getProperty('associations');
        $reflectionProperty->setAccessible(true);

        $reflectionProperty->setValue($entityWithAssociations, []);

        $this->assertSame(false, $entityWithAssociations->hasAssociations());

        $associations = [
            'es-ES' => new EntityWithAssociations(666),
            'pt-BR' => new EntityWithAssociations(999),
        ];

        $reflectionProperty->setValue($entityWithAssociations, $associations);

        $this->assertSame(true, $entityWithAssociations->hasAssociations());
    }
}
