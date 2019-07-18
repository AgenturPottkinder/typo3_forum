<?php

return [
	'ctrl' => [
		'title' => 'LLL:EXT:typo3_forum/Resources/Private/Language/locallang_db.xml:tx_typo3forum_domain_model_forum_tag',
		'label' => 'name',
		'tstamp' => 'tstamp',
		'crdate' => 'crdate',
		'delete' => 'deleted',
		'iconfile' => 'EXT:typo3_forum/Resources/Public/Icons/Forum/Tag.png',
	],
	'interface' => [
		'showRecordFieldList' => 'name,tstamp,crdate,topic_count,feuser',
	],
	'types' => [
		'1' => ['showitem' => 'name,tstamp,crdate,topic_count,feuser'],
	],
	'columns' => [
		'name' => [
			'label' => 'LLL:EXT:typo3_forum/Resources/Private/Language/locallang_db.xml:tx_typo3forum_domain_model_forum_tag.name',
			'config' => [
				'type' => 'input',
			]
		],
		'tstamp' => [
			'label' => 'LLL:EXT:typo3_forum/Resources/Private/Language/locallang_db.xml:tx_typo3forum_domain_model_forum_tag.tstamp',
			'config' => [
				'type' => 'none',
				'format' => 'date',
				'eval' => 'date',
			]
		],
		'crdate' => [
			'label' => 'LLL:EXT:typo3_forum/Resources/Private/Language/locallang_db.xml:tx_typo3forum_domain_model_forum_tag.crdate',
			'config' => [
				'type' => 'none',
				'format' => 'date',
				'eval' => 'date',
			]
		],
		'topic_count' => [
			'label' => 'LLL:EXT:typo3_forum/Resources/Private/Language/locallang_db.xml:tx_typo3forum_domain_model_forum_tag.topicCount',
			'config' => [
				'type' => 'none',
			],
		],
		'feuser' => [
			'label' => 'LLL:EXT:typo3_forum/Resources/Private/Language/locallang_db.xml:tx_typo3forum_domain_model_forum_tag.feuser',
			'config' => [
				'type' => 'select',
				'size' => 10,
				'maxitems' => 99999,
				'foreign_table' => 'fe_users',
				'MM' => 'tx_typo3forum_domain_model_forum_tag_user',
				'renderType' => 'selectSingleBox',
			],
		],
	]
];
