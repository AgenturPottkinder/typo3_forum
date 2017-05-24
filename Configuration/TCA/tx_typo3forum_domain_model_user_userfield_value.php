<?php

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addLLrefForTCAdescr(
	'tx_typo3forum_domain_model_user_userfield_value',
	'EXT:typo3_forum/Resources/Private/Language/locallang_csh_tx_typo3forum_domain_model_user_userfield_value.xml'
);

$lllPath = 'LLL:EXT:typo3_forum/Resources/Private/Language/locallang_db.xml:tx_typo3forum_domain_model_user_userfield_value.';

return [
	'ctrl' => [
		'title' => 'LLL:EXT:typo3_forum/Resources/Private/Language/locallang_db.xml:tx_typo3forum_domain_model_user_userfield_value',
		'label' => 'uid',
		'type' => 'user',
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
		'iconfile' => 'EXT:typo3_forum/Resources/Public/Icons/User/Userfield/Value.png'
	],
	'interface' => [
		'showRecordFieldList' => 'user,userfield,value'
	],
	'types' => [
		'0' => ['showitem' => 'user,userfield,value'],
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
		'user' => [
			'exclude' => 1,
			'label' => $lllPath . 'user',
			'config' => [
				'type' => 'select',
				'renderType' => 'selectSingle',
				'foreign_class' => '\Mittwald\Typo3Forum\Domain\Model\User\FrontendUser',
				'foreign_table' => 'fe_users',
				'maxitems' => 1
			],
		],
		'userfield' => [
			'exclude' => 1,
			'label' => $lllPath . 'userfield',
			'config' => [
				'type' => 'select',
				'renderType' => 'selectSingle',
				'foreign_table' => 'tx_typo3forum_domain_model_user_userfield_value',
				'maxitems' => 1
			],
		],
		'value' => [
			'exclude' => 1,
			'label' => $lllPath . 'value',
			'config' => [
				'type' => 'none',
			],
		],
	],
];
