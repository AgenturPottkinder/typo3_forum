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

use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface;
use TYPO3\CMS\Scheduler\Task\AbstractTask;

/**
 * A turtle with maths skills checks all counter columns of any user and update them on a error.
 *
 * @author  Ruven Fehling <r.fehling@mittwald.de>
 * @package  TYPO3
 * @subpackage  typo3_forum
 */
class Counter extends AbstractTask {

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
		$objectManager = GeneralUtility::makeInstance('TYPO3\\CMS\\Extbase\\Object\\ObjectManager');
		/** @var ConfigurationManagerInterface $configurationManager */
		$configurationManager = $objectManager->get('TYPO3\\CMS\\Extbase\\Configuration\\ConfigurationManagerInterface');
		$this->settings = $configurationManager->getConfiguration(ConfigurationManagerInterface::CONFIGURATION_TYPE_FULL_TYPOSCRIPT);
		$this->settings = $this->settings['plugin.']['tx_typo3forum.']['settings.'];
	}

	/**
	 * @return bool
	 */
	public function execute() {
		if ($this->getForumPid() == false || $this->getUserPid() == false) return false;
		$this->setSettings();

		$this->updateTopic();
		$this->updateUser();
		return true;
	}


	/**
	 * @return void
	 */
	private function updateTopic() {
		$topicCount = [];
		$query = 'SELECT COUNT(*) AS counter, p.topic FROM tx_typo3forum_domain_model_forum_post AS p
				  INNER JOIN tx_typo3forum_domain_model_forum_topic AS t ON t.uid = p.topic
				  WHERE p.pid=' . (int)$this->getForumPid() . ' AND p.deleted=0 AND t.deleted=0
				  GROUP BY p.topic
				  ORDER BY counter ASC';
		$res = $GLOBALS['TYPO3_DB']->sql_query($query);
		while ($row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res)) {
			$topicCount[$row['topic']] = $row['counter'];
		}
		$lastCounter = 1;
		$lastCounterArray = [];
		foreach ($topicCount as $topicUid => $postCount) {
			if ($lastCounter != $postCount) {
				$query = 'UPDATE tx_typo3forum_domain_model_forum_topic SET post_count = ' . (int)$lastCounter . ' WHERE uid IN (' . implode(',', $lastCounterArray) . ')';
				$GLOBALS['TYPO3_DB']->sql_query($query);
				$lastCounterArray = [];
			}
			$lastCounterArray[] = (int)$topicUid;
			$lastCounter = $postCount;
		}
	}

	/**
	 * @return void
	 */
	private function updateUser() {
		$forumPid = (int)$this->getForumPid();
		$userUpdate = [];
		$rankScore = $this->settings['rankScore.'];

		//Find any post_count
		$query = 'SELECT p.author, COUNT(*) AS counter
				  FROM tx_typo3forum_domain_model_forum_post AS p
				  WHERE p.deleted=0 AND p.hidden=0 AND p.author > 0 AND p.pid=' . $forumPid . '
				  GROUP BY p.author';
		$res = $GLOBALS['TYPO3_DB']->sql_query($query);
		while ($row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res)) {
			$userUpdate[$row['author']]['post_count'] = $row['counter'];
		}

		//Find any topic count
		$query = 'SELECT author, COUNT(*) AS counter
				  FROM tx_typo3forum_domain_model_forum_topic
				  WHERE deleted=0 AND hidden=0 AND author > 0 AND pid=' . $forumPid . '
				  GROUP BY author';
		$res = $GLOBALS['TYPO3_DB']->sql_query($query);
		while ($row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res)) {
			$userUpdate[$row['author']]['topic_count'] = $row['counter'];
		}

		// Find any question topic count
		$query = 'SELECT author, COUNT(*) AS counter
				  FROM tx_typo3forum_domain_model_forum_topic
				  WHERE deleted=0 AND hidden=0 AND author > 0 AND pid=' . $forumPid . ' AND question=1
				  GROUP BY author';
		$res = $GLOBALS['TYPO3_DB']->sql_query($query);
		while ($row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res)) {
			$userUpdate[$row['author']]['question_count'] = $row['counter'];
		}

		// Find any favorite count
		$query = 'SELECT t.author, COUNT(*) AS counter
				  FROM tx_typo3forum_domain_model_user_topicfavsubscription AS s
				  INNER JOIN tx_typo3forum_domain_model_forum_topic AS t ON t.uid = s.uid_foreign
				  GROUP BY uid_local';
		$res = $GLOBALS['TYPO3_DB']->sql_query($query);
		while ($row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res)) {
			$userUpdate[$row['author']]['favorite_count'] = $row['counter'];
		}

		//Supported Post User X got
		$query = 'SELECT p.author, COUNT(*) AS counter
				  FROM tx_typo3forum_domain_model_user_supportpost AS s
				  INNER JOIN tx_typo3forum_domain_model_forum_post AS p ON p.uid = s.uid_foreign
				  GROUP BY p.author';
		$res = $GLOBALS['TYPO3_DB']->sql_query($query);
		while ($row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res)) {
			$userUpdate[$row['author']]['support_count'] = $row['counter'];
		}

		//Supported Post User X set
		$query = 'SELECT s.uid_local, COUNT(*) AS counter
				  FROM tx_typo3forum_domain_model_user_supportpost AS s
				  GROUP BY uid_local';
		$res = $GLOBALS['TYPO3_DB']->sql_query($query);
		while ($row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res)) {
			$userUpdate[$row['uid_local']]['markSupport_count'] = $row['counter'];
		}

		//Find all users with their current rank
		$query = 'SELECT fe.uid, fe.tx_typo3forum_rank
				  FROM fe_users AS fe
				  WHERE fe.disable=0 AND fe.deleted=0 AND fe.tx_extbase_type="\Mittwald\Typo3Forum\Domain\Model\User\FrontendUser"
						AND fe.pid=' . (int)$this->getUserPid();
		$res = $GLOBALS['TYPO3_DB']->sql_query($query);
		while ($row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res)) {
			$userUpdate[$row['author']]['rank'] = $row['tx_typo3forum_rank'];
		}

		//Find all ranks
		$query = 'SELECT uid, point_limit
				  FROM  tx_typo3forum_domain_model_user_rank
				  WHERE deleted=0 AND pid=' . (int)$this->getUserPid() . '
				  ORDER BY point_limit ASC';
		$res = $GLOBALS['TYPO3_DB']->sql_query($query);
		$rankArray = [];
		while ($row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res)) {
			$rankArray[$row['uid']] = $row;
		}

		//Now check this giant array
		foreach ($userUpdate as $userUid => $array) {
			$points = 0;
			$points = $points + (int)$array['post_count'] * (int)$rankScore['newPost'];
			$points = $points + (int)$array['markSupport_count'] * (int)$rankScore['markHelpful'];
			$points = $points + (int)$array['favorite_count'] * (int)$rankScore['gotFavorite'];
			$points = $points + (int)$array['support_count'] * (int)$rankScore['gotHelpful'];

			$lastPointLimit = 0;
			foreach ($rankArray as $key => $rank) {
				if ($points >= $lastPointLimit && $points < $rank['point_limit']) {
					$array['rank'] = $rank['uid'];
				}
				$lastPointLimit = $rank['point_limit'];
			}

			$values = [
				'tx_typo3forum_post_count' => (int)$array['post_count'],
				'tx_typo3forum_topic_count' => (int)$array['topic_count'],
				'tx_typo3forum_question_count' => (int)$array['question_count'],
				'tx_typo3forum_helpful_count' => (int)$array['support_count'],
				'tx_typo3forum_points' => (int)$points,
				'tx_typo3forum_rank' => (int)$array['rank'],
			];

			$query = $GLOBALS['TYPO3_DB']->UPDATEquery('fe_users', 'uid=' . (int)$userUid, $values);
			$res = $GLOBALS['TYPO3_DB']->sql_query($query);
		}


		//At last, update the rank count
		$query = $GLOBALS['TYPO3_DB']->UPDATEquery('tx_typo3forum_domain_model_user_rank', '1=1', ['user_count' => 0]);
		$GLOBALS['TYPO3_DB']->sql_query($query);
		$query = 'SELECT tx_typo3forum_rank, COUNT(*) AS counter
				  FROM fe_users
				  WHERE disable=0 AND deleted=0 AND tx_extbase_type="\Mittwald\Typo3Forum\Domain\Model\User\FrontendUser"
				  		AND pid=' . (int)$this->getUserPid() . '
				  GROUP BY tx_typo3forum_rank';
		$res = $GLOBALS['TYPO3_DB']->sql_query($query);
		while ($row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res)) {
			$query = $GLOBALS['TYPO3_DB']->UPDATEquery(
				'tx_typo3forum_domain_model_user_rank', 'uid=' . (int)$row['tx_typo3forum_rank'],
				['user_count' => (int)$row['counter']]);
			$GLOBALS['TYPO3_DB']->sql_query($query);
		}
	}
}
