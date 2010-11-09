<?php

Abstract Class Tx_MmForum_Domain_Factory_AbstractFactory
	Implements t3lib_Singleton {

		/**
		 * @var Tx_MmForum_Domain_Repository_User_FrontendUserRepository
		 */
	Protected $userRepository = NULL;

	Protected Function getClassName() {
		$thisClass = get_class($this);
		$thisClass = preg_replace('/_Factory_/', '_Model_', $thisClass);
		$thisClass = preg_replace('/Factory$/', '', $thisClass);
		Return $thisClass;
	}

	Protected Function getClassInstance() {
		Return t3lib_div::makeInstance($this->getClassName());
	}

	Protected Function getCurrentUser() {
		If($this->userRepository === NULL)
			$this->userRepository =&
				t3lib_div::makeInstance('Tx_MmForum_Domain_Repository_User_FrontendUserRepository');
		Return $this->userRepository->findCurrent();
	}

}

?>