<?php

return [
	'ctrl' => [
		'title' => 'LLL:EXT:typo3_forum/Resources/Private/Language/locallang_db.xml:tx_typo3forum_domain_model_forum_forum',
		'label' => 'title',
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
		'sortby' => 'sorting',
		'iconfile' => 'EXT:typo3_forum/Resources/Public/Icons/Forum/Forum.png',
	],
	'interface' => [
		'showRecordFieldList' => 'hidden,title,description,children,acls,criteria,topics,topic_count,post_count,last_topic,last_post,forum,subscribers,readers,displayed_pid',
	],
	'types' => [
		'1' => ['showitem' => 'hidden,title,description,children,acls,criteria,topics,last_topic,last_post,forum,subscribers,readers'],
	],
    'palettes' => [
        'language' => ['showitem' => 'sys_language_uid, l18n_parent'],
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
            'exclude' => true,
            'label' => 'LLL:EXT:lang/Resources/Private/Language/locallang_general.xlf:LGL.l18n_parent',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'items' => [
                    ['', 0]
                ],
                'foreign_table' => 'sys_category',
                'foreign_table_where' => 'AND sys_category.uid=###REC_FIELD_l18n_parent### AND sys_category.sys_language_uid IN (-1,0)',
                'default' => 0
            ]
        ],
        'l18n_diffsource' => [
            'config' => [
                'type' => 'passthrough',
                'default' => ''
            ]
        ],
		't3ver_label' => [
			'displayCond' => 'FIELD:t3ver_label:REQ:true',
			'label' => 'LLL:EXT:lang/Resources/Private/Language/locallang_general.xlf:LGL.versionLabel',
			'config' => [
				'type' => 'none',
				'cols' => 27,
			],
		],
		'hidden' => [
			'exclude' => 1,
			'label' => 'LLL:EXT:lang/Resources/Private/Language/locallang_general.xlf:LGL.hidden',
			'config' => [
				'type' => 'check',
			],
		],
		'title' => [
			'exclude' => 1,
			'label' => 'LLL:EXT:typo3_forum/Resources/Private/Language/locallang_db.xml:tx_typo3forum_domain_model_forum_forum.title',
			'config' => [
				'type' => 'input',
				'size' => 30,
				'eval' => 'trim,required',
			],
		],
		'description' => [
			'exclude' => 1,
			'label' => 'LLL:EXT:typo3_forum/Resources/Private/Language/locallang_db.xml:tx_typo3forum_domain_model_forum_forum.description',
			'config' => [
				'type' => 'input',
				'size' => 30,
				'eval' => 'trim',
			],
		],
		'children' => [
			'exclude' => 1,
			'label' => 'LLL:EXT:typo3_forum/Resources/Private/Language/locallang_db.xml:tx_typo3forum_domain_model_forum_forum.children',
			'config' => [
				'type' => 'inline',
				'foreign_table' => 'tx_typo3forum_domain_model_forum_forum',
				'foreign_field' => 'forum',
				'foreign_sortby' => 'sorting',
				'maxitems' => 9999,
				'appearance' => [
					'collapse' => 0,
					'newRecordLinkPosition' => 'bottom',
				],
			]
		],
		'topics' => [
			'exclude' => 1,
			'label' => 'LLL:EXT:typo3_forum/Resources/Private/Language/locallang_db.xml:tx_typo3forum_domain_model_forum_forum.topics',
			'config' => [
				'type' => 'inline',
				'foreign_table' => 'tx_typo3forum_domain_model_forum_topic',
				'foreign_default_sortby' => 'ORDER BY tx_typo3forum_domain_model_forum_topic.sticky DESC, tx_typo3forum_domain_model_forum_topic.last_post_crdate DESC',
				'foreign_field' => 'forum',
				'maxitems' => 999999,
				'appearance' => [
					'collapse' => 0,
					'newRecordLinkPosition' => 'bottom',
				],
			]
		],
		'criteria' => [
			'exclude' => 1,
			'label' => 'LLL:EXT:typo3_forum/Resources/Private/Language/locallang_db.xml:tx_typo3forum_domain_model_forum_criteria',
			'config' => [
				'type' => 'select',
				'size' => 10,
				'maxitems' => 99999,
				'renderType' => 'selectMultipleSideBySide',
				'foreign_table' => 'tx_typo3forum_domain_model_forum_criteria',
				'MM' => 'tx_typo3forum_domain_model_forum_criteria_forum'
			],
		],
		'topic_count' => [
			'label' => 'LLL:EXT:typo3_forum/Resources/Private/Language/locallang_db.xml:tx_typo3forum_domain_model_forum_forum.topic_count',
			'config' => [
				'type' => 'none'
			]
		],
		'post_count' => [
			'label' => 'LLL:EXT:typo3_forum/Resources/Private/Language/locallang_db.xml:tx_typo3forum_domain_model_forum_forum.post_count',
			'config' => [
				'type' => 'none'
			]
		],
		'acls' => [
			'exclude' => 1,
			'label' => 'LLL:EXT:typo3_forum/Resources/Private/Language/locallang_db.xml:tx_typo3forum_domain_model_forum_forum.acls',
			'config' => [
				'type' => 'inline',
				'foreign_table' => 'tx_typo3forum_domain_model_forum_access',
				'foreign_field' => 'forum',
				'maxitems' => 9999,
				'appearance' => [
					'collapse' => 0,
					'newRecordLinkPosition' => 'bottom',
				],
			]
		],
		'last_topic' => [
			'exclude' => 1,
			'label' => 'LLL:EXT:typo3_forum/Resources/Private/Language/locallang_db.xml:tx_typo3forum_domain_model_forum_forum.last_topic',
			'config' => [
				'type' => 'none',
				'foreign_table' => 'tx_typo3forum_domain_model_forum_topic',
				'minitems' => 0,
				'maxitems' => 1
			]
		],
		'last_post' => [
			'exclude' => 1,
			'label' => 'LLL:EXT:typo3_forum/Resources/Private/Language/locallang_db.xml:tx_typo3forum_domain_model_forum_forum.last_post',
			'config' => [
				'type' => 'none',
				'foreign_table' => 'tx_typo3forum_domain_model_forum_post',
				'minitems' => 0,
				'maxitems' => 1
			]
		],
		'forum' => [
			'exclude' => 1,
			'label' => 'LLL:EXT:typo3_forum/Resources/Private/Language/locallang_db.xml:tx_typo3forum_domain_model_forum_forum.forum',
			'config' => [
				'type' => 'select',
				'renderType' => 'selectSingle',
				'foreign_table' => 'tx_typo3forum_domain_model_forum_forum',
				'items' => [
					['-', 0],
				],
				'maxitems' => 1
			]
		],
		'subscribers' => [
			'exclude' => 1,
			'label' => 'LLL:EXT:typo3_forum/Resources/Private/Language/locallang_db.xml:tx_typo3forum_domain_model_forum_forum.subscribers',
			'config' => [
				'type' => 'inline',
				'foreign_table' => 'fe_users',
				'MM' => 'tx_typo3forum_domain_model_user_forumsubscription',
				'MM_opposite_field' => 'tx_typo3forum_forum_subscriptions',
				'maxitems' => 9999,
				'size' => 10
			]
		],
		'readers' => [
			'exclude' => 1,
			'label' => 'LLL:EXT:typo3_forum/Resources/Private/Language/locallang_db.xml:tx_typo3forum_domain_model_forum_forum.readers',
			'config' => [
				'type' => 'inline',
				'foreign_table' => 'fe_users',
				'MM' => 'tx_typo3forum_domain_model_user_readforum',
				'MM_opposite_field' => 'tx_typo3forum_read_forum',
				'size' => 10
			],
		],
		'displayed_pid' => [
			'label' => 'LLL:EXT:typo3_forum/Resources/Private/Language/locallang_db.xml:tx_typo3forum_domain_model_forum_forum.displayed_pid',
			'config' => [
				'type' => 'none',
			],
		],
		'sorting' => [
			'label' => 'LLL:EXT:typo3_forum/Resources/Private/Language/locallang_db.xml:tx_typo3forum_domain_model_forum_forum.sorting',
			'config' => [
				'type' => 'none',
			],
		],
	],
];
