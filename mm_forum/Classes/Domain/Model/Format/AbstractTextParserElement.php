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
	 * An abstract text parser element. This may later be a bb code, a smilie or anything
	 * you want.
	 *
	 * @author     Martin Helmich <m.helmich@mittwald.de>
	 * @package    MmForum
	 * @subpackage Domain_Model_Format
	 * @version    $Id$
	 * @license    GNU Public License, version 2
	 *             http://opensource.org/licenses/gpl-license.php
	 *
	 */

Abstract Class Tx_MmForum_Domain_Model_Format_AbstractTextParserElement
	Extends Tx_Extbase_DomainObject_AbstractValueObject {





		/*
		 * ATTRIBUTES
		 */





		/**
		 * The icon that is to be used for this text parser element.
		 * @var string
		 */
	Protected $icon;

		/**
		 * The name of this element. Can also be a locallang label.
		 * @var string
		 */
	Protected $name;

		/**
		 * The default icon directory. This may be overridden by subclasses.
		 * @var string
		 */
	Protected $defaultIconDir = 'Editor/';





		/*
		 * GETTER METHODS
		 */





		/**
		 *
		 * Gets the icon filename.
		 * @return string The icon filename.
		 *
		 */

	Public Function getIcon() { Return $this->icon; }



		/**
		 *
		 * Gets the text parser element name.
		 * @return string The text parser element name
		 *
		 */

	Public Function getName() { Return $this->name; }



		/**
		 *
		 * Gets the full icon filename. This method first looks for the filename in the
		 * upload directory that is configured in the TCA; as a fallback, this method
		 * looks in the /Resources folder in the extension directory.
		 *
		 * @return string The absolute icon filename.
		 *
		 */

	Public Function getIconFilename() {
		global $TCA;
		$GLOBALS['TSFE']->includeTCA();
		t3lib_div::loadTCA('tx_mmforum_domain_model_format_textparser');

		$uploadPaths = Array(
			$TCA['tx_mmforum_domain_model_format_textparser']['columns']['icon']['config']['uploadfolder'],
			t3lib_extMgm::siteRelPath('mm_forum').'Resources/Public/Images/Icons/'.$this->defaultIconDir
		);

		ForEach($uploadPaths As $path)
			If(file_exists($path.$this->getIcon())) Return $path . $this->getIcon();
		Throw New Tx_MmForum_Domain_Exception_TextParser_Exception("The file {$this->getIcon()} could not be found! I looked in: ".print_r($uploadPaths, TRUE), 1288178589);
	}





		/*
		 * SETTERS
		 */





		/**
		 *
		 * Sets the icon filename.
		 * @param string $icon The icon filename.
		 *
		 */

	Public Function setIcon($icon) {
		$this->icon = $icon;
	}



		/**
		 *
		 * Sets the element name.
		 * @param string $name The element name.
		 *
		 */
	
	Public Function setName($name) {
		$this->name = $name;
	}

}

?>
