<?php
defined('TYPO3_MODE') or die();

$tempColumns = [
    'crdate' => [
        'exclude' => 1,
        'config' => ['type' => 'passthrough'],
    ],
    'is_online' => [
        'exclude' => 1,
        'config' => ['type' => 'passthrough'],
    ],
    'date_of_birth' => [
        'exclude' => 1,
        'config' => ['type' => 'passthrough'],
    ],
    'tx_typo3forum_rank' => [
        'exclude' => 1,
        'label' => 'LLL:EXT:typo3_forum/Resources/Private/Language/locallang_db.xlf:tx_typo3forum_domain_model_user_rank',
        'config' => [
            'type' => 'select',
            'renderType' => 'selectSingle',
            'foreign_table' => 'tx_typo3forum_domain_model_user_rank',
            'maxitems' => 1,
        ],
    ],
    'tx_typo3forum_points' => [
        'exclude' => 1,
        'label' => 'LLL:EXT:typo3_forum/Resources/Private/Language/locallang_db.xlf:fe_users.tx_typo3forum_points',
        'config' => ['type' => 'none'],
    ],
    'tx_typo3forum_post_count' => [
        'exclude' => 1,
        'label' => 'LLL:EXT:typo3_forum/Resources/Private/Language/locallang_db.xlf:fe_users.tx_typo3forum_post_count',
        'config' => ['type' => 'none'],
    ],
    'tx_typo3forum_topic_count' => [
        'exclude' => 1,
        'label' => 'LLL:EXT:typo3_forum/Resources/Private/Language/locallang_db.xlf:fe_users.tx_typo3forum_topic_count',
        'config' => ['type' => 'none'],
    ],
    'tx_typo3forum_helpful_count' => [
        'exclude' => 1,
        'label' => 'LLL:EXT:typo3_forum/Resources/Private/Language/locallang_db.xlf:fe_users.tx_typo3forum_helpful_count',
        'config' => ['type' => 'none'],
    ],
    'tx_typo3forum_question_count' => [
        'exclude' => 1,
        'label' => 'LLL:EXT:typo3_forum/Resources/Private/Language/locallang_db.xlf:fe_users.tx_typo3forum_question_count',
        'config' => ['type' => 'none'],
    ],
    'tx_typo3forum_topic_subscriptions' => [
        'exclude' => 1,
        'label' => 'LLL:EXT:typo3_forum/Resources/Private/Language/locallang_db.xlf:fe_users.tx_typo3forum_topic_subscriptions',
        'config' => [
            'type' => 'select',
            'renderType' => 'selectSingleBox',
            'foreign_table' => 'tx_typo3forum_domain_model_forum_topic',
            'MM' => 'tx_typo3forum_domain_model_user_topicsubscription',
            'multiple' => true,
            'maxitems' => 9999,
            'minitems' => 0,
        ],
    ],
    'tx_typo3forum_forum_subscriptions' => [
        'exclude' => 1,
        'label' => 'LLL:EXT:typo3_forum/Resources/Private/Language/locallang_db.xlf:fe_users.tx_typo3forum_forum_subscriptions',
        'config' => [
            'type' => 'select',
            'renderType' => 'selectSingleBox',
            'foreign_table' => 'tx_typo3forum_domain_model_forum_forum',
            'MM' => 'tx_typo3forum_domain_model_user_forumsubscription',
            'multiple' => true,
            'maxitems' => 9999,
            'minitems' => 0,
        ],
    ],
    'tx_typo3forum_signature' => [
        'exclude' => 1,
        'label' => 'LLL:EXT:typo3_forum/Resources/Private/Language/locallang_db.xlf:fe_users.tx_typo3forum_signature',
        'config' => [
            'type' => 'text',
        ],
    ],
    'tx_typo3forum_interests' => [
        'exclude' => 1,
        'label' => 'LLL:EXT:typo3_forum/Resources/Private/Language/locallang_db.xlf:fe_users.tx_typo3forum_interests',
        'config' => [
            'type' => 'text',
        ],
    ],
    'tx_typo3forum_userfield_values' => [
        'exclude' => 1,
        'label' => 'LLL:EXT:typo3_forum/Resources/Private/Language/locallang_db.xlf:fe_users.tx_typo3forum_userfield_values',
        'config' => [
            'type' => 'inline',
            'foreign_table' => 'tx_typo3forum_domain_model_user_userfield_value',
            'foreign_field' => 'user',
            'maxitems' => 9999,
            'appearance' => [
                'collapse' => 0,
                'newRecordLinkPosition' => 'bottom',
            ],
        ],
    ],
    'tx_typo3forum_read_forum' => [
        'exclude' => 1,
        'label' => 'LLL:EXT:typo3_forum/Resources/Private/Language/locallang_db.xlf:fe_users.tx_typo3forum_read_forum',
        'config' => [
            'type' => 'group',
            'foreign_table' => 'tx_typo3forum_domain_model_forum_forum',
            'MM' => 'tx_typo3forum_domain_model_user_readforum',
            'multiple' => true,
            'minitems' => 0,
        ],
    ],
    'tx_typo3forum_read_topics' => [
        'exclude' => 1,
        'label' => 'LLL:EXT:typo3_forum/Resources/Private/Language/locallang_db.xlf:fe_users.tx_typo3forum_read_topics',
        'config' => [
            'type' => 'group',
            'foreign_table' => 'tx_typo3forum_domain_model_forum_topic',
            'MM' => 'tx_typo3forum_domain_model_user_readtopic',
            'multiple' => true,
            'minitems' => 0,
        ],
    ],
    'tx_typo3forum_support_posts' => [
        'exclude' => 1,
        'label' => 'LLL:EXT:typo3_forum/Resources/Private/Language/locallang_db.xlf:fe_users.tx_typo3forum_support_posts',
        'config' => [
            'type' => 'select',
            'renderType' => 'selectSingleBox',
            'foreign_table' => 'tx_typo3forum_domain_model_forum_post',
            'MM' => 'tx_typo3forum_domain_model_user_supportpost',
            'multiple' => true,
            'minitems' => 0,
        ],
    ],
    'tx_typo3forum_use_gravatar' => [
        'exclude' => 1,
        'label' => 'LLL:EXT:typo3_forum/Resources/Private/Language/locallang_db.xlf:fe_users.use_gravatar',
        'config' => [
            'type' => 'check',
        ],
    ],
    'tx_typo3forum_contact' => [
        'exclude' => 1,
        'label' => 'LLL:EXT:typo3_forum/Resources/Private/Language/locallang_db.xlf:fe_users.contact',
        'config' => [
            'type' => 'none',
        ],
    ],
    'tx_typo3forum_facebook' => [
        'exclude' => 1,
        'label' => 'LLL:EXT:typo3_forum/Resources/Private/Language/locallang_db.xlf:fe_users.tx_typo3forum_facebook',
        'config' => [
            'type' => 'input',
            'size' => '255',
        ],
    ],
    'tx_typo3forum_twitter' => [
        'exclude' => 1,
        'label' => 'LLL:EXT:typo3_forum/Resources/Private/Language/locallang_db.xlf:fe_users.tx_typo3forum_twitter',
        'config' => [
            'type' => 'input',
            'size' => '255',
        ],
    ],
    'tx_typo3forum_google' => [
        'exclude' => 1,
        'label' => 'LLL:EXT:typo3_forum/Resources/Private/Language/locallang_db.xlf:fe_users.tx_typo3forum_google',
        'config' => [
            'type' => 'input',
            'size' => '255',
        ],
    ],
    'tx_typo3forum_skype' => [
        'exclude' => 1,
        'label' => 'LLL:EXT:typo3_forum/Resources/Private/Language/locallang_db.xlf:fe_users.tx_typo3forum_skype',
        'config' => [
            'type' => 'input',
            'size' => '255',
        ],
    ],
    'tx_typo3forum_job' => [
        'exclude' => 1,
        'label' => 'LLL:EXT:typo3_forum/Resources/Private/Language/locallang_db.xlf:fe_users.tx_typo3forum_job',
        'config' => [
            'type' => 'input',
            'size' => '255',
        ],
    ],
    'tx_typo3forum_working_environment' => [
        'exclude' => 1,
        'label' => 'LLL:EXT:typo3_forum/Resources/Private/Language/locallang_db.xlf:fe_users.tx_typo3forum_working_environment',
        'config' => [
            'type' => 'select',
            'renderType' => 'selectSingle',
            'items' => [
                ['LLL:EXT:typo3_forum/Resources/Private/Language/locallang.xlf:Working_Environment_0', 0],
                ['LLL:EXT:typo3_forum/Resources/Private/Language/locallang.xlf:Working_Environment_1', 1],
                ['LLL:EXT:typo3_forum/Resources/Private/Language/locallang.xlf:Working_Environment_2', 2],
                ['LLL:EXT:typo3_forum/Resources/Private/Language/locallang.xlf:Working_Environment_3', 3],
                ['LLL:EXT:typo3_forum/Resources/Private/Language/locallang.xlf:Working_Environment_4', 4],
            ],
            'default' => 0,
        ],
    ],
];
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTCAcolumns('fe_users', $tempColumns);

$GLOBALS['TCA']['fe_users']['types']['0']['showitem'] .=
    ',--div--;LLL:EXT:typo3_forum/Resources/Private/Language/locallang_db.xlf:fe_users.tx_typo3forum.tab.settings,'
    . ' tx_typo3forum_points, tx_typo3forum_post_count, tx_typo3forum_topic_count, tx_typo3forum_helpful_count, tx_typo3forum_question_count, tx_typo3forum_rank,'
    . ' tx_typo3forum_signature, tx_typo3forum_userfield_values, tx_typo3forum_use_gravatar, tx_typo3forum_contact, tx_typo3forum_working_environment'
;
