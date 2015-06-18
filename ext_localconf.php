<?php

if (!defined('TYPO3_MODE')) {
	die('Access denied.');
}

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
	$_EXTKEY, 'Pi1',
	array(
		 'Forum'      => 'index, show, new, create, edit, update, delete, markRead, showUnread',
		 'Topic'      => 'index, list, show, new, create, edit, update, delete, questionsHelpBox, solution, listLatest',
		 'Post'       => 'show, new, create, edit, update, delete, deleteAttachment',
		 'User'       => 'showMyProfile, index, list, subscribe, favSubscribe, show, disableUser, unDisableUser, listNotifications, listMessages, createMessage, newMessage',
		 'Report'     => 'newUserReport, newPostReport, createUserReport, createPostReport',
		 'Moderation' => 'indexReport, editReport, newReportComment, editTopic, updateTopic, updateUserReportStatus, updatePostReportStatus, createUserReportComment, createPostReportComment, topicConformDelete',
		 'Tag'		  => 'list, show, new, create, listUserTags, newUserTag, deleteUserTag',
	),
	array(
		 'Forum'      => 'show, index, new, create, edit, update, delete, markRead, showUnread',
		 'Topic'      => 'new, create, edit, update, delete, list',
		 'Post'       => 'new, create, edit, update, delete',
		 'User'       => 'showMyProfile, dashboard, subscribe, favSubscribe, listFavorites, listNotifications, listTopics, listMessages, createMessage',
		 'Report'     => 'new, create',
		 'Moderation' => 'indexReport, updateTopic, updateUserReportStatus, updatePostReportStatus, newReportComment, createUserReportComment, createPostReportComment',
		 'Tag'		  => 'list, show, new, create, listUserTags, newUserTag, deleteUserTag',
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

# TCE-Main hook for clearing all typo3_forum caches
$TYPO3_CONF_VARS['SC_OPTIONS']['t3lib/class.t3lib_tcemain.php']['clearCachePostProc'][]
	= '\Mittwald\Typo3Forum\Cache\CacheManager->clearAll';

if (!is_array($TYPO3_CONF_VARS['SYS']['caching']['cacheConfigurations']['typo3forum_main'])) {
	$TYPO3_CONF_VARS['SYS']['caching']['cacheConfigurations']['typo3forum_main'] = array();
}

if (\TYPO3\CMS\Core\Utility\VersionNumberUtility::convertVersionNumberToInteger(TYPO3_version) < '4006000') {
	if (!isset($TYPO3_CONF_VARS['SYS']['caching']['cacheConfigurations']['typo3forum_main']['frontend'])) {
		$TYPO3_CONF_VARS['SYS']['caching']['cacheConfigurations']['typo3forum_main']['frontend'] = 't3lib_cache_frontend_VariableFrontend';
	}
	if (!isset($TYPO3_CONF_VARS['SYS']['caching']['cacheConfigurations']['typo3forum_main']['backend'])) {
		$TYPO3_CONF_VARS['SYS']['caching']['cacheConfigurations']['typo3forum_main']['backend'] = 't3lib_cache_backend_DbBackend';
	}
	if (!isset($TYPO3_CONF_VARS['SYS']['caching']['cacheConfigurations']['typo3forum_main']['options'])) {
		$TYPO3_CONF_VARS['SYS']['caching']['cacheConfigurations']['typo3forum_main']['options'] = array();
	}
	if (!isset($TYPO3_CONF_VARS['SYS']['caching']['cacheConfigurations']['typo3forum_main']['options']['cacheTable'])) {
		$TYPO3_CONF_VARS['SYS']['caching']['cacheConfigurations']['typo3forum_main']['options']['cacheTable'] = 'tx_typo3forum_cache';
	}
	if (!isset($TYPO3_CONF_VARS['SYS']['caching']['cacheConfigurations']['typo3forum_main']['options']['tagsTable'])) {
		$TYPO3_CONF_VARS['SYS']['caching']['cacheConfigurations']['typo3forum_main']['options']['tagsTable'] = 'tx_typo3forum_cache_tags';
	}
}

$TYPO3_CONF_VARS['FE']['eID_include']['typo3_forum'] = 'EXT:typo3_forum/Classes/Ajax/Dispatcher.php';

// Connect signals to slots. Some parts of extbase suck, but the signal-slot
// pattern is really cool! :P
$signalSlotDispatcher = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\CMS\Extbase\SignalSlot\Dispatcher');
$signalSlotDispatcher->connect('\Mittwald\Typo3Forum\Domain\Model\Forum\Post',
							   'postCreated', '\Mittwald\Typo3Forum\Service\Notification\SubscriptionListener',
							   'onPostCreated');
$signalSlotDispatcher->connect('\Mittwald\Typo3Forum\Domain\Model\Forum\Topic',
							   'topicCreated', '\Mittwald\Typo3Forum\Service\Notification\SubscriptionListener',
							   'onTopicCreated');

// adding scheduler tasks

$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['scheduler']['tasks']['\Mittwald\Typo3Forum\Scheduler\Notification'] = array(
	'extension'        => $_EXTKEY,
	'title'            => 'LLL:EXT:typo3_forum/Resources/Private/Language/locallang.xml:tx_typo3forum_scheduler_notification_title',
	'description'      => 'LLL:EXT:typo3_forum/Resources/Private/Language/locallang.xml:tx_typo3forum_scheduler_notification_description',
	'additionalFields' => '\Mittwald\Typo3Forum\Scheduler\NotificationAdditionalFieldProvider'
);

$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['scheduler']['tasks']['\Mittwald\Typo3Forum\Scheduler\StatsSummary'] = array(
	'extension'        => $_EXTKEY,
	'title'            => 'LLL:EXT:typo3_forum/Resources/Private/Language/locallang.xml:tx_typo3forum_scheduler_statsSummary_title',
	'description'      => 'LLL:EXT:typo3_forum/Resources/Private/Language/locallang.xml:tx_typo3forum_scheduler_statsSummary_description',
	'additionalFields' => '\Mittwald\Typo3Forum\Scheduler\StatsSummaryAdditionalFieldProvider'
);

$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['scheduler']['tasks']['\Mittwald\Typo3Forum\Scheduler\Counter'] = array(
	'extension'        => $_EXTKEY,
	'title'            => 'LLL:EXT:typo3_forum/Resources/Private/Language/locallang.xml:tx_typo3forum_scheduler_counter_title',
	'description'      => 'LLL:EXT:typo3_forum/Resources/Private/Language/locallang.xml:tx_typo3forum_scheduler_counter_description',
	'additionalFields' => '\Mittwald\Typo3Forum\Scheduler\CounterAdditionalFieldProvider'
);

$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['scheduler']['tasks']['\Mittwald\Typo3Forum\Scheduler\ForumRead'] = array(
	'extension'        => $_EXTKEY,
	'title'            => 'LLL:EXT:typo3_forum/Resources/Private/Language/locallang.xml:tx_typo3forum_scheduler_forumRead_title',
	'description'      => 'LLL:EXT:typo3_forum/Resources/Private/Language/locallang.xml:tx_typo3forum_scheduler_forumRead_description',
	'additionalFields' => '\Mittwald\Typo3Forum\Scheduler\ForumReadAdditionalFieldProvider'
);

$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['scheduler']['tasks']['\Mittwald\Typo3Forum\Scheduler\SessionResetter'] = array(
	'extension'        => $_EXTKEY,
	'title'            => 'LLL:EXT:typo3_forum/Resources/Private/Language/locallang.xml:tx_typo3forum_scheduler_seasonResetter_title',
	'description'      => 'LLL:EXT:typo3_forum/Resources/Private/Language/locallang.xml:tx_typo3forum_scheduler_seasonResetter_description',
	'additionalFields' => '\Mittwald\Typo3Forum\Scheduler\SessionResetterAdditionalFieldProvider'
);

