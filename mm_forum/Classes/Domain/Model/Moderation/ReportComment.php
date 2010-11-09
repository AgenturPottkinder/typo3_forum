<?php

Class Tx_MmForum_Domain_Model_Moderation_ReportComment
	Extends Tx_Extbase_DomainObject_AbstractEntity {

		/**
		 * @var Tx_MmForum_Domain_Model_User_FrontendUser
		 */
	Protected $author;

		/**
		 * @var string
		 */
	Protected $text;

		/**
		 * @var Tx_MmForum_Domain_Model_Moderation_Report
		 */
	Protected $report;

		/*
		 * GETTERS
		 */

	Public Function getAuthor() {
		Return $this->author;
	}

	Public Function getText() {
		Return $this->text;
	}

	Public Function getReport() {
		Return $this->report;
	}

		/*
		 * SETTERS
		 */

	Public Function setAuthor(Tx_MmForum_Domain_Model_User_FrontendUser $author) {
		$this->author = $author;
	}

	Public Function setText($text) {
		$this->text = $text;
	}

}

?>
