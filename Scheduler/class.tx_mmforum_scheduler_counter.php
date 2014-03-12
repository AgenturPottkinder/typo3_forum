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
	 * @var array
	 */
	private $settings;


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
	 * @return void
	 */
	public function setSettings() {
		$objectManager = t3lib_div::makeInstance('Tx_Extbase_Object_ObjectManager');
		$configurationManager = $objectManager->get('Tx_Extbase_Configuration_ConfigurationManagerInterface');

		$this->settings = $configurationManager->getConfiguration(
			\TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface::CONFIGURATION_TYPE_FULL_TYPOSCRIPT);
		$this->settings = $this->settings['plugin.']['tx_mmforum.']['settings.'];
	}

	/**
	 * @return bool
	 */
	public function execute() {
		if($this->getForumPid() == false || $this->getUserPid() == false) return false;
		$this->setSettings();

		$this->updateTopic();
		$this->updateUser();
		return true;
	}



	/**
	 * @return void
	 */
	private function updateTopic() {
		$query = 'SELECT COUNT(*) AS counter, p.topic FROM tx_mmforum_domain_model_forum_post AS p
				  INNER JOIN tx_mmforum_domain_model_forum_topic AS t ON t.uid = p.topic
				  WHERE p.pid='.intval($this->getForumPid()).' AND p.deleted=0 AND t.deleted=0
				  GROUP BY p.topic
				  ORDER BY counter ASC';
		$res = $GLOBALS['TYPO3_DB']->sql_query($query);
		while($row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res)) {
			$topicCount[$row['topic']] = $row['counter'];
		}
		$lastCounter = 1;
		$lastCounterArray = array();
		foreach($topicCount AS $topicUid => $postCount) {
			if($lastCounter != $postCount) {
				$query = 'UPDATE tx_mmforum_domain_model_forum_topic SET post_count = '.intval($lastCounter).' WHERE uid IN ('.implode(',',$lastCounterArray).')';
				$res = $GLOBALS['TYPO3_DB']->sql_query($query);
				$lastCounterArray = array();
			}
			$lastCounterArray[] = intval($topicUid);
			$lastCounter = $postCount;
		}
	}



	/**
	 * @return void
	 */
	private function updateUser() {
		$forumPid = intval($this->getForumPid());
		$userUpdate = array();
		$user = array();
		$rankScore = $this->settings['rankScore.'];

		//Find any post_count
		$query = 'SELECT p.author, COUNT(*) AS counter
				  FROM tx_mmforum_domain_model_forum_post AS p
				  WHERE p.deleted=0 AND p.hidden=0 AND p.author > 0 AND p.pid='.$forumPid.'
				  GROUP BY p.author';
		$res = $GLOBALS['TYPO3_DB']->sql_query($query);
		while($row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res)) {
			$userUpdate[$row['author']]['post_count'] = $row['counter'];
		}

		//Find any topic count
		$query = 'SELECT author, COUNT(*) AS counter
				  FROM tx_mmforum_domain_model_forum_topic
				  WHERE deleted=0 AND hidden=0 AND author > 0 AND pid='.$forumPid.'
				  GROUP BY author';
		$res = $GLOBALS['TYPO3_DB']->sql_query($query);
		while($row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res)) {
			$userUpdate[$row['author']]['topic_count'] = $row['counter'];
		}

		// Find any question topic count
		$query = 'SELECT author, COUNT(*) AS counter
				  FROM tx_mmforum_domain_model_forum_topic
				  WHERE deleted=0 AND hidden=0 AND author > 0 AND pid='.$forumPid.' AND question=1
				  GROUP BY author';
		$res = $GLOBALS['TYPO3_DB']->sql_query($query);
		while($row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res)) {
			$userUpdate[$row['author']]['question_count'] = $row['counter'];
		}

		// Find any favorite count
		$query = 'SELECT t.author, COUNT(*) AS counter
				  FROM tx_mmforum_domain_model_user_topicfavsubscription AS s
				  INNER JOIN tx_mmforum_domain_model_forum_topic AS t ON t.uid = s.uid_foreign
				  GROUP BY uid_local';
		$res = $GLOBALS['TYPO3_DB']->sql_query($query);
		while($row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res)) {
			$userUpdate[$row['author']]['favorite_count'] = $row['counter'];
		}

		//Supported Post User X got
		$query = 'SELECT p.author, COUNT(*) AS counter
				  FROM tx_mmforum_domain_model_user_supportpost AS s
				  INNER JOIN tx_mmforum_domain_model_forum_post AS p ON p.uid = s.uid_foreign
				  GROUP BY p.author';
		$res = $GLOBALS['TYPO3_DB']->sql_query($query);
		while($row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res)) {
			$userUpdate[$row['author']]['support_count'] = $row['counter'];
		}

		//Supported Post User X set
		$query = 'SELECT s.uid_local, COUNT(*) AS counter
				  FROM tx_mmforum_domain_model_user_supportpost AS s
				  GROUP BY uid_local';
		$res = $GLOBALS['TYPO3_DB']->sql_query($query);
		while($row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res)) {
			$userUpdate[$row['uid_local']]['markSupport_count'] = $row['counter'];
		}

		//Find all users with their current rank
		$query = 'SELECT fe.uid, fe.tx\\mmforum\\rank
				  FROM fe\\users AS fe
				  WHERE fe.disable=0 AND fe.deleted=0 AND fe.tx\\extbase\\type=\"Mittwald\\MmForum\\Domain\\Model\\User\\FrontendUser\"
						AND fe.pid='.intval($this->getUserPid());
		$res = $GLOBALS['TYPO3_DB']->sql_query($query);
		while($row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res)) {
			$userUpdate[$row['author']]['rank'] = $row['tx_mmforum_rank'];
		}

		//Find all rank
		$query = 'SELECT uid, point_limit
				  FROM  tx_mmforum_domain_model_user_rank
				  WHERE deleted=0 AND pid='.intval($this->getUserPid()).'
				  ORDER BY point_limit ASC';
		$res = $GLOBALS['TYPO3_DB']->sql_query($query);
		while($row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res)) {
			$rankArray[$row['uid']] = $row;
		}

		//Now check this giant array
		foreach($userUpdate AS $userUid => $array) {
			$points = 0;
			$points = $points + intval($array['post_count']) * intval($rankScore['newPost']);
			$points = $points + intval($array['markSupport_count']) * intval($rankScore['markHelpful']);
			$points = $points + intval($array['favorite_count']) * intval($rankScore['gotFavorite']);
			$points = $points + intval($array['support_count']) * intval($rankScore['gotHelpful']);

			$lastPointLimit = 0;
			foreach($rankArray AS $key => $rank) {
				if($points >= $lastPointLimit && $points < $rank['point_limit']) {
					$array['rank'] = $rank['uid'];
				}
				$lastPointLimit = $rank['point_limit'];
			}

			$values = array(
				'tx_mmforum_post_count' => intval($array['post_count']),
				'tx_mmforum_topic_count' => intval($array['topic_count']),
				'tx_mmforum_question_count' => intval($array['question_count']),
				'tx_mmforum_helpful_count' => intval($array['support_count']),
				'tx_mmforum_points' => intval($points),
				'tx_mmforum_rank' => intval($array['rank']),
			);

			$query = $GLOBALS['TYPO3_DB']->UPDATEquery('fe_users','uid='.intval($userUid),$values);
			$res = $GLOBALS['TYPO3_DB']->sql_query($query);
		}


		//At last, update the rank count
		$query = $GLOBALS['TYPO3_DB']->UPDATEquery('tx_mmforum_domain_model_user_rank','1=1',array('user_count' => 0));
		$res = $GLOBALS['TYPO3_DB']->sql_query($query);
		$query = 'SELECT tx\\mmforum\\rank, COUNT(*) AS counter
				  FROM fe\\users
				  WHERE disable=0 AND deleted=0 AND tx\\extbase\\type=\"Mittwald\\MmForum\\Domain\\Model\\User\\FrontendUser\"
				  		AND pid='.intval($this->getUserPid()).'
				  GROUP BY tx_mmforum_rank';
		$res = $GLOBALS['TYPO3_DB']->sql_query($query);
		while($row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res)) {
			$query = $GLOBALS['TYPO3_DB']->UPDATEquery('tx_mmforum_domain_model_user_rank','uid='.intval($row['tx_mmforum_rank']),
														array('user_count' => intval($row['counter'])));
			$res2 = $GLOBALS['TYPO3_DB']->sql_query($query);
		}
	}



}

if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/mm_forum/Scheduler/class.tx_mmforum_scheduler_counter.php'])	{
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/mm_forum/Scheduler/class.tx_mmforum_scheduler_counter.php']);
}