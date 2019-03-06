<?php

defined('TYPO3_MODE') or die();

(function ($_EXTKEY) {
    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addStaticFile(
        $_EXTKEY,
        'Configuration/TypoScript',
        'typo3_forum'
    );

    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addStaticFile(
        $_EXTKEY,
        'Configuration/TypoScript/Bootstrap',
        'typo3_forum Bootstrap Template'
    );
    
})($_EXTKEY);
