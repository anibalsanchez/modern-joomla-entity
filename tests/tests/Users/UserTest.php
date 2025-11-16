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

use Extly\Joomla\Entity\Acl\Acl;
use Extly\Joomla\Entity\Collection;
use Extly\Joomla\Entity\Core\Column as CoreColumn;
use Extly\Joomla\Entity\Users\User;
use Extly\Joomla\Entity\Users\UserGroup;
use Joomla\CMS\User\UserHelper;
use Joomla\Registry\Registry;

/**
 * User entity tests.
 *
 * @since   1.1.0
 */
class UserTest extends \TestCaseDatabase
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
        User::clearAll();
        User::clearActive();

        $this->restoreFactoryState();

        parent::tearDown();
    }

    /**
     * @test
     *
     * @return  void
     */
    public function activeReturnsActiveJoomlaValue()
    {
        $mockSession = $this->getMockBuilder('JSession')
            ->setMethods(['_start', 'get'])
            ->getMock();

        $mockSession->expects($this->once())
            ->method('get')
            ->will($this->returnValue(new \JUser(42)));

        \Joomla\CMS\Factory::$session = $mockSession;

        $this->assertEquals(User::find(42), User::active());
    }

    /**
     * @test
     *
     * @return void
     */
    public function activeReturnsCachedInstance()
    {
        $reflectionClass = new \ReflectionClass(User::class);
        $reflectionProperty = $reflectionClass->getProperty('active');
        $reflectionProperty->setAccessible(true);

        $this->assertSame(null, $reflectionProperty->getValue(User::class));

        $activeUser = new User(999);

        $reflectionProperty->setValue(User::class, $activeUser);

        $this->assertSame($activeUser, User::active());
    }

    /**
     * @test
     *
     * @return void
     */
    public function activeReturnsUnloadedInstance()
    {
        $this->assertEquals(new User(), User::active());
    }

    /**
     * @test
     *
     * @return void
     */
    public function addToUserGroupWorks()
    {
        $user = User::find(42);

        $this->assertEquals([8], $user->userGroupsIds());
        $this->assertEquals([8], $user->userGroups()->ids());

        $user->addToUserGroup(8);

        $this->assertEquals([8], $user->userGroupsIds());
        $this->assertEquals([8], $user->userGroups()->ids());

        $user->addToUserGroup(5);

        $this->assertEquals([5, 8], $user->userGroupsIds());
        $this->assertEquals([5, 8], $user->userGroups()->ids());

        $user->addToUserGroup(1);

        $this->assertEquals([1, 5, 8], $user->userGroupsIds());
        $this->assertEquals([1, 5, 8], $user->userGroups()->ids());
    }

    /**
     * @test
     *
     * @return void
     */
    public function addtoUserGroupsWorks()
    {
        $user = User::find(42);

        $this->assertEquals([8], $user->userGroupsIds());
        $this->assertEquals([8], $user->userGroups()->ids());

        $user->addToUserGroups([]);

        $this->assertEquals([8], $user->userGroupsIds());
        $this->assertEquals([8], $user->userGroups()->ids());

        $user->addToUserGroups([8]);

        $this->assertEquals([8], $user->userGroupsIds());
        $this->assertEquals([8], $user->userGroups()->ids());

        $user->addToUserGroups([5, 1]);

        $this->assertEquals([1, 5, 8], $user->userGroupsIds());
        $this->assertEquals([1, 5, 8], $user->userGroups()->ids());
    }

    /**
     * @test
     *
     * @return void
     */
    public function changePasswordChangesUserPassword()
    {
        $user = User::find(42);

        $currentPassword = $user->get('password');

        $newPassword = 'myNewPassword';
        $newPasswordHash = UserHelper::hashPassword($newPassword);

        $this->assertNotEmpty($currentPassword);

        $user->changePassword($newPassword);

        // Raw password is assigned just in case there is some post-processing needed
        $this->assertSame($newPassword, $user->get('raw_password'));

        User::clear(42);

        $reloadedUser = User::find(42);

        $this->assertNotSame($currentPassword, $newPasswordHash);
        $canLogin = UserHelper::verifyPassword($newPassword, $reloadedUser->get('password'), $reloadedUser->id());

        $this->assertTrue($canLogin);
    }

    /**
     * @test
     *
     * @return void
     *
     * @expectedException  \RuntimeException
     */
    public function changePasswordThrowsExceptionForUnsavedUser()
    {
        $user = new User();
        $user->changePassword('admin1234');
    }

    /**
     * @test
     *
     * @return void
     *
     * @expectedException  \InvalidArgumentException
     */
    public function changePasswordThrowsExceptionForEmptyNewPassword()
    {
        $user = User::find(42);
        $user->changePassword('');
    }

    /**
     * @test
     *
     * @return void
     */
    public function eventsPluginsReturnsUserPluginType()
    {
        $user = User::find(42);

        $reflectionClass = new \ReflectionClass($user);
        $reflectionMethod = $reflectionClass->getMethod('eventsPlugins');
        $reflectionMethod->setAccessible(true);

        $this->assertTrue(in_array('user', $reflectionMethod->invoke($user), true));
    }

    /**
     * @test
     *
     * @return void
     */
    public function clearActiveUnsetsActiveUser()
    {
        $reflectionClass = new \ReflectionClass(User::class);
        $reflectionProperty = $reflectionClass->getProperty('active');
        $reflectionProperty->setAccessible(true);

        $activeUser = new User(999);
        $reflectionProperty->setValue(User::class, $activeUser);

        $this->assertSame($activeUser, $reflectionProperty->getValue(User::class));

        User::clearActive();

        $this->assertSame(null, $reflectionProperty->getValue(User::class));
    }

    /**
     * @test
     *
     * @return void
     */
    public function loadFromDataReturnsExpectedValue()
    {
        $user = User::loadFromData(['username' => 'admin']);

        $this->assertInstanceOf(User::class, $user);
        $this->assertTrue($user->hasId());
        $this->assertSame('Super User', $user->get('name'));
    }

    /**
     * @test
     *
     * @return void
     */
    public function loadFromDataReturnsFalseForNotFoundUser()
    {
        $user = User::loadFromData(['username' => 'osm']);

        $this->assertSame(false, $user);
    }

    /**
     * @test
     *
     * @return void
     *
     * @expectedException  \UnexpectedValueException
     */
    public function loadFromDataThrowsExceptionForWrongData()
    {
        $user = User::loadFromData(['unexistingColumn' => 'column']);
    }

    /**
     * @test
     *
     * @return void
     */
    public function removeFromAllUserGroupsWorks()
    {
        $user = User::find(42);

        $user->addToUserGroups([5, 1, 6]);

        $this->assertEquals([1, 5, 6, 8], $user->userGroupsIds());
        $this->assertEquals([1, 5, 6, 8], $user->userGroups()->ids());

        $user->removeFromAllUserGroups();

        $this->assertEquals([], $user->userGroupsIds());
        $this->assertEquals([], $user->userGroups()->ids());
    }

    /**
     * @test
     *
     * @return void
     */
    public function removeFromUserGroupWorks()
    {
        $user = User::find(42);
        $user->addToUserGroups([5, 1, 6]);

        $this->assertEquals([1, 5, 6, 8], $user->userGroupsIds());
        $this->assertEquals([1, 5, 6, 8], $user->userGroups()->ids());

        $user->removeFromUserGroup(5);

        $this->assertEquals([1, 6, 8], $user->userGroupsIds());
        $this->assertEquals([1, 6, 8], $user->userGroups()->ids());

        $user->removeFromUserGroup(5);

        $this->assertEquals([1, 6, 8], $user->userGroupsIds());
        $this->assertEquals([1, 6, 8], $user->userGroups()->ids());
    }

    /**
     * @test
     *
     * @return void
     */
    public function removeFromUserGroupsWorks()
    {
        $user = User::find(42);
        $user->addToUserGroups([5, 1, 6]);

        $this->assertEquals([1, 5, 6, 8], $user->userGroupsIds());
        $this->assertEquals([1, 5, 6, 8], $user->userGroups()->ids());

        $user->removeFromUserGroups([5, 1, 8]);

        $this->assertEquals([6], $user->userGroupsIds());
        $this->assertEquals([6], $user->userGroups()->ids());

        $user->removeFromUserGroups([4]);

        $this->assertEquals([6], $user->userGroupsIds());
        $this->assertEquals([6], $user->userGroups()->ids());

        $user->removeFromUserGroups([6]);

        $this->assertEquals([], $user->userGroupsIds());
        $this->assertEquals([], $user->userGroups()->ids());
    }

    /**
     * @test
     *
     * @return void
     */
    public function saveTriggersOnUserAfterSaveEventWhenSaveWorks()
    {
        $savedData = [
            'id'       => 23,
            'name'     => 'Epic User',
            'username' => 'epic-user',
            'email'    => 'epicUser@examle.com',
        ];

        $dispatcher = $this->getMockBuilder(\JEventDispatcher::class)
            ->setMethods(['trigger'])
            ->getMock();

        $dispatcher->method('trigger')
            ->with(
                $this->equalTo('onUserAfterSave')
            )
            ->willReturn(true);

        $user = $this->getMockBuilder(User::class)
            ->setMethods(['dispatcher'])
            ->getMock();

        $user->method('dispatcher')
            ->willReturn($dispatcher);

        $user->bind($savedData);
        $user->save();
    }

    /**
     * @test
     *
     * @return void
     */
    public function saveTriggersOnUserAfterSaveEventWhenSaveFails()
    {
        $savedData = [
            'id'       => 23,
            'name'     => 'Epic User',
        ];

        $dispatcher = $this->getMockBuilder(\JEventDispatcher::class)
            ->setMethods(['trigger'])
            ->getMock();

        $dispatcher->method('trigger')
            ->with(
                $this->equalTo('onUserAfterSave')
            )
            ->willReturn(true);

        $user = $this->getMockBuilder(User::class)
            ->setMethods(['dispatcher'])
            ->getMock();

        $user->method('dispatcher')
            ->willReturn($dispatcher);

        $error = '';
        $user->bind($savedData);

        try {
            $user->save();
        } catch (\Exception $exception) {
            $error = $exception->getMessage();
        }

        $this->assertNotSame('', $error);
    }

    /**
     * @test
     *
     * @return void
     */
    public function setActiveSetsActiveUser()
    {
        User::setActive(new User(999));

        $this->assertSame(999, User::active()->id());

        User::setActive(new User(666));

        $this->assertSame(666, User::active()->id());
    }

    /**
     * Acl can be retrieved.
     *
     * @return  void
     */
    public function testAclCanBeRetrieved()
    {
        $entity = new User(666);
        $user = new User(999);

        $acl = $entity->acl($user);

        $reflectionClass = new \ReflectionClass($acl);
        $reflectionProperty = $reflectionClass->getProperty('entity');
        $reflectionProperty->setAccessible(true);

        $userProperty = $reflectionClass->getProperty('user');
        $userProperty->setAccessible(true);

        $this->assertInstanceOf(Acl::class, $acl);
        $this->assertSame($user, $userProperty->getValue($acl));
        $this->assertSame($entity, $reflectionProperty->getValue($acl));
    }

    /**
     * authorise returns true for root.
     *
     * @return  void
     */
    public function testAuthoriseReturnsTrueForRoot()
    {
        $user = $this->getMockBuilder(User::class)
            ->setMethods(['isRoot', 'joomlaUser'])
            ->getMock();

        $user->expects($this->once())
            ->method('isRoot')
            ->willReturn(true);

        $user->expects($this->exactly(0))
            ->method('joomlaUser')
            ->willReturn(null);

        $this->assertTrue($user->authorise('sample.action'));
    }

    /**
     * authorise returns juser authorise.
     *
     * @return  void
     */
    public function testAuthoriseReturnsJUserAuthorise()
    {
        $joomlaUser = $this->getMockBuilder('MockeJoomlaUser')
            ->setMethods(['authorise'])
            ->getMock();

        $joomlaUser->expects($this->once())
            ->method('authorise')
            ->willReturn(true);

        $user = $this->getMockBuilder(User::class)
            ->setMethods(['isRoot', 'joomlaUser'])
            ->getMock();

        $user->expects($this->once())
            ->method('isRoot')
            ->willReturn(false);

        $user->expects($this->once())
            ->method('joomlaUser')
            ->willReturn($joomlaUser);

        $this->assertTrue($user->authorise('sample.action'));
    }

    /**
     * authorise will return false if joomlaUser throws exception.
     *
     * @return  void
     */
    public function testAuthoriseWillReturnFalseIfJoomlaUserThrowsException()
    {
        $joomlaUser = $this->getMockBuilder('MockeJoomlaUser')
            ->setMethods(['authorise'])
            ->getMock();

        $joomlaUser->expects($this->once())
            ->method('authorise')
            ->will($this->throwException(new \Exception('User failure')));

        $user = $this->getMockBuilder(User::class)
            ->setMethods(['isRoot', 'joomlaUser'])
            ->getMock();

        $user->expects($this->once())
            ->method('isRoot')
            ->willReturn(false);

        $user->expects($this->once())
            ->method('joomlaUser')
            ->willReturn($joomlaUser);

        $this->assertFalse($user->authorise('sample.action'));
    }

    /**
     * entity instance can be retrieved.
     *
     * @return  void
     */
    public function testEntityInstanceRetrieved()
    {
        $user = new User();

        $this->assertInstanceOf(User::class, $user);

        $user = new User(12);

        $this->assertInstanceOf(User::class, $user);
    }

    /**
     * isActivated returns correct value.
     *
     * @return  void
     */
    public function testIsActivatedReturnsCorrectValue()
    {
        $user = new User();

        $this->assertSame(false, $user->isActivated());

        $user = new User(999);

        $reflectionClass = new \ReflectionClass($user);
        $reflectionProperty = $reflectionClass->getProperty('row');
        $reflectionProperty->setAccessible(true);

        $reflectionProperty->setValue($user, ['id' => 999, 'activation' => '']);

        $this->assertSame(true, $user->isActivated());

        $reflectionProperty->setValue($user, ['id' => 999, 'activation' => 'qweqweqweqwe213412123']);

        $this->assertSame(false, $user->isActivated());

        $reflectionProperty->setValue($user, ['id' => 999, 'activation' => '0']);

        $this->assertSame(true, $user->isActivated());
    }

    /**
     * isActive returns correct value.
     *
     * @return  void
     */
    public function testIsActiveReturnsCorrectValue()
    {
        // User is blocked
        $user = $this->getMockBuilder(User::class)
            ->setMethods(['isBlocked'])
            ->getMock();

        $user->expects($this->once())
            ->method('isBlocked')
            ->willReturn(true);

        $this->assertSame(false, $user->isActive());

        // User not blocked but not activated
        $user = $this->getMockBuilder(User::class)
            ->setMethods(['isBlocked', 'isActivated'])
            ->getMock();

        $user->expects($this->once())
            ->method('isBlocked')
            ->willReturn(false);

        $user->expects($this->once())
            ->method('isActivated')
            ->willReturn(false);

        $this->assertSame(false, $user->isActive());

        // User not blocked and activated
        $user = $this->getMockBuilder(User::class)
            ->setMethods(['isBlocked', 'isActivated'])
            ->getMock();

        $user->expects($this->once())
            ->method('isBlocked')
            ->willReturn(false);

        $user->expects($this->once())
            ->method('isActivated')
            ->willReturn(true);

        $this->assertSame(true, $user->isActive());
    }

    /**
     * isBlocked returns correct value.
     *
     * @return  void
     */
    public function testIsBlockedReturnsCorrectValue()
    {
        $user = new User();

        $this->assertSame(false, $user->isBlocked());

        $user = new User(999);

        $reflectionClass = new \ReflectionClass($user);
        $reflectionProperty = $reflectionClass->getProperty('row');
        $reflectionProperty->setAccessible(true);

        $reflectionProperty->setValue($user, ['id' => 999, 'block' => '1']);

        $this->assertSame(true, $user->isBlocked());

        $reflectionProperty->setValue($user, ['id' => 999, 'block' => '0']);

        $this->assertSame(false, $user->isBlocked());
    }

    /**
     * canAdmin returns true for root.
     *
     * @return  void
     */
    public function testCanAdminReturnsTrueForRoot()
    {
        $user = $this->getMockBuilder(User::class)
            ->setMethods(['isRoot'])
            ->getMock();

        $user->expects($this->once())
            ->method('isRoot')
            ->willReturn(true);

        $this->assertTrue($user->canAdmin('com_phproberto'));
    }

    /**
     * canAdmin returns authorise result.
     *
     * @return  void
     */
    public function testCanAdminReturnsAuthoriseResult()
    {
        $user = $this->getMockBuilder(User::class)
            ->setMethods(['isRoot', 'authorise'])
            ->getMock();

        $user->expects($this->exactly(3))
            ->method('isRoot')
            ->willReturn(false);

        $user->method('authorise')
            ->with($this->equalTo('core.admin'), $this->equalTo('com_phproberto'))
            ->will($this->onConsecutiveCalls(false, true, false));

        $this->assertFalse($user->canAdmin('com_phproberto'));
        $this->assertTrue($user->canAdmin('com_phproberto'));
        $this->assertFalse($user->canAdmin('com_phproberto'));
    }

    /**
     * getAuthorisedViewLevels returns correct values.
     *
     * @return  void
     */
    public function testGetAuthorisedViewLevelsReturnsCorrectValue()
    {
        $joomlaUser = $this->getMockBuilder('MockeJoomlaUser')
            ->setMethods(['getAuthorisedViewLevels'])
            ->getMock();

        $joomlaUser->method('getAuthorisedViewLevels')
            ->will(
                $this->onConsecutiveCalls(
                    [1, 1, 5],
                    [1, 12, 25],
                    [11, 21, 21]
                )
            );

        $user = $this->getMockBuilder(User::class)
            ->setMethods(['joomlaUser'])
            ->getMock();

        $user->method('joomlaUser')
            ->willReturn($joomlaUser);

        $this->assertSame([1, 5], $user->getAuthorisedViewLevels());
        $this->assertSame([1, 12, 25], $user->getAuthorisedViewLevels());
        $this->assertSame([11, 21], $user->getAuthorisedViewLevels());
    }

    /**
     * getAuthorisedViewLevels returns empty array on joomlaUser exception.
     *
     * @return  void
     */
    public function testgetAuthorisedViewLevelsReturnsEmptyArrayOnJoomlaUserException()
    {
        $user = $this->getMockBuilder(User::class)
            ->setMethods(['joomlaUser'])
            ->getMock();

        $user->method('joomlaUser')
            ->will($this->throwException(new \Exception('User failure')));

        $this->assertSame([], $user->getAuthorisedViewLevels());
    }

    /**
     * juser returns correct instance.
     *
     * @return  void
     */
    public function testJoomlaserReturnsCorrectInstance()
    {
        $user = new User(42);

        $joomlaUser = $user->joomlaUser();

        $this->assertInstanceOf(\Joomla\CMS\User\User::class, $joomlaUser);
        $this->assertSame(42, (int) $joomlaUser->get('id'));
    }

    /**
     * isGuest returns true for non-loaded user.
     *
     * @return  void
     */
    public function testIsGuestReturnsCorrectValue()
    {
        $user = new User();

        $this->assertSame(true, $user->isGuest());

        $user = new User(42);

        $this->assertSame(false, $user->isGuest());
    }

    /**
     * isRoot returns cached value.
     *
     * @return  void
     */
    public function testIsRootReturnsCachedValue()
    {
        $user = new User(999);

        $reflectionClass = new \ReflectionClass($user);
        $reflectionProperty = $reflectionClass->getProperty('isRoot');
        $reflectionProperty->setAccessible(true);

        $reflectionProperty->setValue($user, true);
        $this->assertTrue($user->isRoot());

        $reflectionProperty->setValue($user, false);
        $this->assertFalse($user->isRoot());

        $reflectionProperty->setValue($user, true);
        $this->assertTrue($user->isRoot());
    }

    /**
     * isRoot returns authorise result.
     *
     * @return  void
     */
    public function testIsRootReturnsAuthoriseResult()
    {
        $joomlaUser = $this->getMockBuilder('MockeJoomlaUser')
            ->setMethods(['authorise'])
            ->getMock();

        $joomlaUser->method('authorise')
            ->with('core.admin')
            ->will($this->onConsecutiveCalls(true, false, true));

        $user = $this->getMockBuilder(User::class)
            ->setMethods(['joomlaUser'])
            ->getMock();

        $user->expects($this->exactly(3))
            ->method('joomlaUser')
            ->willReturn($joomlaUser);

        $reflectionClass = new \ReflectionClass($user);
        $reflectionProperty = $reflectionClass->getProperty('isRoot');
        $reflectionProperty->setAccessible(true);

        $reflectionProperty->setValue($user, null);
        $this->assertTrue($user->isRoot());

        $reflectionProperty->setValue($user, null);
        $this->assertFalse($user->isRoot());

        $reflectionProperty->setValue($user, null);
        $this->assertTrue($user->isRoot());
    }

    /**
     * joomlaUser returns guest for missing primary key.
     *
     * @return  void
     */
    public function testJoomlaUserReturnsGuestForMissingPrimaryKey()
    {
        $user = new User();

        $joomlaUser = $user->joomlaUser();

        $this->assertInstanceOf(\Joomla\CMS\User\User::class, $joomlaUser);
        $this->assertSame(0, (int) $joomlaUser->get('id'));
        $this->assertSame(1, $joomlaUser->get('guest'));
    }

    /**
     * joomlaUser throws exception for non-existing user.
     *
     * @return  void
     *
     * @expectedException \RuntimeException
     */
    public function testJoomlaUserThrowsExceptionForNonExistingUser()
    {
        $user = new User(999);

        $joomlaUser = $user->joomlaUser();
    }

    /**
     * loadUserGroups returns empty collection for entities without id.
     *
     * @return  void
     */
    public function testLoadUserGroupsReturnsEmptyCollectionForEntitiesWithoutId()
    {
        $user = new User();

        $reflectionClass = new \ReflectionClass($user);
        $reflectionMethod = $reflectionClass->getMethod('loadUserGroups');
        $reflectionMethod->setAccessible(true);

        $this->assertEquals(new Collection(), $reflectionMethod->invoke($user));
    }

    /**
     * loadUserGroupss returns correct collection for entities with id.
     *
     * @return  void
     */
    public function testLoadUserGroupsReturnsCorrectCollectionForEntitiesWithId()
    {
        $relationships = [
            42 => [8],
            43 => [5],
            44 => [6],
        ];

        foreach ($relationships as $userId => $groupsIds) {
            $user = new User($userId);

            $reflection = new \ReflectionClass($user);

            $method = $reflection->getMethod('loadUserGroups');
            $method->setAccessible(true);

            $expected = new Collection(
                array_map(
                    fn ($groupId) => UserGroup::load($groupId),
                    $groupsIds
                )
            );

            $this->assertEquals($expected, $method->invoke($user));
        }
    }

    /**
     * params can be retrieved.
     *
     * @return  void
     */
    public function testParamsRetrieved()
    {
        $user = new User(999);

        $reflectionClass = new \ReflectionClass($user);

        $reflectionProperty = $reflectionClass->getProperty('row');
        $reflectionProperty->setAccessible(true);
        $reflectionProperty->setValue($user, ['id' => 999, CoreColumn::PARAMS => '']);

        $this->assertEquals(new Registry(), $user->params());

        $user = new User(666);

        $reflectionProperty->setValue($user, ['id' => 666, CoreColumn::PARAMS => '{"timezone":"Europe\/Madrid"}']);

        $this->assertEquals(new Registry(['timezone' => 'Europe/Madrid']), $user->params());
    }

    /**
     * table returns correct table instance.
     *
     * @return  void
     */
    public function testTableReturnsCorrectTableInstance()
    {
        $user = new User();

        $this->assertInstanceOf('JTableUser', $user->table());
    }

    /**
     * userGroupsIds() data provider
     *
     * @return  array
     */
    public function userGroupsIdsDataProvider()
    {
        return [
            [[null, '', ' ', '8', 'test', 04], [8, 4]],
            [[], []],
            [null, []],
        ];
    }

    /**
     * @test
     *
     * @dataProvider  userGroupsIdsDataProvider
     *
     * @return void
     */
    public function userGroupsIdsReturnsExpectedValues($provided, $expected)
    {
        $user = new User();
        $user->bind(
            [
                'id'     => 999,
                'name'   => 'Roberto',
                'groups' => $provided,
            ]
        );

        $this->assertSame($expected, $user->userGroupsIds());
    }

    /**
     * @test
     *
     * @return void
     */
    public function viewLevelsReturnsExpectedCollection()
    {
        $user = new User();
        $viewLevels = $user->viewLevels();

        $this->assertInstanceOf(Collection::class, $viewLevels);
        $this->assertSame([1], $viewLevels->ids());

        $user = $this->getMockBuilder(User::class)
            ->setMethods(['getAuthorisedViewLevels'])
            ->getMock();

        $user->expects($this->once())
            ->method('getAuthorisedViewLevels')
            ->willReturn([2, 8]);

        $this->assertSame([2, 8], $user->viewLevels()->ids());
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
        $phpUnitExtensionsDatabaseDataSetCsvDataSet->addTable('jos_viewlevels', JPATH_TEST_DATABASE.'/jos_viewlevels.csv');

        return $phpUnitExtensionsDatabaseDataSetCsvDataSet;
    }
}
