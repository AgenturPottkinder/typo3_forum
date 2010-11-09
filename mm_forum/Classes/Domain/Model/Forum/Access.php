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
 * Access
 *
 * @version $Id$
 * @copyright Copyright belongs to the respective authors
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 */
Class Tx_MmForum_Domain_Model_Forum_Access Extends Tx_Extbase_DomainObject_AbstractValueObject {

	Const LOGIN_LEVEL_EVERYONE=0;
	Const LOGIN_LEVEL_ANYLOGIN=1;
	Const LOGIN_LEVEL_SPECIFIC=2;

	/**
	 * operation
	 * @var string
	 * @validate NotEmpty
	 */
	Protected $operation;
	
	/**
	 * negate
	 * @var boolean
	 */
	Protected $negate;

	/**
	 * @var integer
	 */
	Protected $loginLevel;
	
	/**
	 * forum
	 * @var Tx_MmForum_Domain_Model_Forum_Forum
	 */
	Protected $forum;
	
	/**
	 * group
	 * @var Tx_MmForum_Domain_Model_User_FrontendUserGroup
	 */
	Protected $group;
	
	

	/**
	 * Getter for operation
	 *
	 * @return string operation
	 */
	Public Function getOperation() {
		Return $this->operation;
	}

	/**
	 * Getter for negate
	 *
	 * @return boolean negate
	 */
	Public Function getNegated() {
		Return $this->negate;
	}
	
	/**
	 * Returns the boolean state of negate
	 *
	 * @return boolean The state of negate
	 */
	Public Function isNegated() {
		Return $this->negate;
	}

	/**
	 * Getter for forum
	 *
	 * @return Tx_MmForum_Domain_Model_Forum_Forum forum
	 */
	Public Function getForum() {
		Return $this->forum;
	}

	/**
	 * Getter for group
	 *
	 * @return Tx_MmForum_Domain_Model_User_FrontendUserGroup group
	 */
	Public Function getGroup() {
		Return $this->group;
	}
	
	Public Function isEveryone(){
		Return $this->loginLevel == Tx_MmForum_Domain_Model_Forum_Access::LOGIN_LEVEL_EVERYONE;
	}
	
	Public Function isAnyLogin() {
		Return $this->loginLevel == Tx_MmForum_Domain_Model_Forum_Access::LOGIN_LEVEL_ANYLOGIN;
	}





	/*
	 * SETTERS
	 */





	/**
	 * Setter for operation
	 *
	 * @param string $operation operation
	 * @return void
	 */
	Public Function setOperation($operation) {
		$this->operation = $operation;
	}
	
	/**
	 * Setter for negate
	 *
	 * @param boolean $negate negate
	 * @return void
	 */
	Public Function setNegated($negate) {
		$this->negate = $negate;
	}

	/**
	 * Setter for group
	 *
	 * @param Tx_MmForum_Domain_Model_User_FrontendUserGroup $group group
	 * @return void
	 */
	Public Function setGroup(Tx_MmForum_Domain_Model_User_FrontendUserGroup $group) {
		$this->group = $group;
	}
	
}
?>
