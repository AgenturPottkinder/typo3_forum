<?php
defined('TYPO3_MODE') || die();

call_user_func(
    static function ($extensionKey) {
        \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPageTSConfig('<INCLUDE_TYPOSCRIPT: source="FILE:EXT:' . $extensionKey . '/Configuration/TSconfig/pageTS.txt">');

        \TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
            'Mittwald.Typo3Forum',
            'Forum',
            [
                \Mittwald\Typo3Forum\Controller\ForumController::class => 'index, show, markRead',
                \Mittwald\Typo3Forum\Controller\TopicController::class => 'show, new, create, solution, removeSolution',
                \Mittwald\Typo3Forum\Controller\PostController::class => 'show, new, create, edit, update, delete, support, unsupport, confirmDelete, downloadAttachment',
                \Mittwald\Typo3Forum\Controller\UserController::class => 'subscribe',
                \Mittwald\Typo3Forum\Controller\ReportController::class => 'newUserReport, newPostReport, createUserReport, createPostReport',
                \Mittwald\Typo3Forum\Controller\ModerationController::class => 'editTopic, updateTopic, confirmDeleteTopic, deleteTopic',
                \Mittwald\Typo3Forum\Controller\TagController::class => 'show',
            ],
            [
                \Mittwald\Typo3Forum\Controller\ForumController::class => 'index, show, markRead',
                \Mittwald\Typo3Forum\Controller\TopicController::class => 'create, solution, removeSolution',
                \Mittwald\Typo3Forum\Controller\PostController::class => 'new, create, edit, update, delete, support, unsupport, confirmDelete, downloadAttachment',
                \Mittwald\Typo3Forum\Controller\UserController::class => 'subscribe',
                \Mittwald\Typo3Forum\Controller\ReportController::class => 'newUserReport, newPostReport, createUserReport, createPostReport',
                \Mittwald\Typo3Forum\Controller\ModerationController::class => 'editTopic, updateTopic, confirmDeleteTopic, deleteTopic',
                \Mittwald\Typo3Forum\Controller\TagController::class => 'show',
            ]
        );
        \TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
            'Mittwald.Typo3Forum',
            'UserProfile',
            [
                \Mittwald\Typo3Forum\Controller\UserController::class => 'show, listPosts, listTopics, listQuestions',
            ],
            [
                \Mittwald\Typo3Forum\Controller\UserController::class => 'listPosts, listTopics, listQuestions',
            ]
        );
        \TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
            'Mittwald.Typo3Forum',
            'ModerationReports',
            [
                \Mittwald\Typo3Forum\Controller\ModerationController::class => 'indexReport, editReport, updatePostReportStatus, updateUserReportStatus, createUserReportComment, createPostReportComment',
            ],
            [
                \Mittwald\Typo3Forum\Controller\ModerationController::class => 'indexReport, editReport, updatePostReportStatus, updateUserReportStatus, createUserReportComment, createPostReportComment',
            ]
        );
        \TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
            'Mittwald.Typo3Forum',
            'UserList',
            [
                \Mittwald\Typo3Forum\Controller\UserController::class => 'list',
            ],
            [
                \Mittwald\Typo3Forum\Controller\UserController::class => 'list',
            ]
        );
        \TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
            'Mittwald.Typo3Forum',
            'Dashboard',
            [
                \Mittwald\Typo3Forum\Controller\UserController::class => 'dashboard, listNotifications, listSubscriptions',
            ],
            [
                \Mittwald\Typo3Forum\Controller\UserController::class => 'dashboard, listNotifications, listSubscriptions',
            ]
        );
        \TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
            'Mittwald.Typo3Forum',
            'TagList',
            [
                \Mittwald\Typo3Forum\Controller\TagController::class => 'list, new, create',
            ],
            [
                \Mittwald\Typo3Forum\Controller\TagController::class => 'list, new, create',
            ]
        );
        \TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
            'Mittwald.Typo3Forum',
            'PostList',
            [
                \Mittwald\Typo3Forum\Controller\PostController::class => 'list',
            ],
            [
                \Mittwald\Typo3Forum\Controller\PostController::class => 'list',
            ]
        );
        \TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
            'Mittwald.Typo3Forum',
            'TopicList',
            [
                \Mittwald\Typo3Forum\Controller\TopicController::class => 'list',
            ],
            [
                \Mittwald\Typo3Forum\Controller\TopicController::class => 'list',
            ]
        );
        \TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
            'Mittwald.Typo3Forum',
            'StatsBox',
            [
                \Mittwald\Typo3Forum\Controller\StatsController::class => 'list',
            ],
            [
            ]
        );

        \TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
            'Mittwald.Typo3Forum',
            'Ajax',
            [
                \Mittwald\Typo3Forum\Controller\AjaxController::class => 'preview'
            ],
            [
                \Mittwald\Typo3Forum\Controller\AjaxController::class => 'preview',
            ]
        );

        // TCE-Main hook for clearing all typo3_forum caches
        $GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['t3lib/class.t3lib_tcemain.php']['clearCachePostProc'][]
            = 'Mittwald\Typo3Forum\Cache\CacheManager->clearAll';

        $GLOBALS['TYPO3_CONF_VARS']['SYS']['caching']['cacheConfigurations']['typo3forum_main']
            ??= [];

        // Connect signals to slots.
        $signalSlotDispatcher = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\CMS\Extbase\SignalSlot\Dispatcher');
        $signalSlotDispatcher->connect('Mittwald\Typo3Forum\Domain\Model\Forum\Post', 'postCreated',
            'Mittwald\Typo3Forum\Service\Notification\SubscriptionListener', 'onPostCreated');
        $signalSlotDispatcher->connect('Mittwald\Typo3Forum\Domain\Model\Forum\Topic', 'topicCreated',
            'Mittwald\Typo3Forum\Service\Notification\SubscriptionListener', 'onTopicCreated');
        $signalSlotDispatcher->connect('TYPO3\CMS\Extbase\Persistence\Generic\Mapper\DataMapper',
            'afterMappingSingleRow', 'Mittwald\Typo3Forum\Service\SettingsHydrator', 'hydrateSettings');
    }, 'typo3_forum'
);
