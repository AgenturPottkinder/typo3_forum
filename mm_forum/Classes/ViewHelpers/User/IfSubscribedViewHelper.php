<?php

Class Tx_MmForum_ViewHelpers_User_IfSubscribedViewHelper
	Extends Tx_Fluid_ViewHelpers_IfViewHelper {

		/**
		 *
		 * @param Tx_MmForum_Domain_Model_SubscribeableInterface $object
		 * @param Tx_MmForum_Domain_Model_User_FrontendUser $user
		 * @return string
		 *
		 */
	Public Function render ( Tx_MmForum_Domain_Model_SubscribeableInterface $object,
	                         Tx_MmForum_Domain_Model_User_FrontendUser      $user = NULL ) {
		If($user === NULL)
			$user =& t3lib_div::makeInstance('Tx_MmForum_Domain_Repository_User_FrontendUserRepository')->findCurrent();

		#t3lib_div::debug($object->getSubscribers(), $object->getSubject()); ob_end_flush();

		ForEach($object->getSubscribers() As $subscriber) {
			If($subscriber->getUid() == $user->getUid()) Return $this->renderThenChild();
		} Return $this->renderElseChild();
	}

}

?>
