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
	 * Abstract base class for all kinds of text parsing services.
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

Class Tx_MmForum_TextParser_Service_BBCodeParserService
	Extends Tx_MmForum_TextParser_Service_AbstractTextParserService {
	
	
	
	
	
		/*
		 * ATTRIBUTES
		 */





		/**
		 * The bb code repository.
		 * @var Tx_MmForum_Domain_Repository_Format_BBCodeRepository
		 */
	Protected $bbCodeRepository;

		/**
		 * All bb codes.
		 * @var array<Tx_MmForum_Domain_Model_Format_BBCode>
		 */
	Protected $bbCodes;





		/*
		 * METHODS
		 */





		/**
		 *
		 * Creates a new instance of this service.
		 *
		 */

	Public Function __construct() {
		parent::__construct();
		$this->bbCodeRepository =&
			t3lib_div::makeInstance('Tx_MmForum_Domain_Repository_Format_BBCodeRepository');
		$this->bbCodes =& $this->bbCodeRepository->findAll();
	}



		/**
		 *
		 * Parses the text. Replaces all bb codes in the text with appropriate HTML tags.
		 *
		 * @param  string $text The text that is to be parsed.
		 * @return string       The parsed text.
		 *
		 */

	Public Function getParsedText($text) {
		ForEach($this->bbCodes As $bbCode) {
			If(   $bbCode InstanceOf Tx_MmForum_Domain_Model_Format_QuoteBBCode
			   || $bbCode InstanceOf Tx_MmForum_Domain_Model_Format_ListBBCode) Continue;
			$text = preg_replace($bbCode->getRegularExpression(), $bbCode->getRegularExpressionReplacement(), $text);
		}
		Return $text;
	}

}

?>