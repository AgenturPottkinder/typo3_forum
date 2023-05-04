<?php
namespace Mittwald\Typo3Forum\Controller;

use Mittwald\Typo3Forum\Domain\Exception\AbstractException;

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

use Mittwald\Typo3Forum\Domain\Model\User\FrontendUser;
use Mittwald\Typo3Forum\Domain\Repository\User\FrontendUserRepository;
use Mittwald\Typo3Forum\Service\Authentication\AuthenticationServiceInterface;
use Mittwald\Typo3Forum\Utility\Localization;
use Psr\Http\Message\ResponseInterface;
use TYPO3\CMS\Core\Messaging\FlashMessage;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;
use TYPO3\CMS\Extbase\Mvc\RequestInterface;
use TYPO3\CMS\Extbase\Service\CacheService;

abstract class AbstractController extends ActionController
{
    const CONTEXT_WEB = 0;
    const CONTEXT_AJAX = 1;
    const CONTEXT_CLI = 2;

    /**
    * An authentication service. Handles the authentication mechanism.
    */
    protected AuthenticationServiceInterface $authenticationService;
    /**
    * The non-namespaced class name of this controller (e.g. ForumController
    * instead of \Mittwald\Typo3Forum\Controller\ForumController).
    */
    protected string $className;
    protected FrontendUserRepository $frontendUserRepository;
    protected CacheService $cacheService;

    /**
    * The current controller context. This context is necessary to enable
    * different behaviour of this controller e.g. in web/ajax/cli context.
    */
    protected int $context = self::CONTEXT_WEB;

    /*
    * METHODS
    */

    public function injectFrontendUserRepository(FrontendUserRepository $frontendUserRepository): void
    {
        $this->frontendUserRepository = $frontendUserRepository;
    }
    public function injectAuthenticationService(AuthenticationServiceInterface $authenticationService): void
    {
        $this->authenticationService = $authenticationService;
    }
    public function injectCacheService(CacheService $cacheService): void
    {
        $this->cacheService = $cacheService;
    }

    /**
    * Handles an exception. This methods modifies the controller context for the
    * template view, causing the view class to look in the same directory regardless
    * of the controller.
    */
    // TODO: Test
    protected function handleError(RequestInterface $request, AbstractException $e)
    {
        $controllerContext = $this->buildControllerContext();
        $controllerContext->getRequest()->setControllerName('Default');
        $controllerContext->getRequest()->setControllerActionName('error');
        $this->view->setControllerContext($controllerContext);

        $content = $this->view->assign('exception', $e)->render('error');

        $response = $this->responseFactory->createResponse(400);
        $response->getBody()->write($content);
        return $response;
    }

    /**
    * Calls a controller action. This method wraps the callActionMethod method of
    * the parent Tx_Extbase_MVC_Controller_ActionController class. It catches all
    * Exceptions that might be thrown inside one of the action methods.
    * This method ONLY catches exceptions that belong to the typo3_forum extension.
    * All other exceptions are not caught.
    */
    protected function callActionMethod(RequestInterface $request): ResponseInterface
    {
        try {
            return parent::callActionMethod($request);
        } catch (AbstractException $e) {
            return $this->handleError($request, $e);
        }
    }

    protected function initializeAction()
    {
        $this->className = array_pop(explode('_', get_class($this)));
    }

    /**
    * Gets the currently logged in frontend user. This method is only a convenience
    * wrapper for the findCurrent-Method of the frontend user repository class.
    *
    * @return FrontendUser The frontend user that is currently logged in, or NULL if no user is logged in.
    */
    protected function getCurrentUser()
    {
        return $this->frontendUserRepository->findCurrent();
    }

    /**
    * Disable default error flash messages (who actually wants to see those?)
    *
    * @return bool Always FALSE.
    */
    protected function getErrorFlashMessage()
    {
        return false;
    }

    /**
    * Clears the cache for the current page. Unfortunately, the
    * "enableAutomaticCacheClearing" feature provided by Extbase does only
    * clear the cache of the record's storage page, but not of the page the
    * record is displayed on (see http://forge.typo3.org/issues/35057 for
    * more information).
    *
    * @see    http://forge.typo3.org/issues/35057
    */
    protected function clearCacheForCurrentPage()
    {
        $this->cacheService->clearPageCache((int)$GLOBALS['TSFE']->id);
    }

    /**
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
    */
    protected function addLocalizedFlashmessage($key, array $arguments = [], $titleKey = null, $severity = FlashMessage::OK)
    {
        $message = new FlashMessage(Localization::translate($key, 'Typo3Forum', $arguments), Localization::translate($titleKey, 'Typo3Forum'), $severity);

        $this->getFlashMessageQueue()->enqueue($message);
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
    protected function redirect($actionName, $controllerName = null, $extensionName = null, array $arguments = null, $pageUid = null, $delay = 0, $statusCode = 303): void
    {
        if ($this->context === self::CONTEXT_WEB && $this->request->getFormat() === 'html') {
            parent::redirect($actionName, $controllerName, $extensionName, $arguments, $pageUid, $delay, $statusCode);
        }
    }

    /**
    * @param $context
    */
    public function setContext($context)
    {
        $this->context = $context;
    }

    /**
    * @param string $url
    * @return mixed
    */
    public function purgeUrl($url)
    {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'PURGE');
        curl_setopt($curl, CURLOPT_HEADER, true);
        curl_setopt($curl, CURLOPT_NOBODY, true);
        curl_setopt($curl, CURLOPT_HTTPHEADER, ['Host:' . $_SERVER['HTTP_HOST']]);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        $result = curl_exec($curl);
        return $result;
    }

    /**
     * Returns true if a referrer was found and redirects to it, otherwise false.
     */
    protected function redirectToReferrer(): bool
    {
        $referrerUri = $this->request->getServerParams()['HTTP_REFERER'] ?? '';
        if ($referrerUri === '') {
            $referrerUri = $this->request->getHeader('referer')[0] ?? '';
        }
        if ($referrerUri === '') {
            return false;
        }

        $this->redirectToUri($referrerUri);
        return true;
    }
}
