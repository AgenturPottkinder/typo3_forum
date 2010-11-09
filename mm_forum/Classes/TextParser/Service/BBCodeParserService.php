<?php

Class Tx_MmForum_TextParser_Service_BBCodeParserService
	Extends Tx_MmForum_TextParser_Service_AbstractTextParserService {
	
		/**
		 * @var Tx_MmForum_Domain_Repository_Format_BBCodeRepository
		 */
	Protected $bbCodeRepository;

	Protected $bbCodes;

	Public Function __construct() {
		$this->bbCodeRepository =&
			t3lib_div::makeInstance('Tx_MmForum_Domain_Repository_Format_BBCodeRepository');
		$this->bbCodes =& $this->bbCodeRepository->findAll();
	}

	Public Function getParsedText($text) {
		ForEach($this->bbCodes As $bbCode)
			$text = preg_replace($bbCode->getRegularExpression(), $bbCode->getRegularExpressionReplacement(), $text);
		Return $text;
	}

}

?>