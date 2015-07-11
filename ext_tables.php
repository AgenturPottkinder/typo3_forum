<?php

if (!defined('TYPO3_MODE'))
	die('Access denied.');

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin(
	'Mittwald.Typo3Forum', 'Pi1', 'typo3_forum'
);
\TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin(
	'Mittwald.Typo3Forum', 'Widget', 'typo3_forum Widgets'
);

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addStaticFile($_EXTKEY, 'Configuration/TypoScript', 'typo3_forum');
