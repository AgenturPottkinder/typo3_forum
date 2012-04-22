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


class Tx_MmForum_Controller_ForumControllerTest extends Tx_MmForum_Controller_AbstractControllerTest {



	/**
	 * @var Tx_MmForum_Controller_ForumController
	 */
	protected $fixture;


	/**
	 * @var PHPUnit_Framework_MockObject_MockObject|Tx_MmForum_Domain_Repository_Forum_ForumRepository
	 */
	public $forumRepositoryMock;


	/**
	 * @var PHPUnit_Framework_MockObject_MockObject|Tx_MmForum_Domain_Repository_Forum_TopicRepository
	 */
	public $topicRepositoryMock;


	/**
	 * @var PHPUnit_Framework_MockObject_MockObject|Tx_MmForum_Domain_Model_Forum_RootForum
	 */
	protected $rootForumMock;



	public function setUp() {
		$this->forumRepositoryMock = $this->getMock('Tx_MmForum_Domain_Repository_Forum_ForumRepository');
		$this->topicRepositoryMock = $this->getMock('Tx_MmForum_Domain_Repository_Forum_TopicRepository');
		$this->rootForumMock       = $this->getMock('Tx_MmForum_Domain_Model_Forum_RootForum');
		$this->buildFixture('Tx_MmForum_Controller_ForumController',
		                    array($this->forumRepositoryMock, $this->topicRepositoryMock, $this->rootForumMock));
	}



	/**
	 * @dataProvider getAllControllerActionsWithMockParameters
	 * @param $actionMethodName
	 * @param $parameters
	 */
	public function testAllControllerActionsPerformAuthorizationCheck($actionMethodName, $parameters) {
		$this->authenticationServiceMock->expects($this->atLeastOnce())->method('checkAuthorization')
			->with($this->isInstanceOf('Tx_MmForum_Domain_Model_Forum_Forum'), $this->anything())
			->will($this->returnValue(TRUE));
		call_user_func_array(array($this->fixture, $actionMethodName), $parameters);
	}



	public function testIndexActionLoadsAllForumsForIndexFromRepository() {
		$this->forumRepositoryMock->expects($this->atLeastOnce())->method('findForIndex')
			->will($this->returnValue($forums = $this->buildForumMockList()));
		$this->fixture->indexAction();
	}



	/**
	 * @depends testIndexActionLoadsAllForumsForIndexFromRepository
	 */
	public function testIndexActionsAssignsAllForumsToView() {
		$this->forumRepositoryMock->expects($this->any())->method('findForIndex')
			->will($this->returnValue($forums = $this->buildForumMockList()));
		$this->fixture->indexAction();
		$this->assertTrue($this->viewMock->containsKeyValuePair('forums', $forums));
	}



	public function testShowActionLoadsAllTopicsFromRepository() {
		$forum = $this->getMock('Tx_MmForum_Domain_Model_Forum_Forum');
		$this->topicRepositoryMock->expects($this->atLeastOnce())->method('findForIndex')
			->with($this->isInstanceOf('Tx_MmForum_Domain_Model_Forum_Forum'))
			->will($this->returnValue($topics = $this->buildTopicMockList()));
		$this->fixture->showAction($forum);

		$this->assertTrue($this->viewMock->containsKeyValuePair('forum', $forum));
		$this->assertTrue($this->viewMock->containsKeyValuePair('topics', $topics));
	}



	/**
	 * @depends testShowActionLoadsAllTopicsFromRepository
	 */
	public function testShowActionAssignsForumAndTopicsToView() {
		$forum = $this->getMock('Tx_MmForum_Domain_Model_Forum_Forum');
		$this->topicRepositoryMock->expects($this->atLeastOnce())->method('findForIndex')
			->with($this->isInstanceOf('Tx_MmForum_Domain_Model_Forum_Forum'))
			->will($this->returnValue($topics = $this->buildTopicMockList()));
		$this->fixture->showAction($forum);

		$this->assertTrue($this->viewMock->containsKeyValuePair('forum', $forum));
		$this->assertTrue($this->viewMock->containsKeyValuePair('topics', $topics));
	}



	public function testUpdateActionCallsUpdateMethodOnRepository() {
		$forum = $this->getMock('Tx_MmForum_Domain_Model_Forum_Forum');
		$this->forumRepositoryMock->expects($this->once())->method('update')
			->with($this->isInstanceOf('Tx_MmForum_Domain_Model_Forum_Forum'));
		$this->fixture->updateAction($forum);
	}



	public function testCreateActionCallsAddMethodOnRepository() {
		$forum = $this->getMock('Tx_MmForum_Domain_Model_Forum_Forum');
		$this->forumRepositoryMock->expects($this->once())->method('add')
			->with($this->isInstanceOf('Tx_MmForum_Domain_Model_Forum_Forum'));
		$this->fixture->createAction($forum);
	}



}
