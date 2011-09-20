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
	 * Abstract basis controller class for all mm_forum controller classes. This
	 * class implements a basic error handling that catches all exception thrown within
	 * a mm_forum controller or in the domain model.
	 *
	 * @author     Martin Helmich <m.helmich@mittwald.de>
	 * @package    MmForum
	 * @subpackage Controller
	 * @version    $Id$
	 *
	 * @copyright  2010 Martin Helmich <m.helmich@mittwald.de>
	 *             Mittwald CM Service GmbH & Co. KG
	 *             http://www.mittwald.de
	 * @license    GNU Public License, version 2
	 *             http://opensource.org/licenses/gpl-license.php
	 *
	 */

abstract class Tx_MmForum_Controller_AbstractController
	extends Tx_Extbase_MVC_Controller_ActionController {





		/*
		 * ATTRIBUTES
		 */





		/**
		 * A repository for frontend users.
		 * @var Tx_MmForum_Domain_Repository_User_FrontendUserRepository
		 */
	protected $frontendUserRepository;

		/**
		 * An authentication service. Handles the authentication mechanism.
		 * @var Tx_MmForum_Service_AuthenticationServiceInterface
		 */
	protected $authenticationService;
	
		/**
		 * A mailing service. Handles the sending of mails to frontend users
		 * (e.g. notifications about new posts/topics, etc.)
		 * @var Tx_MmForum_Service_Mailing_MailingServiceInterface
		 */
	protected $mailingService;

		/**
		 * An array with controller-specific settings. This is read from
		 * plugin.tx_mmforum.settings.[controller-name].
		 * @var array
		 */
	protected $localSettings;
	
		/**
		 * The non-namespaced class name of this controller (e.g. ForumController
		 * instead of Tx_MmForum_Controller_ForumController).
		 * @var string
		 */
	protected $className;
	
	
	
	
	
		/*
		 * DEPENDENCY INJECTORS
		 */
	
	
	
	
	
		/**
		 * 
		 * Injects a frontend user repository.
		 * @param  Tx_MmForum_Domain_Repository_User_FrontendUserRepository $frontendUserRepository
		 *                             A frontend user repository.
		 * @return void
		 * 
		 */
	
	public function injectFrontendUserRepository(Tx_MmForum_Domain_Repository_User_FrontendUserRepository $frontendUserRepository) {
		$this->frontendUserRepository = $frontendUserRepository;
	}
	
	
	
		/**
		 * 
		 * Injects an authentication service.
		 * @param  Tx_MmForum_Service_Authentication_AuthenticationServiceInterface $authenticationService
		 *                             An authentication service.
		 * @return void
		 * 
		 */
	
	public function injectAuthenticationService(Tx_MmForum_Service_Authentication_AuthenticationServiceInterface $authenticationService) {
		$this->authenticationService = $authenticationService;
	}
	
	
	
		/**
		 * 
		 * Injects a mailing service.
		 * @param  Tx_MmForum_Service_Mailing_MailingServiceInterface $mailingService
		 *                             A mailing service.
		 * @return void
		 * 
		 */
	
	public function injectMailingService(Tx_MmForum_Service_Mailing_MailingServiceInterface $mailingService) {
		$this->mailingService = $mailingService;
	}





		/*
		 * METHODS
		 */







		/**
		 *
		 * Handles an exception. This methods modifies the controller context for the
		 * template view, causing the view class to look in the same directory regardless
		 * of the controller.
		 *
		 * @param Tx_MmForum_Domain_Exception_AbstractException $e The exception that is to be handled
		 * @return void
		 *
		 */

	protected function handleError(Tx_MmForum_Domain_Exception_AbstractException $e) {
		$controllerContext = $this->buildControllerContext();
		$controllerContext->getRequest()->setControllerName('Default');
		$controllerContext->getRequest()->setControllerActionName('error');
		$this->view->setControllerContext($controllerContext);

		$content = $this->view->assign('exception', $e)->render('error');
		$this->response->appendContent($content);
	}



		/**
		 *
		 * Calls a controller action. This method wraps the callActionMethod method of
		 * the parent Tx_Extbase_MVC_Controller_ActionController class. It catches all
		 * Exceptions that might be thrown inside one of the action methods.
		 * This method ONLY catches exceptions that belong to the mm_forum extension.
		 * All other exceptions are not caught.
		 *
		 * @return void
		 *
		 */

	protected function callActionMethod() {
		try {
			parent::callActionMethod();
		} catch(Tx_MmForum_Domain_Exception_AbstractException $e) {
			$this->handleError($e);
		}
	}



		/**
		 *
		 * Initializes all action methods. This method does basic initialization tasks,
		 * like instantiating required repositories and services.
		 * @return void
		 *
		 */

	protected function initializeAction() {
		$this->className = array_pop(explode('_',  get_class($this)));
		$this->localSettings = $this->settings[lcfirst($this->className)];

		foreach($this->settings['pids'] As $key => &$value)
			if(!$value) $value = $GLOBALS['TSFE']->id;
			
		$this->on('initialize');
	}



		/**
		 *
		 * Gets the currently logged in frontend user. This method is only a convenience
		 * wrapper for the findCurrent-Method of the frontend user repository class.
		 *
		 * @return Tx_MmForum_Domain_Model_User_FrontendUser
		 *                             The frontend user that is currently logged in, or
		 *                             NULL if no user is logged in.
		 *
		 */

	protected function getCurrentUser() {
		return $this->frontendUserRepository->findCurrent();
	}
	
	protected function on($event, array $arguments=array()) {
		$functionName = 'on'.ucfirst($event);
		$listeners = $GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['mm_forum'][$this->className][$functionName];
		array_unshift($arguments, $this);
		foreach($listeners as $listener) {
			$listenerInstance = $this->objectManager->get($listener);
			$listenerInstance->setView($this->view);
			$listenerInstance->setControllerContext($this->controllerContext);
			$listenerInstance->setSettings($this->settings);
			$listenerInstance->handleEvent($event, $arguments);
		}
	}

}

?>
