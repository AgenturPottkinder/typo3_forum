<?php

/***************************************************************
*  Copyright notice
*
*  (c) 2010 Martin Helmich <m.helmich@mittwald.de>, Mittwald CM Service
*  			
*  All rights reserved
*
*  This script is part of the TYPO3 project. The TYPO3 project is
*  free software; you can redistribute it and/or modify
*  it under the terms of the GNU General Public License as published by
*  the Free Software Foundation; either version 2 of the License, or
*  (at your option) any later version.
*
*  The GNU General Public License can be found at
*  http://www.gnu.org/copyleft/gpl.html.
*
*  This script is distributed in the hope that it will be useful,
*  but WITHOUT ANY WARRANTY; without even the implied warranty of
*  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
*  GNU General Public License for more details.
*
*  This copyright notice MUST APPEAR in all copies of the script!
***************************************************************/

/**
 * Controller for the Userfield object
 *
 * @version $Id$
 * @copyright Copyright belongs to the respective authors
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 */

// TODO: As your extension matures, you should use Tx_Extbase_MVC_Controller_ActionController as base Class, instead of the ScaffoldingController used below.
Class Tx_MmForum_Controller_UserfieldController Extends Tx_ExtbaseKickstarter_Scaffolding_AbstractScaffoldingController {
	
	/**
	 * @var Tx_MmForum_Domain_Repository_UserfieldRepository
	 */
	Protected $userfieldRepository;

	/**
	 * Initializes the current action
	 *
	 * @return void
	 */
	Protected Function initializeAction() {
		$this->userfieldRepository = t3lib_div::makeInstance('Tx_MmForum_Domain_Repository_UserfieldRepository');
	}
	##TOKEN FOR SCAFFOLDING. Will be replaced by the necessary actions for Create, Read, Update and Delete queries by the kickstarter, when using scaffold2file.
	# DO NOT REMOVE THIS TOKEN!##
	

	
}
?>