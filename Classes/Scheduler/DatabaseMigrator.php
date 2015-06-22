<?php
namespace Mittwald\Typo3Forum\Scheduler;

use TYPO3\CMS\Core\Database\DatabaseConnection;
use TYPO3\CMS\Scheduler\Task\AbstractTask;

class DatabaseMigrator extends AbstractTask {

	/**
	 * @var DatabaseConnection
	 */
	protected $databaseConnection;

	/**
	 * @var array
	 */
	protected $legacyTableNames = [
		'tx_mmforum_cache',
		'tx_mmforum_cache_tags',
		'tx_mmforum_domain_model_format_textparser',
		'tx_mmforum_domain_model_forum_access',
		'tx_mmforum_domain_model_forum_ads',
		'tx_mmforum_domain_model_forum_attachment',
		'tx_mmforum_domain_model_forum_criteria',
		'tx_mmforum_domain_model_forum_criteria_forum',
		'tx_mmforum_domain_model_forum_criteria_options',
		'tx_mmforum_domain_model_forum_criteria_topic_options',
		'tx_mmforum_domain_model_forum_forum',
		'tx_mmforum_domain_model_forum_post',
		'tx_mmforum_domain_model_forum_tag',
		'tx_mmforum_domain_model_forum_tag_topic',
		'tx_mmforum_domain_model_forum_tag_user',
		'tx_mmforum_domain_model_forum_topic',
		'tx_mmforum_domain_model_moderation_report',
		'tx_mmforum_domain_model_moderation_reportcomment',
		'tx_mmforum_domain_model_moderation_reportworkflowstatus',
		'tx_mmforum_domain_model_moderation_reportworkflowstatus_followup',
		'tx_mmforum_domain_model_stats_summary',
		'tx_mmforum_domain_model_user_forumsubscription',
		'tx_mmforum_domain_model_user_notification',
		'tx_mmforum_domain_model_user_privatemessages',
		'tx_mmforum_domain_model_user_privatemessages_text',
		'tx_mmforum_domain_model_user_rank',
		'tx_mmforum_domain_model_user_readforum',
		'tx_mmforum_domain_model_user_readtopic',
		'tx_mmforum_domain_model_user_supportpost',
		'tx_mmforum_domain_model_user_topicfavsubscription',
		'tx_mmforum_domain_model_user_topicsubscription',
		'tx_mmforum_domain_model_user_userfield_userfield',
		'tx_mmforum_domain_model_user_userfield_value',
	];

	/**
	 *
	 */
	public function execute() {
		$this->databaseConnection = $GLOBALS['TYPO3_DB'];
		foreach ($this->legacyTableNames as $legacyTableName) {
			$newTableName = str_replace('_mmforum_', '_typo3forum_', $legacyTableName);
			if ($this->tableExists($legacyTableName)) {
				if ($this->tableExists($newTableName)) {
					if (!$this->tableIsEmpty($newTableName)) {
						$this->log(sprintf('Table %s couldn\'t be migrated to %s, because it exists and is not empty.', $legacyTableName, $newTableName));
						continue;
					} else {
						$this->databaseConnection->sql_query(sprintf('DROP TABLE %s', $newTableName));
					}
				}
				$res = $this->databaseConnection->sql_query(sprintf('RENAME TABLE %s TO %s', $legacyTableName, $newTableName));
				if (!$res) {
					$this->log($this->databaseConnection->sql_error());
				}
			}
		}
		return TRUE;
	}

	/**
	 * @param string $tableName
	 * @return bool
	 */
	protected function tableExists($tableName) {
		$res = $this->databaseConnection->sql_query(sprintf('SHOW TABLES LIKE \'%s\'', $tableName));
		return (bool) $this->databaseConnection->sql_num_rows($res);
	}

	/**
	 * @param string $tableName
	 * @return bool
	 */
	protected function tableIsEmpty($tableName) {
		return 0 === $this->databaseConnection->exec_SELECTcountRows('*', $tableName);
	}

	/**
	 * @param string $message
	 */
	protected function log($message) {
		
	}

}