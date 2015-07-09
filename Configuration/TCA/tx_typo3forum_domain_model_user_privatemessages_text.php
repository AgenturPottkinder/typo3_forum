<?php

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::allowTableOnStandardPages('tx_typo3forum_domain_model_user_privatemessages_text');

$lllPath = 'LLL:EXT:typo3_forum/Resources/Private/Language/locallang_db.xml:tx_typo3forum_domain_model_user_privatemessages_text.';

return [
	'ctrl' => [
		'title' => 'LLL:EXT:typo3_forum/Resources/Private/Language/locallang_db.xml:tx_typo3forum_domain_model_user_privatemessages_text',
		'label' => 'uid',
		'iconfile' => \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extRelPath('typo3_forum') . 'Resources/Public/Icons/User/pm.png',
	],
	'interface' => [
		'showRecordFieldList' => 'message_text',
	],
	'types' => [
		'1' => ['showitem' => 'message_text']
	],
	'columns' => [
		'message_text' => [
			'label' => $lllPath . 'message_text',
			'config' => [
				'type' => 'text',
			],
		],
	]
];
