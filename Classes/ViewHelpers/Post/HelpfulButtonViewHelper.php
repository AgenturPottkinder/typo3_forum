<?php
namespace Mittwald\Typo3Forum\ViewHelpers\Post;
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

class HelpfulButtonViewHelper extends CObjectViewHelper {

	/**
	 * @var array
	 */
	protected $settings = NULL;

	/**
	 * The frontend user repository.
	 * @var \Mittwald\Typo3Forum\Domain\Repository\User\FrontendUserRepository
	 */
	protected $frontendUserRepository = NULL;

	/**
	 * An authentication service. Handles the authentication mechanism.
	 *
	 * @var \Mittwald\Typo3Forum\Service\Authentication\AuthenticationServiceInterface
	 * @inject
	 */
	protected $authenticationService;

	public function initialize() {
		parent::initialize();
		$this->settings = $this->templateVariableContainer->get('settings');
	}

	public function initializeArguments() {
		parent::initializeArguments();
		$this->registerArgument('class', 'string', 'CSS class.');
	}

	/**
	 * @param \Mittwald\Typo3Forum\Domain\Model\Forum\Post $post
	 * @param string $countTarget
	 * @param string $countUserTarget
	 * @param string $title
	 * @return string
	 */
	public function render(\Mittwald\Typo3Forum\Domain\Model\Forum\Post $post, $countTarget = NULL, $countUserTarget = NULL, $title = '') {
		$class = $this->settings['forum']['post']['helpfulBtn']['iconClass'];

		if ($this->hasArgument('class')) {
			$class .= ' ' . $this->arguments['class'];
		}
		if($post->getAuthor()->getUid() != $this->authenticationService->getUser()->getUid() and !$this->authenticationService->getUser()->isAnonymous()){
			$class .= ' tx-typo3forum-helpfull-btn';
		}

		if ($post->hasBeenSupportedByUser($this->authenticationService->getUser())) {
			$class .= ' supported';
		}
		$btn = '<div data-toogle="tooltip" title="'.$title.'" data- class="' . $class . '" data-countusertarget="'.$countUserTarget.'" data-counttarget="'.$countTarget.'" data-post="'.$post->getUid().'" data-pageuid="'.$this->settings['pids']['Forum'].'" data-eid="'.$this->settings['forum']['post']['helpfulBtn']['eID'].'"></div>';
		return $btn;
	}
}
