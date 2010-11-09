<?php

Class Tx_MmForum_Domain_Model_Format_SyntaxHighlighting
	Extends Tx_MmForum_Domain_Model_Format_AbstractTextParserElement {

		/**
		 * @var string
		 */
	Protected $smilieShortcut;

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

	Public Function getParsedText($text) {
		Return preg_replace ( $this->getRegularExpression(),
		                      $this->getRegularExpressionReplacement(),
		                      $text );
	}

}

?>
