<?php

/*                                                                      *
 *  COPYRIGHT NOTICE                                                    *
 *                                                                      *
 *  (c) 2010 Martin Helmich <m.helmich@mittwald.de>                     *
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
	 * @version    $Id$
	 *
	 * @copyright  2010 Martin Helmich <m.helmich@mittwald.de>
	 *             Mittwald CM Service GmbH & Co. KG
	 *             http://www.mittwald.de
	 * @license    GNU Public License, version 2
	 *             http://opensource.org/licenses/gpl-license.php
	 *
	 */

Class Tx_MmForum_Service_NotificationService
	Extends Tx_MmForum_Service_AbstractService {





		/*
		 * ATTRIBUTES
		 */





		/**
		 * The view used for rendering the notification mails.
		 * @var Tx_Extbase_MVC_View_AbstractView
		 */
	Protected $notificationView;

		/**
		 * The current controller context. Needs to be injected by the calling
		 * controller class.
		 * @var Tx_Extbase_MVC_Controller_ControllerContext
		 */
	Protected $controllerContext;

		/**
		 * The mailing service. Needs to be injected, too.
		 * @var Tx_MmForum_Service_Mailing_AbstractMailingService
		 */
	Protected $mailingService;





		/*
		 * INITIALIZATION METHODS
		 */




		/**
		 *
		 * Injects the controller context.
		 *
		 * @param  Tx_Extbase_MVC_Controller_ControllerContext $controllerContext
		 *                             The controller context. This needs to be injected
		 *                             by the calling controller.
		 * @return void
		 *
		 */

	Public Function injectControllerContext(Tx_Extbase_MVC_Controller_ControllerContext $controllerContext) {
		$this->controllerContext = $controllerContext;
		$this->controllerContext->getRequest()->setControllerActionName('notify');
	}



		/**
		 *
		 * Injects the mailing service.
		 * @param  Tx_MmForum_Service_Mailing_AbstractMailingService $mailingService
		 *                             The mailing service. This needs to be injected by
		 *                             the calling controller.
		 * @return void
		 *
		 */

	Public Function injectMailingService(Tx_MmForum_Service_Mailing_AbstractMailingService $mailingService) {
		$this->mailingService = $mailingService;
		$this->controllerContext->getRequest()->setFormat($mailingService->getFormat());
	}



		/**
		 *
		 * Initializes the view that is to be used for rendering the notification mails.
		 * @return void
		 *
		 */

	Protected Function initializeView() {
		$this->notificationView = new Tx_Fluid_View_TemplateView();
		$this->notificationView->setControllerContext($this->controllerContext);
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
		 * @param  Tx_MmForum_Domain_Model_NotifiableInterface $notificationObject
		 *                             The object that the subscriber is notified about.
		 *                             This may for example be a new post within an
		 *                             observed topic or forum or a new topic within an
		 *                             observed forum.
		 * @return void
		 *
		 */

	Public Function notifySubscribers ( Tx_MmForum_Domain_Model_SubscribeableInterface $subscriptionObject,
	                                    Tx_MmForum_Domain_Model_NotifiableInterface    $notificationObject ) {
		$this->initializeView();
		$subscribers = $subscriptionObject->getSubscribers();
		ForEach($subscribers As $subscriber) {
			$this->notificationView->assignMultiple (Array(
				'settings'         => $this->settings,
				'subscribedObject' => $subscriptionObject,
				'newObject'        => $notificationObject,
				'subscriber'       => $subscriber ));
			$text = $this->notificationView->render();
			$this->mailingService->sendMail($subscriber, "Hallo", $text);
		}
	}

}

?>
