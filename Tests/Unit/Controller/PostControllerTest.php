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


class Tx_Typo3Forum_Controller_PostControllerTest extends Tx_Typo3Forum_Controller_AbstractControllerTest {



	/**
	 * @var \Mittwald\Typo3Forum\Controller\PostController
	 */
	protected $fixture;


	/**
	 * @var PHPUnit_Framework_MockObject_MockObject
	 */
	protected $forumRepositoryMock, $topicRepositoryMock, $postRepositoryMock, $topicFactoryMock, $postFactoryMock, $topicMock = NULL, $postMock = NULL;


	/**
	 * @var string
	 */
	protected $fixtureClassName = 'Mittwald\Typo3Forum\Controller\PostController';



	public function setUp() {
		$this->forumRepositoryMock = $this->getMock('Tx_Typo3Forum_Domain_Repository_Forum_ForumRepository');
		$this->topicRepositoryMock = $this->getMock('Tx_Typo3Forum_Domain_Repository_Forum_TopicRepository');
		$this->postRepositoryMock  = $this->getMock('Tx_Typo3Forum_Domain_Repository_Forum_PostRepository');
		$this->postFactoryMock     = $this->getMock('Tx_Typo3Forum_Domain_Factory_Forum_PostFactory', array(), array(), '',
		                                            FALSE);
		$this->buildFixture('Mittwald\Typo3Forum\Controller\PostController',
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
		$this->fixture->showAction($this->getMock('\Mittwald\Typo3Forum\Domain\Model\Forum\Post'));
	}



	public function testNewActionWithoutPostAndWithoutQuoteCreatesEmptyPost() {
		$this->postFactoryMock->expects($this->once())->method('createEmptyPost')
			->will($this->returnValue($this->getMock('\Mittwald\Typo3Forum\Domain\Model\Forum\Post')));
		$this->fixture->newAction($this->getMock('\Mittwald\Typo3Forum\Domain\Model\Forum\Topic'));
	}



	/**
	 * @depends testNewActionWithoutPostAndWithoutQuoteCreatesEmptyPost
	 */
	public function testNewActionWithoutPostAndWithoutQuoteAssignsPostToView() {
		$this->postFactoryMock->expects($this->once())->method('createEmptyPost')
			->will($this->returnValue($post = $this->getMock('\Mittwald\Typo3Forum\Domain\Model\Forum\Post')));
		$this->fixture->newAction($this->getMock('\Mittwald\Typo3Forum\Domain\Model\Forum\Topic'));
		$this->assertViewContains('post', $post);
	}



	public function testNewActionAssignsCurrentUserToView() {
		$this->userRepositoryMock->expects($this->any())->method('findCurrent')
			->will($this->returnValue($user = $this->getMock('\Mittwald\Typo3Forum\Domain\Model\User\FrontendUser')));
		$this->fixture->newAction($this->getMock('\Mittwald\Typo3Forum\Domain\Model\Forum\Topic'));
		$this->assertViewContains('currentUser', $user);
	}



	public function testNewActionWithoutPostButWithQuoteCreatesPostFromQuote() {
		$this->postFactoryMock->expects($this->once())->method('createPostWithQuote')
			->with($this->isInstanceOf('\Mittwald\Typo3Forum\Domain\Model\Forum\Post'))
			->will($this->returnValue($this->getMock('\Mittwald\Typo3Forum\Domain\Model\Forum\Post')));
		$this->fixture->newAction($this->getMock('\Mittwald\Typo3Forum\Domain\Model\Forum\Topic'), NULL,
		                          $this->getMock('\Mittwald\Typo3Forum\Domain\Model\Forum\Post'));
	}



	/**
	 * @depends testNewActionWithoutPostButWithQuoteCreatesPostFromQuote
	 */
	public function testNewActionWithoutPostButWithQuoteAssignsPostToView() {
		$this->postFactoryMock->expects($this->once())->method('createPostWithQuote')
			->with($this->isInstanceOf('\Mittwald\Typo3Forum\Domain\Model\Forum\Post'))
			->will($this->returnValue($post = $this->getMock('\Mittwald\Typo3Forum\Domain\Model\Forum\Post')));
		$this->fixture->newAction($this->getMock('\Mittwald\Typo3Forum\Domain\Model\Forum\Topic'), NULL,
		                          $this->getMock('\Mittwald\Typo3Forum\Domain\Model\Forum\Post'));
		$this->assertViewContains('post', $post);
	}



	public function testNewActionWithPostAssignsPostToView() {
		$this->fixture->newAction($this->getMock('\Mittwald\Typo3Forum\Domain\Model\Forum\Topic'),
		                          $post = $this->getMock('\Mittwald\Typo3Forum\Domain\Model\Forum\Post'));
		$this->assertViewContains('post', $post);
	}



	public function testNewActionAssignsTopicToView() {
		$this->fixture->newAction($topic = $this->getMock('\Mittwald\Typo3Forum\Domain\Model\Forum\Topic'));
		$this->assertViewContains('topic', $topic);
	}



	public function testCreateActionAddsPostToTopic() {
		$topic = $this->getMock('\Mittwald\Typo3Forum\Domain\Model\Forum\Topic');
		$topic->expects($this->once())->method('addPost');
		$post = $this->getMock('\Mittwald\Typo3Forum\Domain\Model\Forum\Post');
		$this->fixture->createAction($topic, $post);
	}



	public function testCreateActionUpdatesTopicInTopicRepository() {
		$topic = $this->getMock('\Mittwald\Typo3Forum\Domain\Model\Forum\Topic');
		$post  = $this->getMock('\Mittwald\Typo3Forum\Domain\Model\Forum\Post');
		$this->topicRepositoryMock->expects($this->atLeastOnce())->method('update');
		$this->fixture->createAction($topic, $post);
	}



	/**
	 * @dataProvider getActionMethodsThatAcceptPostAsArgument
	 * @param $actionMethodName
	 */
	public function testActionsAssignPostToView($actionMethodName) {
		$this->fixture->$actionMethodName($post = $this->getMock('\Mittwald\Typo3Forum\Domain\Model\Forum\Post'));
		$this->assertViewContains('post', $post);
	}



	public function getActionMethodsThatAcceptPostAsArgument() {
		return array(array('editAction'), array('confirmDeleteAction'));
	}



	public function testUpdateActionUpdatePostInPostRepository() {
		$this->postRepositoryMock->expects($this->atLeastOnce())->method('update');
		$this->fixture->updateAction($post = $this->getMock('\Mittwald\Typo3Forum\Domain\Model\Forum\Post'));
	}



	public function testDeleteActionDelegatesToPostFactory() {
		$this->postFactoryMock->expects($this->once())->method('deletePost');
		$this->fixture->deleteAction($post = $this->getMock('\Mittwald\Typo3Forum\Domain\Model\Forum\Post'));
	}



	/**
	 * @dataProvider getActionMethodsThatNotifySignalSlotDispatcher
	 * @param $actionMethodName
	 * @param $expectedEventName
	 * @param $arguments
	 */
	public function testActionsNotifiySignalSlotDispatcher($actionMethodName, $expectedEventName, $arguments) {
		$this->signalSlotDispatcherMock->expects($this->once())->method('dispatch')
			->with('\Mittwald\Typo3Forum\Domain\Model\Forum\Post', $expectedEventName, $this->anything());
		call_user_func_array(array($this->fixture, $actionMethodName), $arguments);
	}



	public function testDeleteActionRedirectsToForumIfLastPostOfTopicIsDeleted() {
		$topic = $this->getMock('\Mittwald\Typo3Forum\Domain\Model\Forum\Topic');
		$topic->expects($this->any())->method('getPostCount')->will($this->returnValue(1));
		$this->fixture->expects($this->once())->method('redirect')->with('show', 'Forum');
		$this->fixture->deleteAction($post = $this->getMock('\Mittwald\Typo3Forum\Domain\Model\Forum\Post'));
	}



	public function testDeleteActionRedirectsToTopicIfOtherPostsAreLeftInTopic() {
		$topic = $this->getMock('\Mittwald\Typo3Forum\Domain\Model\Forum\Topic');
		$topic->expects($this->any())->method('getPostCount')->will($this->returnValue(3));
		$this->fixture->expects($this->once())->method('redirect')->with('show', 'Topic');
		$this->fixture->deleteAction($post = $this->getMock('\Mittwald\Typo3Forum\Domain\Model\Forum\Post'));
	}



	public function testPreviewActionAssignsTextToView() {
		$this->fixture->previewAction('foo');
		$this->assertViewContains('text', 'foo');
	}



	public function getActionMethodsThatNotifySignalSlotDispatcher() {
		return array(array('createAction', 'postCreated', array($this->getMock('\Mittwald\Typo3Forum\Domain\Model\Forum\Topic'),
		                                                        $this->getMock('\Mittwald\Typo3Forum\Domain\Model\Forum\Post'))),
		             array('updateAction', 'postUpdated', array($this->getMock('\Mittwald\Typo3Forum\Domain\Model\Forum\Post'))),
		             array('deleteAction', 'postDeleted', array($this->getMock('\Mittwald\Typo3Forum\Domain\Model\Forum\Post'))));
	}



	public function getMock($originalClassName, $methods = array(), array $arguments = array(), $mockClassName = '',
	                        $callOriginalConstructor = TRUE, $callOriginalClone = TRUE, $callAutoload = TRUE) {
		if ($originalClassName === '\Mittwald\Typo3Forum\Domain\Model\Forum\Topic' || $originalClassName === '\Mittwald\Typo3Forum\Domain\Model\Forum\Post') {
			if ($this->topicMock === NULL || $this->postMock === NULL) {
				$this->topicMock = parent::getMock('\Mittwald\Typo3Forum\Domain\Model\Forum\Topic');
				$this->postMock  = parent::getMock('\Mittwald\Typo3Forum\Domain\Model\Forum\Post');
				$this->postMock->expects($this->any())->method('getTopic')->will($this->returnValue($this->topicMock));
				$this->topicMock->expects($this->any())->method('getPosts')
					->will($this->returnValue($this->buildPostMockList()));
			}

			if ($originalClassName === '\Mittwald\Typo3Forum\Domain\Model\Forum\Topic') {
				return $this->topicMock;
			} else {
				return $this->postMock;
			}
		} else {
			return parent::getMock($originalClassName, $methods, $arguments, $mockClassName, $callOriginalConstructor,
			                       $callOriginalClone, $callAutoload);
		}
	}



	public function getModifyingActions() {
		return array('updateAction', 'createAction', 'deleteAction');
	}
}
