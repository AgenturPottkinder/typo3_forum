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


abstract class AbstractControllerTest extends \TYPO3\CMS\Core\Tests\UnitTestCase {



	/**
	 * @var \Mittwald\Typo3Forum\Controller\AbstractController
	 */
	protected $fixture;


	/**
	 * @var \PHPUnit_Framework_MockObject_MockObject|\Mittwald\Typo3Forum\Service\Authentication\AuthenticationServiceInterface
	 */
	protected $authenticationServiceMock;


	/**
	 * @var \PHPUnit_Framework_MockObject_MockObject|\Mittwald\Typo3Forum\Domain\Repository\User\FrontendUserRepository
	 */
	protected $userRepositoryMock;


	/**
	 * @var Tx_Typo3Forum_View_ViewMock
	 */
	protected $viewMock;


	/**
	 * @var \PHPUnit_Framework_MockObject_MockObject|\TYPO3\CMS\Extbase\Mvc\Controller\FlashMessageContainer
	 */
	protected $flashMessageContainerMock;


	/**
	 * @var \PHPUnit_Framework_MockObject_MockObject|\TYPO3\CMS\Extbase\Mvc\Web\Request
	 */
	protected $requestMock;


	/**
	 * @var \PHPUnit_Framework_MockObject_MockObject|\TYPO3\CMS\Extbase\SignalSlot\Dispatcher
	 */
	protected $signalSlotDispatcherMock;


	/**
	 * @var string
	 */
	protected $fixtureClassName = '\Mittwald\Typo3Forum\Controller\ForumController';



	protected function buildFixture($className, array $constructorArguments = array()) {
		$this->userRepositoryMock        = $this->getMock('\Mittwald\Typo3Forum\Domain\Repository\User\FrontendUserRepository');
		$this->authenticationServiceMock = $this->getMock('\Mittwald\Typo3Forum\Service\Authentication\AuthenticationService',
		                                                  array('checkAuthorization'), array($this->userRepositoryMock,
		                                                                                    $this->getMock('Mittwald\\TYPO3Forum\\Cache\\Cache')));

		#$this->viewMock                  = $this->getMockForAbstractClass('Tx_Extbase_MVC_View_AbstractView');
		$this->viewMock                  = new Tx_Typo3Forum_View_ViewMock();
		$this->flashMessageContainerMock = $this->getMock('Tx_Extbase_MVC_Controller_FlashMessages');
		$this->requestMock               = $this->getMock('Tx_Extbase_MVC_Web_Request');
		$this->requestMock->expects($this->any())->method('getFormat')->will($this->returnValue('html'));
		$this->signalSlotDispatcherMock = $this->getMock('Tx_Extbase_SignalSlot_Dispatcher');

		$this->fixture = $this->getAccessibleMock($className, array('redirect', 'clearCacheForCurrentPage'),
		                                          $constructorArguments);
		$this->fixture->injectObjectManager($this->objectManager);
		$this->fixture->injectFrontendUserRepository($this->userRepositoryMock);
		$this->fixture->injectAuthenticationService($this->authenticationServiceMock);
		$this->fixture->injectSignalSlotDispatcher($this->signalSlotDispatcherMock);

		$this->fixture->_set('view', $this->viewMock);
		$this->fixture->_set('flashMessageContainer', $this->flashMessageContainerMock);
		$this->fixture->_set('request', $this->requestMock);
	}



	public function getAllControllerActionsWithMockParameters() {
		$reflectionClass = new \ReflectionClass($this->fixtureClassName);
		$data            = array();

		foreach ($reflectionClass->getMethods(\ReflectionMethod::IS_PUBLIC) as $method) {
			/** @var $method \ReflectionMethod */
			if (substr($method->getName(), -6) !== 'Action') {
				continue;
			}

			$data[] = array($method->getName(), $this->getMockParametersForActionMethod($method));
		}

		return $data;
	}



	protected function getMockParametersForActionMethod(\ReflectionMethod $method) {
		$parameters = array();
		foreach ($method->getParameters() as $parameter) {
			/** @var $parameter \ReflectionParameter */
			if ($parameter->getClass() !== NULL) {
				if ($parameter->getClass()->getName() === '\Mittwald\Typo3Forum\Domain\Model\Forum\Forum') {
					$forum = new \Mittwald\Typo3Forum\Domain\Model\Forum\Forum();
					$forum->injectObjectManager(\TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\CMS\Extbase\Object\ObjectManager'));
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

		return $parameters;
	}



	protected function buildForumMockList() {
		$list = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
		$list->attach($this->getMock('\Mittwald\Typo3Forum\Domain\Model\Forum\Forum'));
		return $list;
	}



	protected function buildTopicMockList() {
		$list = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
		$list->attach($this->getMock('\Mittwald\Typo3Forum\Domain\Model\Forum\Topic'));
		return $list;
	}



	protected function buildPostMockList() {
		$list = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
		$list->attach($this->getMock('\Mittwald\Typo3Forum\Domain\Model\Forum\Post'));
		return $list;
	}



	protected function assertViewContains($key, $value) {
		$this->assertTrue($this->viewMock->containsKeyValuePair($key, $value), 'View did not contain key ' . $key);
	}



	/**
	 * @dataProvider getModifyingActionsWithParameters
	 * @param       $actionMethodName
	 * @param array $parameters
	 */
	public function testModifyingActionsClearPageCache($actionMethodName, array $parameters) {
		$this->fixture->expects($this->atLeastOnce())->method('clearCacheForCurrentPage');
		call_user_func_array(array($this->fixture, $actionMethodName), $parameters);
	}



	abstract public function getModifyingActions();



	public function getModifyingActionsWithParameters() {
		$result = array();
		foreach ($this->getModifyingActions() as $modifyingAction) {
			$result[] = array($modifyingAction,
			                  $this->getMockParametersForActionMethod(new \ReflectionMethod($this->fixtureClassName, $modifyingAction)));
		}
		return $result;
	}



}
