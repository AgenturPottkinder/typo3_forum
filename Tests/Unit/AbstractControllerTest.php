<?php

namespace Mittwald\Typo3Forum\Tests\Unit;

use Mittwald\Typo3Forum\Service\Authentication\AuthenticationService;
use Mittwald\Typo3Forum\Domain\Repository\User\FrontendUserRepository;
use TYPO3\CMS\Core\Tests\UnitTestCase;
use TYPO3\CMS\Extbase\Mvc\Web\Request;
use TYPO3\CMS\Extbase\Mvc\Web\Response;
use TYPO3\CMS\Extbase\Mvc\Web\Routing\UriBuilder;
use TYPO3\CMS\Extbase\Object\ObjectManager;
use TYPO3\CMS\Fluid\View\TemplateView;

abstract class AbstractControllerTest extends UnitTestCase {

	/**
	 * @var \PHPUnit_Framework_MockObject_MockObject|AuthenticationService
	 */
	protected $authenticationServiceMock;

	/**
	 * @var \PHPUnit_Framework_MockObject_MockObject|FrontendUserRepository
	 */
	protected $frontendUserRepositoryMock;

	/**
	 * @var \PHPUnit_Framework_MockObject_MockObject|ObjectManager
	 */
	protected $objectManagerMock;

	/**
	 * @var \PHPUnit_Framework_MockObject_MockObject|Request
	 */
	protected $requestMock;

	/**
	 * @var \PHPUnit_Framework_MockObject_MockObject|Response
	 */
	protected $responseMock;

	/**
	 * @var \PHPUnit_Framework_MockObject_MockObject|UriBuilder
	 */
	protected $uriBuilderMock;

	/**
	 * @var \PHPUnit_Framework_MockObject_MockObject|TemplateView
	 */
	protected $viewMock;

	public function setUp() {
		$this->authenticationServiceMock = $this->getMock(AuthenticationService::class);
		$this->frontendUserRepositoryMock = $this->getMock(
			FrontendUserRepository::class,
			[],
			[$this->getMock(ObjectManager::class)]
		);
		$this->objectManagerMock = $this->getMock(ObjectManager::class);
		$this->objectManagerMock->expects($this->any())
			->method('get')
			->will($this->returnCallback(function($className) {
				return $this->getMock($className);
			}));
		$this->requestMock = $this->getMock(Request::class);
		$this->responseMock = $this->getMock(Response::class);
		$this->responseMock->expects($this->any())->method('shutdown');
		$this->uriBuilderMock = $this->getMock(UriBuilder::class);
		$this->uriBuilderMock->expects($this->any())
			->method('reset')
			->will($this->returnValue($this->uriBuilderMock));
		$this->uriBuilderMock->expects($this->any())
			->method('setTargetPageUid')
			->will($this->returnValue($this->uriBuilderMock));
		$this->viewMock = $this->getMockBuilder(TemplateView::class)
			->setMethods(['__construct', 'assign', 'assignMultiple'])
			->disableOriginalConstructor()
			->getMock();
	}

}
