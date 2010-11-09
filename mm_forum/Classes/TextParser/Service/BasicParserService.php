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
	 * Text parser class that performs basic text parsing functions, such as
	 * HTML escaping, line formatting, etc.
	 *
	 * @author     Martin Helmich <m.helmich@mittwald.de>
	 * @package    MmForum
	 * @subpackage TextParser_Service
	 * @version    $Id$
	 *
	 * @copyright  2010 Martin Helmich <m.helmich@mittwald.de>
	 *             Mittwald CM Service GmbH & Co. KG
	 *             http://www.mittwald.de
	 * @license    GNU Public License, version 2
	 *             http://opensource.org/licenses/gpl-license.php
	 *
	 */

Class Tx_MmForum_TextParser_Service_BasicParserService
	Extends Tx_MmForum_TextParser_Service_AbstractTextParserService {





		/*
		 * ATTRIBUTES
		 */





		/**
		 * The text
		 * @var string
		 */
	Protected $text;

		/**
		 * Protected parts of the parsed text. In these parts, no parsing will be done.
		 * @var array
		 */
	Private $protectedParts = Array();





		/*
		 * CONSTRUCTOR
		 */





		/**
		 *
		 * Creates a new instance of this service.
		 *
		 */

	Public Function __construct() { parent::__construct(); }





		/*
		 * SERVICE METHODS
		 */





		/**
		 *
		 * Renders the parsed text.
		 *
		 * @param  string $text The text to be parsed.
		 * @return string       The parsed text.
		 *
		 */

	Public Function getParsedText($text) {
		$this->text = $text;

		$this->extractProtectedParts();
		$this->escape()
			->paragraphs()
			->lineBreaks();
		$this->restoreProtectedParts();

		Return $this->text;
	}





		/*
		 * HELPER METHODS
		 */





		/**
		 *
		 * Extracts all protected parts from the text and replaces them with placeholders.
		 * @return void
		 *
		 */

	Protected Function extractProtectedParts() {
		$pattern = ',\[code language=[a-z0-9]+\](.*?)\[\/code\],is';
		preg_match_all($pattern, $this->text, $this->protectedParts);
		$this->text = preg_replace($pattern, '###MMFORUM_PROTECTED###', $this->text);
	}



		/**
		 *
		 * Replaces all placeholders for protected parts with the original contents.
		 * @return void
		 *
		 */

	Protected Function restoreProtectedParts() {
		While(($s = strpos($this->text, '###MMFORUM_PROTECTED###')) !== FALSE) {
			$this->text = substr_replace($this->text, array_shift($this->protectedParts[0]), $s, strlen('###MMFORUM_PROTECTED###'));
		}
	}



		/**
		 *
		 * Performs simple HTML escaping on the text.
		 * @return Tx_MmForum_TextParser_Service_BasicParserService
		 *                             $this, for chaining
		 *
		 */

	Protected Function escape() {
		$this->text = htmlspecialchars($this->text); Return $this;
	}



		/**
		 *
		 * Replaces double line breaks with paragraphs.
		 * @return Tx_MmForum_TextParser_Service_BasicParserService
		 *                             $this, for chaining
		 *
		 */

	Protected Function paragraphs() {
		$this->text = str_replace("\r",'',$this->text);
		$this->text = preg_replace(';\n{2,};s',"\n\n",$this->text);

		$paragraphs = t3lib_div::trimExplode("\n\n", $this->text);
		$this->text = '<p>'.implode('</p><p>',$paragraphs).'</p>';
		Return $this;
	}



		/**
		 *
		 * Replaces single line breaks with <br> tags.
		 * @return Tx_MmForum_TextParser_Service_BasicParserService
		 *                             $this, for chaining
		 *
		 */

	Protected Function lineBreaks() {
		$this->text = $this->removeUnneccesaryLinebreaks($this->text);
		$this->text = nl2br($this->text);
		Return $this;
	}



		/**
		 *
		 * Removes superflous line breaks within the text.
		 * @param  string $text The text with linebreaks.
		 * @return string       The text with less linebreaks.
		 *
		 */

	Protected Function removeUnneccesaryLinebreaks($text) {
		$text = preg_replace(',(\[[a-z0-9 ]+\])\s*,is','$1', $text);
		Return $text;
	}

}

?>