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


class TopicFactoryTest extends \Mittwald\Typo3Forum\Tests\Unit\BaseTestCase {


	/**
	 * @var \Mittwald\Typo3Forum\Domain\Factory\Forum\TopicFactory
	 */
	protected $fixture;


	/**
	 * @var PHPUnit_Framework_MockObject_MockObject
	 */
	protected $userRepositoryMock, $forumRepositoryMock, $topicRepositoryMock, $postRepositoryMock, $postFactoryMock, $criteriaRepositoryMock;


	public function setUp() {
		$this->userRepositoryMock = $this->getMock('\Mittwald\Typo3Forum\Domain\Repository\User\FrontendUserRepository');
		$this->forumRepositoryMock = $this->getMock('\Mittwald\Typo3Forum\Domain\Repository\Forum\ForumRepository');
		$this->topicRepositoryMock = $this->getMock('\Mittwald\Typo3Forum\Domain\Repository\Forum\TopicRepository');
		$this->postRepositoryMock = $this->getMock('\Mittwald\Typo3Forum\Domain\Repository\Forum\postRepository');
		$this->postFactoryMock = $this->getMock('Tx_Typo3Forum_Domain_Factory_Forum_PostFactory');
		$this->criteriaRepositoryMock = $this->getMock('\Mittwald\Typo3Forum\Domain\Repository\Forum\CriteriaOptionRepository');


		$this->fixture = new \Mittwald\Typo3Forum\Domain\Factory\Forum\TopicFactory($this->forumRepositoryMock, $this->topicRepositoryMock, $this->postRepositoryMock, $this->postFactoryMock, $this->criteriaRepositoryMock);
		$this->fixture->injectObjectManager($this->objectManager);
		$this->fixture->injectFrontendUserRepository($this->userRepositoryMock);
	}


	/**
	 * @test
	 */
	public function topicCanBeCreated() {
		$forum = $this->getMock('\Mittwald\Typo3Forum\Domain\Model\Forum\Forum');
		$user = $this->getMock('\Mittwald\Typo3Forum\Domain\Model\User\FrontendUser');
		$option = new \Mittwald\Typo3Forum\Domain\Model\Forum\CriteriaOption();
		$option->setName('test');
		$option->setCriteria(new \Mittwald\Typo3Forum\Domain\Model\Forum\Criteria());
		$post = new \Mittwald\Typo3Forum\Domain\Model\Forum\Post('Content');
		$post->setAuthor($user);

		$forum->expects($this->once())->method('addTopic')
			->with($this->isInstance('\Mittwald\Typo3Forum\Domain\Model\Forum\Topic'));
		$this->forumRepositoryMock->expects($this->once())->method('update')
			->with($this->isInstance('\Mittwald\Typo3Forum\Domain\Model\Forum\Forum'));
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
			$user = $this->getMock('\Mittwald\Typo3Forum\Domain\Model\User\FrontendUser');
			$user->expects($this->once())->method('decreasePostCount');
			$post = $this->getMock('\Mittwald\Typo3Forum\Domain\Model\Forum\Post');
			$post->expects($this->any())->method('getAuthor')->will($this->returnValue($user));
			$posts[] = $post;
		}

		$forum = $this->getMock('\Mittwald\Typo3Forum\Domain\Model\Forum\Forum');

		$topic = $this->getMock('\Mittwald\Typo3Forum\Domain\Model\Forum\Topic');
		$topic->expects($this->any())->method('getPosts')->will($this->returnValue($posts));
		$topic->expects($this->any())->method('getForum')->will($this->returnValue($forum));

		$forum->expects($this->once())->method('removeTopic')->with($this->isIdentical($topic));

		$this->userRepositoryMock->expects($this->exactly(5))->method('update')
			->with($this->isInstance('\Mittwald\Typo3Forum\Domain\Model\User\FrontendUser'));
		$this->forumRepositoryMock->expects($this->once())->method('update')->with($this->isIdentical($forum));

		$this->fixture->deleteTopic($topic);
	}


	/**
	 * @test
	 */
	public function shadowTopicCanBeCreated() {
		$post = new \Mittwald\Typo3Forum\Domain\Model\Forum\Post();
		$topic = new \Mittwald\Typo3Forum\Domain\Model\Forum\Topic('Subject');
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
		$sourceForum = $this->getMock('\Mittwald\Typo3Forum\Domain\Model\Forum\Forum');
		$targetForum = $this->getMock('\Mittwald\Typo3Forum\Domain\Model\Forum\Forum');

		$post = new \Mittwald\Typo3Forum\Domain\Model\Forum\Post();
		$topic = new \Mittwald\Typo3Forum\Domain\Model\Forum\Topic('Subject');
		$topic->addPost($post);
		$topic->setForum($sourceForum);

		$sourceForum->expects($this->once())->method('removeTopic')
			->with(new PHPUnit_Framework_Constraint_IsInstanceOf('\Mittwald\Typo3Forum\Domain\Model\Forum\Topic'));
		$sourceForum->expects($this->once())->method('addTopic')
			->with(new PHPUnit_Framework_Constraint_IsInstanceOf('\Mittwald\Typo3Forum\Domain\Model\Forum\ShadowTopic'));
		$targetForum->expects($this->once())->method('addTopic')
			->with(new PHPUnit_Framework_Constraint_IsInstanceOf('\Mittwald\Typo3Forum\Domain\Model\Forum\Topic'));

		$this->forumRepositoryMock->expects($this->exactly(2))->method('update')
			->with(new PHPUnit_Framework_Constraint_IsInstanceOf('\Mittwald\Typo3Forum\Domain\Model\Forum\Forum'));

		$this->fixture->moveTopic($topic, $targetForum);
	}


	/**
	 * @test
	 * @expectedException Tx_Extbase_Object_InvalidClass
	 */
	public function shadowTopicCannotBeMoved() {
		$shadowTopic = new \Mittwald\Typo3Forum\Domain\Model\Forum\ShadowTopic();
		$targetForum = new \Mittwald\Typo3Forum\Domain\Model\Forum\Forum();

		$this->fixture->moveTopic($shadowTopic, $targetForum);
	}


}
