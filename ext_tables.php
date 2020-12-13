<?php

defined('TYPO3_MODE') or die();

(function () {
    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addLLrefForTCAdescr(
        'tx_typo3forum_domain_model_format_textparser',
        'EXT:typo3_forum/Resources/Private/Language/locallang_csh_format_textparser.xlf'
    );
    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addLLrefForTCAdescr(
        'tx_typo3forum_domain_model_forum_access',
        'EXT:typo3_forum/Resources/Private/Language/locallang_csh_forum_access.xlf'
    );
    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addLLrefForTCAdescr(
        'tx_typo3forum_domain_model_forum_attachment',
        'EXT:typo3_forum/Resources/Private/Language/locallang_csh_forum_attachment.xlf'
    );
    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addLLrefForTCAdescr(
        'tx_typo3forum_domain_model_forum_forum',
        'EXT:typo3_forum/Resources/Private/Language/locallang_csh_forum_forum.xlf'
    );
    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addLLrefForTCAdescr(
        'tx_typo3forum_domain_model_forum_post',
        'EXT:typo3_forum/Resources/Private/Language/locallang_csh_forum_post.xlf'
    );
    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addLLrefForTCAdescr(
        'tx_typo3forum_domain_model_forum_topic',
        'EXT:typo3_forum/Resources/Private/Language/locallang_csh_forum_topic.xlf'
    );
    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addLLrefForTCAdescr(
        'tx_typo3forum_domain_model_user_userfield_userfield',
        'EXT:typo3_forum/Resources/Private/Language/locallang_csh_user_userfield_userfield.xlf'
    );
    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addLLrefForTCAdescr(
        'tx_typo3forum_domain_model_user_userfield_value',
        'EXT:typo3_forum/Resources/Private/Language/locallang_csh_user_userfield_value.xlf'
    );
})();
