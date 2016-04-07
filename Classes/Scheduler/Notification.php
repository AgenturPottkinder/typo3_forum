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

class Notification extends AbstractTask {

	/**
	 * @var string
	 */
	protected $forumPids;

	/**
	 * @var string
	 */
	protected $userPids;

	/**
	 * @var int
	 */
	protected $notificationPid;

	/**
	 * @var int
	 */
	protected $lastExecutedCron = 0;

	/**
	 * @var int
	 */
	protected $executedOn = 0;

	/**
	 * @return string
	 */
	public function getForumPids() {
		return $this->forumPids;
	}

	/**
	 * @return string
	 */
	public function getUserPids() {
		return $this->userPids;
	}

	/**
	 * @return int
	 */
	public function getNotificationPid() {
		return $this->notificationPid;
	}


	/**
	 * @return int
	 */
	public function getLastExecutedCron() {
		return (int)$this->lastExecutedCron;
	}


	/**
	 * @return int
	 */
	public function getExecutedOn() {
		return (int)$this->executedOn;
	}

	/**
	 * @param string $forumPids
	 */
	public function setForumPids($forumPids) {
		$this->forumPids = $forumPids;
	}

	/**
	 * @param string $userPids
	 */
	public function setUserPids($userPids) {
		$this->userPids = $userPids;
	}

	/**
	 * @param int $notificationPid
	 */
	public function setNotificationPid($notificationPid) {
		$this->notificationPid = $notificationPid;
	}

	/**
	 * @param int $lastExecutedCron
	 * @return void
	 */
	public function setLastExecutedCron($lastExecutedCron) {
		$this->lastExecutedCron = $lastExecutedCron;
	}


	/**
	 * @param int $executedOn
	 * @return void
	 */
	public function setExecutedOn($executedOn) {
		$this->executedOn = $executedOn;
	}

	/**
	 * @return bool
	 */
	public function execute() {
		if ($this->getForumPids() == false || $this->getUserPids() == false) return false;

		$this->setLastExecutedCron((int)$this->findLastCronExecutionDate());
		$this->setExecutedOn(time());

		$this->checkPostNotifications();
		$this->checkTagsNotification();

		return true;
	}

	/**
	 * @return void
	 */
	private function checkPostNotifications() {

		$query = 'SELECT t.uid
				  FROM tx_typo3forum_domain_model_forum_topic AS t
				  INNER JOIN tx_typo3forum_domain_model_forum_post AS p ON p.uid = t.last_post
				  WHERE t.pid IN (' . $this->getForumPids() . ') AND t.deleted=0
				  		AND p.crdate > ' . $this->getLastExecutedCron() . '
				  GROUP BY t.uid
				  ORDER BY t.last_post DESC';

		$topicRes = $GLOBALS['TYPO3_DB']->sql_query($query);

		while ($topicRow = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($topicRes)) {
			$involvedUser = $this->getUserInvolvedInTopic($topicRow['uid']);
			$query = 'SELECT uid, author
					  FROM tx_typo3forum_domain_model_forum_post
					  WHERE topic=' . (int)$topicRow['uid'] . ' AND crdate > ' . $this->getLastExecutedCron() . '
					  	 	AND deleted=0 AND pid IN (' . $this->getForumPids() . ')';
			$postRes = $GLOBALS['TYPO3_DB']->sql_query($query);
			while ($postRow = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($postRes)) {
				foreach ($involvedUser as $user) {
					if ($user['author'] == $postRow['author']) continue;
					if ($user['firstPostOfUser'] > $postRow['uid']) continue;

					$insert = [
						'crdate' => $this->getExecutedOn(),
						'pid' => $this->getNotificationPid(),
						'feuser' => (int)$user['author'],
						'post' => (int)$postRow['uid'],
						'type' => '\Mittwald\Typo3Forum\Domain\Model\Forum\Post',
						'user_read' => (($this->getLastExecutedCron() == 0) ? 1 : 0)

					];
					$GLOBALS['TYPO3_DB']->exec_INSERTquery('tx_typo3forum_domain_model_user_notification', $insert);
				}
			}
		}
	}

	/**
	 * @return boolean
	 */
	private function checkTagsNotification() {
		$query = 'SELECT tg.uid AS tagUid, t.uid AS topicUid
				 FROM tx_typo3forum_domain_model_forum_tag AS tg
				 INNER JOIN tx_typo3forum_domain_model_forum_tag_topic AS mm ON mm.uid_foreign = tg.uid
				 INNER JOIN tx_typo3forum_domain_model_forum_topic AS t ON t.uid = mm.uid_local
				 INNER JOIN tx_typo3forum_domain_model_forum_post AS p ON p.uid = t.last_post
				 WHERE tg.deleted=0 AND t.deleted=0 AND tg.pid IN (' . $this->getForumPids() . ')
				 	   AND p.crdate > ' . $this->getLastExecutedCron() . '
				 ORDER BY t.last_post DESC';
		$tagsRes = $GLOBALS['TYPO3_DB']->sql_query($query);

		while ($tagsRow = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($tagsRes)) {
			$subscribedTagUser = [];
			$query = 'SELECT fe.uid
					  FROM tx_typo3forum_domain_model_forum_tag AS tg
					  INNER JOIN tx_typo3forum_domain_model_forum_tag_user AS mm ON mm.uid_local = tg.uid
					  INNER JOIN fe_users AS fe ON fe.uid = mm.uid_foreign
					  WHERE tg.uid=' . (int)$tagsRow['tagUid'];
			$userRes = $GLOBALS['TYPO3_DB']->sql_query($query);
			while ($userRow = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($userRes)) {
				$subscribedTagUser[] = $userRow['uid'];
			}

			$query = 'SELECT *
						  FROM tx_typo3forum_domain_model_forum_post AS p
						  WHERE p.topic=' . (int)$tagsRow['topicUid'] . ' AND p.deleted=0 AND p.author > 0
						  		AND p.crdate > ' . (int)$this->getLastExecutedCron() . ' AND pid IN (' . $this->getForumPids() . ')';
			$postRes = $GLOBALS['TYPO3_DB']->sql_query($query);
			while ($postRow = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($postRes)) {
				foreach ($subscribedTagUser as $userUid) {

					if ($postRow['author'] == $userUid) continue;

					$insert = [
						'crdate' => $this->getExecutedOn(),
						'pid' => $this->getNotificationPid(),
						'feuser' => (int)$userUid,
						'post' => (int)$postRow['uid'],
						'tag' => (int)$tagsRow['tagUid'],
						'type' => '\Mittwald\Typo3Forum\Domain\Model\Forum\Tag',
						'user_read' => (($this->getLastExecutedCron() == 0) ? 1 : 0)

					];
					$GLOBALS['TYPO3_DB']->exec_INSERTquery('tx_typo3forum_domain_model_user_notification', $insert);
				}
			}
		}
		return TRUE;
	}

	/**
	 * Get the CrDate of the last inserted notification
	 * @return int
	 */
	private function findLastCronExecutionDate() {
		$query = 'SELECT crdate
				  FROM tx_typo3forum_domain_model_user_notification
				  WHERE pid =' . $this->getNotificationPid() . '
				  ORDER BY crdate DESC
				  LIMIT 1';
		$res = $GLOBALS['TYPO3_DB']->sql_query($query);
		$row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res);
		return (int)$row['crdate'];
	}

	/**
	 * Get all users who are involved in this topic
	 * @param int $topicUid
	 * @return array
	 */
	private function getUserInvolvedInTopic($topicUid) {
		$user = [];
		$query = 'SELECT author, uid
				  FROM tx_typo3forum_domain_model_forum_post
				  WHERE pid IN (' . $this->getForumPids() . ') AND deleted=0 AND author > 0
				  		AND topic=' . (int)$topicUid . '
				  GROUP BY author';
		$res = $GLOBALS['TYPO3_DB']->sql_query($query);
		while ($row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res)) {
			$user[] = [
				'author' => (int)$row['author'],
				'firstPostOfUser' => (int)$row['uid'],
			];
		}
		return $user;
	}
}
