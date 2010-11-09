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
	 * A syntax highlighting element. This class implements the abstract
	 * AbstractTextParserElement class.
	 *
	 * @author     Martin Helmich <m.helmich@mittwald.de>
	 * @package    MmForum
	 * @subpackage Domain_Model_Format
	 * @version    $Id$
	 * @license    GNU Public License, version 2
	 *             http://opensource.org/licenses/gpl-license.php
	 *
	 */

Class Tx_MmForum_Domain_Model_Format_SyntaxHighlighting
	Extends Tx_MmForum_Domain_Model_Format_BBCode {



		/**
		 * The language key.
		 * @var string
		 */

	Protected $language;



		/**
		 *
		 * Return the left (opening) bb code tag.
		 * @return string The left bb code tag.
		 *
		 */

	Public Function getLeftBBCode() {
		Return "[code language={$this->language}]\n";
	}



		/**
		 *
		 * Return the right (closing) bb code tag.
		 * @return string The right bb code tag.
		 *
		 */

	Public Function getRightBBCode() {
		Return '[/code]';
	}



}

?>
