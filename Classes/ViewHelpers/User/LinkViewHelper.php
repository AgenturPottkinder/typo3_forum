<?php
namespace Mittwald\Typo3Forum\ViewHelpers\User;

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
use Mittwald\Typo3Forum\Domain\Model\User\FrontendUserGroup;

class LinkViewHelper extends CObjectViewHelper {

	/**
	 * @var array
	 */
	protected $settings = NULL;

	public function initialize() {
		parent::initialize();
		$this->settings = $this->templateVariableContainer->get('settings');
	}

	public function initializeArguments() {
		parent::initializeArguments();
		$this->registerArgument('class', 'string', 'CSS class.');
		$this->registerArgument('style', 'string', 'CSS inline styles.');
	}

	/**
	 *
	 * @param \Mittwald\Typo3Forum\Domain\Model\User\FrontendUser $user
	 * @param boolean $showOnlineStatus
	 * @param boolean $showOnline
	 * @return string
	 */
	public function render(\Mittwald\Typo3Forum\Domain\Model\User\FrontendUser $user = NULL, $showOnlineStatus = TRUE, $showOnline = FALSE) {
		// if user anonymous: show only the username
		if ($user->isAnonymous()) {
			return $user->getUsername();
		}
		// use uribuilder to genreate the uri for the userprofile
		$uriBuilder = $this->controllerContext->getUriBuilder();
		$uri = $uriBuilder->setTargetPageUid($this->settings['pids']['UserShow'])->setArguments(['tx_typo3forum_pi1[user]' => $user->getUid(), 'tx_typo3forum_pi1[controller]' => 'User', 'tx_typo3forum_pi1[action]' => 'show'])->build();

		$class = 'user-link';

		if ($this->hasArgument('class')) {
			$class .= ' ' . $this->arguments['class'];
		}

		$fullUsername = htmlspecialchars($user->getUsername());
		$limit = (int)$this->settings['cutUsernameOnChar'];
		if ($limit == 0 || strlen($fullUsername) <= $limit) {
			$username = $fullUsername;
		} else {
			$username = substr($fullUsername, 0, $limit) . "...";
		}
		$moderatorMark = "";
		if ($this->settings['moderatorMark']['image']) {
			foreach ($user->getUsergroup() as $group) {
				/** @var FrontendUserGroup $group */
				if ($group->getUserMod() === 1) {
					$moderatorMark = '<img src="' . $this->settings['moderatorMark']['image'] . '" title="' . $this->settings['moderatorMark']['title'] . '" />';
					break;
				}
			}
		}

		if ($showOnlineStatus) {
			if ($showOnline) {
				$onlineStatus = 'user_onlinepoint iconset-8-user-online';
			} else {
				$onlineStatus = 'user_onlinepoint iconset-8-user-offline';
			}
			$link = '<a href="' . $uri . '" class="' . $class . '" title="' . $fullUsername . '">' . $username . ' <i class="' . $onlineStatus . '" data-uid="' . $user->getUid() . '"></i> ' . $moderatorMark . '</a>';
		} else {
			$link = '<a href="' . $uri . '" class="' . $class . '" title="' . $fullUsername . '">' . $username . ' ' . $moderatorMark . '</a>';
		}

		return $link;
	}
}

