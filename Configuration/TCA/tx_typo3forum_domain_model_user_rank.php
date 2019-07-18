<?php

return [
	'ctrl' => [
		'title' => 'LLL:EXT:typo3_forum/Resources/Private/Language/locallang_db.xml:tx_typo3forum_domain_model_user_rank',
		'label' => 'name',
		'sortby' => 'point_limit',
		'iconfile' => 'EXT:typo3_forum/Resources/Public/Icons/User/rank.png',
	],
	'interface' => [
		'showRecordFieldList' => 'name,point_limit,user_count',
	],
	'types' => [
		'1' => ['showitem' => 'name,point_limit,user_count'],
	],
	'columns' => [
		'name' => [
			'label' => 'LLL:EXT:typo3_forum/Resources/Private/Language/locallang_db.xml:tx_typo3forum_domain_model_user_rank.name',
			'config' => [
				'type' => 'input',
			],
		],
		'point_limit' => [
			'label' => 'LLL:EXT:typo3_forum/Resources/Private/Language/locallang_db.xml:tx_typo3forum_domain_model_user_rank.point_limit',
			'config' => [
				'type' => 'input',
			],
		],
		'user_count' => [
			'exclude' => 1,
			'label' => 'LLL:EXT:typo3_forum/Resources/Private/Language/locallang_db.xml:tx_typo3forum_domain_model_user_rank.user_count',
			'config' => [
				'type' => 'none',
			],
		],
	],
];
