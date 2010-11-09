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

Abstract Class Tx_MmForum_Controller_AbstractController Extends Tx_Extbase_MVC_Controller_ActionController {





		/*
		 * ATTRIBUTES
		 */





		/**
		 * A repository for frontend users.
		 * @var Tx_MmForum_Domain_Repository_User_FrontendUserRepository
		 */
	Protected $frontendUserRepository;

		/**
		 * An authentication service. Handles the authentication mechanism.
		 * @var Tx_MmForum_Domain_Service_AuthenticationService
		 */
	Protected $authenticationService;

		/**
		 * A service class that handles the sending of emails. This service is
		 * instantiated on-demand only.
		 * @var Tx_MmForum_Service_Mailing_AbstractMailingService
		 */
	Protected $mailingService;

		/**
		 * An array with controller-specific settings. This is read from
		 * plugin.tx_mmforum.settings.[controller-name].
		 * @var array
		 */
	Protected $localSettings;





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

	Protected Function handleError(Tx_MmForum_Domain_Exception_AbstractException $e) {
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

	Protected Function callActionMethod() {
		Try {
			parent::callActionMethod();
		} Catch(Tx_MmForum_Domain_Exception_AbstractException $e) {
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

	Protected Function  initializeAction() {
		$this->frontendUserRepository =&
			t3lib_div::makeInstance('Tx_MmForum_Domain_Repository_User_FrontendUserRepository');

		$this->buildAuthenticationService();
		$this->localSettings = $this->settings[lcfirst(array_pop(explode('_',  get_class($this))))];

		ForEach($this->settings['pids'] As $key => &$value)
			If(!$value) $value = $GLOBALS['TSFE']->id;
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

	Protected Function getCurrentUser() {
		Return $this->frontendUserRepository->findCurrent();
	}



		/**
		 *
		 * Generates a mailing service class. The class name of the mailing service is
		 * configured dynamically using the plugin.tx_mmforum.settings.mailing.serviceClass
		 * settings. This allows the user to override the mailing service with a custom
		 * class. This class, however, must be a descendant of the
		 * Tx_MmForum_Service_Mailing_AbstractMailingService class.
		 *
		 * @return Tx_MmForum_Service_Mailing_AbstractMailingService
		 *                             An instance of the mailing service class.
		 *
		 */

	Protected Function buildMailingService() {
		If($this->mailingService === NULL) {
			$this->mailingService =& $this->buildService('mailing', 'Tx_MmForum_Service_Mailing_AbstractMailingService');
		} Return $this->mailingService;
	}



		/**
		 *
		 * Generates an authentication service class. The class name of the authentication
		 * service is configured dynamically using the plugin.tx_mmforum.settings.authentication.serviceClass
		 * settings. This allows the user to override the authentication service with a
		 * custom class. This class, however, must implement the Tx_MmForum_Domain_Service_AuthenticationServiceInterface
		 * interface.
		 *
		 * @return Tx_MmForum_Domain_Service_AuthenticationServiceInterface
		 *                             An instance of the authentication service.
		 *
		 */

	Protected Function buildAuthenticationService() {
		If($this->authenticationService === NULL) {
			$this->authenticationService =& $this->buildService('authentication', 'Tx_MmForum_Domain_Service_AuthenticationServiceInterface');
			$this->authenticationService->injectFrontendUser($this->frontendUserRepository->findCurrent());
		} Return $this->authenticationService;
	}



		/**
		 *
		 * Generic method for building a new service instance.
		 *
		 * @param  string $serviceName The name of the service that is to be generated.
		 *                             This value must be a valid index of the
		 *                             plugin.tx_mmforum.settings.services array.
		 * @param  string $expectedInterface
		 *                             The class/interface name that the new service
		 *                             class must be an instance of.
		 * @return Tx_MmForum_Service_AbstractService
		 *                             The service class.
		 *
		 */

	Protected Function buildService($serviceName, $expectedInterface='Tx_MmForum_Service_AbstractService') {
		$className = $this->settings['services'][$serviceName]['serviceClass'];
		If(!class_exists($className))
			Throw New Tx_Extbase_Object_UnknownClass ( "The class specified in plugin."
				. "tx_mmforum.settings.$serviceName.serviceClass ($className) does not exist!",
				1288018736);
		$service =& t3lib_div::makeInstance($className);
		If(!$service InstanceOf $expectedInterface)
			Throw New Tx_Extbase_Object_InvalidClass ( "plugin.tx_mmforum.settings."
				. "$serviceName.serviceClass must be an instance of $expectedInterface,"
				. get_class($this->mailingService) . " given!", 1288018644);
		$service->injectSettings($this->settings);
		Return $service;
	}

}

?>
