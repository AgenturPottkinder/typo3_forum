<?php
namespace Mittwald\MmForum\Domain\Model\Format;


/*                                                                    - *
 *  COPYRIGHT NOTICE                                                    *
 *                                                                      *
 *  (c) 2012 Martin Helmich <m.helmich@mittwald.de>                     *
 *           Mittwald CM Service GmbH & Co KG                           *
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



/**
 *
 * An abstract text parser element. This may later be a bb code, a smilie or anything
 * you want.
 *
 * @author     Martin Helmich <m.helmich@mittwald.de>
 * @package    MmForum
 * @subpackage Domain_Model_Format
 * @version    $Id$
 * @license    GNU public License, version 2
 *             http://opensource.org/licenses/gpl-license.php
 *
 */
abstract class AbstractTextParserElement
	extends \TYPO3\CMS\Extbase\DomainObject\AbstractValueObject {



	/*
	 * ATTRIBUTES
	 */



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



	/*
	  * GETTER METHODS
	  */



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
	 * Gets the text parser element name.
	 * @return string The text parser element name
	 *
	 */
	public function getName() {
		return $this->name;
	}



	/*
	 * SETTERS
	 */



	/**
	 *
	 * Sets the icon CSS class.
	 * @param string $iconClass The icon CSS class.
	 *
	 */
	public function setIconClass($iconClass) {
		$this->iconClass = $iconClass;
	}



	/**
	 *
	 * Sets the element name.
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

?>
