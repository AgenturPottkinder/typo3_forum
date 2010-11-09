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
	 * Models a single ACL entry. This entry grants or denies access to a specific
	 * operation (read, write posts, create topics...) to a single user group. These
	 * ACL entries can be assigned to any Tx_MmForum_Domain_Model_Forum_Forum object and
	 * are inherited down the forum tree unto each single post.
	 *
	 * Every object that implements the Tx_MmForum_Domain_Model_AccessibleInterface
	 * provides methods to check the ACLs of the parent forums.
	 *
	 * @author     Martin Helmich <m.helmich@mittwald.de>
	 * @package    MmForum
	 * @subpackage Domain_Model_Forum
	 * @version    $Id$
	 * @license    GNU Public License, version 2
	 *             http://opensource.org/licenses/gpl-license.php
	 *
	 */

Class Tx_MmForum_Domain_Model_Forum_Access Extends Tx_Extbase_DomainObject_AbstractValueObject {





		/*
		 * CONSTANTS
		 */





		/**
		 * Anyone.
		 */
	Const LOGIN_LEVEL_EVERYONE=0;

		/**
		 * Any logged in user
		 */
	Const LOGIN_LEVEL_ANYLOGIN=1;

		/**
		 * A specifiy user group
		 */
	Const LOGIN_LEVEL_SPECIFIC=2;





		/*
		 * ATTRIBUTES
		 */





		/**
		 * The operation that is to be granted or denied.
		 * @var string
		 */
	Protected $operation;
	
		/**
		 * Whether this entry is negated
		 * @var boolean
		 */
	Protected $negate;

		/**
		 * The required login level. See the LOGIN_LEVEL_* constants.
		 * @var integer
		 */
	Protected $loginLevel;
	
		/**
		 * The forum this ACL entry belongs to.
		 * @var Tx_MmForum_Domain_Model_Forum_Forum
		 */
	Protected $forum;
	
		/**
		 * The user group that is affected by this ACL entry. This property is only
		 * relevant if $loginLevel == LOGIN_LEVEL_SPECIFIC.
		 * @var Tx_MmForum_Domain_Model_User_FrontendUserGroup
		 */
	Protected $affected_group;
	




		/*
		 * GETTER METHODS
		 */





		/**
		 *
		 * Gets the affected operation.
		 * @return string The affected operation.
		 *
		 */

	Public Function getOperation() { Return $this->operation; }



		/**
		 *
		 * Determines if this ACL entry is negated.
		 * @return boolean TRUE, if this entry is negated.
		 *
		 */

	Public Function getNegated() { Return $this->negate; }



		/**
		 *
		 * Determines if this ACL entry is negated.
		 * @return boolean TRUE, if this entry is negated.
		 *
		 */

	Public Function isNegated() {
		Return $this->negate;
	}



		/**
		 *
		 * Gets the forum for this entry.
		 * @return Tx_MmForum_Domain_Model_Forum_Forum The forum for this entry.
		 *
		 */

	Public Function getForum() {
		Return $this->forum;
	}



		/**
		 *
		 * Gets the group for this entry.
		 * @return Tx_MmForum_Domain_Model_User_FrontendUserGroup group The group
		 *
		 */

	Public Function getGroup() {
		Return $this->affected_group;
	}



		/**
		 *
		 * Determines whether this entry affects all visitors.
		 * @return boolean TRUE, when this entry affects all visitors, otherwise FALSE.
		 *
		 */

	Public Function isEveryone(){
		Return $this->loginLevel == Tx_MmForum_Domain_Model_Forum_Access::LOGIN_LEVEL_EVERYONE;
	}



		/**
		 *
		 * Determines whether this entry requires any login.
		 * @return boolean TRUE when this entry requires any login, otherwise FALSE.
		 *
		 */

	Public Function isAnyLogin() {
		Return $this->loginLevel == Tx_MmForum_Domain_Model_Forum_Access::LOGIN_LEVEL_ANYLOGIN;
	}





		/*
		 * SETTERS
		 */





		/**
		 *
		 * Sets the affected operation.
		 * @param string $operation The affected operation
		 * @return void
		 *
		 */

	Public Function setOperation($operation) {
		$this->operation = $operation;
	}



		/**
		 *
		 * Negates this entry.
		 * @param boolean $negate TRUE to negate
		 * @return void
		 *
		 */
	Public Function setNegated($negate) {
		$this->negate = $negate;
	}



		/**
		 *
		 * Sets the group.
		 * @param Tx_MmForum_Domain_Model_User_FrontendUserGroup $group The group
		 * @return void
		 *
		 */
	
	Public Function setGroup(Tx_MmForum_Domain_Model_User_FrontendUserGroup $group) {
		$this->affected_group = $group;
	}
	
}
?>
