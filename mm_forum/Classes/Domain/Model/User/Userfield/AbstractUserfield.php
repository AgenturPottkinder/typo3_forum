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
 * Additional user fields
 *
 * @version $Id$
 * @copyright Copyright belongs to the respective authors
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 */
Abstract Class Tx_MmForum_Domain_Model_User_FrontendUser_Userfield_AbstractUserfield Extends Tx_Extbase_DomainObject_AbstractValueObject {
	
	/**
	 * Name of the userfield
	 * @var string
	 * @validate NotEmpty
	 */
	Protected $name;
	
	
	
	/**
	 * Setter for name
	 *
	 * @param string $name Name of the userfield
	 * @return void
	 */
	Public Function setName($name) {
		$this->name = $name;
	}

	/**
	 * Getter for name
	 *
	 * @return string Name of the userfield
	 */
	Public Function getName() {
		Return $this->name;
	}
	
}
?>
