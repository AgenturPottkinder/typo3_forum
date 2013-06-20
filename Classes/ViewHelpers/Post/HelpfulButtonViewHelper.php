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
class Tx_MmForum_ViewHelpers_Post_HelpfulButtonViewHelper extends \TYPO3\CMS\Fluid\ViewHelpers\CObjectViewHelper {


	/**
	 * @var array
	 */
	protected $settings = NULL;

	/**
	 * The frontend user repository.
	 * @var Tx_MmForum_Domain_Repository_User_FrontendUserRepository
	 */
	protected $frontendUserRepository = NULL;

	/**
	 * An authentication service. Handles the authentication mechanism.
	 *
	 * @var Tx_MmForum_Service_Authentication_AuthenticationServiceInterface
	 */
	protected $authenticationService;


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


	public function initialize() {
		parent::initialize();
		$this->settings = $this->templateVariableContainer->get('settings');
	}


	public function initializeArguments() {
		parent::initializeArguments();
		$this->registerArgument('class', 'string', 'CSS class.');
	}

	/**
	 *
	 * @param Tx_MmForum_Domain_Model_Forum_Post $post
	 * @param string $countTarget
	 * @param string $countUserTarget
	 * @return string
	 */
	public function render(Tx_MmForum_Domain_Model_Forum_Post $post, $countTarget = NULL, $countUserTarget = NULL) {
		$class = $this->settings['forum']['post']['helpfulBtn']['iconClass'];

		if ($this->hasArgument('class')) {
			$class .= ' ' . $this->arguments['class'];
		}
		$class .= ' tx-mmforum-helpfull-btn';
		if ($post->hasBeenSupportedByUser($this->authenticationService->getUser())) {
			$class .= ' supported';
		}
		$btn = '<div class="' . $class . '" data-countusertarget="'.$countUserTarget.'" data-counttarget="'.$countTarget.'" data-post="'.$post->getUid().'" data-pageuid="'.$this->settings['pids']['Forum'].'" data-eid="'.$this->settings['forum']['post']['helpfulBtn']['eID'].'"></div>';
		return $btn;
	}
}
