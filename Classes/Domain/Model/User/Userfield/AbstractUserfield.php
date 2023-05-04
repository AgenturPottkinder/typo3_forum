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

use TYPO3\CMS\Extbase\Annotation\Validate;
use TYPO3\CMS\Extbase\DomainObject\AbstractValueObject;

/**
 * Abstract base class for additional user fields.
 */
abstract class AbstractUserfield extends AbstractValueObject
{
    /**
     * The name of the userfield.
     * @Validate("NotEmpty")
     */
    protected string $name;

    /**
     * A property name of the FrontendUser object. If this is set, this property will
     * be read instead for this userfield.
     */
    protected ?string $mapToUserObject;

    /**
     * Gets the field name.
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Sets the userfield name.
     */
    public function setName($name): self
    {
        $this->name = $name;

        return $this;
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
    public function getValuesForUser(\Mittwald\Typo3Forum\Domain\Model\User\FrontendUser $user): array
    {
        if ($this->isMappedToUserObject()) {
            $propertyNames = explode('|', $this->getUserObjectPropertyName());
            $propertyValues = [];
            foreach ($propertyNames as $propertyName) {
                $propertyValues[] = $user->_getProperty($propertyName);
            }

            return $propertyValues;
        }
        foreach ($user->getUserfieldValues() as $userfieldValue) {
            if ($userfieldValue->getUserfield() == $this) {
                return [$userfieldValue->getValue()];
            }
        }

        return null;
    }

    /**
     * Determines if this userfield is mapped to a FrontendUser property.
     * @return bool TRUE, if this userfield is mapped to a FrontendUser property,
     *                 otherwise FALSE.
     */
    public function isMappedToUserObject(): bool
    {
        return $this->mapToUserObject !== null;
    }

    /**
     * If this userfield is mapped to a FrontendUser property, this method gets the
     * name of the property this userfield is mapped to.
     */
    public function getUserObjectPropertyName(): ?string
    {
        return $this->mapToUserObject;
    }

    /**
     * Sets the FrontendUser property name.
     */
    public function setUserObjectPropertyName(?string $property = null): self
    {
        $this->mapToUserObject = $property;

        return $this;
    }
}
