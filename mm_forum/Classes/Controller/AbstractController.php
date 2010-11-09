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
	 * Abstract basis controller class for all timetracking controller classes. This
	 * class implements a basic error handling that catches all exception throws within
	 * a mittwald_timetrack controller or in the domain model.
	 *
	 * @author     Martin Helmich <m.helmich@mittwald.de>
	 * @package    MmForum
	 * @subpackage Controller
	 * @version    $Id$
	 * @license    GNU Public License, version 2
	 *             http://opensource.org/licenses/gpl-license.php
	 *
	 */

Abstract Class Tx_MmForum_Controller_AbstractController Extends Tx_Extbase_MVC_Controller_ActionController {





		/*
		 * ATTRIBUTES
		 */





		/**
		 * @var Tx_MmForum_Domain_Repository_User_FrontendUserRepository
		 */
	Protected $frontendUserRepository;

		/**
		 * @var Tx_MmForum_Domain_Service_AuthenticationService
		 */
	Protected $authenticationService;

		/**
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
		 * @param Tx_MittwaldTimetrack_Domain_Exception_AbstractException $e The exception that is to be handled
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
		 * This method ONLY catches exceptions that belong to the mittwald_timetrack
		 * extension. All other exceptions are not catched.
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
		 * Initializes all action methods.
		 * @return void
		 *
		 */

	Protected Function  initializeAction() {
		$this->frontendUserRepository =&
			t3lib_div::makeInstance('Tx_MmForum_Domain_Repository_User_FrontendUserRepository');

		$this->authenticationService =&
			t3lib_div::makeInstance('Tx_MmForum_Domain_Service_AuthenticationService');
		$this->authenticationService->injectSettings($this->settings);
		$this->authenticationService->injectFrontendUser($this->getCurrentUser());

		$this->localSettings = $this->settings[lcfirst(array_pop(explode('_',  get_class($this))))];
	}



		/**
		 *
		 * @return Tx_MmForum_Domain_Model_User_FrontendUser
		 * 
		 */
	
	Protected Function getCurrentUser() {
		Return $this->frontendUserRepository->findCurrent();
	}

}

?>
