<?php

return [
	'ctrl' => [
		'title' => 'LLL:EXT:typo3_forum/Resources/Private/Language/locallang_db.xml:tx_typo3forum_domain_model_forum_ad',
		'label' => 'name',
		'tstamp' => 'tstamp',
		'delete' => 'deleted',
		'iconfile' => 'EXT:typo3_forum/Resources/Public/Icons/Forum/Ad.png',
	],
	'interface' => [
		'showRecordFieldList' => 'name,alt_text,url,path,active,category,groups',
	],
	'types' => [
		'1' => ['showitem' => 'name,alt_text,url,path,active,category,groups'],
	],
	'columns' => [
		'name' => [
			'label' => 'LLL:EXT:typo3_forum/Resources/Private/Language/locallang_db.xml:tx_typo3forum_domain_model_forum_ad.name',
			'config' => [
				'type' => 'input',
			],
		],
		'alt_text' => [
			'label' => 'LLL:EXT:typo3_forum/Resources/Private/Language/locallang_db.xml:tx_typo3forum_domain_model_forum_ad.alt',
			'config' => [
				'type' => 'text',
			],
		],
		'url' => [
			'label' => 'LLL:EXT:typo3_forum/Resources/Private/Language/locallang_db.xml:tx_typo3forum_domain_model_forum_ad.url',
			'config' => [
				'type' => 'input',
			],
		],
		'path' => [
			'label' => 'LLL:EXT:typo3_forum/Resources/Private/Language/locallang_db.xml:tx_typo3forum_domain_model_forum_ad.path',
			'config' => [
				'type' => 'input',
			],
		],
		'active' => [
			'label' => 'LLL:EXT:typo3_forum/Resources/Private/Language/locallang_db.xml:tx_typo3forum_domain_model_forum_ad.active',
			'config' => [
				'type' => 'check',
			],
		],
		'category' => [
			'label' => 'LLL:EXT:typo3_forum/Resources/Private/Language/locallang_db.xml:tx_typo3forum_domain_model_forum_ad.category',
			'config' => [
				'type' => 'radio',
				'items' => [
					['all', 0],
					['forum only', 1],
					['topic only', 2],
				],
				'default' => 0,
			],
		],
	],
];
