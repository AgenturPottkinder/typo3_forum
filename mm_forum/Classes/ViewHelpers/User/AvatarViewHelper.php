<?php

Class Tx_MmForum_ViewHelpers_User_AvatarViewHelper Extends Tx_Fluid_ViewHelpers_ImageViewHelper {

	Public Function  initializeArguments() {
		parent::initializeArguments();
	}

		/**
		 *
		 * @param Tx_MmForum_Domain_Model_User_FrontendUser $user 
		 * @param integer $width
		 * @param integer $height
		 * 
		 */
	Public Function render(Tx_MmForum_Domain_Model_User_FrontendUser $user, $width=NULL, $height=NULL) {

		If($user->getImage()) {

		} Else {
			$src = t3lib_extMgm::siteRelPath('mm_forum').'Resources/Public/Images/Icons/AvatarEmpty.png';
		}

		Return parent::render($src, $width, $height);

	}

}

?>
