<?php

return [
	'ctrl' => [
		'title' => 'LLL:EXT:typo3_forum/Resources/Private/Language/locallang_db.xml:tx_typo3forum_domain_model_user_privatemessage_text',
		'label' => 'uid',
		'iconfile' => 'EXT:typo3_forum/Resources/Public/Icons/User/pm.png',
	],
	'interface' => [
		'showRecordFieldList' => 'message_text',
	],
	'types' => [
		'1' => ['showitem' => 'message_text']
	],
	'columns' => [
		'message_text' => [
			'label' => 'LLL:EXT:typo3_forum/Resources/Private/Language/locallang_db.xml:tx_typo3forum_domain_model_user_privatemessage_text.message_text',
			'config' => [
				'type' => 'text',
			],
		],
	]
];
