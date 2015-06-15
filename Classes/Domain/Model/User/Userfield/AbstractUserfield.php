<?php
namespace Mittwald\Typo3Forum\Domain\Model\User\Userfield;
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
 * @package    Typo3Forum
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

abstract class AbstractUserfield extends \TYPO3\CMS\Extbase\DomainObject\AbstractValueObject {



	/*
		  * ATTRIBUTES
		  */



	/**
	 * The name of the userfield.
	 * @var string
	 * @validate NotEmpty
	 */
	protected $name;


	/**
	 * A property name of the FrontendUser object. If this is set, this property will
	 * be read instead for this userfield.
	 * @var string
	 */
	protected $mapToUserObject = NULL;



	/*
		  * GETTERS
		  */



	/**
	 *
	 * Gets the field name.
	 * @return string The field name.
	 *
	 */

	public function getName() {
		return $this->name;
	}



	/**
	 *
	 * Determines if this userfield is mapped to a FrontendUser property.
	 * @return boolean TRUE, if this userfield is mapped to a FrontendUser property,
	 *                 otherwise FALSE.
	 *
	 */

	public function isMappedToUserObject() {
		return $this->mapToUserObject !== NULL;
	}



	/**
	 *
	 * If this userfield is mapped to a FrontendUser property, this method gets the
	 * name of the property this userfield is mapped to.
	 * @return string The FrontendUser property name.
	 *
	 */

	public function getUserObjectPropertyName() {
		return $this->mapToUserObject;
	}



	/**
	 *
	 * Determines the value for this userfield and a specific user.
	 * @param \Mittwald\Typo3Forum\Domain\Model\User\FrontendUser $user
	 *                             The user for which the value of this userfield is
	 *                             to be determined.
	 * @return string              The userfield value.
	 *
	 */

	public function getValueForUser(\Mittwald\Typo3Forum\Domain\Model\User\FrontendUser $user) {
		if ($this->isMappedToUserObject()) {
			$propertyNames  = explode('|', $this->getUserObjectPropertyName());
			$propertyValues = array();
			foreach ($propertyNames as $propertyName) {
				$propertyValues[] = $user->_getProperty($propertyName);
			}
			return $propertyValues;
		} else {
			foreach ($user->getUserfieldValues() as $userfieldValue) {
				if ($userfieldValue->getUserfield() == $userfield) {
					return array($userfieldValue->getValue());
				}
			}
			return NULL;
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

	public function setName($name) {
		$this->name = $name;
	}



	/**
	 *
	 * Sets the FrontendUser property name.
	 *
	 * @param  string $property The FrontendUser property name.
	 * @return void
	 *
	 */

	public function setUserObjectPropertyName($property = NULL) {
		$this->mapToUserObject = $property;
	}

}

?>