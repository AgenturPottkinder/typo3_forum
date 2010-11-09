<?php

Abstract Class Tx_MmForum_Domain_Repository_AbstractRepository
	Extends Tx_Extbase_Persistence_Repository {

	Protected Function getPidList() {
		$extbaseFrameworkConfiguration = Tx_Extbase_Dispatcher::getExtbaseFrameworkConfiguration();
		Return t3lib_div::intExplode(',', $extbaseFrameworkConfiguration['persistence']['storagePid']);
	}

}

?>
