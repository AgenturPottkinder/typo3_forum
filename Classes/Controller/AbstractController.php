<?php
namespace Mittwald\Typo3Forum\Controller;

/*                                                                      *
 *  COPYRIGHT NOTICE                                                    *
 *                                                                      *
 *  (c) 2015 Mittwald CM Service GmbH & Co KG                           *
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

use Mittwald\Typo3Forum\Domain\Exception\AbstractException;
use Mittwald\Typo3Forum\Domain\Model\User\FrontendUser;
use Mittwald\Typo3Forum\Utility\Localization;
use TYPO3\CMS\Core\Messaging\FlashMessage;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;

abstract class AbstractController extends ActionController {

	const CONTEXT_WEB = 0;
	const CONTEXT_AJAX = 1;
	const CONTEXT_CLI = 2;

	/**
	 * An authentication service. Handles the authentication mechanism.
	 *
	 * @var \Mittwald\Typo3Forum\Service\Authentication\AuthenticationServiceInterface
	 * @inject
	 */
	protected $authenticationService;

	/**
	 * The non-namespaced class name of this controller (e.g. ForumController
	 * instead of \Mittwald\Typo3Forum\Controller\ForumController).
	 *
	 * @var string
	 */
	protected $className;

	/**
	 * @var \Mittwald\Typo3Forum\Domain\Repository\User\FrontendUserRepository
	 * @inject
	 */
	protected $frontendUserRepository;

	/**
	 * An array with controller-specific settings. This is read from
	 * plugin.tx_typo3forum.settings.[controller-name].
	 *
	 * @var array
	 */
	protected $localSettings;

	/**
	 * The global SignalSlot-Dispatcher.
	 *
	 * @var \TYPO3\CMS\Extbase\SignalSlot\Dispatcher
	 * @inject
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
	 * METHODS
	 */

	/**
	 *
	 * Handles an exception. This methods modifies the controller context for the
	 * template view, causing the view class to look in the same directory regardless
	 * of the controller.
	 *
	 * @param AbstractException $e The exception that is to be handled
	 *
	 * @return void
	 *
	 */
	protected function handleError(AbstractException $e) {
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
	 * This method ONLY catches exceptions that belong to the typo3_forum extension.
	 * All other exceptions are not caught.
	 *
	 * @return void
	 *
	 */
	protected function callActionMethod() {
		try {
			parent::callActionMethod();
		} catch (AbstractException $e) {
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
		$this->className = array_pop(explode('_', get_class($this)));
		$this->localSettings = $this->settings[lcfirst($this->className)];

		if (!empty($this->settings['pids'])) {
			foreach ($this->settings['pids'] as &$value) {
				if (!$value) {
					$value = $GLOBALS['TSFE']->id;
				}
			}
		}
	}

	/**
	 *
	 * Gets the currently logged in frontend user. This method is only a convenience
	 * wrapper for the findCurrent-Method of the frontend user repository class.
	 *
	 * @return FrontendUser The frontend user that is currently logged in, or NULL if no user is logged in.
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
	 * @param string $key The language key that is to be used for the
	 *                                  flash messages.
	 * @param array $arguments Arguments for the flash message.
	 * @param string $titleKey Optional language key for the message's title.
	 * @param int $severity Message severity (see \TYPO3\CMS\Core\Messaging\FlashMessage::*)
	 *
	 * @return void
	 */
	protected function addLocalizedFlashmessage($key, array $arguments = [], $titleKey = NULL, $severity = FlashMessage::OK) {
		$message = new FlashMessage(Localization::translate($key, 'Typo3Forum', $arguments), Localization::translate($titleKey, 'Typo3Forum'), $severity);
		$this->controllerContext->getFlashMessageQueue()->enqueue($message);
	}

	/**
	 * @param string $actionName
	 * @param string $controllerName
	 * @param string $extensionName
	 * @param array $arguments
	 * @param int $pageUid
	 * @param int $delay
	 * @param int $statusCode
	 */
	protected function redirect($actionName, $controllerName = NULL, $extensionName = NULL, array $arguments = NULL, $pageUid = NULL, $delay = 0, $statusCode = 303) {
		if ($this->context === self::CONTEXT_WEB && $this->request->getFormat() === 'html') {
			parent::redirect($actionName, $controllerName, $extensionName, $arguments, $pageUid, $delay, $statusCode);
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

	/**
	 * @param string $url
	 * @return mixed
	 */
	public function purgeUrl($url) {
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL, $url);
		curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "PURGE");
		curl_setopt($curl, CURLOPT_HEADER, TRUE);
		curl_setopt($curl, CURLOPT_NOBODY, true);
		curl_setopt($curl, CURLOPT_HTTPHEADER, ['Host:' . $_SERVER['HTTP_HOST']]);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
		$result = curl_exec($curl);
		return $result;
	}

}
