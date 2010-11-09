<?php

Class Tx_MmForum_ViewHelpers_Authentication_IfLoggedInViewHelper
	Extends Tx_Fluid_Core_ViewHelper_AbstractViewHelper {

	Public Function render() {
		If($GLOBALS['TSFE']->fe_user->user['uid']) {
			Return $this->renderChildren();
		} Else Return '';
	}

}

?>
