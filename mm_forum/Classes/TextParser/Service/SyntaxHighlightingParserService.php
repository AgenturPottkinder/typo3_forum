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
	 * Text parser class for parsing syntax highlighting.
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

Class Tx_MmForum_TextParser_Service_SyntaxHighlightingParserService
	Extends Tx_MmForum_TextParser_Service_AbstractTextParserService {





		/**
		 * An instance of the GeSHi class.
		 * @var GeSHi
		 */
	Protected $geshi;





		/**
		 *
		 * Creates a new instance of this class.
		 *
		 */

	Public Function __construct() {
		parent::__construct();
		Require_Once t3lib_extMgm::extPath('mm_forum').'Resources/Private/Libraries/GeSHi/geshi.php';
		$this->geshi = New GeSHi();
	}



		/**
		 *
		 * Renders the parsed text.
		 *
		 * @param  string $text The text to be parsed.
		 * @return string       The parsed text.
		 *
		 */

	Public Function getParsedText($text) {
		Return preg_replace_callback(
			',\[code language=([a-z0-9]+)\](.*?)\[\/code\],is',
			array($this,'parseSourceCode'),
			$text
		);
	}



		/**
		 *
		 * Callback function that renders each source code block.
		 *
		 * @param  array $matches PCRE matches.
		 * @return string         The rendered source code block.
		 *
		 */
	
	Protected Function parseSourceCode($matches) {
		$this->geshi->set_language(trim($matches[1]));
		$this->geshi->set_source(trim($matches[2]));
		Return $this->geshi->parse_code();
	}

}

?>
