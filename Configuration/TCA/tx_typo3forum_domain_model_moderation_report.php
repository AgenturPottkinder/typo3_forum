<?php

$lllPath = 'LLL:EXT:typo3_forum/Resources/Private/Language/locallang_db.xml:tx_typo3forum_domain_model_moderation_report.';

return [
	'ctrl' => [
		'title' => 'LLL:EXT:typo3_forum/Resources/Private/Language/locallang_db.xml:tx_typo3forum_domain_model_moderation_report',
		'label' => 'post',
		'type' => 'type',
		'tstamp' => 'tstamp',
		'crdate' => 'crdate',
		'delete' => 'deleted',
		'enablecolumns' => [
			'disabled' => 'hidden',
		],
		'iconfile' => 'EXT:typo3_forum/Resources/Public/Icons/Moderation/Report.png',
	],
	'interface' => [
		'showRecordFieldList' => 'type,reporter,moderator,workflow_status,comments, post, feuser',
	],
	'types' => [
		'1' => ['showitem' => 'type,reporter,moderator,workflow_status,comments'],
		'Mittwald\Typo3Forum\Domain\Model\Moderation\UserReport' => ['showitem' => 'type,reporter,moderator,workflow_status,comments, feuser'],
		'Mittwald\Typo3Forum\Domain\Model\Moderation\PostReport' => ['showitem' => 'type,reporter,moderator,workflow_status,comments, post'],
	],
	'columns' => [
		'hidden' => [
			'exclude' => 1,
			'label' => 'LLL:EXT:lang/locallang_general.xml:LGL.hidden',
			'config' => [
				'type' => 'check',
			],
		],
		'crdate' => [
			'exclude' => 1,
			'label' => 'LLL:EXT:lang/locallang_general.xml:LGL.crdate',
			'config' => [
				'type' => 'passthrough',
			],
		],
		'type' => [
			'exclude' => 1,
			'label' => $lllPath . 'type',
			'config' => [
				'type' => 'select',
				'renderType' => 'selectSingle',
				'items' => [
					[$lllPath . 'type.userReport', 'Mittwald\Typo3Forum\Domain\Model\Moderation\UserReport'],
					[$lllPath . 'type.postReport', 'Mittwald\Typo3Forum\Domain\Model\Moderation\PostReport'],
				],
			],
		],
		'post' => [
			'label' => $lllPath . 'post',
			'config' => [
				'type' => 'select',
				'renderType' => 'selectSingle',
				'foreign_table' => 'tx_typo3forum_domain_model_forum_post',
				'maxitems' => 1
			],
		],
		'feuser' => [
			'exclude' => 1,
			'label' => $lllPath . 'user',
			'config' => [
				'type' => 'select',
				'renderType' => 'selectSingle',
				'foreign_table' => 'fe_users',
				'maxitems' => 1
			],
		],
		'reporter' => [
			'exclude' => 1,
			'label' => $lllPath . 'reporter',
			'config' => [
				'type' => 'select',
				'renderType' => 'selectSingle',
				'foreign_table' => 'fe_users',
				'maxitems' => 1
			],
		],
		'moderator' => [
			'exclude' => 1,
			'label' => $lllPath . 'moderator',
			'config' => [
				'type' => 'select',
				'renderType' => 'selectSingle',
				'foreign_table' => 'fe_users',
				'maxitems' => 1
			],
		],
		'workflow_status' => [
			'exclude' => 1,
			'label' => $lllPath . 'workflow_status',
			'config' => [
				'type' => 'select',
				'renderType' => 'selectSingle',
				'foreign_table' => 'tx_typo3forum_domain_model_moderation_reportworkflowstatus',
				'maxitems' => 1
			],
		],
		'comments' => [
			'exclude' => 1,
			'label' => $lllPath . 'comments',
			'config' => [
				'type' => 'inline',
				'foreign_table' => 'tx_typo3forum_domain_model_moderation_reportcomment',
				'foreign_field' => 'report',
				'maxitems' => 9999,
				'foreign_sortby' => 'tstamp',
				'appearance' => [
					'collapseAll' => TRUE,
					'levelLinksPosition' => 'top',
				],
			],
		],
	],
];
