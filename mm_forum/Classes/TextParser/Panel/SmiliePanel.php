<?php

Class Tx_MmForum_TextParser_Panel_SmiliePanel
	Extends Tx_MmForum_TextParser_Panel_DropdownPanel {

	Protected $smilieRepository;

	Public Function __construct() {
		$this->smilieRepository =&
			#t3lib_div::makeInstance
	}

	Protected Function getItems() {

	}

}

?>