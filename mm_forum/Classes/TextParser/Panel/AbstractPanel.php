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
	 * Abstract base class for editor panels.
	 *
	 * @author     Martin Helmich <m.helmich@mittwald.de>
	 * @package    MmForum
	 * @subpackage TextParser_Panel
	 * @version    $Id$
	 *
	 * @copyright  2010 Martin Helmich <m.helmich@mittwald.de>
	 *             Mittwald CM Service GmbH & Co. KG
	 *             http://www.mittwald.de
	 * @license    GNU Public License, version 2
	 *             http://opensource.org/licenses/gpl-license.php
	 *
	 */

Abstract Class Tx_MmForum_TextParser_Panel_AbstractPanel {





		/*
		 * ATTRIBUTES
		 */





		/**
		 * The panel's title.
		 * @var string
		 */
	Protected $title;

		/**
		 * The settings array. Needs to be injected using the "injectSettings" method.
		 * @var array
		 */
	Protected $settings;

		/**
		 * The html-id of the editor textarea.
		 * @var string
		 */
	Protected $editorId;





		/*
		 * INITIALIZATION
		 */





		/**
		 *
		 * Creates a new instance of this panel.
		 *
		 */

	Public Function  __construct() { }

		/**
		 *
		 * Injects the settings array.
		 * @param  string $settings The settings array.
		 * @return void
		 *
		 */

	Public Function injectSettings($settings) {
		$this->settings = $settings;
		If($settings['title']) $this->title = $settings['title'];
	}



		/**
		 *
		 * Sets the editor id.
		 * @param  string $editorId The editor id.
		 * @return void
		 *
		 */

	Public Function setEditorId($editorId) {
		$this->editorId = $editorId;
	}





		/*
		 * PUBLIC METHODS
		 */





		/**
		 *
		 * Renders the panel.
		 * @return string
		 *
		 */

	Abstract Public Function render();



		/**
		 *
		 * Gets all items that are to be rendered.
		 * @return array<Tx_MmForum_Domain_Model_Format_AbstractTextParserElement>
		 *                             All items that are to be rendered.
		 *
		 */

	Abstract Protected Function getItems();



		/**
		 *
		 * Gets the -- localized -- title of this panel.
		 * @return string The title of this panel.
		 *
		 */

	Public Function getTitle() {
		$localizedTitle = Tx_Extbase_Utility_Localization::translate($this->title, 'MmForum');
		Return $localizedTitle ? $localizedTitle : $this->title;
	}





		/*
		 * HELPER METHODS
		 */





		/**
		 *
		 * Generates an item button for a specific text parser item.
		 * @param  Tx_MmForum_Domain_Model_Format_AbstractTextParserElement $item
		 *                             The item that is to be rendered.
		 * @return string              The rendered item.
		 *
		 */

	Protected Function buildItemButton(Tx_MmForum_Domain_Model_Format_AbstractTextParserElement $item) {
		$filename = $item->getIconFilename();
		$name     = $item->getName();
		$onclick  = $this->buildOnclickFunction($item);

		Return '<img src="'.$filename.'" title="'.$name.'" alt="'.$name.'" onclick="'.$onclick.'" />';
	}



		/**
		 *
		 * Builds the onclick function of a textparser item.
		 *
		 * @param  Tx_MmForum_Domain_Model_Format_AbstractTextParserElement $item
		 *                             The item for which to build the onclick function.
		 * @return string              The onclick function.
		 *
		 */

	Abstract Protected Function buildOnclickFunction(Tx_MmForum_Domain_Model_Format_AbstractTextParserElement $item);

}

?>