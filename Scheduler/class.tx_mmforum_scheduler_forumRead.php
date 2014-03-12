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

		$limit = 86400;

		$query = 'SELECT t.forum, COUNT(*) AS topic_amount
					  FROM tx_mmforum_domain_model_forum_topic AS t
					  WHERE t.pid='.intval($this->getForumPid()).'
					  GROUP BY t.forum';
		$forumRes = $GLOBALS['TYPO3_DB']->sql_query($query);
		while($forumRow = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($forumRes)) {
			$query = "SELECT uid FROM tx_mmforum_domain_model_forum_topic WHERE forum=".$forumRow['forum'];
			$topicRes = $GLOBALS['TYPO3_DB']->sql_query($query);
			$topics = array();
			while($topicRow = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($topicRes)) {
				$topics[] = $topicRow['uid'];
			}

			$query = 'SELECT fe.uid, COUNT(*) AS read_amount
					  FROM fe_users AS fe
					  LEFT JOIN tx_mmforum_domain_model_user_readtopic AS rt ON rt.uid_local = fe.uid
								AND rt.uid_foreign IN ('.implode(',',$topics).')
					  WHERE fe.disable=0 AND fe.deleted=0 AND fe.tx\\extbase\\type=\"Mittwald\\MmForum\\Domain\\Model\\User\\FrontendUser\"
						AND fe.pid='.intval($this->getUserPid()).' AND fe.lastlogin > '.(time()-$limit).'
						GROUP BY fe.uid';
			$userRes = $GLOBALS['TYPO3_DB']->sql_query($query);
			while($userRow = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($userRes)) {
				//First delete all entries for a user to resolve duplicate primaries
				$query = "DELETE FROM tx_mmforum_domain_model_user_readforum
										WHERE uid_local=".intval($userRow['uid']).'
											 AND uid_foreign='.intval($forumRow['forum']);
				$res = $GLOBALS['TYPO3_DB']->sql_query($query);

				if($forumRow['topic_amount'] == $userRow['read_amount']) {
					$insert = array(
						'uid_local'	  => $userRow['uid'],
						'uid_foreign' => $forumRow['forum'],

					);
					$query = $GLOBALS['TYPO3_DB']->INSERTquery('tx_mmforum_domain_model_user_readforum',$insert);
					$res = $GLOBALS['TYPO3_DB']->sql_query($query);
				}
			}
		}

		return true;
	}


}

if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/mm_forum/Scheduler/class.tx_mmforum_scheduler_forumRead.php'])	{
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/mm_forum/Scheduler/class.tx_mmforum_scheduler_forumRead.php']);
}