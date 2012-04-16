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
 * Service class for notifications. This service notifies subscribers of
 * forums and topic about new posts within the subscribed objects.
 *
 * @author     Martin Helmich <m.helmich@mittwald.de>
 * @package    MmForum
 * @subpackage Service
 * @version    $Id: NotificationService.php 39978 2010-11-09 14:19:52Z mhelmich $
 *
 * @copyright  2012 Martin Helmich <m.helmich@mittwald.de>
 *             Mittwald CM Service GmbH & Co. KG
 *             http://www.mittwald.de
 * @license    GNU Public License, version 2
 *             http://opensource.org/licenses/gpl-license.php
 *
 */
class Tx_MmForum_Service_Notification_NotificationService extends Tx_MmForum_Service_AbstractService
	implements Tx_MmForum_Service_Notification_NotificationServiceInterface {



	/*
	 * ATTRIBUTES
	 */



	/**
	 * The view used for rendering the notification mails.
	 *
	 * @var Tx_Extbase_MVC_View_AbstractView
	 */
	protected $notificationView;



	/**
	 * The mailing service. Needs to be injected, too.
	 *
	 * @var Tx_MmForum_Service_Mailing_AbstractMailingService
	 */
	protected $mailingService;



	/*
	  * INITIALIZATION METHODS
	  */



	/**
	 * Creates a new instance of this object.
	 *
	 * @param  Tx_MmForum_Service_Mailing_MailingServiceInterface $mailingService
	 *                             The mailing service. This needs to be injected by
	 *                             the calling controller.
	 */
	public function __construct(Tx_MmForum_Service_Mailing_MailingServiceInterface $mailingService) {
		$this->mailingService = $mailingService;
	}



	/**
	 *
	 * Initializes the view that is to be used for rendering the notification mails.
	 *
	 * @return void
	 *
	 */
	protected function initialize() {
		$this->notificationView = new Tx_Fluid_View_StandaloneView();
		$this->notificationView->setFormat($this->mailingService->getFormat());
		// TODO: Make template path configurable!
		$this->notificationView->setTemplatePathAndFilename(t3lib_extMgm::extPath('mm_forum') . '/Resources/Private/Templates/Topic/Notify.' . $this->mailingService->getFormat());
	}



	/*
	 * SERVICE METHODS
	 */



	/**
	 *
	 * Notifies subscribers of a subscribeable objects about a new notifiable object
	 * within the subscribeable object, e.g. of a new post within a subscribed topic.
	 *
	 * @param  Tx_MmForum_Domain_Model_SubscribeableInterface $subscriptionObject
	 *                             The subscribed object. This may for example be a
	 *                             forum or a topic.
	 * @param  Tx_MmForum_Domain_Model_NotifiableInterface    $notificationObject
	 *                             The object that the subscriber is notified about.
	 *                             This may for example be a new post within an
	 *                             observed topic or forum or a new topic within an
	 *                             observed forum.
	 *
	 * @return void
	 *
	 */
	public function notifySubscribers(Tx_MmForum_Domain_Model_SubscribeableInterface $subscriptionObject,
	                                  Tx_MmForum_Domain_Model_NotifiableInterface $notificationObject) {
		$this->initialize();
		$subscribers = $subscriptionObject->getSubscribers();
		foreach ($subscribers as $subscriber) {
			$this->notificationView->assignMultiple(array('settings'        => $this->settings,
			                                             'subscribedObject' => $subscriptionObject,
			                                             'newObject'        => $notificationObject,
			                                             'subscriber'       => $subscriber));
			$text = $this->notificationView->render();
			// TODO: Read subject from locallang!
			$this->mailingService->sendMail($subscriber, "Hallo", $text);
		}
	}



}

?>
