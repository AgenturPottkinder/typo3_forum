<?php

$lllPath = 'LLL:EXT:typo3_forum/Resources/Private/Language/locallang_db.xml:tx_typo3forum_domain_model_forum_access.';

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addLLrefForTCAdescr(
	'tx_typo3forum_domain_model_forum_access',
	'EXT:typo3_forum/Resources/Private/Language/locallang_csh_tx_typo3forum_domain_model_forum_access.xml'
);

return [
	'ctrl' => [
		'title' => 'LLL:EXT:typo3_forum/Resources/Private/Language/locallang_db.xml:tx_typo3forum_domain_model_forum_access',
		'label' => 'operation',
		'type' => 'login_level',
		'tstamp' => 'tstamp',
		'crdate' => 'crdate',
		'versioningWS' => true,
		'origUid' => 't3_origuid',
		'languageField' => 'sys_language_uid',
		'transOrigPointerField' => 'l18n_parent',
		'transOrigDiffSourceField' => 'l18n_diffsource',
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
        'sys_language_uid' => [
            'exclude' => true,
            'label' => 'LLL:EXT:lang/Resources/Private/Language/locallang_general.xlf:LGL.language',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'foreign_table' => 'sys_language',
                'foreign_table_where' => 'ORDER BY sys_language.title',
                'items' => [
                    ['LLL:EXT:lang/Resources/Private/Language/locallang_general.xlf:LGL.allLanguages', -1],
                    ['LLL:EXT:lang/Resources/Private/Language/locallang_general.xlf:LGL.default_value', 0]
                ],
                'default' => 0,
                'fieldWizard' => [
                    'selectIcons' => [
                        'disabled' => false,
                    ],
                ],
            ]
        ],
		'l18n_parent' => [
			'displayCond' => 'FIELD:sys_language_uid:>:0',
			'exclude' => 1,
			'label' => 'LLL:EXT:lang/locallang_general.php:LGL.l18n_parent',
			'config' => [
				'type' => 'select',
				'renderType' => 'selectSingle',
				'items' => [
					['', 0],
				],
				'foreign_table' => 'tx_typo3forum_domain_model_forum_access',
				'foreign_table_where' => 'AND tx_typo3forum_domain_model_forum_access.uid=###REC_FIELD_l18n_parent### AND tx_typo3forum_domain_model_forum_access.sys_language_uid IN (-1,0)',
			],
		],
		'l18n_diffsource' => [
			'config' => [
				'type' => 'passthrough'
			],
		],
		't3ver_label' => [
			'displayCond' => 'FIELD:t3ver_label:REQ:true',
			'label' => 'LLL:EXT:lang/locallang_general.php:LGL.versionLabel',
			'config' => [
				'type' => 'none',
				'cols' => 27
			],
		],
		'hidden' => [
			'exclude' => 1,
			'label' => 'LLL:EXT:lang/locallang_general.xml:LGL.hidden',
			'config' => [
				'type' => 'check'
			],
		],
		'operation' => [
			'exclude' => 1,
			'label' => $lllPath . 'operation',
			'config' => [
				'type' => 'select',
				'renderType' => 'selectSingle',
				'maxitems' => 1,
				'items' => [
					[$lllPath . 'operation.read', \Mittwald\Typo3Forum\Domain\Model\Forum\Access::TYPE_READ],
					[$lllPath . 'operation.newTopic', \Mittwald\Typo3Forum\Domain\Model\Forum\Access::TYPE_NEW_TOPIC],
					[$lllPath . 'operation.newPost', \Mittwald\Typo3Forum\Domain\Model\Forum\Access::TYPE_NEW_POST],
					[$lllPath . 'operation.editPost', \Mittwald\Typo3Forum\Domain\Model\Forum\Access::TYPE_EDIT_POST],
					[$lllPath . 'operation.deletePost', \Mittwald\Typo3Forum\Domain\Model\Forum\Access::TYPE_DELETE_POST],
					[$lllPath . 'operation.moderation', \Mittwald\Typo3Forum\Domain\Model\Forum\Access::TYPE_MODERATE],
				],
			],
		],
		'negate' => [
			'exclude' => 1,
			'label' => $lllPath . 'negate',
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
			'label' => $lllPath . 'group',
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
			'label' => $lllPath . 'login_level',
			'config' => [
				'type' => 'select',
				'renderType' => 'selectSingle',
				'items' => [
					[$lllPath . 'login_level.everyone', 0],
					[$lllPath . 'login_level.anylogin', 1],
					[$lllPath . 'login_level.specific', 2],
				],
			],
		],
	],
];
