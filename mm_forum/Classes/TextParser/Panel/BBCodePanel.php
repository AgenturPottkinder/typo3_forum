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
	 * Panel for rendering bb code buttons.
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

Class Tx_MmForum_TextParser_Panel_BBCodePanel
	Extends Tx_MmForum_TextParser_Panel_DefaultPanel {





		/*
		 * ATTRIBUTES
		 */





		/**
		 * The bb code repository.
		 * @var Tx_MmForum_Domain_Repository_Format_BBCodeRepository
		 */
	Protected $bbCodeRepository = NULL;





		/*
		 * INITIALIZATION
		 */





		/**
		 *
		 * Creates a new instance of this object.
		 *
		 */

	Public Function __construct() {
		parent::__construct();
		$this->bbCodeRepository =&
			t3lib_div::makeInstance('Tx_MmForum_Domain_Repository_Format_BBCodeRepository');
	}





		/*
		 * HELPER METHODS
		 */





		/**
		 *
		 * Gets all items that are to be rendered.
		 * @return array<Tx_MmForum_Domain_Model_Format_AbstractTextParserElement>
		 *                             All items that are to be rendered.
		 *
		 */

	Protected Function getItems() { Return $this->bbCodeRepository->findAll(); }



		/**
		 *
		 * Builds the onclick function of a textparser item.
		 *
		 * @param  Tx_MmForum_Domain_Model_Format_AbstractTextParserElement $item
		 *                             The item for which to build the onclick function.
		 * @return string              The onclick function.
		 *
		 */

	Protected Function buildOnclickFunction(Tx_MmForum_Domain_Model_Format_AbstractTextParserElement $item) {
		If(!$item InstanceOf Tx_MmForum_Domain_Model_Format_BBCode)
			Throw New Tx_Extbase_Object_InvalidClass (
				"Instance of Tx_MmForum_Domain_Model_Format_BBCode expected, ".get_class($item)." given!", 1288188529);

		Return '$(\'#'.$this->editorId.'\').wrapSelection(\''
			. $this->escapeBBCode($item->getLeftBBCode()).'\',\''
			. $this->escapeBBCode($item->getRightBBCode()).'\');';
	}



		/**
		 *
		 * Escapes bb codes for display in javascript code.
		 * @param  string $bbCode The bb code that is to be escaped.
		 * @return string         The escaped bb code.
		 *
		 */

	Protected Function escapeBBCode($bbCode) { Return str_replace("\n",'\n',$bbCode); }

}

?>