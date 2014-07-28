<?php
namespace Mittwald\MmForum\Service\Notification;


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


class SubscriptionListenerTest extends \TYPO3\CMS\Extbase\Tests\Unit\BaseTestCase {



	/**
	 * @var \Mittwald\MmForum\Service\Notification\SubscriptionListener
	 */
	protected $fixture;


	/**
	 * @var PHPUnit_Framework_MockObject_MockObject
	 */
	protected $notificationServiceMock = NULL;



	public function setUp() {
		$this->notificationServiceMock = $this->getMock('Mittwald\\MmForum\\Service\\Notification\\NotificationService', array(),
			array(), '', FALSE);
		$this->fixture                 = new SubscriptionListener($this->mailingServiceMock);
		$this->fixture->injectNotificationService($this->notificationServiceMock);
	}



	/**
	 * @test
	 */
	public function onTopicCreatedCallsNotificationService() {
		$post  = new \Mittwald\MmForum\Domain\Model\Forum\Post('Text');
		$forum = new \Mittwald\MmForum\Domain\Model\Forum\Forum('Forum');
		$topic = new \Mittwald\MmForum\Domain\Model\Forum\Topic('Topic');
		$topic->setForum($forum);
		$topic->addPost($post);
		$forum->addTopic($topic);

		$this->notificationServiceMock->expects($this->once())->method('notifySubscribers')
			->with(new PHPUnit_Framework_Constraint_IsInstanceOf('Mittwald\\MmForum\\Domain\\Model\\Forum\\Forum'),
			new PHPUnit_Framework_Constraint_IsInstanceOf('Mittwald\\MmForum\\Domain\\Model\\Forum\\Topic'));
		$this->fixture->onTopicCreated($topic);
	}



	/**
	 * @test
	 */
	public function onPostCreatedCallsNotificationService() {
		$post  = new \Mittwald\MmForum\Domain\Model\Forum\Post('Text');
		$forum = new \Mittwald\MmForum\Domain\Model\Forum\Forum('Forum');
		$topic = new \Mittwald\MmForum\Domain\Model\Forum\Topic('Topic');
		$topic->setForum($forum);
		$topic->addPost($post);
		$forum->addTopic($topic);

		$this->notificationServiceMock->expects($this->once())->method('notifySubscribers')
			->with(new PHPUnit_Framework_Constraint_IsInstanceOf('Mittwald\\MmForum\\Domain\\Model\\Forum\\Topic'),
			new PHPUnit_Framework_Constraint_IsInstanceOf('Mittwald\\MmForum\\Domain\\Model\\Forum\\Post'));
		$this->fixture->onPostCreated($post);
	}



}
