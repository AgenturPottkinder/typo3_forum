<?php
namespace Mittwald\Typo3Forum\Service;
class SessionHandlingService implements \TYPO3\CMS\Core\SingletonInterface {

	Public function set($key, $object){
		$sessionData = serialize($object);
		$GLOBALS['TSFE']->fe_user->setKey('ses', 'tx_typo3forum_'.$key, $sessionData);
		$GLOBALS['TSFE']->fe_user->storeSessionData();
		return $this;
	}
	Public function get($key){
		$sessionData = $GLOBALS['TSFE']->fe_user->getKey('ses', 'tx_typo3forum_'.$key);
		return unserialize($sessionData);
	}
}
?>