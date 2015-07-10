<?php
namespace Mittwald\Typo3Forum\Domain\Model\Format;

/*                                                                    - *
 *  COPYRIGHT NOTICE                                                    *
 *                                                                      *
 *  (c) 2015 Mittwald CM Service GmbH & Co KG                           *
 *           All rights reserved                                        *
 *                                                                      *
 *  This script is part of the TYPO3 project. The TYPO3 project is      *
 *  free software; you can redistribute it and/or modify                *
 *  it under the terms of the GNU General public License as published   *
 *  by the Free Software Foundation; either version 2 of the License,   *
 *  or (at your option) any later version.                              *
 *                                                                      *
 *  The GNU General public License can be found at                      *
 *  http://www.gnu.org/copyleft/gpl.html.                               *
 *                                                                      *
 *  This script is distributed in the hope that it will be useful,      *
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of      *
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the       *
 *  GNU General public License for more details.                        *
 *                                                                      *
 *  This copyright notice MUST APPEAR in all copies of the script!      *
 *                                                                      */

use TYPO3\CMS\Extbase\DomainObject\AbstractValueObject;

/**
 *
 * An abstract text parser element. This may later be a bb code, a smiley or anything
 * you want.
 */
abstract class AbstractTextParserElement extends AbstractValueObject {

	/**
	 * The CSS class that will be used to render this element's button.
	 * @var string
	 */
	protected $iconClass;

	/**
	 * The name of this element. Can also be a locallang label.
	 * @var string
	 */
	protected $name;

	/**
	 * The default icon directory. This may be overridden by subclasses.
	 * @var string
	 */
	protected $defaultIconDir = 'Editor/';

	/**
	 *
	 * Gets the icon filename.
	 * @return string The icon filename.
	 *
	 */
	public function getIconClass() {
		return $this->iconClass;
	}

	/**
	 *
	 * Sets the icon CSS class.
	 *
	 * @param string $iconClass The icon CSS class.
	 *
	 */
	public function setIconClass($iconClass) {
		$this->iconClass = $iconClass;
	}

	/**
	 *
	 * Gets the text parser element name.
	 * @return string The text parser element name
	 *
	 */
	public function getName() {
		return $this->name;
	}

	/**
	 *
	 * Sets the element name.
	 *
	 * @param string $name The element name.
	 *
	 */
	public function setName($name) {
		$this->name = $name;
	}

	/**
	 * @param string $defaultIconDir
	 */
	public function setDefaultIconDir($defaultIconDir) {
		$this->defaultIconDir = $defaultIconDir;
	}
}