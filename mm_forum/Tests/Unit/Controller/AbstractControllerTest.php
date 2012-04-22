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


abstract class Tx_MmForum_Controller_AbstractControllerTest extends Tx_Extbase_Tests_Unit_BaseTestCase {



	/**
	 * @var Tx_MmForum_Controller_AbstractController
	 */
	protected $fixture;


	/**
	 * @var PHPUnit_Framework_MockObject_MockObject|Tx_MmForum_Service_Authentication_AuthenticationServiceInterface
	 */
	protected $authenticationServiceMock;


	/**
	 * @var PHPUnit_Framework_MockObject_MockObject|Tx_MmForum_Domain_Repository_User_FrontendUserRepository
	 */
	protected $userRepositoryMock;


	/**
	 * @var Tx_MmForum_View_ViewMock
	 */
	protected $viewMock;


	/**
	 * @var PHPUnit_Framework_MockObject_MockObject|Tx_Extbase_MVC_Controller_FlashMessages
	 */
	protected $flashMessageContainerMock;


	/**
	 * @var PHPUnit_Framework_MockObject_MockObject|Tx_Extbase_MVC_Web_Request
	 */
	protected $requestMock;


	/**
	 * @var PHPUnit_Framework_MockObject_MockObject|Tx_Extbase_SignalSlot_Dispatcher
	 */
	protected $signalSlotDispatcherMock;


	/**
	 * @var string
	 */
	protected $fixtureClassName = 'Tx_MmForum_Controller_ForumController';



	protected function buildFixture($className, array $constructorArguments = array()) {
		$this->userRepositoryMock        = $this->getMock('Tx_MmForum_Domain_Repository_User_FrontendUserRepository');
		$this->authenticationServiceMock = $this->getMock('Tx_MmForum_Service_Authentication_AuthenticationService',
		                                                  array('checkAuthorization'), array($this->userRepositoryMock,
		                                                                                    $this->getMock('Tx_MmForum_Cache_Cache')));

		#$this->viewMock                  = $this->getMockForAbstractClass('Tx_Extbase_MVC_View_AbstractView');
		$this->viewMock                  = new Tx_MmForum_View_ViewMock();
		$this->flashMessageContainerMock = $this->getMock('Tx_Extbase_MVC_Controller_FlashMessages');
		$this->requestMock               = $this->getMock('Tx_Extbase_MVC_Web_Request');
		$this->requestMock->expects($this->any())->method('getFormat')->will($this->returnValue('html'));
		$this->signalSlotDispatcherMock = $this->getMock('Tx_Extbase_SignalSlot_Dispatcher');

		$this->fixture = $this->getAccessibleMock($className, array('redirect'), $constructorArguments);
		$this->fixture->injectObjectManager($this->objectManager);
		$this->fixture->injectFrontendUserRepository($this->userRepositoryMock);
		$this->fixture->injectAuthenticationService($this->authenticationServiceMock);
		$this->fixture->injectSignalSlotDispatcher($this->signalSlotDispatcherMock);

		$this->fixture->_set('view', $this->viewMock);
		$this->fixture->_set('flashMessageContainer', $this->flashMessageContainerMock);
		$this->fixture->_set('request', $this->requestMock);
	}



	public function getAllControllerActionsWithMockParameters() {
		$reflectionClass = new ReflectionClass($this->fixtureClassName);
		$data            = array();

		foreach ($reflectionClass->getMethods(ReflectionMethod::IS_PUBLIC) as $method) {
			/** @var $method ReflectionMethod */
			if (substr($method->getName(), -6) !== 'Action') {
				continue;
			}

			$parameters = array();
			foreach ($method->getParameters() as $parameter) {
				/** @var $parameter ReflectionParameter */
				if ($parameter->getClass() !== NULL) {
					if ($parameter->getClass()->getName() === 'Tx_MmForum_Domain_Model_Forum_Forum') {
						$forum = new Tx_MmForum_Domain_Model_Forum_Forum();
						$forum->injectObjectManager(t3lib_div::makeInstance('Tx_Extbase_Object_ObjectManager'));
						$parameters[] = $forum;
					} else {
						$parameters[] = $this->getMock($parameter->getClass()->getName());
					}
				} elseif ($parameter->isArray() === TRUE) {
					$parameters[] = array(1, 2, 3);
				} else {
					$parameters[] = 1337;
				}
			}

			$data[] = array($method->getName(), $parameters);
		}

		return $data;
	}



	protected function buildForumMockList() {
		$list = new Tx_Extbase_Persistence_ObjectStorage();
		$list->attach($this->getMock('Tx_MmForum_Domain_Model_Forum_Forum'));
		return $list;
	}



	protected function buildTopicMockList() {
		$list = new Tx_Extbase_Persistence_ObjectStorage();
		$list->attach($this->getMock('Tx_MmForum_Domain_Model_Forum_Topic'));
		return $list;
	}



	protected function buildPostMockList() {
		$list = new Tx_Extbase_Persistence_ObjectStorage();
		$list->attach($this->getMock('Tx_MmForum_Domain_Model_Forum_Post'));
		return $list;
	}



	protected function assertViewContains($key, $value) {
		$this->assertTrue($this->viewMock->containsKeyValuePair($key, $value), 'View did not contain key ' . $key);
	}



}
