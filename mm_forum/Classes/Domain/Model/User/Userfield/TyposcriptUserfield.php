<?php

Class Tx_MmForum_Domain_Model_User_Userfield_TyposcriptUserfield
	Extends Tx_MmForum_Domain_Model_User_FrontendUser_Userfield_AbstractUserfield {

		/**
		 * @var string
		 */
	Protected $typoscriptPath;

		/**
		 * Gets the typoscript path
		 * @return string
		 */
	Public Function getTyposcriptPath() {
		Return $this->typoscriptPath;
	}

}

?>
