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
 * Count all Topics, Posts and Users and write result into summary table
 */
class StatsSummary extends AbstractTask {

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
	protected $statsPid;

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
	public function getStatsPid() {
		return $this->statsPid;
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
	 * @param int $statsPid
	 */
	public function setStatsPid($statsPid) {
		$this->statsPid = $statsPid;
	}

	/**
	 * @return bool
	 */
	public function execute() {
		if (!$this->getForumPids() || !$this->getUserPids() || !$this->getStatsPid()) {
			return FALSE;
		}
		$results = [];

		$query = 'SELECT COUNT(*) AS counter
				  FROM tx_typo3forum_domain_model_forum_post
				  WHERE deleted=0 AND pid IN (' . $this->getForumPids() . ');';
		$res = $GLOBALS['TYPO3_DB']->sql_query($query);
		$row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res);
		$results[] = (int)$row['counter'];

		$query = 'SELECT COUNT(*) AS counter
				  FROM tx_typo3forum_domain_model_forum_topic
				  WHERE deleted=0 AND pid IN (' . $this->getForumPids() . ');';
		$res = $GLOBALS['TYPO3_DB']->sql_query($query);
		$row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res);
		$results[] = (int)$row['counter'];

		$query = "SELECT COUNT(*) AS counter
				  FROM fe_users
				  WHERE deleted=0 AND disable=0 AND tx_extbase_type LIKE 'Mittwald\\\\Typo3Forum\\\\Domain\\\\Model\\\\User\\\\FrontendUser' ESCAPE '|'
				  		AND pid IN (" . $this->getUserPids() . ");";
		$res = $GLOBALS['TYPO3_DB']->sql_query($query);
		$row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res);
		$results[] = (int)$row['counter'];

		foreach ($results as $typeUid => $amount) {
			$values = [
				'pid' => (int)$this->getStatsPid(),
				'tstamp' => time(),
				'type' => (int)$typeUid,
				'amount' => (int)$amount,
			];
			$query = $GLOBALS['TYPO3_DB']->INSERTquery('tx_typo3forum_domain_model_stats_summary', $values);
			$GLOBALS['TYPO3_DB']->sql_query($query);
		}

		return true;
	}
}
