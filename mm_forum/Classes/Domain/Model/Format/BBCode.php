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
	 * A bb code element. This class implements the abstract AbstractTextParserElement
	 * class.
	 *
	 * @author     Martin Helmich <m.helmich@mittwald.de>
	 * @package    MmForum
	 * @subpackage Domain_Model_Format
	 * @version    $Id$
	 * @license    GNU Public License, version 2
	 *             http://opensource.org/licenses/gpl-license.php
	 *
	 */

Class Tx_MmForum_Domain_Model_Format_BBCode
	Extends Tx_MmForum_Domain_Model_Format_AbstractTextParserElement {





		/*
		 * ATTRIBUTES
		 */





		/**
		 * The regular expression that will be used to match the bb code.
		 * @var string
		 */
	Protected $regularExpression;

		/**
		 * The replacement pattern or the regular expression.
		 * @var string
		 */
	Protected $regularExpressionReplacement;

		/**
		 * The bb code wrap. This string specifies which bb codes are to be inserted into
		 * the post text by the bb code editor.
		 * @var string
		 */
	Protected $bbcodeWrap;





		/*
		 * GETTERS
		 */





		/**
		 *
		 * Get the regular expression.
		 * @return string The regular expression
		 *
		 */

	Public Function getRegularExpression() {
		Return $this->regularExpression;
	}



		/**
		 *
		 * Gets the replacement pattern for the regular expression.
		 * @return string The replacement pattern for the regular expression.
		 *
		 */

	Public Function getRegularExpressionReplacement() {
		Return $this->regularExpressionReplacement;
	}



		/**
		 *
		 * Return the left (opening) bb code tag.
		 * @return string The left bb code tag.
		 *
		 */

	Public Function getLeftBBCode() {
		Return array_shift(explode('|',$this->bbcodeWrap));
	}



		/**
		 *
		 * Return the right (closing) bb code tag.
		 * @return string The right bb code tag.
		 *
		 */

	Public Function getRightBBCode() {
		Return array_pop(explode('|',$this->bbcodeWrap));
	}

}

?>
