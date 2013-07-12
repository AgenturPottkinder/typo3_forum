<?php

if (!defined('TYPO3_MODE')) {
	die('Access denied.');
}

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
	$_EXTKEY, 'Pi1',
	array(
		 'Forum'      => 'index, show, new, create, edit, update, delete, markRead',
		 'Topic'      => 'index, show, new, create, edit, update, delete, questionsHelpBox, solution',
		 'Post'       => 'show, new, create, edit, update, delete, deleteAttachment',
		 'User'       => 'showMyProfile, index, list, subscribe, favSubscribe, show, disableUser, unDisableUser',
		 'Report'     => 'newUserReport, newPostReport, createUserReport, createPostReport',
		 'Moderation' => 'show, editTopic, updateTopic, updateUserReportStatus, updatePostReportStatus, createUserReportComment, createPostReportComment, topicConformDelete',
		 'Tag'		  => 'list, show, listUserTags, newUserTag, deleteUserTag',
	),
	array(
		 'Forum'      => 'new, create, edit, update, delete, markRead',
		 'Topic'      => 'new, create, edit, update, delete',
		 'Post'       => 'new, create, edit, update, delete',
		 'User'       => 'showMyProfile, dashboard, subscribe, favSubscribe, listFavorites, listMessages, listNotifications, listTopics',
		 'Report'     => 'new, create',
		 'Moderation' => 'show, updateTopic, updateReportStatus, newReportComment, createReportComment',
		 'Tag'		  => 'list, listUserTags',
	)
);
\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
	$_EXTKEY, 'Widget',
	array(
		 'User'		=> 'list',
		 'Stats'	=> 'list',
	),
	array(
		 'User' => 'list',
	)
);

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
	$_EXTKEY, 'Ajax', array(
		'Forum' => 'index',
		'Post' => 'preview, addSupporter, removeSupporter',
		'Tag'   => 'autoComplete',
		'Ajax' => 'main, postSummary, loginbox'
	), array(
		'Forum' => 'index',
		'Post' => 'preview, addSupporter, removeSupporter',
		'Ajax' => 'main, postSummary, loginbox',
	)
);

# TCE-Main hook for clearing all mm_forum caches
$TYPO3_CONF_VARS['SC_OPTIONS']['t3lib/class.t3lib_tcemain.php']['clearCachePostProc'][]
	= 'EXT:mm_forum/Classes/Cache/CacheManager.php:Tx_MmForum_Cache_CacheManager->clearAll';

if (!is_array($TYPO3_CONF_VARS['SYS']['caching']['cacheConfigurations']['mmforum_main'])) {
	$TYPO3_CONF_VARS['SYS']['caching']['cacheConfigurations']['mmforum_main'] = array();
}

if (\TYPO3\CMS\Core\Utility\VersionNumberUtility::convertVersionNumberToInteger(TYPO3_version) < '4006000') {
	if (!isset($TYPO3_CONF_VARS['SYS']['caching']['cacheConfigurations']['mmforum_main']['frontend'])) {
		$TYPO3_CONF_VARS['SYS']['caching']['cacheConfigurations']['mmforum_main']['frontend'] = 't3lib_cache_frontend_VariableFrontend';
	}
	if (!isset($TYPO3_CONF_VARS['SYS']['caching']['cacheConfigurations']['mmforum_main']['backend'])) {
		$TYPO3_CONF_VARS['SYS']['caching']['cacheConfigurations']['mmforum_main']['backend'] = 't3lib_cache_backend_DbBackend';
	}
	if (!isset($TYPO3_CONF_VARS['SYS']['caching']['cacheConfigurations']['mmforum_main']['options'])) {
		$TYPO3_CONF_VARS['SYS']['caching']['cacheConfigurations']['mmforum_main']['options'] = array();
	}
	if (!isset($TYPO3_CONF_VARS['SYS']['caching']['cacheConfigurations']['mmforum_main']['options']['cacheTable'])) {
		$TYPO3_CONF_VARS['SYS']['caching']['cacheConfigurations']['mmforum_main']['options']['cacheTable'] = 'tx_mmforum_cache';
	}
	if (!isset($TYPO3_CONF_VARS['SYS']['caching']['cacheConfigurations']['mmforum_main']['options']['tagsTable'])) {
		$TYPO3_CONF_VARS['SYS']['caching']['cacheConfigurations']['mmforum_main']['options']['tagsTable'] = 'tx_mmforum_cache_tags';
	}
}

$TYPO3_CONF_VARS['FE']['eID_include']['mm_forum'] = 'EXT:mm_forum/Classes/Ajax/Dispatcher.php';

// Connect signals to slots. Some parts of extbase suck, but the signal-slot
// pattern is really cool! :P
$signalSlotDispatcher = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\CMS\Extbase\SignalSlot\Dispatcher');
$signalSlotDispatcher->connect('Tx_MmForum_Domain_Model_Forum_Post',
							   'postCreated', 'Tx_MmForum_Service_Notification_SubscriptionListener',
							   'onPostCreated');
$signalSlotDispatcher->connect('Tx_MmForum_Domain_Model_Forum_Topic',
							   'topicCreated', 'Tx_MmForum_Service_Notification_SubscriptionListener',
							   'onTopicCreated');

// adding scheduler tasks

$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['scheduler']['tasks']['tx_mmforum_scheduler_notification'] = array(
	'extension'        => $_EXTKEY,
	'title'            => 'LLL:EXT:mm_forum/Resources/Private/Language/locallang.xml:tx_mmforum_scheduler_notification_title',
	'description'      => 'LLL:EXT:mm_forum/Resources/Private/Language/locallang.xml:tx_mmforum_scheduler_notification_description',
	'additionalFields' => 'tx_mmforum_scheduler_notification_additionalFieldProvider'
);

$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['scheduler']['tasks']['tx_mmforum_scheduler_statsSummary'] = array(
	'extension'        => $_EXTKEY,
	'title'            => 'LLL:EXT:mm_forum/Resources/Private/Language/locallang.xml:tx_mmforum_scheduler_statsSummary_title',
	'description'      => 'LLL:EXT:mm_forum/Resources/Private/Language/locallang.xml:tx_mmforum_scheduler_statsSummary_description',
	'additionalFields' => 'tx_mmforum_scheduler_statsSummary_additionalFieldProvider'
);

$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['scheduler']['tasks']['tx_mmforum_scheduler_counter'] = array(
	'extension'        => $_EXTKEY,
	'title'            => 'LLL:EXT:mm_forum/Resources/Private/Language/locallang.xml:tx_mmforum_scheduler_counter_title',
	'description'      => 'LLL:EXT:mm_forum/Resources/Private/Language/locallang.xml:tx_mmforum_scheduler_counter_description',
	'additionalFields' => 'tx_mmforum_scheduler_counter_additionalFieldProvider'
);

$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['scheduler']['tasks']['tx_mmforum_scheduler_forumRead'] = array(
	'extension'        => $_EXTKEY,
	'title'            => 'LLL:EXT:mm_forum/Resources/Private/Language/locallang.xml:tx_mmforum_scheduler_forumRead_title',
	'description'      => 'LLL:EXT:mm_forum/Resources/Private/Language/locallang.xml:tx_mmforum_scheduler_forumRead_description',
	'additionalFields' => 'tx_mmforum_scheduler_forumRead_additionalFieldProvider'
);