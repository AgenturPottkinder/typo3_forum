<?php

/*                                                                    - *
 *  COPYRIGHT NOTICE                                                    *
 *                                                                      *
 *  (c) 2012 Martin Helmich <typo3@martin-helmich.de>                   *
 *           All rights reserved                                        *
 *                                                                      *
 *  This script is part of the TYPO3 project. The TYPO3 project is      *
 *  free software; you can redistribute it and/or modify                *
 *  it under the terms of the GNU General Public License as published   *
 *  by the Free Software Foundation; either version 2 of the License,   *
 *  or (at your option) any later version.                              *
 *                                                                      *
 *  The GNU General Public License can be found at                      *
 *  http://www.gnu.org/copyleft/gpl.html.                               *
 *                                                                      *
 *  This script is distributed in the hope that it will be useful,      *
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of      *
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the       *
 *  GNU General Public License for more details.                        *
 *                                                                      *
 *  This copyright notice MUST APPEAR in all copies of the script!      *
 *                                                                      */



/**
 *
 * Listener class for modifications. This class uses Extbase's Signal-/Slot
 * mechanism to "listen" for new posts and topics and notifies the subscribers
 * of the regarding objects.
 *
 * @author     Martin Helmich <typo3@martin-helmich.de>
 * @package    MmForum
 * @subpackage Service\Notification
 * @version    $Id: NotificationService.php 39978 2010-11-09 14:19:52Z mhelmich $
 *
 * @copyright  2012 Martin Helmich <typo3@martin-helmich.de>
 *             http://www.martin-helmich.de
 * @license    GNU Public License, version 2
 *             http://opensource.org/licenses/gpl-license.php
 *
 */
final class Tx_MmForum_Service_Notification_SubscriptionListener {



	/**
	 * An instance of the notification service.
	 * @var Tx_MmForum_Service_Notification_NotificationServiceInterface
	 */
	protected $notificationService = NULL;



	/**
	 * Injects an instance of the notification service.
	 *
	 * @param Tx_MmForum_Service_Notification_NotificationServiceInterface $notificationService
	 *                                 An instance of the notification service.
	 * @return void
	 */
	public function injectNotificationService(Tx_MmForum_Service_Notification_NotificationServiceInterface $notificationService) {
		$this->notificationService = $notificationService;
	}



	/**
	 * Is fired when a new post is created.
	 *
	 * @param  Tx_MmForum_Domain_Model_Forum_Post $post Event data.
	 * @return void
	 */
	public function onPostCreated($post) {
		if ($post instanceof Tx_MmForum_Domain_Model_Forum_Post) {
			$this->notificationService->notifySubscribers($post->getTopic(), $post);
		}
	}



	/**
	 * Is fired when a new topic is created.
	 *
	 * @param  Tx_MmForum_Domain_Model_Forum_Topic $topic Event data.
	 * @return void
	 */
	public function onTopicCreated($topic) {
		if ($topic instanceof Tx_MmForum_Domain_Model_Forum_Topic) {

		}
	}



}
