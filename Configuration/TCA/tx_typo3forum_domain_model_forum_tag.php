<?php

return [
    'ctrl' => [
        'title' => 'LLL:EXT:typo3_forum/Resources/Private/Language/locallang_db.xlf:tx_typo3forum_domain_model_forum_tag',
        'label' => 'name',
        'tstamp' => 'tstamp',
        'crdate' => 'crdate',
        'delete' => 'deleted',
        'iconfile' => 'EXT:typo3_forum/Resources/Public/Icons/Forum/Tag.png',
    ],
    'types' => [
        '1' => ['showitem' => 'name,topic_count,color'],
    ],
    'columns' => [
        'name' => [
            'label' => 'LLL:EXT:typo3_forum/Resources/Private/Language/locallang_db.xlf:tx_typo3forum_domain_model_forum_tag.name',
            'config' => [
                'type' => 'input',
            ]
        ],
        'color' => [
            'label' => 'LLL:EXT:typo3_forum/Resources/Private/Language/locallang_db.xlf:tx_typo3forum_domain_model_forum_tag.color',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'foreign_table' => 'tx_typo3forum_domain_model_forum_color',
                'foreign_table_where' => '',
                'items' => [
                    ['', 0],
                ],
            ]
        ],
        'topic_count' => [
            'label' => 'LLL:EXT:typo3_forum/Resources/Private/Language/locallang_db.xlf:tx_typo3forum_domain_model_forum_tag.topicCount',
            'config' => [
                'type' => 'input',
                'eval' => 'int',
                'default' => 0,
            ],
        ],
        'tstamp' => [
            'label' => 'LLL:EXT:typo3_forum/Resources/Private/Language/locallang_db.xlf:tx_typo3forum_domain_model_forum_tag.tstamp',
            'config' => [
                'type' => 'input',
                'renderType' => 'inputDateTime',
                'format' => 'date',
                'eval' => 'date',
            ]
        ],
        'crdate' => [
            'label' => 'LLL:EXT:typo3_forum/Resources/Private/Language/locallang_db.xlf:tx_typo3forum_domain_model_forum_tag.crdate',
            'config' => [
                'type' => 'input',
                'renderType' => 'inputDateTime',
                'format' => 'date',
                'eval' => 'date',
            ]
        ],
    ]
];
