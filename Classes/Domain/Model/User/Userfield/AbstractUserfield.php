<?php
namespace Mittwald\Typo3Forum\Domain\Model\User\Userfield;

/*                                                                      *
 *  COPYRIGHT NOTICE                                                    *
 *                                                                      *
 *  (c) 2015 Mittwald CM Service GmbH & Co KG                           *
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

use TYPO3\CMS\Extbase\DomainObject\AbstractValueObject;

/**
 * Abstract base class for additional user fields.
 */
abstract class AbstractUserfield extends AbstractValueObject {

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

	/**
	 * Gets the field name.
	 * @return string The field name.
	 */
	public function getName() {
		return $this->name;
	}

	/**
	 * Sets the userfield name.
	 *
	 * @param string $name Name of the userfield
	 *
	 * @return void
	 */
	public function setName($name) {
		$this->name = $name;
	}

	/**
	 * Determines the value for this userfield and a specific user.
	 *
	 * @param \Mittwald\Typo3Forum\Domain\Model\User\FrontendUser $user
	 *                             The user for which the value of this userfield is
	 *                             to be determined.
	 *
	 * @return string              The userfield value.
	 */
	public function getValueForUser(\Mittwald\Typo3Forum\Domain\Model\User\FrontendUser $user) {
		if ($this->isMappedToUserObject()) {
			$propertyNames = explode('|', $this->getUserObjectPropertyName());
			$propertyValues = [];
			foreach ($propertyNames as $propertyName) {
				$propertyValues[] = $user->_getProperty($propertyName);
			}

			return $propertyValues;
		} else {
			foreach ($user->getUserfieldValues() as $userfieldValue) {
				if ($userfieldValue->getUserfield() == $userfield) {
					return [$userfieldValue->getValue()];
				}
			}

			return NULL;
		}
	}

	/**
	 * Determines if this userfield is mapped to a FrontendUser property.
	 * @return boolean TRUE, if this userfield is mapped to a FrontendUser property,
	 *                 otherwise FALSE.
	 */
	public function isMappedToUserObject() {
		return $this->mapToUserObject !== NULL;
	}

	/**
	 * If this userfield is mapped to a FrontendUser property, this method gets the
	 * name of the property this userfield is mapped to.
	 * @return string The FrontendUser property name.
	 */
	public function getUserObjectPropertyName() {
		return $this->mapToUserObject;
	}

	/**
	 * Sets the FrontendUser property name.
	 *
	 * @param string $property The FrontendUser property name.
	 *
	 * @return void
	 */
	public function setUserObjectPropertyName($property = NULL) {
		$this->mapToUserObject = $property;
	}
}
