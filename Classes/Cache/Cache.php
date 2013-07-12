<?php

/*                                                                      *
 *  COPYRIGHT NOTICE                                                    *
 *                                                                      *
 *  (c) 2012 Martin Helmich <m.helmich@mittwald.de>                     *
 *           Mittwald CM Service GmbH & Co KG                           *
 *           All rights reserved                                        *
 *                                                                      *
 *  This script is part of the TYPO3 project. The TYPO3 project is      *
 *  free software; you can redistribute it and/or modify                *
 *  it under the terms of the GNU General Public License as published   *
 *  by the Free Software Foundation; either version 2 of the License,   *
 *  or (at your option) any later version.                              *
 *                                                                      *
 *  The GNU General Public License can be found at                      *
 *  http://www.gnu.org/copyleft/gpl.html.                               *
 *                                                                      *
 *  This script is distributed in the hope that it will be useful,      *
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of      *
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the       *
 *  GNU General Public License for more details.                        *
 *                                                                      *
 *  This copyright notice MUST APPEAR in all copies of the script!      *
 *                                                                      */



/**
 *
 * This class provides access to the TYPO3 caching framework to the mm_forum components.
 * Basically, this class is just a very thin wrapper around the TYPO3 caching framework.
 * It encapsulated creation and retrieval of the appropriate caches and can be very
 * easily obtained using dependency injection.
 *
 * @author     Martin Helmich <m.helmich@mittwald.de>
 * @package    MmForum
 * @subpackage Cache
 * @version    $Id$
 *
 * @copyright  2012 Martin Helmich <m.helmich@mittwald.de>
 *             Mittwald CM Service GmbH & Co. KG
 *             http://www.mittwald.de
 * @license    GNU Public License, version 2
 *             http://opensource.org/licenses/gpl-license.php
 *
 */

class Tx_MmForum_Cache_Cache implements \TYPO3\CMS\Core\SingletonInterface {



	protected $cacheInstance = NULL;



	public function __construct() {
		\TYPO3\CMS\Core\Cache\Cache::initializeCachingFramework();
		try {
			$this->cacheInstance = $GLOBALS['typo3CacheManager']->getCache('mmforum_main');
		} catch (\TYPO3\CMS\Core\Cache\Exception\NoSuchCacheException $e) {
			$this->cacheInstance = $GLOBALS['typo3CacheFactory']->create('mmforum_main',
			                                                             $GLOBALS['TYPO3_CONF_VARS']['SYS']['caching']['cacheConfigurations']['mmforum_main']['frontend'],
			                                                             $GLOBALS['TYPO3_CONF_VARS']['SYS']['caching']['cacheConfigurations']['mmforum_main']['backend'],
			                                                             $GLOBALS['TYPO3_CONF_VARS']['SYS']['caching']['cacheConfigurations']['mmforum_main']['options']);
		}
	}



	public function has($identifier) {
		return $this->cacheInstance->has($identifier);
	}



	public function get($identifier) {
		return $this->cacheInstance->get($identifier);
	}



	public function set($identifier, $value, array $tags = array(), $lifetime = NULL) {
		$this->cacheInstance->set($identifier, $value, $tags, $lifetime);
	}



	public function flush() {
		$this->cacheInstance->flush();
	}

}