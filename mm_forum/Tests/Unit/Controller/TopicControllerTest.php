<?php

/*                                                                    - *
 *  COPYRIGHT NOTICE                                                    *
 *                                                                      *
 *  (c) 2012 Martin Helmich <typo3@martin-helmich.de>                   *
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


class Tx_MmForum_Controller_TopicControllerTest extends Tx_MmForum_Controller_AbstractControllerTest {



	/**
	 * @var Tx_MmForum_Controller_TopicController
	 */
	protected $fixture;


	/**
	 * @var PHPUnit_Framework_MockObject_MockObject
	 */
	protected $forumRepositoryMock, $topicRepositoryMock, $postRepositoryMock, $topicFactoryMock, $postFactoryMock;


	/**
	 * @var string
	 */
	protected $fixtureClassName = 'Tx_MmForum_Controller_TopicController';



	public function setUp() {
		$this->forumRepositoryMock = $this->getMock('Tx_MmForum_Domain_Repository_Forum_ForumRepository');
		$this->topicRepositoryMock = $this->getMock('Tx_MmForum_Domain_Repository_Forum_TopicRepository');
		$this->postRepositoryMock  = $this->getMock('Tx_MmForum_Domain_Repository_Forum_PostRepository');
		$this->topicFactoryMock    = $this->getMock('Tx_MmForum_Domain_Factory_Forum_TopicFactory', array(), array(),
		                                            '', FALSE);
		$this->postFactoryMock     = $this->getMock('Tx_MmForum_Domain_Factory_Forum_PostFactory', array(), array(), '',
		                                            FALSE);
		$this->buildFixture('Tx_MmForum_Controller_TopicController',
		                    array($this->forumRepositoryMock, $this->topicRepositoryMock, $this->postRepositoryMock,
		                         $this->topicFactoryMock, $this->postFactoryMock));
	}



	/**
	 * @dataProvider getAllControllerActionsWithMockParameters
	 * @param $actionMethodName
	 * @param $parameters
	 */
	public function testAllControllerActionsPerformAuthorizationCheck($actionMethodName, $parameters) {
		$this->authenticationServiceMock->expects($this->atLeastOnce())->method('checkAuthorization')
			->will($this->returnValue(TRUE));
		call_user_func_array(array($this->fixture, $actionMethodName), $parameters);
	}



	public function testShowActionQueriesPostsFromRepository() {
		$this->postRepositoryMock->expects($this->atLeastOnce())->method('findForTopic')
			->will($this->returnValue($posts = $this->buildPostMockList()));
		$this->fixture->showAction($topic = $this->getMock('Tx_MmForum_Domain_Model_Forum_Topic'));
	}



	public function testShowActionAssignsTopicToView() {
		$this->postRepositoryMock->expects($this->atLeastOnce())->method('findForTopic')
			->will($this->returnValue($posts = $this->buildPostMockList()));
		$this->fixture->showAction($topic = $this->getMock('Tx_MmForum_Domain_Model_Forum_Topic'));
		$this->assertTrue($this->viewMock->containsKeyValuePair('topic', $topic));
	}



	public function testShowActionAssignsPostsToView() {
		$this->postRepositoryMock->expects($this->atLeastOnce())->method('findForTopic')
			->will($this->returnValue($posts = $this->buildPostMockList()));
		$this->fixture->showAction($topic = $this->getMock('Tx_MmForum_Domain_Model_Forum_Topic'));
		$this->assertTrue($this->viewMock->containsKeyValuePair('posts', $posts));
	}



	public function testShowActionAddsTopicToUsersReadTopics() {
		$this->userRepositoryMock->expects($this->any())->method('findCurrent')
			->will($this->returnValue($user = $this->getMock('Tx_MmForum_Domain_Model_User_FrontendUser')));
		$this->userRepositoryMock->expects($this->atLeastOnce())->method('update');
		$user->expects($this->once())->method('addReadObject')
			->with($this->isInstanceOf('Tx_MmForum_Domain_Model_Forum_Topic'));
		$this->fixture->showAction($topic = $this->getMock('Tx_MmForum_Domain_Model_Forum_Topic'));
	}



	public function testNewActionAssignsForumPostAndSubjectToView() {
		$this->fixture->newAction($forum = $this->getMock('Tx_MmForum_Domain_Model_Forum_Forum'),
		                          $post = $this->getMock('Tx_MmForum_Domain_Model_Forum_Post'), 'Foo');
		$this->assertTrue($this->viewMock->containsKeyValuePair('forum', $forum));
		$this->assertTrue($this->viewMock->containsKeyValuePair('post', $post));
		$this->assertTrue($this->viewMock->containsKeyValuePair('subject', 'Foo'));
	}



	public function testCreateActionDelegatesTopicCreationToTopicFactory() {
		$this->topicFactoryMock->expects($this->once())->method('createTopic');
		$this->fixture->createAction($forum = $this->getMock('Tx_MmForum_Domain_Model_Forum_Forum'),
		                             $post = $this->getMock('Tx_MmForum_Domain_Model_Forum_Post'), 'Foo');
	}



	public function testCreateActionNotifiesSignalSlotDispatcher() {
		$this->signalSlotDispatcherMock->expects($this->once())->method('dispatch')
			->with('Tx_MmForum_Domain_Model_Forum_Topic', 'topicCreated', $this->anything());
		$this->fixture->createAction($forum = $this->getMock('Tx_MmForum_Domain_Model_Forum_Forum'),
		                             $post = $this->getMock('Tx_MmForum_Domain_Model_Forum_Post'), 'Foo');
	}



	public function getModifyingActions() {
		return array('createAction');
	}
}
