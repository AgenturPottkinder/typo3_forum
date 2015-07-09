<?php

if (!defined('TYPO3_MODE'))
	die('Access denied.');

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin(
	'Mittwald.Typo3Forum', 'Pi1', 'typo3_forum'
);
\TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin(
	'Mittwald.Typo3Forum', 'Widget', 'typo3_forum Widgets'
);

$extPath = \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extPath($_EXTKEY);

if (TYPO3_MODE === 'BE') {
	\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::registerExtDirectComponent(
		'Typo3Forum.ForumIndex.DataProvider',
		$extPath . 'Classes/ExtDirect/ForumDataProvider.php:\Mittwald\Typo3Forum\ExtDirect\ForumDataProvider',
		'web', 'user,group'
	);

	\TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerModule(
		'Mittwald.Typo3Forum', 'web', 'tx_typo3forum_m1', '', ['Backend' => 'indexForum', 'Forum' => 'update'],
		[
			'access' => 'user,group',
			'icon' => 'EXT:' . $_EXTKEY . '/ext_icon.gif',
			'labels' => 'LLL:EXT:' . $_EXTKEY . '/Resources/Private/Language/locallang_mod.xml',
			'navigationComponentId' => 'typo3-pagetree',
		]
	);
}

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addStaticFile($_EXTKEY, 'Configuration/TypoScript', 'typo3_forum');
