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
	 * @var array
	 */
	protected $legacyFeGroupsFields = [
		'tx_mmforum_user_mod'
	];

	/**
	 * @var array
	 */
	protected $legacyFeUsersFields = [
		'tx_mmforum_rank',
		'tx_mmforum_points',
		'tx_mmforum_post_count',
		'tx_mmforum_topic_count',
		'tx_mmforum_question_count',
		'tx_mmforum_topic_favsubscriptions',
		'tx_mmforum_topic_subscriptions',
		'tx_mmforum_forum_subscriptions',
		'tx_mmforum_helpful_count',
		'tx_mmforum_private_messages',
		'tx_mmforum_helpful_count_session',
		'tx_mmforum_post_count_session',
		'tx_mmforum_signature',
		'tx_mmforum_interests',
		'tx_mmforum_userfield_values',
		'tx_mmforum_read_forum',
		'tx_mmforum_read_topics',
		'tx_mmforum_support_posts',
		'tx_mmforum_use_gravatar',
		'tx_mmforum_facebook',
		'tx_mmforum_twitter',
		'tx_mmforum_google',
		'tx_mmforum_skype',
		'tx_mmforum_job',
		'tx_mmforum_working_environment',
		'tx_mmforum_contact',
	];

	/**
	 * @var array
	 */
	protected $legacyFeUsersTypes = [
		'Tx_MmForum_Domain_Model_User_FrontendUser',
	];

	/**
	 * @var array
	 */
	protected $legacyTtContentListTypes = [
		'mmforum_pi1'
	];

	/**
	 *
	 */
	public function execute() {
		$this->databaseConnection = $GLOBALS['TYPO3_DB'];
		$this->migrateTables();
		$this->migrateFeGroupsFields();
		$this->migrateFeUsersFields();
		$this->migrateFeUsersTypes();
		$this->migrateTtContentPlugins();
		return TRUE;
	}

	/**
	 *
	 */
	protected function migrateTables() {
		foreach ($this->legacyTableNames as $legacyTableName) {
			$newTableName = str_replace('_mmforum_', '_typo3forum_', $legacyTableName);
			// special case for tx_typo3forum_domain_model_moderation_reportworkflowstatus_followup which is too long for MySQL (>64 characters)
			if ($newTableName === 'tx_typo3forum_domain_model_moderation_reportworkflowstatus_followup') {
				$newTableName = 'tx_typo3forum_domain_model_moderation_reportworkflowstatus_mm';
			}
			// special case for tx_typo3forum_domain_model_user_privatemessages which has been renamed to singluar
			if ($newTableName === 'tx_typo3forum_domain_model_user_privatemessages') {
				$newTableName = 'tx_typo3forum_domain_model_user_privatemessage';
			}
			// special case for tx_typo3forum_domain_model_user_privatemessages_text which has been renamed to singluar
			if ($newTableName === 'tx_typo3forum_domain_model_user_privatemessages_text') {
				$newTableName = 'tx_typo3forum_domain_model_user_privatemessage_text';
			}
			// special case for tx_typo3forum_domain_model_forum_ads which has been renamed to singluar
			if ($newTableName === 'tx_typo3forum_domain_model_forum_ads') {
				$newTableName = 'tx_typo3forum_domain_model_forum_ad';
			}
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
	}

	/**
	 *
	 */
	protected function migrateFeUsersFields() {
		$users = $this->databaseConnection->exec_SELECTgetRows('*', 'fe_users', '1=1');
		foreach ($users as $user) {
			foreach($this->legacyFeUsersFields as $legacyFeUsersField) {
				$newFeUsersField = str_replace('_mmforum_', '_typo3forum_', $legacyFeUsersField);
				if (isset($user[$legacyFeUsersField]) && isset($user[$newFeUsersField]) && !empty($user[$legacyFeUsersField])) {
					$user[$newFeUsersField] = $user[$legacyFeUsersField];
					$user[$legacyFeUsersField] = '';
					$this->databaseConnection->exec_UPDATEquery('fe_users', 'uid = ' . (int)$user['uid'], $user);
				}
			}
		}
	}

	/**
	 *
	 */
	protected function migrateFeGroupsFields() {
		$groups = $this->databaseConnection->exec_SELECTgetRows('*', 'fe_groups', '1=1');
		foreach ($groups as $group) {
			foreach($this->legacyFeGroupsFields as $legacyFeGroupsField) {
				$newFeGroupsField = str_replace('_mmforum_', '_typo3forum_', $legacyFeGroupsField);
				if (isset($group[$legacyFeGroupsField]) && isset($group[$newFeGroupsField]) && !empty($group[$legacyFeGroupsField])) {
					$group[$newFeGroupsField] = $group[$legacyFeGroupsField];
					$group[$legacyFeGroupsField] = '';
					$this->databaseConnection->exec_UPDATEquery('fe_groups', 'uid = ' . (int)$group['uid'], $group);
				}
			}
		}
	}

	/**
	 *
	 */
	protected function migrateFeUsersTypes() {
		$users = $this->databaseConnection->exec_SELECTgetRows('*', 'fe_users', '1=1');
		foreach ($users as $user) {
			foreach($this->legacyFeUsersTypes as $legacyFeUsersType) {
				$newFeUsersType = str_replace(
					['Tx_MmForum', '_'],
					['Mittwald\Typo3Forum', '\\'],
					$legacyFeUsersType
				);
				if ($user['tx_extbase_type'] === $legacyFeUsersType) {
					$user['tx_extbase_type'] = $newFeUsersType;
					$this->databaseConnection->exec_UPDATEquery('fe_users', 'uid = ' . (int)$user['uid'], $user);
				}
			}
		}
	}

	/**
	 *
	 */
	protected function migrateTtContentPlugins() {
		$contentElements = $this->databaseConnection->exec_SELECTgetRows('*', 'tt_content', '1=1');
		foreach ($contentElements as $contentElement) {
			foreach ($this->legacyTtContentListTypes as $legacyTtContentListType) {
				$newTtContentListType = str_replace('mmforum_', 'typo3forum_', $legacyTtContentListType);
				if ($contentElement['list_type'] === $legacyTtContentListType) {
					$contentElement['list_type'] = $newTtContentListType;
					$this->databaseConnection->exec_UPDATEquery('tt_content', 'uid = ' . (int)$contentElement['uid'], $contentElement);
				}
			}
		}
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
