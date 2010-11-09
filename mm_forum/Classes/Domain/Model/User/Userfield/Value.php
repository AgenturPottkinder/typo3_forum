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
	 * A userfield value. This class models an association between userfields,
	 * users and a specific userfield value.
	 *
	 * @author     Martin Helmich <m.helmich@mittwald.de>
	 * @package    MmForum
	 * @subpackage Domain_Model_User_Userfield
	 * @version    $Id$
	 *
	 * @copyright  2010 Martin Helmich <m.helmich@mittwald.de>
	 *             Mittwald CM Service GmbH & Co. KG
	 *             http://www.mittwald.de
	 * @license    GNU Public License, version 2
	 *             http://opensource.org/licenses/gpl-license.php
	 *
	 */

Class Tx_MmForum_Domain_Model_User_Userfield_Value
	Extends Tx_Extbase_DomainObject_AbstractValueObject {





		/*
		 * ATTRIBUTES
		 */





		/**
		 * The userfield.
		 * @var Tx_MmForum_Domain_Model_User_Userfield_AbstractUserfield
		 */
	Protected $userfield;

		/**
		 * The user.
		 * @var Tx_MmForum_Domain_Model_User_FrontendUser
		 */
	Protected $user;

		/**
		 * The value.
		 * @var string
		 */
	Protected $value;





		/*
		 * GETTER METHODS
		 */





		/**
		 *
		 * Gets the userfield.
		 * @return Tx_MmForum_Domain_Model_User_Userfield_AbstractUserfield The userfield.
		 *
		 */

	Public Function getUserfield() { Return $this->userfield; }



		/**
		 *
		 * Gets the user.
		 * @return Tx_MmForum_Domain_Model_User_FrontendUser The user
		 *
		 */
	Public Function getUser() { Return $this->user; }



		/**
		 *
		 * Gets the value.
		 * @return string The value
		 *
		 */
	
	Public Function getValue() { Return $this->value; }

}

?>