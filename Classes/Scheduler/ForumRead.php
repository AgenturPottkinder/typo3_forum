<?php
namespace Mittwald\Typo3Forum\Scheduler;

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

use TYPO3\CMS\Scheduler\Task\AbstractTask;

/**
 * Check for any user which forum is read and which not. Best way to ensure performance.
 */
class ForumRead extends AbstractTask {

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
		if ($this->getForumPid() == false || $this->getUserPid() == false) return false;

		$limit = 86400;

		$query = 'SELECT t.forum, COUNT(*) AS topic_amount
					  FROM tx_typo3forum_domain_model_forum_topic AS t
					  WHERE t.pid=' . (int)$this->getForumPid() . '
					  GROUP BY t.forum';
		$forumRes = $GLOBALS['TYPO3_DB']->sql_query($query);
		while ($forumRow = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($forumRes)) {
			$query = "SELECT uid FROM tx_typo3forum_domain_model_forum_topic WHERE forum=" . $forumRow['forum'];
			$topicRes = $GLOBALS['TYPO3_DB']->sql_query($query);
			$topics = [];
			while ($topicRow = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($topicRes)) {
				$topics[] = $topicRow['uid'];
			}

			$query = 'SELECT fe.uid, COUNT(*) AS read_amount
					  FROM fe_users AS fe
					  LEFT JOIN tx_typo3forum_domain_model_user_readtopic AS rt ON rt.uid_local = fe.uid
								AND rt.uid_foreign IN (' . implode(',', $topics) . ')
					  WHERE fe.disable=0 AND fe.deleted=0 AND fe.tx_extbase_type="\Mittwald\Typo3Forum\Domain\Model\User\FrontendUser"
						AND fe.pid=' . (int)$this->getUserPid() . ' AND fe.lastlogin > ' . (time() - $limit) . '
						GROUP BY fe.uid';
			$userRes = $GLOBALS['TYPO3_DB']->sql_query($query);
			while ($userRow = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($userRes)) {
				//First delete all entries for a user to resolve duplicate primaries
				$query = "DELETE FROM tx_typo3forum_domain_model_user_readforum
										WHERE uid_local=" . (int)$userRow['uid'] . '
											 AND uid_foreign=' . (int)$forumRow['forum'];
				$GLOBALS['TYPO3_DB']->sql_query($query);

				if ($forumRow['topic_amount'] == $userRow['read_amount']) {
					$insert = [
						'uid_local' => $userRow['uid'],
						'uid_foreign' => $forumRow['forum'],

					];
					$query = $GLOBALS['TYPO3_DB']->INSERTquery('tx_typo3forum_domain_model_user_readforum', $insert);
					$GLOBALS['TYPO3_DB']->sql_query($query);
				}
			}
		}

		return TRUE;
	}
}