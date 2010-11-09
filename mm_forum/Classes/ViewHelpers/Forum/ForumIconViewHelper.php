<?php

Class Tx_MmForum_ViewHelpers_Forum_ForumIconViewHelper Extends Tx_Fluid_ViewHelpers_ImageViewHelper {

	Public Function initializeArguments() {
		$this->registerUniversalTagAttributes();
	}

		/**
		 *
		 * @param Tx_MmForum_Domain_Model_Forum_Forum $forum
		 * @param integer $width
		 * @param string $alt
		 * 
		 */
	Public Function render(Tx_MmForum_Domain_Model_Forum_Forum $forum, $width=NULL, $alt="") {
		$this->tag->addAttribute('alt', '');
		$src = t3lib_extMgm::siteRelPath('mm_forum').'Resources/Public/Images/Icons/Forum.png';
		Return parent::render($src, $width);
	}

}

?>
