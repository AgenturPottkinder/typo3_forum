<?php
namespace Mittwald\MmForum\Controller;


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


class ForumControllerTest extends AbstractControllerTest {



	/**
	 * @var \Mittwald\MmForum\Controller\ForumController
	 */
	protected $fixture;


	/**
	 * @var PHPUnit_Framework_MockObject_MockObject|\Mittwald\MmForum\Domain\Repository\Forum\ForumRepository
	 */
	public $forumRepositoryMock;


	/**
	 * @var PHPUnit_Framework_MockObject_MockObject|\Mittwald\MmForum\Domain\Repository\Forum\TopicRepository
	 */
	public $topicRepositoryMock;


	/**
	 * @var PHPUnit_Framework_MockObject_MockObject|\Mittwald\MmForum\Domain\Model\Forum\RootForum
	 */
	protected $rootForumMock;



	public function setUp() {
		$this->forumRepositoryMock = $this->getMock('Mittwald\\MmForum\\Domain\\Repository\\Forum\\ForumRepository');
		$this->topicRepositoryMock = $this->getMock('Mittwald\\MmForum\\Domain\\Repository\\Forum\\TopicRepository');
		$this->rootForumMock       = $this->getMock('Mittwald\\MmForum\\Domain\\Model\\Forum\\RootForum');
		$this->buildFixture('Mittwald\\MmForum\\Controller\\ForumController',
		                    array($this->forumRepositoryMock, $this->topicRepositoryMock, $this->rootForumMock));
	}



	/**
	 * @dataProvider getAllControllerActionsWithMockParameters
	 * @param $actionMethodName
	 * @param $parameters
	 */
	public function testAllControllerActionsPerformAuthorizationCheck($actionMethodName, $parameters) {
		$this->authenticationServiceMock->expects($this->atLeastOnce())->method('checkAuthorization')
			->with($this->isInstanceOf('Mittwald\\MmForum\\Domain\\Model\\Forum\\Forum'), $this->anything())
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
		$forum = $this->getMock('Mittwald\\MmForum\\Domain\\Model\\Forum\\Forum');
		$this->topicRepositoryMock->expects($this->atLeastOnce())->method('findForIndex')
			->with($this->isInstanceOf('Mittwald\\MmForum\\Domain\\Model\\Forum\\Forum'))
			->will($this->returnValue($topics = $this->buildTopicMockList()));
		$this->fixture->showAction($forum);

		$this->assertTrue($this->viewMock->containsKeyValuePair('forum', $forum));
		$this->assertTrue($this->viewMock->containsKeyValuePair('topics', $topics));
	}



	/**
	 * @depends testShowActionLoadsAllTopicsFromRepository
	 */
	public function testShowActionAssignsForumAndTopicsToView() {
		$forum = $this->getMock('Mittwald\\MmForum\\Domain\\Model\\Forum\\Forum');
		$this->topicRepositoryMock->expects($this->atLeastOnce())->method('findForIndex')
			->with($this->isInstanceOf('Mittwald\\MmForum\\Domain\\Model\\Forum\\Forum'))
			->will($this->returnValue($topics = $this->buildTopicMockList()));
		$this->fixture->showAction($forum);

		$this->assertTrue($this->viewMock->containsKeyValuePair('forum', $forum));
		$this->assertTrue($this->viewMock->containsKeyValuePair('topics', $topics));
	}



	public function testUpdateActionCallsUpdateMethodOnRepository() {
		$forum = $this->getMock('Mittwald\\MmForum\\Domain\\Model\\Forum\\Forum');
		$this->forumRepositoryMock->expects($this->once())->method('update')
			->with($this->isInstanceOf('Mittwald\\MmForum\\Domain\\Model\\Forum\\Forum'));
		$this->fixture->updateAction($forum);
	}



	public function testCreateActionCallsAddMethodOnRepository() {
		$forum = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('Mittwald\\MmForum\\Domain\\Model\\Forum\\Forum');
		$this->forumRepositoryMock->expects($this->once())->method('add')
			->with($this->isInstanceOf('Mittwald\\MmForum\\Domain\\Model\\Forum\\Forum'));
		$this->fixture->createAction($forum);
	}



	public function getModifyingActions() {
		return array('updateAction', 'createAction');
	}
}
