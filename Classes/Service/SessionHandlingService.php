<?php
namespace Mittwald\MmForum\Service;


class SessionHandlingService implements t3lib_Singleton {

	Public function set($key, $object){
		$sessionData = serialize($object);
		$GLOBALS['TSFE']->fe_user->setKey('ses', 'tx_mmforum_'.$key, $sessionData);
		$GLOBALS['TSFE']->fe_user->storeSessionData();
		return $this;
	}
	Public function get($key){
		$sessionData = $GLOBALS['TSFE']->fe_user->getKey('ses', 'tx_mmforum_'.$key);
		return unserialize($sessionData);
	}
}
?>