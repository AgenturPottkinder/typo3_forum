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
 * Abstract basis controller class for all mm_forum controller classes. This
 * class implements a basic error handling that catches all exception thrown within
 * a mm_forum controller or in the domain model.
 *
 * @author     Martin Helmich <m.helmich@mittwald.de>
 * @package    MmForum
 * @subpackage Controller
 * @version    $Id$
 *
 * @copyright  2012 Martin Helmich <m.helmich@mittwald.de>
 *             Mittwald CM Service GmbH & Co. KG
 *             http://www.mittwald.de
 * @license    GNU Public License, version 2
 *             http://opensource.org/licenses/gpl-license.php
 *
 */
abstract class Tx_MmForum_Controller_AbstractController extends \TYPO3\CMS\Extbase\Mvc\Controller\ActionController {



	/*
	 * CONSTANTS
	 */

	/**
	 *
	 */
	const CONTEXT_WEB = 0;



	/**
	 *
	 */
	const CONTEXT_AJAX = 1;



	/**
	 *
	 */
	const CONTEXT_CLI = 2;



	/*
	  * ATTRIBUTES
	  */



	/**
	 * A repository for frontend users.
	 *
	 * @var Tx_MmForum_Domain_Repository_User_FrontendUserRepository
	 */
	protected $frontendUserRepository;



	/**
	 * An authentication service. Handles the authentication mechanism.
	 *
	 * @var Tx_MmForum_Service_Authentication_AuthenticationServiceInterface
	 */
	protected $authenticationService;



	/**
	 * An array with controller-specific settings. This is read from
	 * plugin.tx_mmforum.settings.[controller-name].
	 *
	 * @var array
	 */
	protected $localSettings;



	/**
	 * The non-namespaced class name of this controller (e.g. ForumController
	 * instead of Tx_MmForum_Controller_ForumController).
	 *
	 * @var string
	 */
	protected $className;



	/**
	 * The global SignalSlot-Dispatcher.
	 *
	 * @var Tx_Extbase_SignalSlot_Dispatcher
	 */
	protected $signalSlotDispatcher;



	/**
	 * The current controller context. This context is necessary to enable
	 * different behaviour of this controller e.g. in web/ajax/cli context.
	 *
	 * @var integer
	 */
	protected $context = self::CONTEXT_WEB;



	/*
	  * DEPENDENCY INJECTORS
	  */



	/**
	 *
	 * Injects a frontend user repository.
	 *
	 * @param  Tx_MmForum_Domain_Repository_User_FrontendUserRepository $frontendUserRepository
	 *                             A frontend user repository.
	 *
	 * @return void
	 *
	 */
	public function injectFrontendUserRepository(Tx_MmForum_Domain_Repository_User_FrontendUserRepository $frontendUserRepository) {
		$this->frontendUserRepository = $frontendUserRepository;
	}



	/**
	 *
	 * Injects an authentication service.
	 *
	 * @param  Tx_MmForum_Service_Authentication_AuthenticationServiceInterface $authenticationService
	 *                             An authentication service.
	 *
	 * @return void
	 *
	 */
	public function injectAuthenticationService(Tx_MmForum_Service_Authentication_AuthenticationServiceInterface $authenticationService) {
		$this->authenticationService = $authenticationService;
	}



	/**
	 *
	 * Injects an instance of the Extbase SignalSlot-Dispatcher.
	 *
	 * @param \TYPO3\CMS\Extbase\SignalSlot\Dispatcher $signalSlotDispatcher
	 *                                 An instance of the Extbase SignalSlot
	 *                                 Dispatcher.
	 *
	 * @return void
	 *
	 */
	public function injectSignalSlotDispatcher(\TYPO3\CMS\Extbase\SignalSlot\Dispatcher $signalSlotDispatcher) {
		$this->signalSlotDispatcher = $signalSlotDispatcher;
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
	 *
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
		} catch (Tx_MmForum_Domain_Exception_AbstractException $e) {
			$this->handleError($e);
		}
	}



	/**
	 *
	 * Initializes all action methods. This method does basic initialization tasks,
	 * like instantiating required repositories and services.
	 *
	 * @return void
	 *
	 */
	protected function initializeAction() {
		$this->className     = array_pop(explode('_', get_class($this)));
		$this->localSettings = $this->settings[lcfirst($this->className)];

		foreach ($this->settings['pids'] as &$value) {
			if (!$value) {
				$value = $GLOBALS['TSFE']->id;
			}
		}
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



	/**
	 *
	 * Disable default error flash messages (who actually wants to see those?)
	 *
	 * @return boolean Always FALSE.
	 *
	 */
	protected function getErrorFlashMessage() {
		return FALSE;
	}



	/**
	 *
	 * Clears the cache for the current page. Unfortunately, the
	 * "enableAutomaticCacheClearing" feature provided by Extbase does only
	 * clear the cache of the record's storage page, but not of the page the
	 * record is displayed on (see http://forge.typo3.org/issues/35057 for
	 * more information).
	 *
	 * @see    http://forge.typo3.org/issues/35057
	 * @return void
	 *
	 */
	protected function clearCacheForCurrentPage() {
		$this->cacheService->clearPageCache((int)$GLOBALS['TSFE']->id);
	}



	/**
	 *
	 * Adds a localized message to the flash message container. This method is
	 * just a shorthand for
	 *
	 *     this->flashMessageContainer->add(Tx_Extbase_Utility_Localization(...));
	 *
	 * @param  string $key              The language key that is to be used for the
	 *                                  flash messages.
	 * @param  array  $arguments        Arguments for the flash message.
	 * @param  string $titleKey         Optional language key for the message's title.
	 * @param  string $severity         Message severity (see \TYPO3\CMS\Core\Messaging\FlashMessage::*)
	 *
	 * @return void
	 */
	protected function addLocalizedFlashmessage($key, array $arguments = array(), $titleKey = NULL,
	                                            $severity = \TYPO3\CMS\Core\Messaging\FlashMessage::OK) {
		$this->controllerContext->getFlashMessageQueue()->addMessage(
			new \TYPO3\CMS\Core\Messaging\FlashMessage(
				Tx_MmForum_Utility_Localization::translate($key, 'MmForum', $arguments),
				Tx_MmForum_Utility_Localization::translate($titleKey, 'MmForum'), $severity
			)
		);
	}



	/**
	 * @param            $actionName
	 * @param null       $controllerName
	 * @param null       $extensionName
	 * @param array|null $arguments
	 * @param null       $pageUid
	 * @param int        $delay
	 * @param int        $statusCode
	 *
	 * @return void
	 */
	protected function redirect($actionName, $controllerName = NULL, $extensionName = NULL, array $arguments = NULL,
	                            $pageUid = NULL, $delay = 0, $statusCode = 303) {
		if ($this->context === self::CONTEXT_WEB && $this->request->getFormat() === 'html') {
			/** @noinspection PhpInconsistentReturnPointsInspection */
			return parent::redirect($actionName, $controllerName, $extensionName, $arguments, $pageUid, $delay,
			                        $statusCode);
		} else {
			// Ignore for now...
			/** @noinspection PhpInconsistentReturnPointsInspection */
			return;
		}
	}



	/**
	 * @return mixed|null
	 */
	protected function resolveViewObjectName() {
		if ($this->context === self::CONTEXT_WEB) {
			return parent::resolveViewObjectName();
		}

		return NULL;
	}



	/**
	 * @param $context
	 */
	public function setContext($context) {
		$this->context = $context;
	}



}
