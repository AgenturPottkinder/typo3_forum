<?php
declare(strict_types = 1);

return [
    \Mittwald\Typo3Forum\Domain\Model\User\Userfield\AbstractUserfield::class => [
        'tableName' => 'tx_typo3forum_domain_model_user_userfield_userfield',
        'recordType' => \Mittwald\Typo3Forum\Domain\Model\User\Userfield\AbstractUserfield::class,
        'subclasses' => [
            \Mittwald\Typo3Forum\Domain\Model\User\Userfield\TyposcriptUserfield::class,
            \Mittwald\Typo3Forum\Domain\Model\User\Userfield\TextUserfield::class
        ]
    ],
    \Mittwald\Typo3Forum\Domain\Model\User\Userfield\TyposcriptUserfield::class => [
        'tableName' => 'tx_typo3forum_domain_model_user_userfield_userfield',
        'recordType' => \Mittwald\Typo3Forum\Domain\Model\User\Userfield\TyposcriptUserfield::class,
        'subclasses' => [
            \Mittwald\Typo3Forum\Domain\Model\User\Userfield\TextUserfield::class
        ]
    ],
    \Mittwald\Typo3Forum\Domain\Model\Moderation\AbstractReport::class => [
        'tableName' => 'tx_typo3forum_domain_model_moderation_report',
        'recordType' => \Mittwald\Typo3Forum\Domain\Model\User\Userfield\AbstractUserfield::class,
        'subclasses' => [
            \Mittwald\Typo3Forum\Domain\Model\Moderation\UserReport::class,
            \Mittwald\Typo3Forum\Domain\Model\Moderation\PostReport::class
        ]
    ],
    \Mittwald\Typo3Forum\Domain\Model\Forum\ShadowTopic::class => [
        'tableName' => 'tx_typo3forum_domain_model_forum_topic',
        'recordType' => '1',
    ],
    \Mittwald\Typo3Forum\Domain\Model\Format\SyntaxHighlighting::class => [
        'tableName' => 'tx_typo3forum_domain_model_format_textparser',
        'recordType' => \Mittwald\Typo3Forum\Domain\Model\Format\SyntaxHighlighting::class,
        'properties' => [
            'language' => [
                'fieldName' => 'alias'
            ],
        ],
    ],
    \Mittwald\Typo3Forum\Domain\Model\Format\Smiley::class => [
        'tableName' => 'tx_typo3forum_domain_model_format_textparser',
        'recordType' => \Mittwald\Typo3Forum\Domain\Model\Format\Smiley::class,
        'properties' => [
            'smileyShortcut' => [
                'fieldName' => 'alias'
            ],
        ],
    ],
    \Mittwald\Typo3Forum\Domain\Model\Format\AbstractTextParserElement::class => [
        'tableName' => 'tx_typo3forum_domain_model_format_textparser',
        'recordType' => 0,
        'subclasses' => [
            \Mittwald\Typo3Forum\Domain\Model\Format\BBCode::class,
            \Mittwald\Typo3Forum\Domain\Model\Format\Smiley::class,
            \Mittwald\Typo3Forum\Domain\Model\Format\SyntaxHighlighting::class
        ]
    ],
    \Mittwald\Typo3Forum\Domain\Model\Forum\Topic::class => [
        'tableName' => 'tx_typo3forum_domain_model_forum_topic',
        'recordType' => 0,
        'subclasses' => [
            \Mittwald\Typo3Forum\Domain\Model\Forum\ShadowTopic::class
        ]
    ],
    \Mittwald\Typo3Forum\Domain\Model\Format\BBCode::class => [
        'tableName' => 'tx_typo3forum_domain_model_format_textparser',
        'recordType' => \Mittwald\Typo3Forum\Domain\Model\Format\BBCode::class,
    ],
    \Mittwald\Typo3Forum\Domain\Model\Moderation\PostReport::class => [
        'tableName' => 'tx_typo3forum_domain_model_moderation_report',
        'recordType' => \Mittwald\Typo3Forum\Domain\Model\Moderation\PostReport::class,
    ],
    \Mittwald\Typo3Forum\Domain\Model\Moderation\UserReport::class => [
        'tableName' => 'tx_typo3forum_domain_model_moderation_report',
        'recordType' => \Mittwald\Typo3Forum\Domain\Model\Moderation\UserReport::class,
    ],
    \Mittwald\Typo3Forum\Domain\Model\User\Userfield\TextUserfield::class => [
        'tableName' => 'tx_typo3forum_domain_model_user_userfield_userfield',
        'recordType' => \Mittwald\Typo3Forum\Domain\Model\User\Userfield\TextUserfield::class,
    ],
    \Mittwald\Typo3Forum\Domain\Model\User\FrontendUserGroup::class => [
        'tableName' => 'fe_groups',
        'properties' => [
            'userMod' => [
                'fieldName' => 'tx_typo3forum_user_mod'
            ]
        ]
    ],
    \Mittwald\Typo3Forum\Domain\Model\User\FrontendUser::class => [
        'tableName' => 'fe_users',
        'properties' => [
            'signature' => [
                'fieldName' => 'tx_typo3forum_signature'
            ],
            'postCount' => [
                'fieldName' => 'tx_typo3forum_post_count'
            ],
            'topicCount' => [
                'fieldName' => 'tx_typo3forum_topic_count'
            ],
            'helpfulCount' => [
                'fieldName' => 'tx_typo3forum_helpful_count'
            ],
            'questionCount' => [
                'fieldName' => 'tx_typo3forum_question_count'
            ],
            'supportPosts' => [
                'fieldName' => 'tx_typo3forum_support_posts'
            ],
            'facebook' => [
                'fieldName' => 'tx_typo3forum_facebook'
            ],
            'twitter' => [
                'fieldName' => 'tx_typo3forum_twitter'
            ],
            'google' => [
                'fieldName' => 'tx_typo3forum_google'
            ],
            'skype' => [
                'fieldName' => 'tx_typo3forum_skype'
            ],
            'job' => [
                'fieldName' => 'tx_typo3forum_job'
            ],
            'interests' => [
                'fieldName' => 'tx_typo3forum_interests'
            ],
            'workingEnvironment' => [
                'fieldName' => 'tx_typo3forum_working_environment'
            ],
            'topicSubscriptions' => [
                'fieldName' => 'tx_typo3forum_topic_subscriptions'
            ],
            'forumSubscriptions' => [
                'fieldName' => 'tx_typo3forum_forum_subscriptions'
            ],
            'readTopics' => [
                'fieldName' => 'tx_typo3forum_read_topics'
            ],
            'readForum' => [
                'fieldName' => 'tx_typo3forum_read_forum'
            ],
            'useGravatar' => [
                'fieldName' => 'tx_typo3forum_use_gravatar'
            ],
            'contact' => [
                'fieldName' => 'tx_typo3forum_contact'
            ],
            'rank' => [
                'fieldName' => 'tx_typo3forum_rank'
            ],
            'points' => [
                'fieldName' => 'tx_typo3forum_points'
            ],
            'dateOfBirth' => [
                'fieldName' => 'date_of_birth'
            ],
        ]
    ],
];
