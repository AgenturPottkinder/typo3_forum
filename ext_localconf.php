<?php

defined('TYPO3_MODE') or die();

$_EXTKEY = 'typo3_forum';

TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPageTSConfig('<INCLUDE_TYPOSCRIPT: source="FILE:EXT:' . $_EXTKEY . '/Configuration/TSconfig/pageTS.txt">');

TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
	'Mittwald.Typo3Forum',
	'Pi1',
	[
		'Forum' => 'index, show, markRead, showUnread',
		'Topic' => 'show, new, create, solution, listLatest',
		'Post' => 'show, new, create, edit, update, delete, downloadAttachment',
		'User' => 'showMyProfile, index, list, subscribe, favSubscribe, show, disableUser, unDisableUser, listNotifications, listMessages, createMessage, newMessage, listPosts',
		'Report' => 'newUserReport, newPostReport, createUserReport, createPostReport',
		'Moderation' => 'indexReport, editReport, newReportComment, editTopic, updateTopic, updateUserReportStatus, updatePostReportStatus, createUserReportComment, createPostReportComment, topicConformDelete',
		'Tag' => 'list, show, new, create, listUserTags, newUserTag, deleteUserTag',
	],
	[
		'Forum' => 'show, index, create, update, delete, markRead, showUnread',
		'Topic' => 'create',
		'Post' => 'new, create, edit, update, delete, downloadAttachment',
		'User' => 'showMyProfile, dashboard, subscribe, favSubscribe, listFavorites, listNotifications, listTopics, listMessages, createMessage,listPosts',
		'Report' => 'newUserReport, newPostReport, createUserReport, createPostReport',
		'Moderation' => 'indexReport, updateTopic, updateUserReportStatus, updatePostReportStatus, newReportComment, createUserReportComment, createPostReportComment',
		'Tag' => 'list, show, new, create, listUserTags, newUserTag, deleteUserTag',
	]
);

TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
	'Mittwald.Typo3Forum',
	'Widget',
	[
		'User' => 'list',
		'Stats' => 'list',
	],
	[
		'User' => 'list',
	]
);

TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
	'Mittwald.Typo3Forum',
	'Ajax',
	[
		'Forum' => 'index',
		'Post' => 'preview, addSupporter, removeSupporter',
		'Tag' => 'autoComplete',
		'Ajax' => 'main, postSummary, loginbox'
	],
	[
		'Forum' => 'index',
		'Post' => 'preview, addSupporter, removeSupporter',
		'Ajax' => 'main, postSummary, loginbox',
	]
);

# TCE-Main hook for clearing all typo3_forum caches
$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['t3lib/class.t3lib_tcemain.php']['clearCachePostProc'][] = Mittwald\Typo3Forum\Cache\CacheManager::class.'->clearAll';

if (!is_array($GLOBALS['TYPO3_CONF_VARS']['SYS']['caching']['cacheConfigurations']['typo3forum_main'])) {
	$GLOBALS['TYPO3_CONF_VARS']['SYS']['caching']['cacheConfigurations']['typo3forum_main'] = [];
}

if (TYPO3\CMS\Core\Utility\VersionNumberUtility::convertVersionNumberToInteger(TYPO3_version) < '4006000') {
	if (!isset($GLOBALS['TYPO3_CONF_VARS']['SYS']['caching']['cacheConfigurations']['typo3forum_main']['frontend'])) {
		$GLOBALS['TYPO3_CONF_VARS']['SYS']['caching']['cacheConfigurations']['typo3forum_main']['frontend'] = 't3lib_cache_frontend_VariableFrontend';
	}
	if (!isset($GLOBALS['TYPO3_CONF_VARS']['SYS']['caching']['cacheConfigurations']['typo3forum_main']['backend'])) {
		$GLOBALS['TYPO3_CONF_VARS']['SYS']['caching']['cacheConfigurations']['typo3forum_main']['backend'] = 't3lib_cache_backend_DbBackend';
	}
	if (!isset($GLOBALS['TYPO3_CONF_VARS']['SYS']['caching']['cacheConfigurations']['typo3forum_main']['options'])) {
		$GLOBALS['TYPO3_CONF_VARS']['SYS']['caching']['cacheConfigurations']['typo3forum_main']['options'] = [];
	}
	if (!isset($GLOBALS['TYPO3_CONF_VARS']['SYS']['caching']['cacheConfigurations']['typo3forum_main']['options']['cacheTable'])) {
		$GLOBALS['TYPO3_CONF_VARS']['SYS']['caching']['cacheConfigurations']['typo3forum_main']['options']['cacheTable'] = 'tx_typo3forum_cache';
	}
	if (!isset($GLOBALS['TYPO3_CONF_VARS']['SYS']['caching']['cacheConfigurations']['typo3forum_main']['options']['tagsTable'])) {
		$GLOBALS['TYPO3_CONF_VARS']['SYS']['caching']['cacheConfigurations']['typo3forum_main']['options']['tagsTable'] = 'tx_typo3forum_cache_tags';
	}
}

$GLOBALS['TYPO3_CONF_VARS']['FE']['eID_include']['typo3_forum'] = \Mittwald\Typo3Forum\Ajax\Dispatcher::class . '::processRequest';

// Connect signals to slots. Some parts of extbase suck, but the signal-slot
// pattern is really cool! :P
/* @var \TYPO3\CMS\Extbase\SignalSlot\Dispatcher $signalSlotDispatcher */
$signalSlotDispatcher = TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(TYPO3\CMS\Extbase\SignalSlot\Dispatcher::class);
$signalSlotDispatcher->connect(
    Mittwald\Typo3Forum\Domain\Model\Forum\Post::class, 'postCreated', 
    Mittwald\Typo3Forum\Service\Notification\SubscriptionListener::class, 'onPostCreated'
);
$signalSlotDispatcher->connect(
    Mittwald\Typo3Forum\Domain\Model\Forum\Topic::class, 'topicCreated', 
    Mittwald\Typo3Forum\Service\Notification\SubscriptionListener::class, 'onTopicCreated'
);
$signalSlotDispatcher->connect(
    TYPO3\CMS\Extensionmanager\Service\ExtensionManagementService::class, 'hasInstalledExtensions', 
    Mittwald\Typo3Forum\Service\InstallService::class, 'checkForMigrationOption'
);

// adding scheduler tasks

$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['scheduler']['tasks'][Mittwald\Typo3Forum\Scheduler\Counter::class] = [
	'extension' => $_EXTKEY,
	'title' => 'LLL:EXT:typo3_forum/Resources/Private/Language/locallang.xml:tx_typo3forum_scheduler_counter_title',
	'description' => 'LLL:EXT:typo3_forum/Resources/Private/Language/locallang.xml:tx_typo3forum_scheduler_counter_description',
	'additionalFields' => Mittwald\Typo3Forum\Scheduler\CounterAdditionalFieldProvider::class
];

$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['scheduler']['tasks'][Mittwald\Typo3Forum\Scheduler\DatabaseMigrator::class] = [
	'extension' => $_EXTKEY,
	'title' => 'LLL:EXT:typo3_forum/Resources/Private/Language/locallang.xml:tx_typo3forum_scheduler_databaseMigrator_title',
	'description' => 'LLL:EXT:typo3_forum/Resources/Private/Language/locallang.xml:tx_typo3forum_scheduler_databaseMigrator_description',
];

$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['scheduler']['tasks'][Mittwald\Typo3Forum\Scheduler\ForumRead::class] = [
	'extension' => $_EXTKEY,
	'title' => 'LLL:EXT:typo3_forum/Resources/Private/Language/locallang.xml:tx_typo3forum_scheduler_forumRead_title',
	'description' => 'LLL:EXT:typo3_forum/Resources/Private/Language/locallang.xml:tx_typo3forum_scheduler_forumRead_description',
	'additionalFields' => Mittwald\Typo3Forum\Scheduler\ForumReadAdditionalFieldProvider::class,
];

$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['scheduler']['tasks'][Mittwald\Typo3Forum\Scheduler\Notification::class] = [
	'extension' => $_EXTKEY,
	'title' => 'LLL:EXT:typo3_forum/Resources/Private/Language/locallang.xml:tx_typo3forum_scheduler_notification_title',
	'description' => 'LLL:EXT:typo3_forum/Resources/Private/Language/locallang.xml:tx_typo3forum_scheduler_notification_description',
	'additionalFields' => Mittwald\Typo3Forum\Scheduler\NotificationAdditionalFieldProvider::class,
];

$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['scheduler']['tasks'][Mittwald\Typo3Forum\Scheduler\SessionResetter::class] = [
	'extension' => $_EXTKEY,
	'title' => 'LLL:EXT:typo3_forum/Resources/Private/Language/locallang.xml:tx_typo3forum_scheduler_sessionResetter_title',
	'description' => 'LLL:EXT:typo3_forum/Resources/Private/Language/locallang.xml:tx_typo3forum_scheduler_sessionResetter_description',
	'additionalFields' => Mittwald\Typo3Forum\Scheduler\SessionResetterAdditionalFieldProvider::class,
];

$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['scheduler']['tasks'][Mittwald\Typo3Forum\Scheduler\StatsSummary::class] = [
	'extension' => $_EXTKEY,
	'title' => 'LLL:EXT:typo3_forum/Resources/Private/Language/locallang.xml:tx_typo3forum_scheduler_statsSummary_title',
	'description' => 'LLL:EXT:typo3_forum/Resources/Private/Language/locallang.xml:tx_typo3forum_scheduler_statsSummary_description',
	'additionalFields' => Mittwald\Typo3Forum\Scheduler\StatsSummaryAdditionalFieldProvider::class,
];
