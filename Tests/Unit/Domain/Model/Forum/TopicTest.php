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


class TopicTest extends \Mittwald\MmForum\Unit\BaseTestCase {


	/**
	 * @var \Mittwald\MmForum\Domain\Model\Forum\Topic
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
		$this->fixture = $this->objectManager->create('Mittwald\\MmForum\\Domain\\Model\\Forum\\Topic');
	}


	public function testInitialValueOfTimestampIsCurrentDate() {
		$this->assertEquals(new DateTime(), $this->fixture->getTimestamp());
	}


	public function testConstructorSetsSubject() {
		$this->fixture = new Topic('FOO');
		$this->assertEquals('FOO', $this->fixture->getSubject());
	}


	public function testConstructorSetsEmptySubjectPerDefault() {
		$this->assertEquals('', $this->fixture->getSubject());
	}


	public function testSetSubjectSetsSubject() {
		$this->fixture->setSubject('FOO');
		$this->assertEquals('FOO', $this->fixture->getSubject());
		$this->assertEquals('FOO', $this->fixture->getTitle());
		$this->assertEquals('FOO', $this->fixture->getName());
	}


	public function testGetDescriptionReturnsContentOfFirstPost() {
		$this->fixture->addPost(new Post('BAZ'));
		$this->assertEquals('BAZ', $this->fixture->getDescription());
	}


	public function testSetAuthorSetsAuthor() {
		$this->fixture->setAuthor($user = new \Mittwald\MmForum\Domain\Model\User\FrontendUser('martin'));
		$this->assertTrue($this->fixture->getAuthor() === $user);
	}


	public function testSetForumSetsForum() {
		$this->fixture->setForum($forum = new Forum('FORUM'));
		$this->assertTrue($this->fixture->getForum() === $forum);
	}


	public function testSetClosedClosesTopic() {
		$this->fixture->setClosed(TRUE);
		$this->assertTrue($this->fixture->isClosed());
	}


	public function testSetStickyMakesTopicSticky() {
		$this->fixture->setSticky(TRUE);
		$this->assertTrue($this->fixture->isSticky());
	}


	public function testSetQuestionSetsQuestion() {
		$this->fixture->setQuestion(1);
		$this->assertEquals(1, $this->fixture->getQuestion());
	}


	public function testAddPostAddsPost() {
		$this->fixture->addPost($post = new Post('CONTENT'));
		$this->assertContains($post, $this->fixture->getPosts());
		$this->assertEquals(1, count($this->fixture->getPosts()));
	}


	/**
	 * @depends testAddPostAddsPost
	 */
	public function testAddPostRefreshesCounterAndReferencesForFirstPost() {
		$this->fixture->addPost($post = new Post('CONTENT'));
		$this->assertEquals(1, $this->fixture->getPostCount());
		$this->assertTrue($this->fixture->getLastPost() === $post);
	}


	/**
	 * @depends testAddPostAddsPost
	 */
	public function testGetReplyCountReturnsPostCountMinusOne() {
		$this->fixture->addPost($post = new Post('CONTENT'));
		$this->assertEquals(0, $this->fixture->getReplyCount());
	}


	/**
	 * @depends testAddPostRefreshesCounterAndReferencesForFirstPost
	 */
	public function testAddPostRefreshesCounterAndReferencesForLaterPost() {
		$post = new Post('CONTENT_2');
		$post->_setProperty('crdate', new DateTime('tomorrow'));
		$this->testAddPostRefreshesCounterAndReferencesForFirstPost();
		$this->fixture->addPost($post);
		$this->assertEquals(2, $this->fixture->getPostCount());
		$this->assertTrue($this->fixture->getLastPost() === $post);
	}


	/**
	 * @depends testAddPostAddsPost
	 */
	public function testAddPostRefreshesForumPostCounterAndReference() {
		$forum = new Forum();

		$this->fixture->setForum($forum);
		$this->fixture->addPost($post = new Post('CONTENT'));

		$this->assertEquals(1, $forum->getPostCount());
		$this->assertTrue($forum->getLastPost() === $post);
	}


	/**
	 * @depends           testAddPostAddsPost
	 * @expectedException \Mittwald\MmForum\Domain\Exception\InvalidOperationException
	 */
	public function testRemovePostThrowsExceptionWhenRemovingLastPost() {
		$this->fixture->addPost($post = new Post('CONTENT'));
		$this->fixture->removePost($post);

		$this->assertEquals(0, $this->fixture->getPostCount());
		$this->assertNull($this->fixture->getLastPost());
	}


	/**
	 * @depends testAddPostAddsPost
	 */
	public function testRemovePostRemovesNonlastPost() {
		$post2 = new Post('CONTENT');
		$post2->_setProperty('crdate', new DateTime('tomorrow'));
		$this->fixture->addPost($post1 = new Post('CONTENT'));
		$this->fixture->addPost($post2);
		$this->fixture->removePost($post2);

		$this->assertEquals(1, $this->fixture->getPostCount());
		$this->assertTrue($this->fixture->getLastPost() === $post1);
	}


	/**
	 * @depends testAddPostRefreshesForumPostCounterAndReference
	 */
	public function testRemovePostRefreshesForumPostCounterAndReference() {
		$forum = new Forum();
		$post2 = new Post('CONTENT');
		$post2->_setProperty('crdate', new DateTime('tomorrow'));

		$this->fixture->addPost($post1 = new Post('CONTENT'));
		$forum->addTopic($this->fixture);
		$this->fixture->addPost($post2);
		$this->fixture->removePost($post2);

		$this->assertEquals(1, $forum->getPostCount());
		$this->assertTrue($forum->getLastPost() === $post1);
	}


	public function testHasAlwaysBeenReadByAnonymousUser() {
		$this->assertTrue($this->fixture->hasBeenReadByUser(NULL));
	}


	public function testAddReaderAddsReader() {
		$this->fixture->addReader($reader = new \Mittwald\MmForum\Domain\Model\User\FrontendUser('martin'));
		$this->assertTrue($this->fixture->hasBeenReadByUser($reader));
	}


	/**
	 * @depends testAddPostAddsPost
	 * @depends testAddReaderAddsReader
	 */
	public function testAddPostRemovesAllReaders() {
		$this->fixture->addReader($reader = new \Mittwald\MmForum\Domain\Model\User\FrontendUser('martin'));
		$this->fixture->addPost($post = new Post('CONTENT'));
		$this->assertFalse($this->fixture->hasBeenReadByUser($reader));
	}


	/**
	 * @depends testAddReaderAddsReader
	 */
	public function testRemoveReaderRemovesReader() {
		$this->fixture->addReader($reader = new \Mittwald\MmForum\Domain\Model\User\FrontendUser('martin'));
		$this->fixture->removeReader($reader);
		$this->assertFalse($this->fixture->hasBeenReadByUser($reader));
	}


	/**
	 * @depends testAddReaderAddsReader
	 */
	public function testRemoveAllReadersRemovesAllReaders() {
		$this->fixture->addReader($reader = new \Mittwald\MmForum\Domain\Model\User\FrontendUser('martin'));
		$this->fixture->removeAllReaders();
		$this->assertFalse($this->fixture->hasBeenReadByUser($reader));
	}


	public function testAddSubscriberAddsSubscriber() {
		$this->fixture->addSubscriber($subscriber = new \Mittwald\MmForum\Domain\Model\User\FrontendUser('martin123'));
		$this->assertEquals(1, count($this->fixture->getSubscribers()));
		$this->assertContains($subscriber, $this->fixture->getSubscribers());
	}


	/**
	 * @depends testAddSubscriberAddsSubscriber
	 */
	public function testRemoveSubscriberRemovesSubscriber() {
		$this->fixture->addSubscriber($subscriber = new \Mittwald\MmForum\Domain\Model\User\FrontendUser('martin123'));
		$this->fixture->removeSubscriber($subscriber);
		$this->assertEquals(0, count($this->fixture->getSubscribers()));
		$this->assertNotContains($subscriber, $this->fixture->getSubscribers());
	}


	public function testGetRootlineReturnsParentsAndSelf() {
		$forum = new Forum();
		$this->fixture->setForum($forum);

		$rootline = $this->fixture->getRootline(TRUE);
		$this->assertTrue($rootline[0] === $forum);
		$this->assertTrue($rootline[1] === $this->fixture);
	}


	public function testDeniesNewPostAccessToAnonymousUsers() {
		$this->assertFalse($this->fixture->checkNewPostAccess(NULL));
	}


	public function testDeniesNewPostAccessOnClosedTopicsToRegularUsersWithAccess() {
		/** @var $forum \Mittwald\MmForum\Domain\Model\Forum\Forum */
		$forum = $this->objectManager->create('Mittwald\\MmForum\\Domain\\Model\\Forum\\Forum');
		$forum->addAcl(new Access('newPost', Access::LOGIN_LEVEL_ANYLOGIN));
		$user = new \Mittwald\MmForum\Domain\Model\User\FrontendUser('martin');

		$this->fixture->setForum($forum);
		$this->fixture->setClosed(TRUE);

		$this->assertFalse($this->fixture->checkNewPostAccess($user));
	}


	public function testGrantsNewPostAccessOnClosedTopicsToModeratorUsers() {
		$user = new \Mittwald\MmForum\Domain\Model\User\FrontendUser('martin');
		$user->addUsergroup($group = new \Mittwald\MmForum\Domain\Model\User\FrontendUserGroup('group'));
		$forum = new Forum();
		$forum->addAcl(new Access('moderate', Access::LOGIN_LEVEL_SPECIFIC, $group));

		$this->fixture->setForum($forum);
		$this->fixture->setClosed(TRUE);

		$this->assertTrue($this->fixture->checkNewPostAccess($user));
	}


	public function testDelegatesAccessChecksOtherThanNewpostToParentForum() {
		$user = new \Mittwald\MmForum\Domain\Model\User\FrontendUser('martin');
		$forum = $this->getMock('Mittwald\\MmForum\\Domain\\Model\\Forum\\Forum');
		$forum->expects($this->atLeastOnce())->method('checkAccess')
			->with(self::isInstanceOf('Mittwald\\MmForum\\Domain\\Model\\User\\FrontendUser'), 'read')
			->will($this->returnValue(FALSE));

		$this->fixture->setForum($forum);
		$this->assertFalse($this->fixture->checkAccess($user, 'read'));
	}


	public function testGetAuthorReturnsAnonymousUserIfNoAuthorIsSet() {
		$this->assertInstanceOf('Mittwald\\MmForum\\Domain\\Model\\User\\AnonymousFrontendUser', $this->fixture->getAuthor());
	}


	public function testGetAuthorReturnsAnonymousUserFromFirstPostIfNoAuthorIsSet() {
		$post = new Post();
		$post->setAuthorName('martin');
		$this->fixture->addPost($post);
		$this->assertEquals('martin', $this->fixture->getAuthor()->getUsername());
	}


}