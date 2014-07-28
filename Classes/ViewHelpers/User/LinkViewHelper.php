<?php
namespace Mittwald\MmForum\ViewHelpers\User;


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
class LinkViewHelper extends \TYPO3\CMS\Fluid\ViewHelpers\CObjectViewHelper {


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
	 * @param \Mittwald\MmForum\Domain\Model\User\FrontendUser $user
	 * @param boolean $showOnlineStatus
	 * @param boolean $showOnline
	 * @return string
	 */
	public function render(\Mittwald\MmForum\Domain\Model\User\FrontendUser $user = NULL, $showOnlineStatus = TRUE, $showOnline = FALSE) {
		// if user anonymous: show only the username
		if ($user->isAnonymous()) {
			return $user->getUsername();
		}
		// use uribuilder to genreate the uri for the userprofile
		$uriBuilder = $this->controllerContext->getUriBuilder();
		$uri = $uriBuilder->setTargetPageUid($this->settings['pids']['UserShow'])->setArguments(array('tx_mmforum_pi1[user]' => $user->getUid(), 'tx_mmforum_pi1[controller]' => 'User', 'tx_mmforum_pi1[action]' => 'show'))->build();

		$class = 'user-link';


		if ($this->hasArgument('class')) {
			$class .= ' ' . $this->arguments['class'];
		}

		$fullUsername = htmlspecialchars($user->getUsername());
		$limit = intval($this->settings['cutUsernameOnChar']);
		if($limit == 0 || strlen($fullUsername) <= $limit) {
			$username = $fullUsername;
		} else {
			$username = substr($fullUsername,0,$limit)."...";
		}
		$moderatorMark = "";
		if($this->settings['moderatorMark']['image']) {
			foreach($user->getUsergroup() AS $group) {
				if($group->getUserMod() == 1) {
					$moderatorMark = '<img src="'.$this->settings['moderatorMark']['image'].'"
											title="'.$this->settings['moderatorMark']['title'].'" />';
					break;
				}
			}
		}

		if ($showOnlineStatus) {
			if($showOnline) {
				$onlineStatus = 'user_onlinepoint iconset-8-user-online';
			}else{
				$onlineStatus = 'user_onlinepoint iconset-8-user-offline';
			}
			$link = '<a href="' . $uri . '" class="' . $class . '" title="' . $fullUsername.'">' . $username . ' <i class="'.$onlineStatus.'" data-uid="'.$user->getUid().'"></i> '.$moderatorMark.'</a>';
		} else {
			$link = '<a href="' . $uri . '" class="' . $class . '" title="' . $fullUsername . '">' . $username . ' '.$moderatorMark.'</a>';
		}

		return $link;
	}
}

