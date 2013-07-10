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
 * Check for any user which forum is read and which not. Best way to ensure performance.
 *
 * @author	Ruven Fehling <r.fehling@mittwald.de>
 * @package	TYPO3
 * @subpackage	mm_forum
 */
class tx_mmforum_scheduler_forumRead extends \TYPO3\CMS\Scheduler\Task\AbstractTask {

	/**
	 * @var int
	 */
	protected $forumPid;

	/**
	 * @var int
	 */
	protected $userPid;


	/**
	 * @return int
	 */
	public function getForumPid() {
		return $this->forumPid;
	}

	/**
	 * @return int
	 */
	public function getUserPid() {
		return $this->userPid;
	}

	/**
	 * @param int $forumPid
	 */
	public function setForumPid($forumPid) {
		$this->forumPid = $forumPid;
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
		if($this->getForumPid() == false || $this->getUserPid() == false) return false;

		$query = 'SELECT fe.uid
				  FROM fe_users AS fe
				  WHERE fe.disable=0 AND fe.deleted=0 AND fe.tx_extbase_type="Tx_MmForum_Domain_Model_User_FrontendUser"
				  		AND fe.pid='.intval($this->getUserPid()).' AND fe.lastlogin > '.(time()-86400);

		$userRes = $GLOBALS['TYPO3_DB']->sql_query($query);
		while($userRow = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($userRes)) {
			//First delete all entries for a user to resolve duplicate primaries
			$query = "DELETE FROM tx_mmforum_domain_model_user_readforum WHERE uid_local=".intval($userRow['uid']);
			$res = $GLOBALS['TYPO3_DB']->sql_query($query);

			//Now check all forum
			$query = 'SELECT t.forum
					  FROM tx_mmforum_domain_model_forum_topic AS t
					  INNER JOIN tx_mmforum_domain_model_user_readtopic AS rt ON rt.uid_foreign = t.uid
					  														   AND rt.uid_local='.intval($userRow['uid']).'
					  WHERE t.pid='.intval($this->getForumPid()).' AND t.deleted=0 AND t.hidden=0 AND t.author > 0
					  GROUP BY t.forum';

			$topicRes = $GLOBALS['TYPO3_DB']->sql_query($query);
			while($topicRow = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($topicRes)) {
				$insert = array(
					'uid_local'	  => $userRow['uid'],
					'uid_foreign' => $topicRow['forum'],

				);
				$res = $GLOBALS['TYPO3_DB']->exec_INSERTquery('tx_mmforum_domain_model_user_readforum',$insert);
			}
		}

		return true;
	}


}

if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/mm_forum/Scheduler/class.tx_mmforum_scheduler_forumRead.php'])	{
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/mm_forum/Scheduler/class.tx_mmforum_scheduler_forumRead.php']);
}