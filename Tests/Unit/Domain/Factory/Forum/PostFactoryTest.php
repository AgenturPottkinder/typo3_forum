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


class Tx_Typo3Forum_Domain_Factory_Forum_PostFactoryTest extends \TYPO3\CMS\Core\Tests\UnitTestCase {



	/**
	 * @var \Mittwald\Typo3Forum\Domain\Factory\Forum\PostFactory
	 */
	protected $fixture;


	/**
	 * @var PHPUnit_Framework_MockObject_MockObject
	 */
	protected $userRepositoryMock;



	public function setUp() {
		$this->userRepositoryMock = $this->getMock('\Mittwald\Typo3Forum\Domain\Repository\User\FrontendUserRepository');

		$this->fixture = new Tx_Typo3Forum_Domain_Factory_Forum_PostFactory();
		$this->fixture->injectObjectManager($this->objectManager);
		$this->fixture->injectFrontendUserRepository($this->userRepositoryMock);
	}



	/**
	 * @test
	 */
	public function createEmptyPostReturnsEmptyPost() {
		$post = $this->fixture->createEmptyPost();

		$this->assertInstanceOf('\Mittwald\Typo3Forum\Domain\Model\Forum\Post', $post);
		$this->assertEquals('', $post->getText());
	}



	/**
	 * @test
	 */
	public function createQuotePostContainsTextOfQuotedPost() {
		$quotedPost = new \Mittwald\Typo3Forum\Domain\Model\Forum\Post("Quote");
		$quotedPost->_setProperty('uid', 123);

		$post = $this->fixture->createPostWithQuote($quotedPost);

		$this->assertInstanceOf('\Mittwald\Typo3Forum\Domain\Model\Forum\Post', $post);
		$this->assertEquals('[quote=123]Quote[/quote]', $post->getText());
	}



	/**
	 * @test
	 */
	public function userPostCountIsIncreasedWhenPostIsAssigned() {
		$post = new \Mittwald\Typo3Forum\Domain\Model\Forum\Post('Content');
		$user = $this->getMock('\Mittwald\Typo3Forum\Domain\Model\User\FrontendUser');
		$user->expects($this->once())->method('increasePostCount');
		$this->userRepositoryMock->expects($this->once())->method('update')
			->with(new PHPUnit_Framework_Constraint_IsInstanceOf('\Mittwald\Typo3Forum\Domain\Model\User\FrontendUser'));

		$this->fixture->assignUserToPost($post, $user);

		$this->assertTrue($post->getAuthor() == $user);
	}



	/**
	 * @test
	 */
	public function postIsAssignedToCurrentUserWhenNoUserSpecified() {
		$post = new \Mittwald\Typo3Forum\Domain\Model\Forum\Post('Content');
		$user = $this->getMock('\Mittwald\Typo3Forum\Domain\Model\User\FrontendUser');
		$user->expects($this->once())->method('increasePostCount');
		$this->userRepositoryMock->expects($this->any())->method('findCurrent')->will($this->returnValue($user));
		$this->userRepositoryMock->expects($this->once())->method('update')
			->with(new PHPUnit_Framework_Constraint_IsInstanceOf('\Mittwald\Typo3Forum\Domain\Model\User\FrontendUser'));

		$this->fixture->assignUserToPost($post);

		$this->assertTrue($post->getAuthor() == $user);
	}



	/**
	 * @test
	 */
	public function postIsNotAssignedIfCurrentUserIsAnonymous() {
		$post = new \Mittwald\Typo3Forum\Domain\Model\Forum\Post('Content');
		$this->userRepositoryMock->expects($this->any())->method('findCurrent')
			->will($this->returnValue(new \Mittwald\Typo3Forum\Domain\Model\User\AnonymousFrontendUser()));
		$this->userRepositoryMock->expects($this->never())->method('update');
		$this->fixture->assignUserToPost($post);
	}



	/**
	 * @test
	 * @expectedException \Mittwald\Typo3Forum\Domain\Exception\Authentication\NotLoggedInException
	 */
	public function exceptionIsThrownWhenAssigningPostToNullUser() {
		$post = new \Mittwald\Typo3Forum\Domain\Model\Forum\Post('Content');
		$this->userRepositoryMock->expects($this->any())->method('findCurrent')->will($this->returnValue(NULL));
		$this->fixture->assignUserToPost($post);
	}



	/**
	 * @test
	 */
	public function userPostCountsAreDecreasedAndIncreasedWhenPostIsReassigned() {
		$newUser = $this->getMock('\Mittwald\Typo3Forum\Domain\Model\User\FrontendUser');
		$newUser->expects($this->once())->method('increasePostCount');
		$oldUser = $this->getMock('\Mittwald\Typo3Forum\Domain\Model\User\FrontendUser');
		$oldUser->expects($this->once())->method('decreasePostCount');
		$post = new \Mittwald\Typo3Forum\Domain\Model\Forum\Post('Content');
		$post->setAuthor($oldUser);

		$this->userRepositoryMock->expects($this->exactly(2))->method('update')
			->with(new PHPUnit_Framework_Constraint_IsInstanceOf('\Mittwald\Typo3Forum\Domain\Model\User\FrontendUser'));

		$this->fixture->assignUserToPost($post, $newUser);

		$this->assertTrue($post->getAuthor() == $newUser);
	}



	/**
	 * @test
	 */
	public function topicIsDeletedWhenLastPostIsDeleted() {
		$topic = $this->getMock('\Mittwald\Typo3Forum\Domain\Model\Forum\Topic');
		$topic->expects($this->any())->method('getPostCount')->will($this->returnValue(1));

		$post = new \Mittwald\Typo3Forum\Domain\Model\Forum\Post('Content');
		$post->setTopic($topic);

		$topicFactory = $this->getMock('\Mittwald\Typo3Forum\Domain\Factory\Forum\TopicFactory', array(), array(), '', FALSE);
		$topicFactory->expects($this->once())->method('deleteTopic')
			->with(new PHPUnit_Framework_Constraint_IsInstanceOf('\Mittwald\Typo3Forum\Domain\Model\Forum\Topic'));
		$this->fixture->injectTopicFactory($topicFactory);
		$this->fixture->deletePost($post);
	}



	/**
	 * @test
	 */
	public function userPostCountIsDecreasedAndTopicUpdatedWhenPostIsDeleted() {
		$topic = $this->getMock('\Mittwald\Typo3Forum\Domain\Model\Forum\Topic');
		$topic->expects($this->any())->method('getPostCount')->will($this->returnValue(3));
		$topic->expects($this->once())->method('removePost')
			->with(new PHPUnit_Framework_Constraint_IsInstanceOf('\Mittwald\Typo3Forum\Domain\Model\Forum\Post'));
		$user = $this->getMock('\Mittwald\Typo3Forum\Domain\Model\User\FrontendUser');
		$user->expects($this->once())->method('decreasePostCount');

		$post = new \Mittwald\Typo3Forum\Domain\Model\Forum\Post('Content');
		$post->setTopic($topic);
		$post->setAuthor($user);

		$topicRepository = $this->getMock('\Mittwald\Typo3Forum\Domain\Repository\Forum\TopicRepository');
		$topicRepository->expects($this->once())->method('update')
			->with(new PHPUnit_Framework_Constraint_IsInstanceOf('\Mittwald\Typo3Forum\Domain\Model\Forum\Topic'));
		$this->userRepositoryMock->expects($this->once())->method('update')
			->with(new PHPUnit_Framework_Constraint_IsInstanceOf('\Mittwald\Typo3Forum\Domain\Model\User\FrontendUser'));

		$this->fixture->injectTopicRepository($topicRepository);
		$this->fixture->deletePost($post);
	}



}
