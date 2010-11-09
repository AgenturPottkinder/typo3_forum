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
	 * Abstract base class for additional user fields.
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

Abstract Class Tx_MmForum_Domain_Model_User_Userfield_AbstractUserfield Extends Tx_Extbase_DomainObject_AbstractValueObject {





		/*
		 * ATTRIBUTES
		 */





		/**
		 * The name of the userfield.
		 * @var string
		 * @validate NotEmpty
		 */
	Protected $name;

		/**
		 * A property name of the FrontendUser object. If this is set, this property will
		 * be read instead for this userfield.
		 * @var string
		 */
	Protected $mapToUserObject = NULL;





		/*
		 * GETTERS
		 */





		/**
		 *
		 * Gets the field name.
		 * @return string The field name.
		 *
		 */
	
	Public Function getName() { Return $this->name; }



		/**
		 *
		 * Determines if this userfield is mapped to a FrontendUser property.
		 * @return boolean TRUE, if this userfield is mapped to a FrontendUser property,
		 *                 otherwise FALSE.
		 *
		 */

	Public Function isMappedToUserObject() { Return $this->mapToUserObject !== NULL; }



		/**
		 *
		 * If this userfield is mapped to a FrontendUser property, this method gets the
		 * name of the property this userfield is mapped to.
		 * @return string The FrontendUser property name.
		 *
		 */
	
	Public Function getUserObjectPropertyName() { Return $this->mapToUserObject; }



		/**
		 *
		 * Determines the value for this userfield and a specific user.
		 * @param Tx_MmForum_Domain_Model_User_FrontendUser $user
		 *                             The user for which the value of this userfield is
		 *                             to be determined.
		 * @return string              The userfield value.
		 *
		 */

	Public Function getValueForUser(Tx_MmForum_Domain_Model_User_FrontendUser $user) {
		If($this->isMappedToUserObject()) {
			$propertyNames = explode('|',$this->getUserObjectPropertyName());
			$propertyValues = Array();
			ForEach($propertyNames As $propertyName)
				$propertyValues[] = $user->_getProperty ($propertyName);
			Return $propertyValues;
		} Else {
			ForEach($user->getUserfieldValues() as $userfieldValue) {
				If($userfieldValue->getUserfield() == $userfield) Return Array($userfieldValue->getValue());
			} Return NULL;
		}
	}





		/*
		 * SETTERS
		 */





		/**
		 *
		 * Sets the userfield name.
		 *
		 * @param string $name Name of the userfield
		 * @return void
		 *
		 */
	
	Public Function setName($name) { $this->name = $name; }



		/**
		 *
		 * Sets the FrontendUser property name.
		 *
		 * @param  string $property The FrontendUser property name.
		 * @return void
		 * 
		 */
	
	Public Function setUserObjectPropertyName($property=NULL) { $this->mapToUserObject = $property; }
	
}
?>