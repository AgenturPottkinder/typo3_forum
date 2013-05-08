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
 * ViewHelper that renders a user's avatar.
 *
 * @author     Martin Helmich <m.helmich@mittwald.de>
 * @package    MmForum
 * @subpackage ViewHelpers_User
 * @version    $Id$
 *
 * @copyright  2012 Martin Helmich <m.helmich@mittwald.de>
 *             Mittwald CM Service GmbH & Co. KG
 *             http://www.mittwald.de
 * @license    GNU Public License, version 2
 *             http://opensource.org/licenses/gpl-license.php
 *
 */
class Tx_MmForum_ViewHelpers_User_AvatarViewHelper extends Tx_Fluid_ViewHelpers_ImageViewHelper {



	/**
	 * An instance of the Extbase Signal-/Slot-Dispatcher.
	 * @var Tx_Extbase_SignalSlot_Dispatcher
	 */
	protected $slots;



	/**
	 *
	 * Injector for the Signal-/Slot-Dispatcher.
	 *
	 * @param Tx_Extbase_SignalSlot_Dispatcher $signalSlotDispatcher
	 *                                 An instance of the Extbase Signal-/Slot-
	 *                                 Dispatcher.
	 * @return void
	 *
	 */
	public function injectSignalSlotDispatcher(Tx_Extbase_SignalSlot_Dispatcher $signalSlotDispatcher) {
		$this->slots = $signalSlotDispatcher;
	}



	/**
	 *
	 * Initializes the view helper's arguments.
	 *
	 */
	public function initializeArguments() {
		parent::initializeArguments();
	}



	/**
	 *
	 * Renders the avatar.
	 *
	 * @param  Tx_MmForum_Domain_Model_User_FrontendUser $user
	 *                                                               The user whose avatar is to be rendered.
	 * @param  integer                                   $width      The desired avatar width
	 * @param  integer                                   $height     The desired avatar height
	 * @return string              HTML content
	 *
	 */
	public function render(Tx_MmForum_Domain_Model_User_FrontendUser $user, $width = NULL, $height = NULL) {
		$avatarFilename = NULL;
		$avatarFilename = $user->getImagePath();
		if ($avatarFilename === NULL) {
			$avatarFilename = t3lib_extMgm::siteRelPath('mm_forum') . 'Resources/Public/Images/Icons/AvatarEmpty.png';
		}

		return parent::render($avatarFilename, $width, $height);
	}



}
