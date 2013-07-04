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
 * @subpackage	mm_forum
 */
class tx_mmforum_scheduler_notification extends \TYPO3\CMS\Scheduler\Task\AbstractTask {

	/**
	 * @var int
	 */
	protected $forumPid;

	/**
	 * @var int
	 */
	protected $userPid;

	/**
	 * @var int
	 */
	protected $lastExecutedCron = 0;

	/**
	 * @return int
	 */
	public function getForumPid() {
		return intval($this->forumPid);
	}

	/**
	 * @return int
	 */
	public function getUserPid() {
		return intval($this->userPid);
	}

	/**
	 * @return int
	 */
	public function getLastExecutedCron() {
		return intval($this->lastExecutedCron);
	}

	/**
	 * @param int $forumPid
	 */
	public function setForumPid($forumPid) {
		$this->forumPid = intval($forumPid);
	}

	/**
	 * @param int $userPid
	 */
	public function setUserPid($userPid) {
		$this->userPid = intval($userPid);
	}

	/**
	 * @param int $lastExecutedCron
	 * @return void
	 */
	public function setLastExecutedCron($lastExecutedCron) {
		$this->lastExecutedCron = $lastExecutedCron;
	}


	/**
	 * @return bool
	 */
	public function execute() {
		$this->setLastExecutedCron(intval($this->findLastCronExecutionDate()));

		$query = 'SELECT t.uid
				  FROM tx_mmforum_domain_model_forum_topic AS t
				  INNER JOIN tx_mmforum_domain_model_forum_post AS p ON p.uid = t.last_post
				  WHERE t.pid = '.intval($this->getForumPid()).' AND t.deleted=0 AND p.crdate > '.$this->getLastExecutedCron().'
				  GROUP BY t.uid';
		$topicRes = $GLOBALS['TYPO3_DB']->sql_query($query);
		$executedOn = time();
		while($topicRow = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($topicRes)) {
			$involvedUser = $this->getUserInvolvedInTopic($topicRow['uid']);
			$query = 'SELECT *
					  FROM tx_mmforum_domain_model_forum_post
					  WHERE topic='.intval($topicRow['uid']).' AND deleted=0 AND pid='.$this->getForumPid().'
					  		AND crdate > '.$this->getLastExecutedCron();
			$postRes = $GLOBALS['TYPO3_DB']->sql_query($query);
			while($postRow = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($postRes)) {
				foreach($involvedUser AS $userUid) {
					$insert = array(
						'crdate'	=> $executedOn,
						'feuser'	=> intval($userUid),
						'post'		=> intval($postRow['uid']),
						'type'		=> 'Tx_MmForum_Domain_Model_Forum_Post',

					);
					$res = $GLOBALS['TYPO3_DB']->exec_INSERTquery('tx_mmforum_domain_model_user_notification',$insert);
				}
			}
		}
		return true;
	}


	/**
	 * Get the CrDate of the last inserted notification
	 * @return int
	 */
	private function findLastCronExecutionDate() {
		$query = 'SELECT crdate
				  FROM tx_mmforum_domain_model_user_notification
				  WHERE pid='.intval($this->getUserPid()).'
				  ORDER BY crdate DESC
				  LIMIT 1';
		$res = $GLOBALS['TYPO3_DB']->sql_query($query);
		$row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res);
		return intval($row['crdate']);
	}

	/**
	 * Get all users who are involved in this topic
	 * @param int $topicUid
	 * @return array
	 */
	private function getUserInvolvedInTopic($topicUid) {
		$user = array();
		$query = 'SELECT DISTINCT author
				  FROM tx_mmforum_domain_model_forum_post
				  WHERE pid='.intval($this->getUserPid()).' AND deleted=0 AND crdate > '.$this->getLastExecutedCron();
		$res = $GLOBALS['TYPO3_DB']->sql_query($query);
		while($row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res)) {
			$user[] = intval($row['author']);
		}
		return $user;
	}


}

if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/mm_forum/Scheduler/class.tx_mmforum_scheduler_notification.php'])	{
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/mm_forum/Scheduler/class.tx_mmforum_scheduler_notification.php']);
}