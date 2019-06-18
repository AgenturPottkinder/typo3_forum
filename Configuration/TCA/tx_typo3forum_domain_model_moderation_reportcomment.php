<?php

return [
	'ctrl' => [
		'title' => 'LLL:EXT:typo3_forum/Resources/Private/Language/locallang_db.xml:tx_typo3forum_domain_model_moderation_reportcomment',
		'label' => 'text',
		'tstamp' => 'tstamp',
		'delete' => 'deleted',
		'enablecolumns' => [
			'disabled' => 'hidden'
		],
		'iconfile' => 'EXT:typo3_forum/Resources/Public/Icons/Moderation/ReportComment.png'
	],
	'interface' => [
		'showRecordFieldList' => 'report,author,text,tstamp'
	],
	'types' => [
		'1' => ['showitem' => 'report,author,text'],
	],
	'columns' => [
		'hidden' => [
			'exclude' => 1,
			'label'   => 'LLL:EXT:lang/Resources/Private/Language/locallang_general.xlf:LGL.hidden',
			'config'  => [
				'type' => 'check',
			],
		],
		'tstamp' => [
			'exclude' => 1,
			'label'   => 'LLL:EXT:lang/Resources/Private/Language/locallang_general.xlf:LGL.timestamp',
			'config'  => [
				'type' => 'passthrough',
			],
		],
		'report' => [
			'exclude' => 1,
			'label'   => 'LLL:EXT:typo3_forum/Resources/Private/Language/locallang_db.xml:tx_typo3forum_domain_model_moderation_reportcomment.report',
			'config' => [
				'type' => 'select',
				'renderType' => 'selectSingle',
				'foreign_table' => 'tx_typo3forum_domain_model_moderation_report',
				'maxitems' => 1,
			],
		],
		'author' => [
			'exclude' => 1,
			'label'   => 'LLL:EXT:typo3_forum/Resources/Private/Language/locallang_db.xml:tx_typo3forum_domain_model_moderation_reportcomment.author',
			'config' => [
				'type' => 'select',
				'renderType' => 'selectSingle',
				'foreign_table' => 'fe_users',
				'maxitems' => 1,
			],
		],
		'text' => [
			'exclude' => 1,
			'label'   => 'LLL:EXT:typo3_forum/Resources/Private/Language/locallang_db.xml:tx_typo3forum_domain_model_moderation_report.moderator',
			'config' => [
				'type' => 'text',
			],
		],
	],
];
