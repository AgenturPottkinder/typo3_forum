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
            'userMod' => [
                'fieldName' => 'tx_typo3forum_user_mod'
            ]
        ]
    ],
    \Mittwald\Typo3Forum\Domain\Model\User\FrontendUser::class => [
        'tableName' => 'fe_users',
        'recordType' => \Mittwald\Typo3Forum\Domain\Model\User\FrontendUser::class,
        'properties' => [
            'signature' => [
                'fieldName' => 'tx_typo3forum_signature'
            ],
            'postCount' => [
                'fieldName' => 'tx_typo3forum_post_count'
            ],
            'postCountSession' => [
                'fieldName' => 'tx_typo3forum_post_count_session'
            ],
            'topicCount' => [
                'fieldName' => 'tx_typo3forum_topic_count'
            ],
            'helpfulCount' => [
                'fieldName' => 'tx_typo3forum_helpful_count'
            ],
            'helpfulCountSession' => [
                'fieldName' => 'tx_typo3forum_helpful_count_session'
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
            'topicFavSubscriptions' => [
                'fieldName' => 'tx_typo3forum_topic_favsubscriptions'
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
            'privateMessages' => [
                'fieldName' => 'tx_typo3forum_private_messages'
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
