<?php
namespace Mittwald\Typo3Forum\ViewHelpers\Ext;
/*                                                                    - *
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

use TYPO3\CMS\Fluid\ViewHelpers\CObjectViewHelper;

class UserLinkViewHelper extends CObjectViewHelper {

    /**
     * @var \Mittwald\Typo3Forum\Configuration\ConfigurationBuilder
     * @inject
     */
    protected $configurationBuilder;

	/**
	 * Whole TypoScript typo3_forum settings
	 * @var array
	 */
	protected $settings;

	/**
	 * An authentication service. Handles the authentication mechanism.
	 *
	 * @var \Mittwald\Typo3Forum\Service\Authentication\AuthenticationServiceInterface
	 * @inject
	 */
	protected $authenticationService = NULL;

	public function initializeObject() {
		$this->settings = $this->configurationBuilder->getSettings();
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
	 * render
	 *
	 * @param bool|TRUE $link
	 *
	 * @return string
	 */
	public function render($link = TRUE) {
		$user = $this->authenticationService->getUser();
		if($link){
				$uriBuilder = $this->controllerContext->getUriBuilder();
				$uri = $uriBuilder->setTargetPageUid($this->settings['pids']['UserShow'])->setArguments(['tx_typo3forum_pi1[user]' => $user->getUid(), 'tx_typo3forum_pi1[controller]' => 'User', 'tx_typo3forum_pi1[action]' => 'show'])->build();
				return '<a href="' . $uri . '" title="' . $user->getUsername() . '">' . $user->getUsername() . '</a>';
		}else{
			return $user->getUsername();
		}
	}
}