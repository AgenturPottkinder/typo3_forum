<?php

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addLLrefForTCAdescr(
	'tx_typo3forum_domain_model_forum_topic',
	'EXT:typo3_forum/Resources/Private/Language/locallang_csh_tx_typo3forum_domain_model_forum_topic.xml'
);

$lllPath = 'LLL:EXT:typo3_forum/Resources/Private/Language/locallang_db.xml:tx_typo3forum_domain_model_forum_topic.';

return [
	'ctrl' => [
		'title' => 'LLL:EXT:typo3_forum/Resources/Private/Language/locallang_db.xml:tx_typo3forum_domain_model_forum_topic',
		'type' => 'type',
		'label' => 'subject',
		'tstamp' => 'tstamp',
		'crdate' => 'crdate',
		'versioningWS' => true,
		'origUid' => 't3_origuid',
		'languageField' => 'sys_language_uid',
		'delete' => 'deleted',
		'enablecolumns' => [
			'disabled' => 'hidden'
		],
		'iconfile' => 'EXT:typo3_forum/Resources/Public/Icons/Forum/Topic.png'
	],
	'interface' => [
		'showRecordFieldList' => 'type,subject,posts,author,subscribers,last_post,forum,target,question,criteria_options,solution,fav_subscribers,tags'
	],
	'types' => [
		'0' => ['showitem' => 'type,subject,posts,author,subscribers,last_post,forum,readers,question,solution,fav_subscribers,tags'],
		'1' => ['showitem' => 'type,subject,forum,last_post,target'],
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
		't3ver_label' => [
			'displayCond' => 'FIELD:t3ver_label:REQ:true',
			'label' => 'LLL:EXT:lang/locallang_general.php:LGL.versionLabel',
			'config' => [
				'type' => 'none',
				'cols' => 27
			],
		],
		'crdate' => [
			'exclude' => 1,
			'label' => 'LLL:EXT:lang/locallang_general.xml:LGL.crdate',
			'config' => [
				'type' => 'passthrough'
			],
		],
		'hidden' => [
			'exclude' => 1,
			'label' => 'LLL:EXT:lang/locallang_general.xml:LGL.hidden',
			'config' => [
				'type' => 'check'
			],
		],
		'type' => [
			'exclude' => 1,
			'label' => $lllPath . 'type',
			'config' => [
				'type' => 'select',
				'renderType' => 'selectSingle',
				'maxitems' => 1,
				'minitems' => 1,
				'default' => 0,
				'items' => [
					[$lllPath . 'type.0', 0],
					[$lllPath . 'type.1', 1],
				],
			],
		],
		'subject' => [
			'exclude' => 1,
			'label' => $lllPath . 'subject',
			'config' => [
				'type' => 'input',
				'size' => 30,
				'eval' => 'trim,required'
			],
		],
		'posts' => [
			'label' => $lllPath . 'posts',
			'config' => [
				'type' => 'inline',
				'foreign_sortby' => 'uid',
				'foreign_table' => 'tx_typo3forum_domain_model_forum_post',
				'foreign_field' => 'topic',
				'maxitems' => 9999,
				'appearance' => [
					'collapse' => 0,
					'newRecordLinkPosition' => 'bottom',
				],
			],
		],
		'post_count' => [
			'exclude' => 1,
			'label' => $lllPath . 'post_count',
			'config' => [
				'type' => 'none'
			],
		],
		'author' => [
			'label' => $lllPath . 'author',
			'config' => [
				'type' => 'select',
				'renderType' => 'selectSingle',
				'foreign_table' => 'fe_users',
				'foreign_class' => '\Mittwald\Typo3Forum\Domain\Model\User\FrontendUser',
				'maxitems' => 1
			],
		],
		'last_post' => [
			'label' => $lllPath . 'last_post',
			'config' => [
				'type' => 'select',
				'renderType' => 'selectSingle',
				'foreign_table' => 'tx_typo3forum_domain_model_forum_post',
				'minitems' => 1,
				'maxitems' => 1,
			],
		],
		'last_post_crdate' => [
			'label' => $lllPath . 'last_post_crdate',
			'config' => [
				'type' => 'none'
			],
		],
		'is_solved' => [
			'label' => $lllPath . 'is_solved',
			'config' => [
				'type' => 'none'
			],
		],
		'solution' => [
			'label' => $lllPath . 'solution',
			'config' => [
				'type' => 'select',
				'renderType' => 'selectSingle',
				'foreign_class' => '\Mittwald\Typo3Forum\Domain\Model\Forum\Post',
				'foreign_table' => 'tx_typo3forum_domain_model_forum_post',
				'maxitems' => 1
			],
		],
		'forum' => [
			'label' => 'LLL:EXT:typo3_forum/Resources/Private/Language/locallang_db.xml:tx_typo3forum_domain_model_forum_forum',
			'config' => [
				'type' => 'select',
				'renderType' => 'selectSingle',
				'foreign_class' => '\Mittwald\Typo3Forum\Domain\Model\Forum\Forum',
				'foreign_table' => 'tx_typo3forum_domain_model_forum_forum',
				'maxitems' => 1
			],
		],
		'closed' => [
			'label' => $lllPath . 'closed',
			'config' => [
				'type' => 'check'
			],
		],
		'sticky' => [
			'label' => $lllPath . 'sticky',
			'config' => [
				'type' => 'check'
			],
		],
		'question' => [
			'label' => $lllPath . 'question',
			'config' => [
				'type' => 'check'
			],
		],
		'criteria_options' => [
			'label' => 'LLL:EXT:typo3_forum/Resources/Private/Language/locallang_db.xml:tx_typo3forum_domain_model_forum_criteria_options',
			'config' => [
				'type' => 'select',
				'renderType' => 'selectMultipleSideBySide',
				'size' => 10,
				'maxitems' => 99999,
				'foreign_table' => 'tx_typo3forum_domain_model_forum_criteria_options',
				'MM' => 'tx_typo3forum_domain_model_forum_criteria_topic_options'
			],
		],
		'tags' => [
			'label' => $lllPath . 'tags',
			'config' => [
				'type' => 'select',
				'renderType' => 'selectMultipleSideBySide',
				'size' => 10,
				'maxitems' => 99999,
				'foreign_table' => 'tx_typo3forum_domain_model_forum_tag',
				'MM' => 'tx_typo3forum_domain_model_forum_tag_topic'
			],
		],
		'subscribers' => [
			'label' => $lllPath . 'subscribers',
			'config' => [
				'type' => 'select',
				'renderType' => 'selectMultipleSideBySide',
				'foreign_table' => 'fe_users',
				'foreign_class' => '\Mittwald\Typo3Forum\Domain\Model\User\FrontendUser',
				'MM' => 'tx_typo3forum_domain_model_user_topicsubscription',
				'MM_opposite_field' => 'tx_typo3forum_topic_subscriptions',
				'maxitems' => 9999,
				'size' => 10
			],
		],
		'fav_subscribers' => [
			'label' => $lllPath . 'fav_subscribers',
			'config' => [
				'type' => 'select',
				'renderType' => 'selectMultipleSideBySide',
				'foreign_table' => 'fe_users',
				'foreign_class' => '\Mittwald\Typo3Forum\Domain\Model\User\FrontendUser',
				'MM' => 'tx_typo3forum_domain_model_user_topicfavsubscription',
				'MM_opposite_field' => 'tx_typo3forum_topic_favsubscriptions',
				'maxitems' => 9999,
				'size' => 10
			],
		],
		'target' => [
			'label' => $lllPath . 'target',
			'config' => [
				'type' => 'select',
				'renderType' => 'selectSingle',
				'foreign_table' => 'tx_typo3forum_domain_model_forum_topic',
				'minitems' => 1,
				'maxitems' => 1,
			],
		],
		'readers' => [
			'label' => $lllPath . 'readers',
			'config' => [
				'type' => 'select',
				'renderType' => 'selectSingleBox',
				'foreign_table' => 'fe_users',
				'foreign_class' => '\Mittwald\Typo3Forum\Domain\Model\User\FrontendUser',
				'MM' => 'tx_typo3forum_domain_model_user_readtopic',
				'MM_opposite_field' => 'tx_typo3forum_read_topics',
				'size' => 10
			],
		],
	],
];
