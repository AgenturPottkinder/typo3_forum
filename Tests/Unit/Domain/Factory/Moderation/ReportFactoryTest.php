<?php

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


class Tx_MmForum_Domain_Factory_Moderation_ReportFactoryTest extends Tx_Extbase_Tests_Unit_BaseTestCase {



	/**
	 * @var Tx_MmForum_Domain_Factory_Moderation_ReportFactory
	 */
	protected $fixture;


	/**
	 * @var PHPUnit_Framework_MockObject_MockObject
	 */
	protected $userRepositoryMock, $workflowStatusRepositoryMock;


	/**
	 * @var Tx_MmForum_Domain_Model_Moderation_ReportWorkflowStatus
	 */
	protected $initialStatus;



	public function setUp() {
		$this->userRepositoryMock           = $this->getMock('Tx_MmForum_Domain_Repository_User_FrontendUserRepository');
		$this->workflowStatusRepositoryMock = $this->getMock('Tx_MmForum_Domain_Repository_Moderation_ReportWorkflowStatusRepository');
		$this->workflowStatusRepositoryMock->expects($this->any())->method('findInitial')
			->will($this->returnValue($this->initialStatus = new Tx_MmForum_Domain_Model_Moderation_ReportWorkflowStatus('Open', TRUE, FALSE)));

		$this->fixture = new Tx_MmForum_Domain_Factory_Moderation_ReportFactory($this->workflowStatusRepositoryMock);
		$this->fixture->injectObjectManager($this->objectManager);
		$this->fixture->injectFrontendUserRepository($this->userRepositoryMock);
	}



	/**
	 * @test
	 */
	public function createReportCreatesNewReportFromComment() {
		$user = new Tx_MmForum_Domain_Model_User_FrontendUser('martin', 'secret');
		$post = new Tx_MmForum_Domain_Model_Forum_Post('Content');
		$this->userRepositoryMock->expects($this->any())->method('findCurrent')->will($this->returnValue($user));

		$comment = new Tx_MmForum_Domain_Model_Moderation_ReportComment($user, 'Content');
		$report  = $this->fixture->createReport($comment, $post);

		$this->assertInstanceOf('Tx_MmForum_Domain_Model_Moderation_Report', $report);
		$this->assertTrue($report->getPost() === $post);
		$this->assertTrue($report->getWorkflowStatus() === $this->initialStatus);
		$this->assertTrue($report->getReporter() === $user);
	}



}
