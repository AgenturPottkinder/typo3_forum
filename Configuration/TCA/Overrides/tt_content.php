<?php

(function () {
    $flexFormPath = 'FILE:EXT:typo3_forum/Configuration/FlexForms/';
    \TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin('Mittwald.Typo3Forum', 'Pi1', 'typo3_forum');
    \TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin('Mittwald.Typo3Forum', 'Widget', 'typo3_forum Widgets');

    $pluginSignature = strtolower(\TYPO3\CMS\Core\Utility\GeneralUtility::underscoredToUpperCamelCase('typo3_forum')) . '_pi1';
    $GLOBALS['TCA']['tt_content']['types']['list']['subtypes_addlist'][$pluginSignature] = 'pi_flexform';
    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPiFlexFormValue($pluginSignature, $flexFormPath . 'Pi1.xml');

    $pluginSignature = strtolower(\TYPO3\CMS\Core\Utility\GeneralUtility::underscoredToUpperCamelCase('typo3_forum')) . '_widget';
    $GLOBALS['TCA']['tt_content']['types']['list']['subtypes_addlist'][$pluginSignature] = 'pi_flexform';
    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPiFlexFormValue($pluginSignature, $flexFormPath . 'Widgets.xml');
})();
