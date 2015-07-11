<?php

namespace Mittwald\Typo3Forum\Tests\Unit;

use Mittwald\Typo3Forum\Service\Authentication\AuthenticationService;
use TYPO3\CMS\Core\Tests\UnitTestCase;
use TYPO3\CMS\Fluid\View\TemplateView;

abstract class AbstractControllerTest extends UnitTestCase {

	/**
	 * @var \PHPUnit_Framework_MockObject_MockObject|AuthenticationService
	 */
	protected $authenticationServiceMock;

	/**
	 * @var \PHPUnit_Framework_MockObject_MockObject|TemplateView
	 */
	protected $viewMock;

	/**
	 *
	 */
	public function setUp() {
		$this->authenticationServiceMock = $this->getMock('Mittwald\\Typo3Forum\\Service\\Authentication\\AuthenticationService');
		$this->viewMock = $this->getMockBuilder('TYPO3\\CMS\\Fluid\\View\\TemplateView')
			->setMethods(['__construct', 'assign', 'assignMultiple'])
			->disableOriginalConstructor()
			->getMock();
	}

}
