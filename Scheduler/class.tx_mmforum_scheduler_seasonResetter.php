<?php
/*                                                                    - *
 *  COPYRIGHT NOTICE                                                    *
 *                                                                      *
 *  (c) 2013 Ruven Fehling <r.fehling@mittwald.de>                     *
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
 * Lorem Ipsum
 *
 * @author	Ruven Fehling <r.fehling@mittwald.de>
 * @package	TYPO3
 * @subpackage	typo3_forum
 */
class tx_typo3forum_scheduler_seasonResetter extends \TYPO3\CMS\Scheduler\Task\AbstractTask {


	/**
	 * @var int
	 */
	protected $userPid;


	/**
	 * @return int
	 */
	public function getUserPid() {
		return $this->userPid;
	}


	/**
	 * @param int $userPid
	 */
	public function setUserPid($userPid) {
		$this->userPid = $userPid;
	}



	/**
	 * @return bool
	 */
	public function execute() {
		if(intval($this->getUserPid()) == 0) return false;

		$updateArray= array(
			'tx_typo3forum_helpful_count_season' => 0,
			'tx_typo3forum_post_count_season' => 0,
		);
		$query = $GLOBALS['TYPO3_DB']->UPDATEquery('fe_users','pid='.intval($this->getUserPid()),$updateArray);
		$res = $GLOBALS['TYPO3_DB']->sql_query($query);
		if($res == false) {
			return false;
		} else {
			return true;
		}
	}



}

if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/typo3_forum/Scheduler/class.tx_typo3forum_scheduler_seasonResetter.php'])	{
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/typo3_forum/Scheduler/class.tx_typo3forum_scheduler_seasonResetter.php']);
}