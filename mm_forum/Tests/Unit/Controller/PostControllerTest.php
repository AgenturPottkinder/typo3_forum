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


class Tx_MmForum_Controller_PostControllerTest extends Tx_MmForum_Controller_AbstractControllerTest {



	/**
	 * @var Tx_MmForum_Controller_PostController
	 */
	protected $fixture;


	/**
	 * @var PHPUnit_Framework_MockObject_MockObject
	 */
	protected $forumRepositoryMock, $topicRepositoryMock, $postRepositoryMock, $topicFactoryMock, $postFactoryMock, $topicMock = NULL, $postMock = NULL;


	/**
	 * @var string
	 */
	protected $fixtureClassName = 'Tx_MmForum_Controller_PostController';



	public function setUp() {
		$this->forumRepositoryMock = $this->getMock('Tx_MmForum_Domain_Repository_Forum_ForumRepository');
		$this->topicRepositoryMock = $this->getMock('Tx_MmForum_Domain_Repository_Forum_TopicRepository');
		$this->postRepositoryMock  = $this->getMock('Tx_MmForum_Domain_Repository_Forum_PostRepository');
		$this->postFactoryMock     = $this->getMock('Tx_MmForum_Domain_Factory_Forum_PostFactory', array(), array(), '',
		                                            FALSE);
		$this->buildFixture('Tx_MmForum_Controller_PostController',
		                    array($this->forumRepositoryMock, $this->topicRepositoryMock, $this->postRepositoryMock,
		                         $this->postFactoryMock));
	}



	/**
	 * @dataProvider getAllControllerActionsWithMockParameters
	 * @param $actionMethodName
	 * @param $parameters
	 */
	public function testAllControllerActionsPerformAuthorizationCheck($actionMethodName, $parameters) {
		if ($actionMethodName === 'previewAction') {
			return;
		} else {
			$this->authenticationServiceMock->expects($this->atLeastOnce())->method('checkAuthorization')
				->will($this->returnValue(TRUE));
			call_user_func_array(array($this->fixture, $actionMethodName), $parameters);
		}
	}



	public function testShowActionRedirectsToTopicController() {
		$this->fixture->expects($this->once())->method('redirect')->with('show', 'Topic');
		$this->fixture->showAction($this->getMock('Tx_MmForum_Domain_Model_Forum_Post'));
	}



	public function testNewActionWithoutPostAndWithoutQuoteCreatesEmptyPost() {
		$this->postFactoryMock->expects($this->once())->method('createEmptyPost')
			->will($this->returnValue($this->getMock('Tx_MmForum_Domain_Model_Forum_Post')));
		$this->fixture->newAction($this->getMock('Tx_MmForum_Domain_Model_Forum_Topic'));
	}



	/**
	 * @depends testNewActionWithoutPostAndWithoutQuoteCreatesEmptyPost
	 */
	public function testNewActionWithoutPostAndWithoutQuoteAssignsPostToView() {
		$this->postFactoryMock->expects($this->once())->method('createEmptyPost')
			->will($this->returnValue($post = $this->getMock('Tx_MmForum_Domain_Model_Forum_Post')));
		$this->fixture->newAction($this->getMock('Tx_MmForum_Domain_Model_Forum_Topic'));
		$this->assertViewContains('post', $post);
	}



	public function testNewActionWithoutPostButWithQuoteCreatesPostFromQuote() {
		$this->postFactoryMock->expects($this->once())->method('createPostWithQuote')
			->with($this->isInstanceOf('Tx_MmForum_Domain_Model_Forum_Post'))
			->will($this->returnValue($this->getMock('Tx_MmForum_Domain_Model_Forum_Post')));
		$this->fixture->newAction($this->getMock('Tx_MmForum_Domain_Model_Forum_Topic'), NULL,
		                          $this->getMock('Tx_MmForum_Domain_Model_Forum_Post'));
	}



	/**
	 * @depends testNewActionWithoutPostButWithQuoteCreatesPostFromQuote
	 */
	public function testNewActionWithoutPostButWithQuoteAssignsPostToView() {
		$this->postFactoryMock->expects($this->once())->method('createPostWithQuote')
			->with($this->isInstanceOf('Tx_MmForum_Domain_Model_Forum_Post'))
			->will($this->returnValue($post = $this->getMock('Tx_MmForum_Domain_Model_Forum_Post')));
		$this->fixture->newAction($this->getMock('Tx_MmForum_Domain_Model_Forum_Topic'), NULL,
		                          $this->getMock('Tx_MmForum_Domain_Model_Forum_Post'));
		$this->assertViewContains('post', $post);
	}



	public function testNewActionWithPostAssignsPostToView() {
		$this->fixture->newAction($this->getMock('Tx_MmForum_Domain_Model_Forum_Topic'),
		                          $post = $this->getMock('Tx_MmForum_Domain_Model_Forum_Post'));
		$this->assertViewContains('post', $post);
	}



	public function testNewActionAssignsTopicToView() {
		$this->fixture->newAction($topic = $this->getMock('Tx_MmForum_Domain_Model_Forum_Topic'));
		$this->assertViewContains('topic', $topic);
	}



	public function testCreateActionAddsPostToTopic() {
		$topic = $this->getMock('Tx_MmForum_Domain_Model_Forum_Topic');
		$topic->expects($this->once())->method('addPost');
		$post = $this->getMock('Tx_MmForum_Domain_Model_Forum_Post');
		$this->fixture->createAction($topic, $post);
	}



	public function testCreateActionUpdatesTopicInTopicRepository() {
		$topic = $this->getMock('Tx_MmForum_Domain_Model_Forum_Topic');
		$post  = $this->getMock('Tx_MmForum_Domain_Model_Forum_Post');
		$this->topicRepositoryMock->expects($this->atLeastOnce())->method('update');
		$this->fixture->createAction($topic, $post);
	}



	/**
	 * @dataProvider getActionMethodsThatAcceptPostAsArgument
	 * @param $actionMethodName
	 */
	public function testActionsAssignPostToView($actionMethodName) {
		$this->fixture->$actionMethodName($post = $this->getMock('Tx_MmForum_Domain_Model_Forum_Post'));
		$this->assertViewContains('post', $post);
	}



	public function getActionMethodsThatAcceptPostAsArgument() {
		return array(array('editAction'), array('confirmDeleteAction'));
	}



	public function testUpdateActionUpdatePostInPostRepository() {
		$this->postRepositoryMock->expects($this->atLeastOnce())->method('update');
		$this->fixture->updateAction($post = $this->getMock('Tx_MmForum_Domain_Model_Forum_Post'));
	}



	public function testDeleteActionDelegatesToPostFactory() {
		$this->postFactoryMock->expects($this->once())->method('deletePost');
		$this->fixture->deleteAction($post = $this->getMock('Tx_MmForum_Domain_Model_Forum_Post'));
	}



	/**
	 * @dataProvider getActionMethodsThatNotifySignalSlotDispatcher
	 * @param $actionMethodName
	 * @param $expectedEventName
	 * @param $arguments
	 */
	public function testActionsNotifiySignalSlotDispatcher($actionMethodName, $expectedEventName, $arguments) {
		$this->signalSlotDispatcherMock->expects($this->once())->method('dispatch')
			->with('Tx_MmForum_Domain_Model_Forum_Post', $expectedEventName, $this->anything());
		call_user_func_array(array($this->fixture, $actionMethodName), $arguments);
	}



	public function testDeleteActionRedirectsToForumIfLastPostOfTopicIsDeleted() {
		$topic = $this->getMock('Tx_MmForum_Domain_Model_Forum_Topic');
		$topic->expects($this->any())->method('getPostCount')->will($this->returnValue(1));
		$this->fixture->expects($this->once())->method('redirect')->with('show', 'Forum');
		$this->fixture->deleteAction($post = $this->getMock('Tx_MmForum_Domain_Model_Forum_Post'));
	}



	public function testDeleteActionRedirectsToTopicIfOtherPostsAreLeftInTopic() {
		$topic = $this->getMock('Tx_MmForum_Domain_Model_Forum_Topic');
		$topic->expects($this->any())->method('getPostCount')->will($this->returnValue(3));
		$this->fixture->expects($this->once())->method('redirect')->with('show', 'Topic');
		$this->fixture->deleteAction($post = $this->getMock('Tx_MmForum_Domain_Model_Forum_Post'));
	}



	public function testPreviewActionAssignsTextToView() {
		$this->fixture->previewAction('foo');
		$this->assertViewContains('text', 'foo');
	}



	public function getActionMethodsThatNotifySignalSlotDispatcher() {
		return array(array('createAction', 'postCreated', array($this->getMock('Tx_MmForum_Domain_Model_Forum_Topic'),
		                                                        $this->getMock('Tx_MmForum_Domain_Model_Forum_Post'))),
		             array('updateAction', 'postUpdated', array($this->getMock('Tx_MmForum_Domain_Model_Forum_Post'))),
		             array('deleteAction', 'postDeleted', array($this->getMock('Tx_MmForum_Domain_Model_Forum_Post'))));
	}



	public function getMock($originalClassName, $methods = array(), array $arguments = array(), $mockClassName = '',
	                        $callOriginalConstructor = TRUE, $callOriginalClone = TRUE, $callAutoload = TRUE) {
		if ($originalClassName === 'Tx_MmForum_Domain_Model_Forum_Topic' || $originalClassName === 'Tx_MmForum_Domain_Model_Forum_Post') {
			if ($this->topicMock === NULL || $this->postMock === NULL) {
				$this->topicMock = parent::getMock('Tx_MmForum_Domain_Model_Forum_Topic');
				$this->postMock  = parent::getMock('Tx_MmForum_Domain_Model_Forum_Post');
				$this->postMock->expects($this->any())->method('getTopic')->will($this->returnValue($this->topicMock));
				$this->topicMock->expects($this->any())->method('getPosts')
					->will($this->returnValue($this->buildPostMockList()));
			}

			if ($originalClassName === 'Tx_MmForum_Domain_Model_Forum_Topic') {
				return $this->topicMock;
			} else {
				return $this->postMock;
			}
		} else {
			return parent::getMock($originalClassName, $methods, $arguments, $mockClassName, $callOriginalConstructor,
			                       $callOriginalClone, $callAutoload);
		}
	}



}
