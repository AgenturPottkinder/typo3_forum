<?php

/*                                                                      *
 *  COPYRIGHT NOTICE                                                    *
 *                                                                      *
 *  (c) 2011 Martin Helmich <m.helmich@mittwald.de>                     *
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
	 * @author     Martin Helmich <m.helmich@mittwald.de>
	 * @package    MmForum
	 * @subpackage Cache
	 * @version    $Id: TextParserService.php 39978 2010-11-09 14:19:52Z mhelmich $
	 *
	 * @copyright  2010 Martin Helmich <m.helmich@mittwald.de>
	 *             Mittwald CM Service GmbH & Co. KG
	 *             http://www.mittwald.de
	 * @license    GNU Public License, version 2
	 *             http://opensource.org/licenses/gpl-license.php
	 *
	 */

class Tx_MmForum_Cache_CacheManager {
	
	protected $fileCachePath = 'typo3temp/mm_forum';
	
	public function clearAll() {
		
			# Neither the Extbase autoloader nor the TYPO3 internal autoloader
			# appear to be doing anything at this point, so we have to include
			# manually... :(
		require_once t3lib_extMgm::extPath('mm_forum').'Classes/Cache/Cache.php';
		$cache = t3lib_div::makeInstance('Tx_MmForum_Cache_Cache');
		$cache->flush();
		
		$this->deleteTemporaryFiles();
	}
	
	protected function deleteTemporaryFiles() {
		$files = glob(PATH_site.$this->fileCachePath.'/*');
		foreach($files as $file) unlink($file);
	}
	
}