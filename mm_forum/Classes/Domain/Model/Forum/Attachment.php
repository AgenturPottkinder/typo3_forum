<?php

Class Tx_MmForum_Domain_Model_Forum_Attachment Extends Tx_Extbase_DomainObject_AbstractEntity {

		/*
		 * ATTRIBUTES
		 */

		/**
		 * @var string
		 */
	Protected $filename;

		/**
		 * @var string
		 */
	Protected $mimeType;

		/**
		 * @var integer
		 */
	Protected $downloadCount;

		/*
		 * GETTERS
		 */

	Public Function getFilename() {
		Return $this->filename;
	}

	Public Function getAbsoluteFilename() {
		global $TCA;
		$GLOBALS['TSFE']->includeTCA();
		t3lib_div::loadTCA('tx_mmforum_domain_model_forum_attachment');

		$uploadPath = $TCA['tx_mmforum_domain_model_forum_attachment']['columns']['filename']['config']['uploadfolder'];
		Return $uploadPath . $this->getFilename();
	}

	Public Function getFilesize() {
		Return filesize($this->getAbsoluteFilename());
	}

	Public Function getMimeType() {
		Return $this->mimeType;
	}

	Public Function getDownloadCount() {
		Return $this->downloadCount;
	}

		/*
		 * SETTERS
		 */

	Public Function setFilename($filename) {
		$this->filename = $filename;
	}

	Public Function setMimeType($mimeType) {
		$this->mimeType = $mimeType;
	}

	Public Function increateDownloadCount() {
		$this->downloadCount ++;
	}

}

?>