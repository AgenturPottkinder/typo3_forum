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


class Tx_MmForum_Domain_Factory_Forum_PostFactoryTest extends Tx_Extbase_Tests_Unit_BaseTestCase {



	/**
	 * @var Tx_MmForum_Domain_Factory_Forum_PostFactory
	 */
	protected $fixture;


	/**
	 * @var PHPUnit_Framework_MockObject_MockObject
	 */
	protected $userRepositoryMock;



	public function setUp() {
		$this->userRepositoryMock = $this->getMock('Tx_MmForum_Domain_Repository_User_FrontendUserRepository');

		$this->fixture = new Tx_MmForum_Domain_Factory_Forum_PostFactory();
		$this->fixture->injectObjectManager($this->objectManager);
		$this->fixture->injectFrontendUserRepository($this->userRepositoryMock);
	}



	/**
	 * @test
	 */
	public function createEmptyPostReturnsEmptyPost() {
		$post = $this->fixture->createEmptyPost();

		$this->assertInstanceOf('Tx_MmForum_Domain_Model_Forum_Post', $post);
		$this->assertEquals('', $post->getText());
	}



	/**
	 * @test
	 */
	public function createQuotePostContainsTextOfQuotedPost() {
		$quotedPost = new Tx_MmForum_Domain_Model_Forum_Post("Quote");
		$quotedPost->_setProperty('uid', 123);

		$post = $this->fixture->createPostWithQuote($quotedPost);

		$this->assertInstanceOf('Tx_MmForum_Domain_Model_Forum_Post', $post);
		$this->assertEquals('[quote=123]Quote[/quote]', $post->getText());
	}



	/**
	 * @test
	 */
	public function userPostCountIsIncreasedWhenPostIsAssigned() {
		$post = new Tx_MmForum_Domain_Model_Forum_Post('Content');
		$user = $this->getMock('Tx_MmForum_Domain_Model_User_FrontendUser');
		$user->expects($this->once())->method('increasePostCount');
		$this->userRepositoryMock->expects($this->once())->method('update')
			->with(new PHPUnit_Framework_Constraint_IsInstanceOf('Tx_MmForum_Domain_Model_User_FrontendUser'));

		$this->fixture->assignUserToPost($post, $user);

		$this->assertTrue($post->getAuthor() == $user);
	}



	/**
	 * @test
	 */
	public function userPostCountsAreDecreasedAndIncreasedWhenPostIsReassigned() {
		$newUser = $this->getMock('Tx_MmForum_Domain_Model_User_FrontendUser');
		$newUser->expects($this->once())->method('increasePostCount');
		$oldUser = $this->getMock('Tx_MmForum_Domain_Model_User_FrontendUser');
		$oldUser->expects($this->once())->method('decreasePostCount');
		$post = new Tx_MmForum_Domain_Model_Forum_Post('Content');
		$post->setAuthor($oldUser);

		$this->userRepositoryMock->expects($this->exactly(2))->method('update')
			->with(new PHPUnit_Framework_Constraint_IsInstanceOf('Tx_MmForum_Domain_Model_User_FrontendUser'));

		$this->fixture->assignUserToPost($post, $newUser);

		$this->assertTrue($post->getAuthor() == $newUser);
	}



	/**
	 * @test
	 */
	public function topicIsDeletedWhenLastPostIsDeleted() {
		$topic = $this->getMock('Tx_MmForum_Domain_Model_Forum_Topic');
		$topic->expects($this->any())->method('getPostCount')->will($this->returnValue(1));

		$post = new Tx_MmForum_Domain_Model_Forum_Post('Content');
		$post->setTopic($topic);

		$topicFactory = $this->getMock('Tx_MmForum_Domain_Factory_Forum_TopicFactory', array(), array(), '', FALSE);
		$topicFactory->expects($this->once())->method('deleteTopic')
			->with(new PHPUnit_Framework_Constraint_IsInstanceOf('Tx_MmForum_Domain_Model_Forum_Topic'));
		$this->fixture->injectTopicFactory($topicFactory);
		$this->fixture->deletePost($post);
	}



	/**
	 * @test
	 */
	public function userPostCountIsDecreasedAndTopicUpdatedWhenPostIsDeleted() {
		$topic = $this->getMock('Tx_MmForum_Domain_Model_Forum_Topic');
		$topic->expects($this->any())->method('getPostCount')->will($this->returnValue(3));
		$topic->expects($this->once())->method('removePost')
			->with(new PHPUnit_Framework_Constraint_IsInstanceOf('Tx_MmForum_Domain_Model_Forum_Post'));
		$user = $this->getMock('Tx_MmForum_Domain_Model_User_FrontendUser');
		$user->expects($this->once())->method('decreasePostCount');

		$post = new Tx_MmForum_Domain_Model_Forum_Post('Content');
		$post->setTopic($topic);
		$post->setAuthor($user);

		$topicRepository = $this->getMock('Tx_MmForum_Domain_Repository_Forum_TopicRepository');
		$topicRepository->expects($this->once())->method('update')
			->with(new PHPUnit_Framework_Constraint_IsInstanceOf('Tx_MmForum_Domain_Model_Forum_Topic'));
		$this->userRepositoryMock->expects($this->once())->method('update')
			->with(new PHPUnit_Framework_Constraint_IsInstanceOf('Tx_MmForum_Domain_Model_User_FrontendUser'));

		$this->fixture->injectTopicRepository($topicRepository);
		$this->fixture->deletePost($post);
	}



}
