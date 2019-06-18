<?php

return [
	'ctrl' => [
		'title' => 'LLL:EXT:typo3_forum/Resources/Private/Language/locallang_db.xml:tx_typo3forum_domain_model_user_userfield_userfield',
		'label' => 'name',
		'type' => 'type',
		'tstamp' => 'tstamp',
		'crdate' => 'crdate',
		'versioningWS' => true,
		'origUid' => 't3_origuid',
		'languageField' => 'sys_language_uid',
		'transOrigPointerField' => 'l18n_parent',
		'transOrigDiffSourceField' => 'l18n_diffsource',
		'delete' => 'deleted',
		'enablecolumns' => [
			'disabled' => 'hidden',
		],
		'iconfile' => 'EXT:typo3_forum/Resources/Public/Icons/User/Userfield/Userfield.png',
	],
	'interface' => [
		'showRecordFieldList' => 'type,name,typoscript_path,map_to_user_object',
	],
	'types' => [
		'0' => ['showitem' => 'type,name,map_to_user_object'],
		'Mittwald\Typo3Forum\Domain\Model\User\Userfield\TyposcriptUserfield' => ['showitem' => 'type,name,typoscript_path,map_to_user_object'],
		'Mittwald\Typo3Forum\Domain\Model\User\Userfield\TextUserfield' => ['showitem' => 'type,name,map_to_user_object'],
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
				'foreign_table' => 'tx_typo3forum_domain_model_user_userfield_userfield',
				'foreign_table_where' => 'AND tx_typo3forum_domain_model_user_userfield_userfield.uid=###REC_FIELD_l18n_parent### AND tx_typo3forum_domain_model_user_userfield_userfield.sys_language_uid IN (-1,0)',
			],
		],
		'l18n_diffsource' => [
			'config' => [
				'type' => 'passthrough',
			],
		],
		't3ver_label' => [
			'displayCond' => 'FIELD:t3ver_label:REQ:true',
			'label' => 'LLL:EXT:lang/locallang_general.php:LGL.versionLabel',
			'config' => [
				'type' => 'none',
				'cols' => 27,
			],
		],
		'hidden' => [
			'exclude' => 1,
			'label' => 'LLL:EXT:lang/locallang_general.xml:LGL.hidden',
			'config' => [
				'type' => 'check',
			],
		],
		'type' => [
			'label' => 'LLL:EXT:typo3_forum/Resources/Private/Language/locallang_db.xml:tx_typo3forum_domain_model_user_userfield_userfield.type',
			'config' => [
				'type' => 'select',
				'renderType' => 'selectSingle',
				'items' => [
					['LLL:EXT:typo3_forum/Resources/Private/Language/locallang_db.xml:tx_typo3forum_domain_model_user_userfield_userfield.type.undefined', 0],
					['LLL:EXT:typo3_forum/Resources/Private/Language/locallang_db.xml:tx_typo3forum_domain_model_user_userfield_userfield.type.typoscript', 'Mittwald\Typo3Forum\Domain\Model\User\Userfield\TyposcriptUserfield'],
					['LLL:EXT:typo3_forum/Resources/Private/Language/locallang_db.xml:tx_typo3forum_domain_model_user_userfield_userfield.type.text', 'Mittwald\Typo3Forum\Domain\Model\User\Userfield\TextUserfield'],
				],
			],
		],
		'name' => [
			'exclude' => 1,
			'label' => 'LLL:EXT:typo3_forum/Resources/Private/Language/locallang_db.xml:tx_typo3forum_domain_model_user_userfield_userfield.name',
			'config' => [
				'type' => 'input',
				'size' => 30,
				'eval' => 'trim,required',
			],
		],
		'typoscript_path' => [
			'exclude' => 1,
			'label' => 'LLL:EXT:typo3_forum/Resources/Private/Language/locallang_db.xml:tx_typo3forum_domain_model_user_userfield_userfield.typoscript_path',
			'config' => [
				'type' => 'input',
				'size' => 30,
				'eval' => 'trim,required',
			],
		],
		'map_to_user_object' => [
			'exclude' => 1,
			'label' => 'LLL:EXT:typo3_forum/Resources/Private/Language/locallang_db.xml:tx_typo3forum_domain_model_user_userfield_userfield.map_to_user_object',
			'config' => [
				'type' => 'input',
				'size' => 30,
				'eval' => 'trim',
				'checkbox' => TRUE,
			],
		],
	],
];

