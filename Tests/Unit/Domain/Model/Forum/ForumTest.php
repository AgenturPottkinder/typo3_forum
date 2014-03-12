<?php
namespace Mittwald\MmForum\Domain\Model\Forum;


/*                                                                    - *
 *  COPYRIGHT NOTICE                                                    *
 *                                                                      *
 *  (c) 2012 Martin Helmich <m.helmich@mittwald.de>                     *
 *           Mittwald CM Service GmbH & Co KG                           *
 *           All rights reserved                                        *
 *                                                                      *
 *  This script is part of the TYPO3 project. The TYPO3 project is      *
 *  free software; you can redistribute it and/or modify                *
 *  it under the terms of the GNU General public License as published   *
 *  by the Free Software Foundation; either version 2 of the License,   *
 *  or (at your option) any later version.                              *
 *                                                                      *
 *  The GNU General public License can be found at                      *
 *  http://www.gnu.org/copyleft/gpl.html.                               *
 *                                                                      *
 *  This script is distributed in the hope that it will be useful,      *
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of      *
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the       *
 *  GNU General public License for more details.                        *
 *                                                                      *
 *  This copyright notice MUST APPEAR in all copies of the script!      *
 *                                                                      */



class ForumTest extends \Mittwald\MmForum\Unit\BaseTestCase {



	/**
	 * @var \Mittwald\MmForum\Domain\Model\Forum\Forum
	 */
	protected $fixture = NULL;


	/**
	 * @var PHPUnit_Framework_MockObject_MockObject
	 */
	protected $authenticationServiceMock, $userRepositoryMock, $cacheMock;


	/**
	 * @var \Mittwald\MmForum\Service\Authentication\AuthenticationServiceInterface
	 */
	protected $authenticationService;



	public function setUp() {
		$this->userRepositoryMock = $this->getMock('Mittwald\\MmForum\\Domain\\Repository\\User\\FrontendUserRepository');
		$this->userRepositoryMock->expects($this->any())->method('findCurrent')->will($this->returnValue(NULL));
		$this->cacheMock = $this->getMock('Mittwald\\MmForum\\Cache\\Cache');
		$this->cacheMock->expects($this->any())->method('has')->will($this->returnValue(FALSE));

		$this->authenticationService     = new \Mittwald\MmForum\Service\Authentication\AuthenticationService($this->userRepositoryMock, $this->cacheMock);
		$this->authenticationServiceMock = $this->getMock('Mittwald\\MmForum\\Service\\Authentication\\AuthenticationService',
		                                                  array('checkAuthorization'), array(), '', FALSE);
		$this->authenticationServiceMock->expects($this->any())->method('checkAuthorization')
			->will($this->returnValue(TRUE));

		$this->fixture = new Forum();
		$this->fixture->injectObjectManager($this->objectManager);
		$this->fixture->injectAuthenticationService($this->authenticationService);
	}



	public function testConstructorSetsTitle() {
		$this->fixture = new Forum('FOO');
		$this->assertEquals('FOO', $this->fixture->getTitle());
	}



	public function testConstructorSetsEmptyTitlePerDefault() {
		$this->fixture = new Forum();
		$this->assertEquals('', $this->fixture->getTitle());
	}



	public function testSetTitleSetsTitle() {
		$this->fixture->setTitle('FOO');
		$this->assertEquals('FOO', $this->fixture->getTitle());
	}



	public function testSetDescriptionSetsDescription() {
		$this->fixture->setDescription('BAR');
		$this->assertEquals('BAR', $this->fixture->getDescription());
	}



	public function testAddSubscriberAddsSubscriber() {
		$subscriber = new \Mittwald\MmForum\Domain\Model\User\FrontendUser('martin', 'secret');
		$this->fixture->addSubscriber($subscriber);
		$this->assertContains($subscriber, $this->fixture->getSubscribers());
	}



	/**
	 * @depends testAddSubscriberAddsSubscriber
	 */
	public function testAddSubscriberAddsSubscriberOnlyOnce() {
		$subscriber = new \Mittwald\MmForum\Domain\Model\User\FrontendUser('martin', 'secret');
		$this->fixture->addSubscriber($subscriber);
		$this->fixture->addSubscriber($subscriber);
		$this->assertEquals(1, count($this->fixture->getSubscribers()));
	}



	/**
	 * @depends testAddSubscriberAddsSubscriber
	 */
	public function testRemoveSubscriberRemovesSubscriber() {
		$subscriber = new \Mittwald\MmForum\Domain\Model\User\FrontendUser('martin', 'secret');
		$this->fixture->addSubscriber($subscriber);
		$this->fixture->removeSubscriber($subscriber);
		$this->assertNotContains($subscriber, $this->fixture->getSubscribers());
	}



	public function testGetChildrenReturnsOnlyAccessibleChildren() {
		$this->authenticationServiceMock = $this->getMock('Mittwald\\MmForum\\Service\\Authentication\\AuthenticationService',
		                                                  array(), array(), '', FALSE);
		$this->authenticationServiceMock->expects($this->exactly(3))->method('checkAuthorization')
			->with(self::isInstanceOf('Mittwald\\MmForum\\Domain\\Model\\Forum\\Forum'), self::equalTo('read'))
			->will($this->returnCallback(function($forum) {
			return $forum->getTitle() !== 'Child 3';
		}));
		$this->fixture->injectAuthenticationService($this->authenticationServiceMock);
		for ($i = 1; $i <= 3; $i++) {
			$this->fixture->addChild(new Forum('Child ' . $i));
		}

		$children = $this->fixture->getChildren();
		$this->assertEquals(2, $children->count());
	}



	public function testHasBeenReadByUserIsAlwaysTrueOnAnonymousLogin() {
		$this->assertTrue($this->fixture->hasBeenReadByUser(NULL));
	}



	public function testHasBeenReadByUserIsTrueWhenAllTopicsAreRead() {
		$user = $this->getMock('Mittwald\\MmForum\\Domain\\Model\\User\\FrontendUser');
		for ($i = 1; $i <= 3; $i++) {
			$topic = $this->getMock('Mittwald\\MmForum\\Domain\\Model\\Forum\\Topic');
			$topic->expects($this->any())->method('getLastPost')
				->will($this->returnValue(new Post('Content')));
			$topic->expects($this->atLeastOnce())->method('hasBeenReadByUser')
				->with(self::isInstanceOf(get_class($user)))->will($this->returnValue(TRUE));
			$this->fixture->addTopic($topic);
		}

		$this->assertTrue($this->fixture->hasBeenReadByUser($user));
	}



	public function testHasBeenReadByUserIsFalseWhenAtLeastOneTopicIsUnread() {
		$user = $this->getMock('Mittwald\\MmForum\\Domain\\Model\\User\\FrontendUser');
		for ($i = 1; $i <= 3; $i++) {
			$topic = $this->getMock('Mittwald\\MmForum\\Domain\\Model\\Forum\\Topic');
			$topic->expects($this->any())->method('getLastPost')
				->will($this->returnValue(new Post('Content')));
			$topic->expects($this->atLeastOnce())->method('hasBeenReadByUser')
				->with(self::isInstanceOf(get_class($user)))->will($this->returnValue($i !== 3));
			$this->fixture->addTopic($topic);
		}

		$this->assertFalse($this->fixture->hasBeenReadByUser($user));
	}



	public function testAddChildAddsChild() {
		$this->fixture->addChild($child = new Forum('CHILD'));
		$this->assertEquals(1, count($this->fixture->getChildren()));
	}



	/**
	 * @depends testAddChildAddsChild
	 */
	public function testAddChildRefreshesCachedVisibleChildren() {
		$this->assertEquals(0, count($this->fixture->getChildren()));
		$this->fixture->addChild($child = new Forum('CHILD'));
		$this->assertEquals(1, count($this->fixture->getChildren()));
	}



	/**
	 * @depends testAddChildAddsChild
	 */
	public function testAddChildCallsSetParentOnChild() {
		$child = $this->getMock('Mittwald\\MmForum\\Domain\\Model\\Forum\\Forum');
		$child->expects($this->once())->method('setParent')
			->with(self::isInstanceOf('Mittwald\\MmForum\\Domain\\Model\\Forum\\Forum'));

		$this->fixture->addChild($child);
	}



	/**
	 * @depends testAddChildAddsChild
	 */
	public function testAddChildRefreshesCountersAndReferences() {
		$post  = new Post('CONTENT');
		$topic = new Topic('TOPIC_1');
		$child = new Forum('CHILD_1');

		$topic->addPost($post);
		$child->addTopic($topic);

		$oldPostCount  = $this->fixture->getPostCount();
		$oldTopicCount = $this->fixture->getTopicCount();

		$this->fixture->addChild($child);

		$this->assertTrue($this->fixture->getLastPost() === $post, 'Last post reference has not been set!');
		$this->assertTrue($this->fixture->getLastTopic() === $topic, 'Last topic reference has not been set!');
		$this->assertEquals($oldPostCount + $child->getPostCount(), $this->fixture->getPostCount());
		$this->assertEquals($oldTopicCount + $child->getTopicCount(), $this->fixture->getTopicCount());
	}



	public function testAddTopicAddsTopic() {
		$topic = $this->createTopic();
		$this->fixture->addTopic($topic);

		$this->assertContains($topic, $this->fixture->getTopics());
		$this->assertEquals(1, count($this->fixture->getTopics()));
	}



	/**
	 * @depends testAddChildAddsChild
	 */
	public function testRemoveChildRemovesChild() {
		$this->fixture->addChild($child = new Forum('CHILD'));
		$this->fixture->removeChild($child);
		$this->assertNotContains($child, $this->fixture->getChildren());
		$this->assertEquals(0, count($this->fixture->getChildren()));
	}



	/**
	 * @depends testAddTopicAddsTopic
	 */
	public function testAddTopicRefreshesCountersAndReferencesOnFirstTopic() {
		$post1 = new Post('CONTENT 1');
		$post2 = new Post('CONTENT 2');
		$post2->_setProperty('crdate', new DateTime('tomorrow'));
		$topic = new Topic('TOPIC_1');
		$topic->addPost($post1);
		$topic->addPost($post2);

		$this->fixture->addTopic($topic);

		$this->assertEquals(2, $this->fixture->getPostCount());
		$this->assertEquals(1, $this->fixture->getTopicCount());
		$this->assertTrue($this->fixture->getLastTopic() === $topic);
		$this->assertTrue($this->fixture->getLastPost() === $post2);
	}



	/**
	 * @depends testAddTopicRefreshesCountersAndReferencesOnFirstTopic
	 */
	public function testAddTopicRefreshesCountersAndReferencesOnLaterTopic() {
		$this->testAddTopicRefreshesCountersAndReferencesOnFirstTopic();

		$post3 = new Post('CONTENT 3');
		$post3->_setProperty('crdate', new DateTime('now + 2 days'));
		$topic = new Topic('TOPIC_2');
		$topic->addPost($post3);

		$this->fixture->addTopic($topic);

		$this->assertEquals(3, $this->fixture->getPostCount());
		$this->assertEquals(2, $this->fixture->getTopicCount());
		$this->assertTrue($this->fixture->getLastTopic() === $topic);
		$this->assertTrue($this->fixture->getLastPost() === $post3);
	}



	/**
	 * @depends testAddTopicAddsTopic
	 */
	public function testRemoveTopicRemovesTopic() {
		$topic = $this->createTopic();
		$this->fixture->addTopic($topic);
		$this->fixture->removeTopic($topic);

		$this->assertNotContains($topic, $this->fixture->getTopics());
		$this->assertEquals(0, count($this->fixture->getTopics()));
	}



	/**
	 * @depends testAddTopicRefreshesCountersAndReferencesOnLaterTopic
	 */
	public function testRemoveTopicRefreshesCountersAndReferencesOnLastTopic() {
		$this->testAddTopicRefreshesCountersAndReferencesOnFirstTopic();

		$topic = $this->fixture->getLastTopic();
		$this->fixture->removeTopic($topic);

		$this->assertEquals(0, $this->fixture->getPostCount());
		$this->assertEquals(0, $this->fixture->getTopicCount());
		$this->assertNull($this->fixture->getLastTopic());
		$this->assertNull($this->fixture->getLastPost());
	}



	/**
	 * @depends testAddTopicRefreshesCountersAndReferencesOnLaterTopic
	 */
	public function testRemoveTopicRefreshesCountersAndReferencesOnNonlastTopic() {
		$this->testAddTopicRefreshesCountersAndReferencesOnLaterTopic();

		$topic = $this->fixture->getLastTopic();
		$this->fixture->removeTopic($topic);

		$this->assertEquals(2, $this->fixture->getPostCount());
		$this->assertEquals(1, $this->fixture->getTopicCount());
		$this->assertInstanceOf('Mittwald\\MmForum\\Domain\\Model\\Forum\\Topic', $this->fixture->getLastTopic());
		$this->assertTrue($this->fixture->getLastTopic() !== $topic);
		$this->assertInstanceOf('Mittwald\\MmForum\\Domain\\Model\\Forum\\Post', $this->fixture->getLastPost());
		$this->assertTrue($this->fixture->getLastPost() !== $topic->getLastPost());
	}



	public function testAddTopicSetsForumOnTopic() {
		$topic = $this->createTopic();
		$this->fixture->addTopic($topic);
		$this->assertTrue($topic->getForum() === $this->fixture);
	}



	/**
	 * @depends testAddTopicRefreshesCountersAndReferencesOnLaterTopic
	 */
	public function testAddTopicRefreshesCountersAndReferencesRecursively() {
		$lowerForum = new Forum('LOWER');
		$this->fixture->addChild($lowerForum);

		$post  = new Post('CONTENT');
		$topic = new Topic('TOPIC');
		$topic->addPost($post);

		$lowerForum->addTopic($topic);

		$this->assertEquals(1, $this->fixture->getPostCount());
		$this->assertEquals(1, $this->fixture->getTopicCount());
		$this->assertTrue($this->fixture->getLastTopic() === $topic);
		$this->assertTrue($this->fixture->getLastPost() === $post);
	}



	/**
	 * @depends testRemoveTopicRefreshesCountersAndReferencesOnNonlastTopic
	 */
	public function testRemoveTopicRefreshesCountersAndReferencesRecursively() {
		$this->testAddTopicRefreshesCountersAndReferencesRecursively();
		$topic      = $this->fixture->getLastTopic();
		$post       = $topic->getLastPost();
		$lowerForum = $topic->getForum();

		$lowerForum->removeTopic($topic);

		$this->assertEquals(0, $this->fixture->getPostCount());
		$this->assertEquals(0, $this->fixture->getTopicCount());
		$this->assertNull($this->fixture->getLastTopic());
		$this->assertNull($this->fixture->getLastPost());
	}



	public function testGetRootlineReturnsAllParentForumsAndSelfByDefault() {
		$levelTwo = new Forum('Ebene 2');
		$levelTwo->injectAuthenticationService($this->authenticationServiceMock);
		$levelOne = new Forum('Ebene 1');
		$levelOne->injectAuthenticationService($this->authenticationServiceMock);

		$levelTwo->addChild($this->fixture);
		$levelOne->addChild($levelTwo);

		$rootline = $this->fixture->getRootline(TRUE);

		$this->assertEquals(3, count($rootline));
	}



	public function testAddAclAddsAcl() {
		$acl = new Access('newTopic', Access::LOGIN_LEVEL_ANYLOGIN);
		$this->fixture->addAcl($acl);

		$this->assertContainsOnly($acl, $this->fixture->getAcls());
	}



	/**
	 * @depends testAddAclAddsAcl
	 */
	public function testRemoveAclRemovesAcl() {
		$acl = new Access('newTopic', Access::LOGIN_LEVEL_ANYLOGIN);
		$this->fixture->addAcl($acl);
		$this->fixture->removeAcl($acl);

		$this->assertNotContains($acl, $this->fixture->getAcls());
		$this->assertEquals(0, count($this->fixture->getAcls()));
	}



	public function testGrantsReadAccessForRootForumsWithoutAcls() {
		$this->assertTrue($this->fixture->checkAccess(NULL, 'read'));
	}



	/**
	 * @dataProvider getNonReadAccessChecks
	 * @param string $operation
	 */
	public function testDeniesNonreadAccessForRootForumsWithoutAcls($operation = 'newTopic') {
		$this->assertFalse($this->fixture->checkAccess(NULL, $operation));
	}



	/**
	 * @dataProvider getNonReadAccessChecks
	 * @param string $operation
	 */
	public function testDelegatesAccessCheckToParentIfNoAclsAreSet($operation = 'newTopic') {
		$parent = $this->getMock('Mittwald\\MmForum\\Domain\\Model\\Forum\\Forum');
		$parent->expects($this->once())->method('checkAccess')->with(NULL, $operation)->will($this->returnValue(FALSE));
		$this->fixture->setParent($parent);

		$this->assertFalse($this->fixture->checkAccess(NULL, $operation));
	}



	/**
	 * @dataProvider getNonReadAccessChecks
	 * @depends      testAddAclAddsAcl
	 * @param string $operation
	 */
	public function testGrantsAccessToEveryoneIfGrantingAclIsFound($operation = 'newTopic') {
		$this->fixture->addAcl(new Access($operation, Access::LOGIN_LEVEL_EVERYONE));
		$this->assertTrue($this->fixture->checkAccess(NULL, $operation));
	}



	/**
	 * @dataProvider getNonReadAccessChecks
	 * @depends      testAddAclAddsAcl
	 * @param string $operation
	 */
	public function testDeniesAccessToEveryoneIfDenyingAclIsFound($operation = 'newTopic') {
		$acl = new Access($operation, Access::LOGIN_LEVEL_EVERYONE);
		$acl->setNegated(TRUE);
		$this->fixture->addAcl($acl);
		$this->assertFalse($this->fixture->checkAccess(NULL, $operation));
	}



	/**
	 * @dataProvider getNonReadAccessChecks
	 * @depends      testAddAclAddsAcl
	 * @param string $operation
	 */
	public function testGrantsAccessToAnyLoginIfGrantingAclForAnyLoginIsFound($operation = 'newTopic') {
		$this->fixture->addAcl(new Access($operation, Access::LOGIN_LEVEL_ANYLOGIN));
		$this->assertTrue($this->fixture->checkAccess($this->createUser(), $operation));
	}



	/**
	 * @dataProvider getNonReadAccessChecks
	 * @depends      testAddAclAddsAcl
	 * @param string $operation
	 */
	public function testDeniesAccessToAnonymousIfGrantingAclForAnyLoginIsFound($operation = 'newTopic') {
		$this->fixture->addAcl(new Access($operation, Access::LOGIN_LEVEL_ANYLOGIN));
		$this->assertFalse($this->fixture->checkAccess(NULL, $operation));
	}



	/**
	 * @dataProvider getNonReadAccessChecks
	 * @depends      testAddAclAddsAcl
	 * @param string $operation
	 */
	public function testGrantsAccessToMemberIfGrantingAclForGroupIsFound($operation = 'newTopic') {
		$user = $this->createUser();
		$user->addUsergroup($group = new \Mittwald\MmForum\Domain\Model\User\FrontendUserGroup('GROUP'));
		$this->fixture->addAcl(new Access($operation, Access::LOGIN_LEVEL_SPECIFIC, $group));
		$this->assertTrue($this->fixture->checkAccess($user, $operation));
	}



	/**
	 * @dataProvider getNonReadAccessChecks
	 * @depends      testAddAclAddsAcl
	 * @param string $operation
	 */
	public function testDeniesAccessToAnyLoginIfGrantingAclForGroupIsFound($operation = 'newTopic') {
		$user  = $this->createUser();
		$group = new \Mittwald\MmForum\Domain\Model\User\FrontendUserGroup('GROUP');
		$this->fixture->addAcl(new Access($operation, Access::LOGIN_LEVEL_SPECIFIC, $group));
		$this->assertFalse($this->fixture->checkAccess($user, $operation));
	}



	/**
	 * @dataProvider getNonReadAccessChecks
	 * @depends      testAddAclAddsAcl
	 * @param string $operation
	 */
	public function testDeniesNonreadAccessForRootForumsWithoutMatchingAcl($operation = 'newTopic') {
		$this->fixture->addAcl(new Access('not_matching_operation', Access::LOGIN_LEVEL_ANYLOGIN));
		$this->assertFalse($this->fixture->checkAccess($this->createUser(), $operation));
	}



	/**
	 * @dataProvider getNonReadAccessChecks
	 * @depends      testAddAclAddsAcl
	 * @param string $operation
	 */
	public function testDelegatesAccessCheckToParentIfNoAclMatches($operation = 'newTopic') {
		$parent = $this->getMock('Mittwald\\MmForum\\Domain\\Model\\Forum\\Forum');
		$parent->expects($this->once())->method('checkAccess')->with(NULL, $operation)->will($this->returnValue(FALSE));

		$this->fixture->addAcl(new Access('not_matching_operation', Access::LOGIN_LEVEL_ANYLOGIN));
		$this->fixture->setParent($parent);

		$this->assertFalse($this->fixture->checkAccess(NULL, $operation));
	}



	public function testGetParentReturnsVirtualRootForumIfNoneIsSet() {
		$this->assertInstanceOf('Mittwald\\MmForum\\Domain\\Model\\Forum\\RootForum', $this->fixture->getParent());
		$this->assertInstanceOf('Mittwald\\MmForum\\Domain\\Model\\Forum\\RootForum', $this->fixture->getForum());
	}



	protected function createTopic($postCount = 1) {
		$topic = new Topic('SUBJECT');
		for ($i = 1; $i <= $postCount; $i++) {
			$topic->addPost(new Post('CONTENT_' . $i));
		}
		return $topic;
	}



	protected function createUser() {
		return new \Mittwald\MmForum\Domain\Model\User\FrontendUser('martin', 'secret');
	}



	public function getNonReadAccessChecks() {
		return array(array('newPost'), array('newTopic'), array('moderate'), array('administrate'));
	}


}
