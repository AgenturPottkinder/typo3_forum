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
    \TYPO3\CMS\Extbase\Domain\Model\FrontendUser::class => [
        'subclasses' => [
            \Mittwald\Typo3Forum\Domain\Model\User\FrontendUser::class
        ]
    ],
    \TYPO3\CMS\Extbase\Domain\Model\FrontendUserGroup::class => [
        'subclasses' => [
            \Mittwald\Typo3Forum\Domain\Model\User\FrontendUserGroup::class,
        ]
    ],
    \Mittwald\Typo3Forum\Domain\Model\User\PrivateMessageText::class => [
        'tableName' => 'tx_typo3forum_domain_model_user_privatemessage_text',
    ],
    \Mittwald\Typo3Forum\Domain\Model\Forum\CriteriaOption::class => [
        'tableName' => 'tx_typo3forum_domain_model_forum_criteria_options',
    ],
    \Mittwald\Typo3Forum\Domain\Model\Forum\ShadowTopic::class => [
        'tableName' => 'tx_typo3forum_domain_model_forum_topic',
    ],
    \Mittwald\Typo3Forum\Domain\Model\Format\SyntaxHighlighting::class => [
        'tableName' => 'tx_typo3forum_domain_model_format_textparser',
        'recordType' => \Mittwald\Typo3Forum\Domain\Model\Format\SyntaxHighlighting::class,
    ],
    \Mittwald\Typo3Forum\Domain\Model\Format\Smiley::class => [
        'tableName' => 'tx_typo3forum_domain_model_format_textparser',
        'recordType' => \Mittwald\Typo3Forum\Domain\Model\Format\Smiley::class,
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
        'subclasses' => [
            \Mittwald\Typo3Forum\Domain\Model\Forum\ShadowTopic::class
        ]
    ],
    \Mittwald\Typo3Forum\Domain\Model\Format\BBCode::class => [
        'tableName' => 'tx_typo3forum_domain_model_format_textparser',
        'recordType' => \Mittwald\Typo3Forum\Domain\Model\Format\BBCode::class,
        'subclasses' => [
            \Mittwald\Typo3Forum\Domain\Model\Format\QuoteBBCode::class,
            Mittwald\Typo3Forum\Domain\Model\Format\ListBBCode::class
        ]
    ],
    \Mittwald\Typo3Forum\Domain\Model\Format\ListBBCode::class => [
        'tableName' => 'tx_typo3forum_domain_model_format_textparser',
        'recordType' => \Mittwald\Typo3Forum\Domain\Model\Format\ListBBCode::class,
    ],
    \Mittwald\Typo3Forum\Domain\Model\Format\QuoteBBCode::class => [
        'tableName' => 'tx_typo3forum_domain_model_format_textparser',
        'recordType' => \Mittwald\Typo3Forum\Domain\Model\Format\QuoteBBCode::class,
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
        'recordType' => \Mittwald\Typo3Forum\Domain\Model\User\FrontendUserGroup::class,
        'properties' => [
            'tx_typo3forum_user_mod' => [
                'fieldName' => 'userMod'
            ]
        ]
    ],
    \Mittwald\Typo3Forum\Domain\Model\User\FrontendUser::class => [
        'tableName' => 'fe_users',
        'recordType' => \Mittwald\Typo3Forum\Domain\Model\User\FrontendUser::class,
        'properties' => [
            'tx_typo3forum_signature' => [
                'fieldName' => 'signature'
            ],
            'tx_typo3forum_post_count' => [
                'fieldName' => 'postCount'
            ],
            'tx_typo3forum_post_count_session' => [
                'fieldName' => 'postCountSession'
            ],
            'tx_typo3forum_topic_count' => [
                'fieldName' => 'topicCount'
            ],
            'tx_typo3forum_helpful_count' => [
                'fieldName' => 'helpfulCount'
            ],
            'tx_typo3forum_helpful_count_session' => [
                'fieldName' => 'helpfulCountSession'
            ],
            'tx_typo3forum_question_count' => [
                'fieldName' => 'questionCount'
            ],
            'tx_typo3forum_support_posts' => [
                'fieldName' => 'supportPosts'
            ],
            'tx_typo3forum_facebook' => [
                'fieldName' => 'facebook'
            ],
            'tx_typo3forum_twitter' => [
                'fieldName' => 'twitter'
            ],
            'tx_typo3forum_google' => [
                'fieldName' => 'google'
            ],
            'tx_typo3forum_skype' => [
                'fieldName' => 'skype'
            ],
            'tx_typo3forum_job' => [
                'fieldName' => 'job'
            ],
            'tx_typo3forum_interests' => [
                'fieldName' => 'interests'
            ],
            'tx_typo3forum_working_environment' => [
                'fieldName' => 'workingEnvironment'
            ],
            'tx_typo3forum_topic_favsubscriptions' => [
                'fieldName' => 'topicFavSubscriptions'
            ],
            'tx_typo3forum_topic_subscriptions' => [
                'fieldName' => 'topicSubscriptions'
            ],
            'tx_typo3forum_forum_subscriptions' => [
                'fieldName' => 'forumSubscriptions'
            ],
            'tx_typo3forum_read_topics' => [
                'fieldName' => 'readTopics'
            ],
            'tx_typo3forum_read_forum' => [
                'fieldName' => 'readForum'
            ],
            'tx_typo3forum_use_gravatar' => [
                'fieldName' => 'useGravatar'
            ],
            'tx_typo3forum_contact' => [
                'fieldName' => 'contact'
            ],
            'tx_typo3forum_private_messages' => [
                'fieldName' => 'privateMessages'
            ],
            'tx_typo3forum_rank' => [
                'fieldName' => 'rank'
            ],
            'tx_typo3forum_points' => [
                'fieldName' => 'points'
            ],
            'date_of_birth' => [
                'fieldName' => 'dateOfBirth'
            ],
        ]
    ],
];
