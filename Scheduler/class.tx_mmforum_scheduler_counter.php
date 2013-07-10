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
 * A turtle with maths skills checks all counter columns of any user and update them on a error.
 *
 * @author	Ruven Fehling <r.fehling@mittwald.de>
 * @package	TYPO3
 * @subpackage	mm_forum
 */
class tx_mmforum_scheduler_counter extends \TYPO3\CMS\Scheduler\Task\AbstractTask {

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
		$this->updateUser();
		return true;
	}

	/**
	 * @return void
	 */
	private function updateUser() {
		$forumPid = intval($this->getForumPid());

		$query = 'SELECT author, COUNT(*) AS counter, SUM(helpful_count) AS helpful_count, SUM(supporters) AS support_count
				  FROM tx_mmforum_domain_model_forum_post
				  WHERE deleted=0 AND hidden=0 AND author > 0 AND pid='.$forumPid.'
				  GROUP BY author';
		$res = $GLOBALS['TYPO3_DB']->sql_query($query);
		while($row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res)) {
			$userUpdate[$row['author']]['post_count'] = $row['counter'];
			$userUpdate[$row['author']]['helpful_count'] = $row['helpful_count'];
			$userUpdate[$row['author']]['support_count'] = $row['support_count'];
		}

		$query = 'SELECT author, COUNT(*) AS counter
				  FROM tx_mmforum_domain_model_forum_topic
				  WHERE deleted=0 AND hidden=0 AND author > 0 AND pid='.$forumPid.'
				  GROUP BY author';
		$res = $GLOBALS['TYPO3_DB']->sql_query($query);
		while($row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res)) {
			$userUpdate[$row['author']]['topic_count'] = $row['counter'];
		}

		$query = 'SELECT author, COUNT(*) AS counter
				  FROM tx_mmforum_domain_model_forum_topic
				  WHERE deleted=0 AND hidden=0 AND author > 0 AND pid='.$forumPid.' AND question=1
				  GROUP BY author';
		$res = $GLOBALS['TYPO3_DB']->sql_query($query);
		while($row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res)) {
			$userUpdate[$row['author']]['question_count'] = $row['counter'];
		}

		foreach($userUpdate AS $userUid => $array) {

			$values = array(
				'tx_mmforum_post_count' => intval($array['post_count']),
				'tx_mmforum_topic_count' => intval($array['topic_count']),
				'tx_mmforum_question_count' => intval($array['question_count']),
				//'tx_mmforum_helpful_count' => intval($array['helpful_count']), #later
				//'tx_mmforum_support_posts' => intval($array['support_count']), #later
			);

			$query = $GLOBALS['TYPO3_DB']->UPDATEquery('fe_users','uid='.intval($userUid),$values);
			$res = $GLOBALS['TYPO3_DB']->sql_query($query);
		}
	}


}

if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/mm_forum/Scheduler/class.tx_mmforum_scheduler_counter.php'])	{
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/mm_forum/Scheduler/class.tx_mmforum_scheduler_counter.php']);
}