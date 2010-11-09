<?php

Class Tx_MmForum_Domain_Model_Format_BBCode
	Extends Tx_MmForum_Domain_Model_Format_AbstractTextParserElement {

		/**
		 * @var string
		 */
	Protected $regularExpression;

		/**
		 * @var string
		 */
	Protected $regularExpressionReplacement;

		/**
		 * Get the regular expression
		 * @return string
		 */
	Public Function getRegularExpression() {
		Return $this->regularExpression;
	}
	
	Public Function getRegularExpressionReplacement() {
		Return $this->regularExpressionReplacement;
	}

}

?>
