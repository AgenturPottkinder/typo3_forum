<?php

return [
	'ctrl' => [
		'title' => 'LLL:EXT:typo3_forum/Resources/Private/Language/locallang_db.xml:tx_typo3forum_domain_model_forum_access',
		'label' => 'operation',
		'type' => 'login_level',
		'tstamp' => 'tstamp',
		'crdate' => 'crdate',
		'delete' => 'deleted',
		'enablecolumns' => [
			'disabled' => 'hidden'
		],
		'iconfile' => 'EXT:typo3_forum/Resources/Public/Icons/Forum/Access.png',
	],
	'interface' => [
		'showRecordFieldList' => 'login_level,operation,negate,forum,affected_group'
	],
	'types' => [
		'0' => ['showitem' => 'login_level,operation,negate,forum'],
		'1' => ['showitem' => 'login_level,operation,negate,forum'],
		'2' => ['showitem' => 'login_level,operation,negate,forum,affected_group'],
	],
	'columns' => [
		'hidden' => [
			'exclude' => 1,
			'label' => 'LLL:EXT:lang/Resources/Private/Language/locallang_general.xlf:LGL.hidden',
			'config' => [
				'type' => 'check'
			],
		],
		'operation' => [
			'exclude' => 1,
			'label' => 'LLL:EXT:typo3_forum/Resources/Private/Language/locallang_db.xml:tx_typo3forum_domain_model_forum_access.operation',
			'config' => [
				'type' => 'select',
				'renderType' => 'selectSingle',
				'maxitems' => 1,
				'items' => [
					['LLL:EXT:typo3_forum/Resources/Private/Language/locallang_db.xml:tx_typo3forum_domain_model_forum_access.operation.read', \Mittwald\Typo3Forum\Domain\Model\Forum\Access::TYPE_READ],
					['LLL:EXT:typo3_forum/Resources/Private/Language/locallang_db.xml:tx_typo3forum_domain_model_forum_access.operation.newTopic', \Mittwald\Typo3Forum\Domain\Model\Forum\Access::TYPE_NEW_TOPIC],
					['LLL:EXT:typo3_forum/Resources/Private/Language/locallang_db.xml:tx_typo3forum_domain_model_forum_access.operation.newPost', \Mittwald\Typo3Forum\Domain\Model\Forum\Access::TYPE_NEW_POST],
					['LLL:EXT:typo3_forum/Resources/Private/Language/locallang_db.xml:tx_typo3forum_domain_model_forum_access.operation.editPost', \Mittwald\Typo3Forum\Domain\Model\Forum\Access::TYPE_EDIT_POST],
					['LLL:EXT:typo3_forum/Resources/Private/Language/locallang_db.xml:tx_typo3forum_domain_model_forum_access.operation.deletePost', \Mittwald\Typo3Forum\Domain\Model\Forum\Access::TYPE_DELETE_POST],
					['LLL:EXT:typo3_forum/Resources/Private/Language/locallang_db.xml:tx_typo3forum_domain_model_forum_access.operation.moderation', \Mittwald\Typo3Forum\Domain\Model\Forum\Access::TYPE_MODERATE],
				],
			],
		],
		'negate' => [
			'exclude' => 1,
			'label' => 'LLL:EXT:typo3_forum/Resources/Private/Language/locallang_db.xml:tx_typo3forum_domain_model_forum_access.negate',
			'config' => [
				'type' => 'check',
				'default' => 0
			],
		],
		'forum' => [
			'exclude' => 1,
			'label' => 'LLL:EXT:typo3_forum/Resources/Private/Language/locallang_db.xml:tx_typo3forum_domain_model_forum_forum',
			'config' => [
				'type' => 'select',
				'renderType' => 'selectSingle',
				'foreign_class' => '\Mittwald\Typo3Forum\Domain\Model\Forum\Forum',
				'foreign_table' => 'tx_typo3forum_domain_model_forum_forum',
				'maxitems' => 1
			],
		],
		'affected_group' => [
			'exclude' => 1,
			'label' => 'LLL:EXT:typo3_forum/Resources/Private/Language/locallang_db.xml:tx_typo3forum_domain_model_forum_access.group',
			'config' => [
				'type' => 'select',
				'renderType' => 'selectSingle',
				'foreign_table' => 'fe_groups',
				'foreign_class' => '\Mittwald\Typo3Forum\Domain\Model\User\FrontendUserGroup',
				'maxitems' => 1
			],
		],
		'login_level' => [
			'exclude' => 1,
			'label' => 'LLL:EXT:typo3_forum/Resources/Private/Language/locallang_db.xml:tx_typo3forum_domain_model_forum_access.login_level',
			'config' => [
				'type' => 'select',
				'renderType' => 'selectSingle',
				'items' => [
					['LLL:EXT:typo3_forum/Resources/Private/Language/locallang_db.xml:tx_typo3forum_domain_model_forum_access.login_level.everyone', 0],
					['LLL:EXT:typo3_forum/Resources/Private/Language/locallang_db.xml:tx_typo3forum_domain_model_forum_access.login_level.anylogin', 1],
					['LLL:EXT:typo3_forum/Resources/Private/Language/locallang_db.xml:tx_typo3forum_domain_model_forum_access.login_level.specific', 2],
				],
			],
		],
	],
];
