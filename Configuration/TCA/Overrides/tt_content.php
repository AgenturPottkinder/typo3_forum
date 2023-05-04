<?php

(function () {
    \TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin(
        'Mittwald.Typo3Forum',
        'Forum',
        'LLL:EXT:typo3_forum/Resources/Private/Language/locallang_flexforms.xlf:Behaviour_Action_Forum',
        null,
        'TYPO3 Forum'
    );
    \TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin(
        'Mittwald.Typo3Forum',
        'UserProfile',
        'LLL:EXT:typo3_forum/Resources/Private/Language/locallang_flexforms.xlf:Behaviour_Action_User_Show',
        null,
        'TYPO3 Forum'
    );
    \TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin(
        'Mittwald.Typo3Forum',
        'ModerationReports',
        'LLL:EXT:typo3_forum/Resources/Private/Language/locallang_flexforms.xlf:Behaviour_Action_Moderation_Reports',
        null,
        'TYPO3 Forum'
    );
    \TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin(
        'Mittwald.Typo3Forum',
        'UserList',
        'LLL:EXT:typo3_forum/Resources/Private/Language/locallang_flexforms.xlf:Behaviour_Action_Users_List',
        null,
        'TYPO3 Forum'
    );
    \TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin(
        'Mittwald.Typo3Forum',
        'Dashboard',
        'LLL:EXT:typo3_forum/Resources/Private/Language/locallang_flexforms.xlf:Behaviour_Action_Dashboard',
        null,
        'TYPO3 Forum'
    );
    \TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin(
        'Mittwald.Typo3Forum',
        'TagList',
        'LLL:EXT:typo3_forum/Resources/Private/Language/locallang_flexforms.xlf:Behaviour_Action_Tags',
        null,
        'TYPO3 Forum'
    );
    \TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin(
        'Mittwald.Typo3Forum',
        'PostList',
        'LLL:EXT:typo3_forum/Resources/Private/Language/locallang_flexforms.xlf:Behaviour_Action_Posts_List',
        null,
        'TYPO3 Forum'
    );
    \TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin(
        'Mittwald.Typo3Forum',
        'TopicList',
        'LLL:EXT:typo3_forum/Resources/Private/Language/locallang_flexforms.xlf:Behaviour_Action_Topics_List',
        null,
        'TYPO3 Forum'
    );

    \TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin(
        'Mittwald.Typo3Forum',
        'StatsBox',
        'LLL:EXT:typo3_forum/Resources/Private/Language/locallang_flexforms.xlf:Behaviour_Widget_Stats_Box',
        null,
        'TYPO3 Forum'
    );

    $flexFormPath = 'FILE:EXT:typo3_forum/Configuration/FlexForms/';

    foreach ([
        'typo3forum_postlist' => 'PostList',
        'typo3forum_topiclist' => 'TopicList',
        'typo3forum_userlist' => 'UserList',
    ] as $pluginSignature => $flexFormFileName) {
        $GLOBALS['TCA']['tt_content']['types']['list']['subtypes_addlist'][$pluginSignature] = 'pi_flexform';
        \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPiFlexFormValue($pluginSignature, $flexFormPath . $flexFormFileName . '.xml');
    }
})();
