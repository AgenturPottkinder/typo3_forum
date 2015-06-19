<?php
namespace Mittwald\Typo3Forum\Tests\Unit\Controller;
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


class UserControllerTest extends \Mittwald\Typo3Forum\Tests\Unit\Controller\AbstractControllerTest {



	/**
	 * @var \Mittwald\Typo3Forum\Controller\UserController
	 */
	protected $fixture;


	/**
	 * @var string
	 */
	protected $fixtureClassName = '\Mittwald\Typo3Forum\Controller\UserController';


	/**
	 * @var \PHPUnit_Framework_MockObject_MockObject
	 */
	protected $topicRepositoryMock, $userfieldRepositoryMock, $forumRepositoryMock, $userMock;



	public function setUp() {
		$this->topicRepositoryMock     = $this->getMock('Mittwald\Typo3Forum\Domain\Repository\Forum\TopicRepository');
		$this->forumRepositoryMock     = $this->getMock('Mittwald\Typo3Forum\Domain\Repository\Forum\ForumRepository');
		$this->userfieldRepositoryMock = $this->getMock('Mittwald\Typo3Forum\Domain\Repository\User\UserfieldRepository');
		$this->userMock                = $this->getMock('Mittwald\Typo3Forum\Domain\Model\User\FrontendUser');

		$this->buildFixture($this->fixtureClassName, array($this->forumRepositoryMock, $this->topicRepositoryMock,
		                                                  $this->userfieldRepositoryMock));
	}



	/**
	 * @dataProvider getModifyingActionsWithParameters
	 * @param       $actionMethodName
	 * @param array $parameters
	 */
	public function testModifyingActionsClearPageCache($actionMethodName, array $parameters) {
		$this->userRepositoryMock
			->expects($this->any())
			->method('findCurrent')
			->will($this->returnValue($this->userMock));
		parent::testModifyingActionsClearPageCache($actionMethodName, $parameters);
	}



	/**
	 * @dataProvider      getActionsThatRequireALoggedInUser
	 * @expectedException \Mittwald\Typo3Forum\Domain\Exception\Authentication\NotLoggedInException
	 * @param $actionMethodName
	 * @param $parameters
	 */
	public function testActionsThatRequireALoggedInUserThrowExceptionWhenNoUserIsLoggedIn($actionMethodName,
	                                                                                      $parameters) {
		$this->userRepositoryMock
			->expects($this->any())
			->method('findCurrent')
			->will($this->returnValue(new \Mittwald\Typo3Forum\Domain\Model\User\AnonymousFrontendUser()));
		call_user_func_array(array($this->fixture, $actionMethodName), $parameters);
	}



	/**
	 * @expectedException \Mittwald\Typo3Forum\Domain\Exception\Authentication\NotLoggedInException
	 */
	public function testListPostsActionThrowsExceptionWhenNoUserIsSpecifiedAndNoUserIsLoggedIn() {
		$this->userRepositoryMock
			->expects($this->any())
			->method('findCurrent')
			->will($this->returnValue(new \Mittwald\Typo3Forum\Domain\Model\User\AnonymousFrontendUser()));
		$this->fixture->listPostsAction();
	}



	public function getActionsThatRequireALoggedInUser() {
		return array(array('subscribeAction',
		                   $this->getMockParametersForActionMethod(new \ReflectionMethod($this->fixtureClassName, 'subscribeAction'))),
		             array('listSubscriptionsAction',
		                   $this->getMockParametersForActionMethod(new \ReflectionMethod($this->fixtureClassName, 'listSubscriptionsAction'))));
	}



	public function testListSubscriptionsActionLoadsSubscribedTopicsFromRepository() {
		$this->topicRepositoryMock
			->expects($this->once())
			->method('findBySubscriber');
		$this->userRepositoryMock
			->expects($this->any())
			->method('findCurrent')
			->will($this->returnValue($this->userMock));
		$this->fixture->listSubscriptionsAction();
	}



	public function testListSubscriptionsActionLoadsSubscribedForumsFromRepository() {
		$this->forumRepositoryMock
			->expects($this->once())
			->method('findBySubscriber');
		$this->userRepositoryMock
			->expects($this->any())
			->method('findCurrent')
			->will($this->returnValue($this->userMock));
		$this->fixture->listSubscriptionsAction();
	}



//	public function getActionMethodsThatNotifySignalSlotDispatcher() {
//		return array(array('createAction', 'postCreated', array($this->getMock('Mittwald\Typo3Forum\Domain\Model\Forum\Topic'),
//		                                                        $this->getMock('Mittwald\Typo3Forum\Domain\Model\Forum\Post'))),
//		             array('updateAction', 'postUpdated', array($this->getMock('Mittwald\Typo3Forum\Domain\Model\Forum\Post'))),
//		             array('deleteAction', 'postDeleted', array($this->getMock('Mittwald\Typo3Forum\Domain\Model\Forum\Post'))));
//	}



	public function getModifyingActions() {
		return array('subscribeAction');
	}
}
