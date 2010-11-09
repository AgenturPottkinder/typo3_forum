<?php

Class Tx_MmForum_ViewHelpers_Forum_TopicIconViewHelper Extends Tx_Fluid_ViewHelpers_ImageViewHelper {

	Public Function initializeArguments() {
		$this->registerUniversalTagAttributes();
	}

		/**
		 *
		 * @param Tx_MmForum_Domain_Model_Forum_Topic $topic
		 * @param integer $width
		 * 
		 */
	Public Function render(Tx_MmForum_Domain_Model_Forum_Topic $topic, $width=NULL) {
		$this->tag->addAttribute('alt', '');
		$src = t3lib_extMgm::siteRelPath('mm_forum').'Resources/Public/Images/Icons/Topic.png';
		Return parent::render($src, $width);
	}

}

?>
