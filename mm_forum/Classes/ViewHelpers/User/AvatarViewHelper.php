<?php

/*                                                                      *
 *  COPYRIGHT NOTICE                                                    *
 *                                                                      *
 *  (c) 2010 Martin Helmich <m.helmich@mittwald.de>                     *
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
	 * @copyright  2010 Martin Helmich <m.helmich@mittwald.de>
	 *             Mittwald CM Service GmbH & Co. KG
	 *             http://www.mittwald.de
	 * @license    GNU Public License, version 2
	 *             http://opensource.org/licenses/gpl-license.php
	 *
	 */

Class Tx_MmForum_ViewHelpers_User_AvatarViewHelper Extends Tx_Fluid_ViewHelpers_ImageViewHelper {



		/**
		 *
		 * Initializes the view helper's arguments.
		 *
		 */

	Public Function initializeArguments() {
		parent::initializeArguments();
	}



		/**
		 *
		 * Renders the avatar.
		 *
		 * @param  Tx_MmForum_Domain_Model_User_FrontendUser $user
		 *                             The user whose avatar is to be rendered.
		 * @param  integer $width      The desired avatar width
		 * @param  integer $height     The desired avatar height
		 * @return  string             HTML content
		 * 
		 */

	Public Function render(Tx_MmForum_Domain_Model_User_FrontendUser $user, $width=NULL, $height=NULL) {
		If($user->getImage()) {

		} Else {
			$src = t3lib_extMgm::siteRelPath('mm_forum').'Resources/Public/Images/Icons/AvatarEmpty.png';
		} Return parent::render($src, $width, $height);
	}

}

?>
