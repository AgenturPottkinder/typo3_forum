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


class Tx_Typo3Forum_Controller_TopicControllerTest extends Tx_Typo3Forum_Controller_AbstractControllerTest {



	/**
	 * @var \Mittwald\Typo3Forum\Controller\TopicController
	 */
	protected $fixture;


	/**
	 * @var PHPUnit_Framework_MockObject_MockObject
	 */
	protected $forumRepositoryMock, $topicRepositoryMock, $postRepositoryMock, $topicFactoryMock, $postFactoryMock,
			  $criteraRepositoryMock, $sessionHandlingMock, $attachmentServiceMock;


	/**
	 * @var string
	 */
	protected $fixtureClassName = '\Mittwald\Typo3Forum\Controller\TopicController';



	public function setUp() {
		$this->forumRepositoryMock		= $this->getMock('Tx_Typo3Forum_Domain_Repository_Forum_ForumRepository');
		$this->topicRepositoryMock		= $this->getMock('Tx_Typo3Forum_Domain_Repository_Forum_TopicRepository');
		$this->postRepositoryMock		= $this->getMock('Tx_Typo3Forum_Domain_Repository_Forum_PostRepository');
		$this->topicFactoryMock			= $this->getMock('\Mittwald\Typo3Forum\Domain\Factory\Forum\TopicFactory', array(), array(),
														'', FALSE);
		$this->postFactoryMock			= $this->getMock('Tx_Typo3Forum_Domain_Factory_Forum_PostFactory', array(), array(), '',
														FALSE);
		$this->criteraRepositoryMock	= $this->getMock('Tx_Typo3Forum_Domain_Repository_Forum_CriteriaRepository');
		$this->sessionHandlingMock		= $this->getMock('Tx_Typo3Forum_Service_SessionHandlingService');
		$this->attachmentServiceMock	= $this->getMock('Tx_Typo3Forum_Service_AttachmentService');

		$this->buildFixture('\Mittwald\Typo3Forum\Controller\TopicController',
		                    array($this->forumRepositoryMock, $this->topicRepositoryMock, $this->postRepositoryMock,
		                         $this->topicFactoryMock, $this->postFactoryMock, $this->criteraRepositoryMock,
								 $this->sessionHandlingMock, $this->attachmentServiceMock
							));
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
		$this->fixture->showAction($topic = $this->getMock('\Mittwald\Typo3Forum\Domain\Model\Forum\Topic'));
	}



	public function testShowActionAssignsTopicToView() {
		$this->postRepositoryMock->expects($this->atLeastOnce())->method('findForTopic')
			->will($this->returnValue($posts = $this->buildPostMockList()));
		$this->fixture->showAction($topic = $this->getMock('\Mittwald\Typo3Forum\Domain\Model\Forum\Topic'));
		$this->assertTrue($this->viewMock->containsKeyValuePair('topic', $topic));
	}



	public function testShowActionAssignsPostsToView() {
		$this->postRepositoryMock->expects($this->atLeastOnce())->method('findForTopic')
			->will($this->returnValue($posts = $this->buildPostMockList()));
		$this->fixture->showAction($topic = $this->getMock('\Mittwald\Typo3Forum\Domain\Model\Forum\Topic'));
		$this->assertTrue($this->viewMock->containsKeyValuePair('posts', $posts));
	}



	public function testShowActionAddsTopicToUsersReadTopicsIfUserIsLoggedIn() {
		$this->userRepositoryMock->expects($this->any())->method('findCurrent')
			->will($this->returnValue($user = $this->getMock('\Mittwald\Typo3Forum\Domain\Model\User\FrontendUser')));
		$this->userRepositoryMock->expects($this->atLeastOnce())->method('update');
		$user->expects($this->once())->method('addReadObject')
			->with($this->isInstanceOf('\Mittwald\Typo3Forum\Domain\Model\Forum\Topic'));
		$this->fixture->showAction($topic = $this->getMock('\Mittwald\Typo3Forum\Domain\Model\Forum\Topic'));
	}



	public function testShowActionDoesNotAddTopicToUsersReadTopicsIfNoUserIsLoggedIn() {
		$this->userRepositoryMock->expects($this->any())->method('findCurrent')
			->will($this->returnValue($user = new \Mittwald\Typo3Forum\Domain\Model\User\AnonymousFrontendUser()));
		$this->userRepositoryMock->expects($this->never())->method('update');
		$this->fixture->showAction($topic = $this->getMock('\Mittwald\Typo3Forum\Domain\Model\Forum\Topic'));
	}



	public function testNewActionAssignsForumPostAndSubjectToView() {
		$this->fixture->newAction($forum = $this->getMock('\Mittwald\Typo3Forum\Domain\Model\Forum\Forum'),
		                          $post = $this->getMock('\Mittwald\Typo3Forum\Domain\Model\Forum\Post'), 'Foo');
		$this->assertTrue($this->viewMock->containsKeyValuePair('forum', $forum));
		$this->assertTrue($this->viewMock->containsKeyValuePair('post', $post));
		$this->assertTrue($this->viewMock->containsKeyValuePair('subject', 'Foo'));
	}



	public function testNewActionAssignsCurrentUserToView() {
		$this->userRepositoryMock->expects($this->any())->method('findCurrent')
			->will($this->returnValue($user = $this->getMock('\Mittwald\Typo3Forum\Domain\Model\User\FrontendUser')));
		$this->fixture->newAction($forum = $this->getMock('\Mittwald\Typo3Forum\Domain\Model\Forum\Forum'),
		                          $post = $this->getMock('\Mittwald\Typo3Forum\Domain\Model\Forum\Post'), 'Foo');
		$this->assertViewContains('currentUser', $user);
	}



	public function testCreateActionDelegatesTopicCreationToTopicFactory() {
		$this->topicFactoryMock->expects($this->once())->method('createTopic');
		$this->fixture->createAction($forum = $this->getMock('\Mittwald\Typo3Forum\Domain\Model\Forum\Forum'),
		                             $post = $this->getMock('\Mittwald\Typo3Forum\Domain\Model\Forum\Post'), 'Foo');
	}



	public function testCreateActionNotifiesSignalSlotDispatcher() {
		$this->signalSlotDispatcherMock->expects($this->once())->method('dispatch')
			->with('\Mittwald\Typo3Forum\Domain\Model\Forum\Topic', 'topicCreated', $this->anything());
		$this->fixture->createAction($forum = $this->getMock('\Mittwald\Typo3Forum\Domain\Model\Forum\Forum'),
		                             $post = $this->getMock('\Mittwald\Typo3Forum\Domain\Model\Forum\Post'), 'Foo');
	}


	public function testSolutionActionSetsSolution() {
		$post = $this->getMock('\Mittwald\Typo3Forum\Domain\Model\Forum\Post');
		$topic = $this->getMock('\Mittwald\Typo3Forum\Domain\Model\Forum\Topic');
		$post->expects($this->any())->method('setSolution');
		$post->expects($this->any())->method('getTopic')->will($this->returnValue($topic));

		$this->fixture->solutionAction($post);
		$this->assertTrue(true);
	}


	/**
	 * @expectedException \Mittwald\Typo3Forum\Domain\Exception\Authentication\NoAccessException
	 */
	public function testSolutionUnauthorizedAccessThrowsException() {
		$post = $this->getMock('\Mittwald\Typo3Forum\Domain\Model\Forum\Post');
		$this->authenticationServiceMock->expects($this->any())->method('getUser')->will($this->returnValue('Foo'));
		$post->expects($this->any())->method('getAuthor')->will($this->returnValue('Bar'));

		$this->fixture->solutionAction($post);
	}


	public function getModifyingActions() {
		return array('createAction');
	}
}
