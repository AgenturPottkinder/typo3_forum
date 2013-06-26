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
 * ViewHelper that renders a big button.
 *
 * @author     Martin Helmich <m.helmich@mittwald.de>
 * @package    MmForum
 * @subpackage ViewHelpers_Control
 * @version    $Id: BigButtonViewHelper.php 52309 2011-09-20 18:54:26Z mhelmich $
 *
 * @copyright  2012 Martin Helmich <typo3@martin-helmich.de>
 *             http://www.martin-helmich.de
 * @license    GNU Public License, version 2
 *             http://opensource.org/licenses/gpl-license.php
 *
 */
class Tx_MmForum_ViewHelpers_Ext_UserLinkViewHelper extends \TYPO3\CMS\Fluid\ViewHelpers\CObjectViewHelper {


	/**
	 * An instance of the mm_forum authentication service.
	 * @var TYPO3\CMS\Extbase\Service\TypoScriptService
	 */
	protected $typoScriptService = NULL;

	/**
	 * Whole TypoScript mm_forum settings
	 * @var array
	 */
	protected $settings;

	/**
	 * An authentication service. Handles the authentication mechanism.
	 *
	 * @var Tx_MmForum_Service_Authentication_AuthenticationServiceInterface
	 */
	protected $authenticationService = NULL;


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
	 * Injects an instance of the \TYPO3\CMS\Extbase\Service\TypoScriptService.
	 * @param
	 * @param \TYPO3\CMS\Extbase\Service\TypoScriptService $typoScriptService
	 */
	public function injectTyposcriptService(\TYPO3\CMS\Extbase\Service\TypoScriptService $typoScriptService) {

		$this->typoScriptService = $typoScriptService;
		$ts = $this->typoScriptService->convertTypoScriptArrayToPlainArray(\TYPO3\CMS\Extbase\Configuration\FrontendConfigurationManager::getTypoScriptSetup());
		$this->settings = $ts['plugin']['tx_mmforum']['settings'];
	}


	public function initialize() {
		parent::initialize();
	}


	public function initializeArguments() {
		parent::initializeArguments();
		$this->registerArgument('class', 'string', 'CSS class.');
		$this->registerArgument('style', 'string', 'CSS inline styles.');
	}

	/**
	 * @param string $type
	 * @param bool $showOnlineStatus
	 * @return string
	 */
	public function render($link = TRUE) {
		$user = $this->authenticationService->getUser();
		if($link){
				$uriBuilder = $this->controllerContext->getUriBuilder();
				$uri = $uriBuilder->setTargetPageUid($this->settings['pids']['UserShow'])->setArguments(array('tx_mmforum_pi1[user]' => $user->getUid(), 'tx_mmforum_pi1[controller]' => 'User', 'tx_mmforum_pi1[action]' => 'show'))->build();
				return '<a href="' . $uri . '" title="' . $user->getUsername() . '">' . $user->getUsername() . '</a>';
		}else{
			return $user->getUsername();
		}
	}
}