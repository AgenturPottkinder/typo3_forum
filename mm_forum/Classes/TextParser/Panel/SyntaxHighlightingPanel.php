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
	 * Panel for displaying syntax highlighting buttons.
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

Class Tx_MmForum_TextParser_Panel_SyntaxHighlightingPanel
	Extends Tx_MmForum_TextParser_Panel_BBCodePanel {



		/**
		 * The syntax highlighting repository.
		 * @var Tx_MmForum_Domain_Repository_Format_SyntaxHighlightingRepository
		 */

	Protected $syntaxHighlightingRepository;



		/**
		 *
		 * Creates a new instance of this panel
		 *
		 */

	Public Function __construct() {
		$this->syntaxHighlightingRepository =&
			t3lib_div::makeInstance('Tx_MmForum_Domain_Repository_Format_SyntaxHighlightingRepository');
	}

	

		/**
		 *
		 * Gets all items that are to be rendered.
		 * @return array<Tx_MmForum_Domain_Model_Format_AbstractTextParserElement>
		 *                             All items that are to be rendered.
		 *
		 */

	Protected Function getItems() {
		Return $this->syntaxHighlightingRepository->findAll();
	}

}

?>