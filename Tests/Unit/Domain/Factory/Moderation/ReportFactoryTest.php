<?php
namespace Mittwald\Typo3Forum\Tests\Unit\Domain\Moderation;
/*                                                                    - *
 *  COPYRIGHT NOTICE                                                    *
 *                                                                      *
 *  (c) 2012 Martin Helmich <m.helmich@mittwald.de>                     *
 *           Mittwald CM Service GmbH & Co KG                           *
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


class ReportFactoryTest extends \TYPO3\CMS\Core\Tests\UnitTestCase {



	/**
	 * @var \Mittwald\Typo3Forum\Domain\Factory\Moderation\ReportFactory
	 */
	protected $fixture;


	/**
	 * @var \PHPUnit_Framework_MockObject_MockObject
	 */
	protected $userRepositoryMock, $workflowStatusRepositoryMock;


	/**
	 * @var \Mittwald\Typo3Forum\Domain\Model\Moderation\ReportWorkflowStatus
	 */
	protected $initialStatus;



	public function setUp() {
		$this->userRepositoryMock           = $this->getMock('\Mittwald\Typo3Forum\Domain\Repository\User\FrontendUserRepository');
		$this->workflowStatusRepositoryMock = $this->getMock('\Mittwald\Typo3Forum\Domain\Repository\Moderation\ReportWorkflowStatusRepository');
		$this->workflowStatusRepositoryMock->expects($this->any())->method('findInitial')
			->will($this->returnValue($this->initialStatus = new \Mittwald\Typo3Forum\Domain\Model\Moderation\ReportWorkflowStatus('Open', TRUE, FALSE)));

		$this->fixture = new \Mittwald\Typo3Forum\Domain\Factory\Moderation\ReportFactory($this->workflowStatusRepositoryMock);
		$this->fixture->injectObjectManager($this->objectManager);
		$this->fixture->injectFrontendUserRepository($this->userRepositoryMock);
	}



	/**
	 * @test
	 */
	public function createReportCreatesNewReportFromComment() {
		$user = new \Mittwald\Typo3Forum\Domain\Model\User\FrontendUser('martin', 'secret');
		$post = new \Mittwald\Typo3Forum\Domain\Model\Forum\Post('Content');
		$this->userRepositoryMock->expects($this->any())->method('findCurrent')->will($this->returnValue($user));

		$comment = new \Mittwald\Typo3Forum\Domain\Model\Moderation\ReportComment($user, 'Content');
		$report  = $this->fixture->createReport($comment, $post);

		$this->assertInstanceOf('\Mittwald\Typo3Forum\Domain\Model\Moderation\Report', $report);
		$this->assertTrue($report->getPost() === $post);
		$this->assertTrue($report->getWorkflowStatus() === $this->initialStatus);
		$this->assertTrue($report->getReporter() === $user);
	}



}
