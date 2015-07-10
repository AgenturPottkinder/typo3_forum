<?php
namespace Mittwald\Typo3Forum\Service;

use TYPO3\CMS\Core\SingletonInterface;

class SessionHandlingService implements SingletonInterface {

	public function set($key, $object) {
		$sessionData = serialize($object);
		$GLOBALS['TSFE']->fe_user->setKey('ses', 'tx_typo3forum_' . $key, $sessionData);
		$GLOBALS['TSFE']->fe_user->storeSessionData();
		return $this;
	}

	public function get($key) {
		$sessionData = $GLOBALS['TSFE']->fe_user->getKey('ses', 'tx_typo3forum_' . $key);
		return unserialize($sessionData);
	}
}

?>