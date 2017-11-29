<?php
namespace Mittwald\Typo3Forum\Cache;

/*                                                                    - *
 *  COPYRIGHT NOTICE                                                    *
 *                                                                      *
 *  (c) 2015 Mittwald CM Service GmbH & Co KG                           *
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

use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Object\ObjectManager;

class CacheManager {

	/**
	 * @var array
	 */
	protected $fileCachePaths = ['typo3temp/typo3_forum', 'typo3temp/typo3_forum/gravatar'];

	/**
	 *
	 */
	public function clearAll() {
		/** @var ObjectManager $objectManager */
		$objectManager = GeneralUtility::makeInstance(ObjectManager::class);
		$cache = $objectManager->get(Cache::class);
		$cache->flush();
		$this->deleteTemporaryFiles();
	}

	/**
	 *
	 */
	protected function deleteTemporaryFiles() {
		foreach ($this->fileCachePaths as $fileCachePath) {
			$files = glob(PATH_site . $fileCachePath . '/*');

			if(!is_array($files)) {
				// skip
				continue;
			}

			foreach ($files as $file) {
				if (is_file($file)) {
					unlink($file);
				}
			}
		}
	}
}
