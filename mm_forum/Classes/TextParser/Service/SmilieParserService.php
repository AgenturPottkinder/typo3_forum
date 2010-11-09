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
	 * Text parser class for parsing smilies.
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

Class Tx_MmForum_TextParser_Service_SmilieParserService
	Extends Tx_MmForum_TextParser_Service_AbstractTextParserService {





		/*
		 * ATTRIBUTES
		 */





		/**
		 * The smilie repository
		 * @var Tx_MmForum_Domain_Repository_Format_SmilieRepository
		 */
	Protected $smilieRepository;

		/**
		 * All smilies.
		 * @var array<Tx_MmForum_Domain_Model_Format_Smilie>
		 */
	Protected $smilies;





		/*
		 * METHODS
		 */





		/**
		 *
		 * Creates a new instance of this service.
		 *
		 */

	Public Function  __construct() {
		$this->smilieRepository =&
			t3lib_div::makeInstance('Tx_MmForum_Domain_Repository_Format_SmilieRepository');
		$this->smilies = $this->smilieRepository->findAll();
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
		ForEach($this->smilies As &$smilie) {
			$text = str_replace ( $smilie->getSmilieShortcut(),
			                      $this->getSmilieIcon($smilie), $text );
		} Return $text;
	}



		/**
		 *
		 * Renders a smilie icon.
		 * @param  Tx_MmForum_Domain_Model_Format_Smilie $smilie
		 *                             The smilie that is to be rendered.
		 * @return string              The smilie as HTML code.
		 *
		 */

	Protected Function getSmilieIcon(Tx_MmForum_Domain_Model_Format_Smilie $smilie) {
		Return '<img src="'.$smilie->getIconFilename().'" alt="'.$smilie->getSmilieShortcut().'" />';
	}

}

?>
