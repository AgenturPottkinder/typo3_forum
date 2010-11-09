<?php

Class Tx_MmForum_Domain_Repository_User_FrontendUserRepository
	Extends Tx_Extbase_Domain_Repository_FrontendUserRepository {

	Public Function findCurrent() {

		$currentUserUid = $GLOBALS['TSFE']->fe_user->user['uid'];
		Return $currentUserUid ? $this->findByUid($currentUserUid) : NULL;

	}

}

?>
