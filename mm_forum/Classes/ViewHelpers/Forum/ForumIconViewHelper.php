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
	 * ViewHelper that renders a forum icon.
	 *
	 * @author     Martin Helmich <m.helmich@mittwald.de>
	 * @package    MmForum
	 * @subpackage ViewHelpers_Forum
	 * @version    $Id$
	 *
	 * @copyright  2010 Martin Helmich <m.helmich@mittwald.de>
	 *             Mittwald CM Service GmbH & Co. KG
	 *             http://www.mittwald.de
	 * @license    GNU Public License, version 2
	 *             http://opensource.org/licenses/gpl-license.php
	 *
	 */

Class Tx_MmForum_ViewHelpers_Forum_ForumIconViewHelper Extends Tx_Fluid_ViewHelpers_CObjectViewHelper {



		/**
		 * The frontend user repository.
		 * @var Tx_MmForum_Domain_Repository_User_FrontendUserRepository
		 */
	Protected $frontendUserRepository = NULL;





		/**
		 *
		 * Initializes this view helper.
		 * @return void
		 *
		 */

	Public Function initialize() {
		parent::initialize();
		$this->frontendUserRepository =&
			t3lib_div::makeInstance('Tx_MmForum_Domain_Repository_User_FrontendUserRepository');
	}



		/**
		 *
		 * Initializes the view helper arguments.
		 * @return void
		 *
		 */

	Public Function initializeArguments() { }



		/**
		 *
		 * Renders the forum icon.
		 *
		 * @param Tx_MmForum_Domain_Model_Forum_Forum $forum
		 *                             The forum for which the icon is to be rendered.
		 * @param  integer $width      Image width
		 * @param   string $alt        Alt text
		 * @return  string             The rendered icon.
		 * 
		 */
	
	Public Function render(Tx_MmForum_Domain_Model_Forum_Forum $forum=NULL, $width=NULL, $alt="") {
		Return parent::render('plugin.tx_mmforum.settings.icons.forum', $this->getDataArray($forum));
	}



		/**
		 *
		 * Generates a data array that will be passed to the typoscript object for
		 * rendering the icon.
		 * @param  Tx_MmForum_Domain_Model_Forum_Forum $forum
		 *                             The topic for which the icon is to be displayed.
		 * @return array               The data array for the typoscript object.
		 *
		 */

	Protected Function getDataArray(Tx_MmForum_Domain_Model_Forum_Forum $forum=NULL) {
		If($forum === NULL) Return Array();
		Else {
			$user =& $this->frontendUserRepository->findCurrent();
			Return Array( 'new'       => !$forum->hasBeenReadByUser($user),
			              'closed'    => !$forum->checkNewPostAccess($user) );
		}
	}

}

?>
