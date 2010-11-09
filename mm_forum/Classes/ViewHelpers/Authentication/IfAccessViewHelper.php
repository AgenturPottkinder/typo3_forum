<?php

Class Tx_MmForum_ViewHelpers_Authentication_IfAccessViewHelper
	Extends Tx_Fluid_Core_ViewHelper_AbstractViewHelper {

		/**
		 * @var Tx_MmForum_Domain_Repository_User_FrontendUserRepository
		 */
	Protected $frontendUserRepository;

	Public Function  initialize() {
		parent::initialize();
		$this->frontendUserRepository =& t3lib_div::makeInstance('Tx_MmForum_Domain_Repository_User_FrontendUserRepository');
	}

		/**
		 *
		 * @param Tx_MmForum_Domain_Model_AccessibleInterface $forum
		 * @param string $accessType
		 * @return string
		 * 
		 */
	Public Function render(Tx_MmForum_Domain_Model_AccessibleInterface $object, $accessType='read') {
		Return $object->_checkAccess($this->frontendUserRepository->findCurrent(), $accessType)
			? $this->renderChildren() : '';
	}

}

?>
