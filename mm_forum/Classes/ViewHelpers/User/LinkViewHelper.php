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
class Tx_MmForum_ViewHelpers_User_LinkViewHelper extends Tx_Fluid_ViewHelpers_CObjectViewHelper {



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
	 * @param Tx_MmForum_Domain_Model_User_FrontendUser $user
	 * @param boolean                                   $withoutWrap
	 *
	 */
	public function render(Tx_MmForum_Domain_Model_User_FrontendUser $user, $withoutWrap = FALSE) {
		$class = 'nav nav-pills';
		if ($this->hasArgument('class')) {
			$class .= ' ' . $this->arguments['class'];
		}

		$tagContent = parent::render('plugin.tx_mmforum.renderer.navigation.userlink', $this->getDataArray($user));
		if ($withoutWrap === TRUE) {
			return $tagContent;
		}

		return '<ul class="' . $class . '">' . $tagContent . '</ul>';
	}



	protected function getDataArray(Tx_MmForum_Domain_Model_User_FrontendUser $user = NULL) {
		if ($user === NULL) {
			return array();
		}

		$data = array('uid'            => $user->getUid(),
		              'username'       => $user->getUsername(),
		              'profilePageUid' => (int)$this->settings['pids']['UserShow']);

		foreach ($user->getContactData() as $type => $value) {
			$data['contact_' . $type] = $value;
		}

		return $data;
	}



}
