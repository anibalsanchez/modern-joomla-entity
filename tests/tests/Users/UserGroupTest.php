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

namespace Extly\Joomla\Entity\Tests\Users;

use Extly\Joomla\Entity\Collection;
use Extly\Joomla\Entity\Users\Column;
use Extly\Joomla\Entity\Users\User;
use Extly\Joomla\Entity\Users\UserGroup;
use Joomla\Registry\Registry;

/**
 * UserGroup entity tests.
 *
 * @since   1.1.0
 */
class UserGroupTest extends \TestCaseDatabase
{
    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     *
     * @return  void
     */
    protected function setUp()
    {
        parent::setUp();

        $this->saveFactoryState();

        \Joomla\CMS\Factory::$session = $this->getMockSession();
        \Joomla\CMS\Factory::$config = $this->getMockConfig();
        \Joomla\CMS\Factory::$application = $this->getMockCmsApp();
    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     *
     * @return  void
     */
    protected function tearDown()
    {
        UserGroup::clearAll();

        $this->restoreFactoryState();

        parent::tearDown();
    }

    /**
     * loadUsers returns empty collection for entities without id.
     *
     * @return  void
     */
    public function testLoadUsersReturnsEmptyCollectionForEntitiesWithoutId()
    {
        $userGroup = new UserGroup();

        $reflectionClass = new \ReflectionClass($userGroup);
        $reflectionMethod = $reflectionClass->getMethod('loadUsers');
        $reflectionMethod->setAccessible(true);

        $this->assertEquals(new Collection(), $reflectionMethod->invoke($userGroup));
    }

    /**
     * loadUsers returns correct collection for entities with id.
     *
     * @return  void
     */
    public function testLoadUsersReturnsCorrectCollectionForEntitiesWithId()
    {
        $users = [
            333 => ['id' => 333, 'name' => 'Héctor Tilla'],
            666 => ['id' => 666, 'name' => 'Carmelo Cotón'],
            999 => ['id' => 999, 'name' => 'Ricardo Borriquero'],
        ];

        $items = [
            (object) $users[666],
            (object) $users[999],
        ];

        $usersModel = $this->getMockBuilder('UsersModelMock')
            ->disableOriginalConstructor()
            ->setMethods(['getItems'])
            ->getMock();

        $usersModel->expects($this->once())
            ->method('getItems')
            ->willReturn($items);

        $entity = $this->getMockBuilder(UserGroup::class)
            ->setMethods(['usersModel'])
            ->getMock();

        $entity->expects($this->once())
            ->method('usersModel')
            ->willReturn($usersModel);

        $reflectionClass = new \ReflectionClass($entity);

        $reflectionProperty = $reflectionClass->getProperty('id');
        $reflectionProperty->setAccessible(true);
        $reflectionProperty->setValue($entity, 333);

        $reflectionMethod = $reflectionClass->getMethod('loadUsers');
        $reflectionMethod->setAccessible(true);

        $user666 = new User(666);
        $user666->bind($users[666]);

        $user999 = new User(999);
        $user999->bind($users[999]);

        $collection = new Collection([$user666, $user999]);

        $this->assertEquals($collection, $reflectionMethod->invoke($entity));
    }

    /**
     * table returns correct table instance.
     *
     * @return  void
     */
    public function testTableReturnsCorrectTableInstance()
    {
        $userGroup = new UserGroup();

        $this->assertInstanceOf('JTableUsergroup', $userGroup->table());
    }

    /**
     * usersModel returns correct value.
     *
     * @return  void
     */
    public function testUsersModelReturnsCorrectValue()
    {
        $entity = new UserGroup();

        $reflectionClass = new \ReflectionClass($entity);
        $reflectionMethod = $reflectionClass->getMethod('usersModel');
        $reflectionMethod->setAccessible(true);

        $model = $reflectionMethod->invoke($entity);

        $this->assertInstanceOf('UsersModelUsers', $model);
        $this->assertSame(null, $model->getState('filter.group_id'));

        $entity = new UserGroup(34);

        $model = $reflectionMethod->invoke($entity);

        $this->assertInstanceOf('UsersModelUsers', $model);
        $this->assertSame(34, $model->getState('filter.group_id'));
    }

    /**
     * Gets the data set to be loaded into the database during setup
     *
     * @return  \PHPUnit_Extensions_Database_DataSet_CsvDataSet
     */
    protected function getDataSet()
    {
        $phpUnitExtensionsDatabaseDataSetCsvDataSet = new \PHPUnit_Extensions_Database_DataSet_CsvDataSet(',', "'", '\\');
        $phpUnitExtensionsDatabaseDataSetCsvDataSet->addTable('jos_users', JPATH_TEST_DATABASE.'/jos_users.csv');
        $phpUnitExtensionsDatabaseDataSetCsvDataSet->addTable('jos_usergroups', JPATH_TEST_DATABASE.'/jos_usergroups.csv');
        $phpUnitExtensionsDatabaseDataSetCsvDataSet->addTable('jos_user_usergroup_map', JPATH_TEST_DATABASE.'/jos_user_usergroup_map.csv');

        return $phpUnitExtensionsDatabaseDataSetCsvDataSet;
    }
}
