<?php

$tempColumns = [
	'tx_typo3forum_user_mod' => [
		'label' => 'LLL:EXT:typo3_forum/Resources/Private/Language/locallang_db.xml:fe_groups.user_mod',
		'config' => [
			'type' => 'check',
		],
	],
];
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTCAcolumns('fe_groups', $tempColumns);
$GLOBALS['TCA']['fe_groups']['types']['Mittwald\Typo3Forum\Domain\Model\User\FrontendUserGroup'] = $GLOBALS['TCA']['fe_groups']['types']['0'];
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTcaSelectItem(
	'fe_groups',
	'tx_extbase_type',
	[
		'LLL:EXT:typo3_forum/Resources/Private/Language/locallang_db.xml:fe_groups.tx_extbase_type.typo3_forum',
		'Mittwald\Typo3Forum\Domain\Model\User\FrontendUserGroup',
	]
);
$GLOBALS['TCA']['fe_groups']['types']['Mittwald\Typo3Forum\Domain\Model\User\FrontendUserGroup']['showitem'] .=
	',--div--;LLL:EXT:typo3_forum/Resources/Private/Language/locallang_db.xml:fe_users.tx_typo3forum.tab.settings,' .
	'tx_typo3forum_user_mod';
