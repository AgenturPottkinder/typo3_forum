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


class Tx_MmForum_Service_Notification_NotificationServiceTest extends Tx_Extbase_Tests_Unit_BaseTestCase {



	/**
	 * @var Tx_MmForum_Service_Notification_NotificationService
	 */
	protected $fixture;


	/**
	 * @var PHPUnit_Framework_MockObject_MockObject
	 */
	protected $mailingServiceMock = NULL;



	public function setUp() {
		$this->mailingServiceMock = $this->getMock('Tx_MmForum_Service_Mailing_PlainMailingService');
		$this->fixture            = new Tx_MmForum_Service_Notification_NotificationService($this->mailingServiceMock);
	}



	/**
	 * @test
	 * @param $format
	 * @dataProvider getMailingServiceFormats
	 */
	public function mailingServiceIsCalledWhenSubscribersAreNotified($format) {
		// Add mock function.
		$this->mailingServiceMock->expects($this->any())->method('getFormat')->will($this->returnValue($format));
		$this->mailingServiceMock->expects($this->exactly(5))->method('sendMail')
			->with(new PHPUnit_Framework_Constraint_IsInstanceOf('Tx_MmForum_Domain_Model_User_FrontendUser'));

		$post  = new Tx_MmForum_Domain_Model_Forum_Post('Post 1');
		$topic = new Tx_MmForum_Domain_Model_Forum_Topic('Topic');
		$forum = new Tx_MmForum_Domain_Model_Forum_Forum('Forum');
		$topic->setForum($forum);
		$topic->addPost($post);
		$forum->addTopic($topic);

		for ($i = 1; $i <= 5; $i++) {
			$topic->addSubscriber(new Tx_MmForum_Domain_Model_User_FrontendUser('User ' . $i, 'secret'));
		}

		$notifiable = new Tx_MmForum_Domain_Model_Forum_Post('Post 2');
		$this->fixture->notifySubscribers($topic, $notifiable);
	}



	public function getMailingServiceFormats() {
		return array(array('html'), array('txt'));
	}



}
