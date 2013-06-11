<?php

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


class Tx_MmForum_Domain_Factory_Forum_TopicFactoryTest extends Tx_MmForum_Unit_BaseTestCase {


	/**
	 * @var Tx_MmForum_Domain_Factory_Forum_TopicFactory
	 */
	protected $fixture;


	/**
	 * @var PHPUnit_Framework_MockObject_MockObject
	 */
	protected $userRepositoryMock, $forumRepositoryMock, $topicRepositoryMock, $postRepositoryMock, $postFactoryMock, $criteriaRepositoryMock;


	public function setUp() {
		$this->userRepositoryMock = $this->getMock('Tx_MmForum_Domain_Repository_User_FrontendUserRepository');
		$this->forumRepositoryMock = $this->getMock('Tx_MmForum_Domain_Repository_Forum_ForumRepository');
		$this->topicRepositoryMock = $this->getMock('Tx_MmForum_Domain_Repository_Forum_TopicRepository');
		$this->postRepositoryMock = $this->getMock('Tx_MmForum_Domain_Repository_Forum_PostRepository');
		$this->postFactoryMock = $this->getMock('Tx_MmForum_Domain_Factory_Forum_PostFactory');
		$this->criteriaRepositoryMock = $this->getMock('Tx_MmForum_Domain_Repository_Forum_CriteriaOptionRepository');


		$this->fixture = new Tx_MmForum_Domain_Factory_Forum_TopicFactory($this->forumRepositoryMock, $this->topicRepositoryMock, $this->postRepositoryMock, $this->postFactoryMock, $this->criteriaRepositoryMock);
		$this->fixture->injectObjectManager($this->objectManager);
		$this->fixture->injectFrontendUserRepository($this->userRepositoryMock);
	}


	/**
	 * @test
	 */
	public function topicCanBeCreated() {
		$forum = $this->getMock('Tx_MmForum_Domain_Model_Forum_Forum');
		$user = $this->getMock('Tx_MmForum_Domain_Model_User_FrontendUser');
		$option = new Tx_MmForum_Domain_Model_Forum_CriteriaOption();
		$option->setName('test');
		$post = new Tx_MmForum_Domain_Model_Forum_Post('Content');
		$post->setAuthor($user);

		$forum->expects($this->once())->method('addTopic')
			->with($this->isInstance('Tx_MmForum_Domain_Model_Forum_Topic'));
		$this->forumRepositoryMock->expects($this->once())->method('update')
			->with($this->isInstance('Tx_MmForum_Domain_Model_Forum_Forum'));
		$this->userRepositoryMock->expects($this->any())->method('findCurrent')->will($this->returnValue($user));
		$this->topicRepositoryMock->expects($this->any())->method('addCriteriaOption')
			->with($option);
		$this->criteriaRepositoryMock->expects($this->any())->method('findByUid')->with(1337)
			->will($this->returnValue($option));

		$topic = $this->fixture->createTopic($forum, $post, 'Subject', 1, array(1 => 1337));

		$this->assertEquals('Subject', $topic->getSubject());
		$this->assertTrue($topic->getAuthor() == $user);
		$this->assertEquals(1, count($topic->getPosts()));
		$this->assertEquals(1, $topic->getQuestion());
		$this->assertInstanceOf('TYPO3\CMS\Extbase\Persistence\ObjectStorage', $topic->getCriteriaOptions());
		$this->assertTrue($topic->getForum() == $forum);
	}


	/**
	 * @test
	 */
	public function topicCanBeDeleted() {
		$posts = array();
		for ($i = 1; $i <= 5; $i++) {
			$user = $this->getMock('Tx_MmForum_Domain_Model_User_FrontendUser');
			$user->expects($this->once())->method('decreasePostCount');
			$post = $this->getMock('Tx_MmForum_Domain_Model_Forum_Post');
			$post->expects($this->any())->method('getAuthor')->will($this->returnValue($user));
			$posts[] = $post;
		}

		$forum = $this->getMock('Tx_MmForum_Domain_Model_Forum_Forum');

		$topic = $this->getMock('Tx_MmForum_Domain_Model_Forum_Topic');
		$topic->expects($this->any())->method('getPosts')->will($this->returnValue($posts));
		$topic->expects($this->any())->method('getForum')->will($this->returnValue($forum));

		$forum->expects($this->once())->method('removeTopic')->with($this->isIdentical($topic));

		$this->userRepositoryMock->expects($this->exactly(5))->method('update')
			->with($this->isInstance('Tx_MmForum_Domain_Model_User_FrontendUser'));
		$this->forumRepositoryMock->expects($this->once())->method('update')->with($this->isIdentical($forum));

		$this->fixture->deleteTopic($topic);
	}


	/**
	 * @test
	 */
	public function shadowTopicCanBeCreated() {
		$post = new Tx_MmForum_Domain_Model_Forum_Post();
		$topic = new Tx_MmForum_Domain_Model_Forum_Topic('Subject');
		$topic->addPost($post);

		$shadowTopic = $this->fixture->createShadowTopic($topic);

		$this->assertTrue($shadowTopic->getTarget() == $topic);
		$this->assertEquals('Subject', $shadowTopic->getSubject());
		$this->assertTrue($shadowTopic->getLastPost() == $post);
	}


	/**
	 * @test
	 */
	public function topicCanBeMoved() {
		$sourceForum = $this->getMock('Tx_MmForum_Domain_Model_Forum_Forum');
		$targetForum = $this->getMock('Tx_MmForum_Domain_Model_Forum_Forum');

		$post = new Tx_MmForum_Domain_Model_Forum_Post();
		$topic = new Tx_MmForum_Domain_Model_Forum_Topic('Subject');
		$topic->addPost($post);
		$topic->setForum($sourceForum);

		$sourceForum->expects($this->once())->method('removeTopic')
			->with(new PHPUnit_Framework_Constraint_IsInstanceOf('Tx_MmForum_Domain_Model_Forum_Topic'));
		$sourceForum->expects($this->once())->method('addTopic')
			->with(new PHPUnit_Framework_Constraint_IsInstanceOf('Tx_MmForum_Domain_Model_Forum_ShadowTopic'));
		$targetForum->expects($this->once())->method('addTopic')
			->with(new PHPUnit_Framework_Constraint_IsInstanceOf('Tx_MmForum_Domain_Model_Forum_Topic'));

		$this->forumRepositoryMock->expects($this->exactly(2))->method('update')
			->with(new PHPUnit_Framework_Constraint_IsInstanceOf('Tx_MmForum_Domain_Model_Forum_Forum'));

		$this->fixture->moveTopic($topic, $targetForum);
	}


	/**
	 * @test
	 * @expectedException Tx_Extbase_Object_InvalidClass
	 */
	public function shadowTopicCannotBeMoved() {
		$shadowTopic = new Tx_MmForum_Domain_Model_Forum_ShadowTopic();
		$targetForum = new Tx_MmForum_Domain_Model_Forum_Forum();

		$this->fixture->moveTopic($shadowTopic, $targetForum);
	}


}
