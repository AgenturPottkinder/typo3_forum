<?php

return [
    'ctrl' => [
        'title' => 'LLL:EXT:typo3_forum/Resources/Private/Language/locallang_db.xlf:tx_typo3forum_domain_model_forum_attachment',
        'label' => 'name',
        'tstamp' => 'tstamp',
        'crdate' => 'crdate',
        'delete' => 'deleted',
        'enablecolumns' => ['disabled' => 'hidden'],
        'iconfile' => 'EXT:typo3_forum/Resources/Public/Icons/Forum/Attachment.png'
    ],
    'types' => [
        '1' => ['showitem' => 'name,referenced_files,download_count'],
    ],
    'columns' => [
        't3ver_label' => [
            'displayCond' => 'FIELD:t3ver_label:REQ:true',
            'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.versionLabel',
            'config' => [
                'type' => 'none',
                'cols' => 27
            ],
        ],
        'hidden' => [
            'exclude' => 1,
            'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.hidden',
            'config' => [
                'type' => 'check'
            ],
        ],
        'crdate' => [
            'exclude' => 1,
            'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.creationDate',
            'config' => [
                'type' => 'passthrough'
            ],
        ],
        'post' => [
            'exclude' => 1,
            'label' => 'LLL:EXT:typo3_forum/Resources/Private/Language/locallang_db.xlf:tx_typo3forum_domain_model_forum_post.topic',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'foreign_table' => 'tx_typo3forum_domain_model_forum_post',
                'maxitems' => 1
            ],
        ],
        'download_count' => [
            'exclude' => 1,
            'label' => 'LLL:EXT:typo3_forum/Resources/Private/Language/locallang_db.xlf:tx_typo3forum_domain_model_forum_attachment.download_count',
            'config' => [
                'type' => 'none'
            ],
        ],
        'referenced_files' => [
            'label' => 'LLL:EXT:typo3_forum/Resources/Private/Language/locallang_db.xlf:tx_typo3forum_domain_model_forum_attachment.referenced_files',
            'config' => \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::getFileFieldTCAConfig(
                'referenced_files',
                [
                    'maxitems' => 1,
                ],
                $GLOBALS['TYPO3_CONF_VARS']['GFX']['imagefile_ext']
            ),
        ],
        'name' => [
            'label' => 'LLL:EXT:typo3_forum/Resources/Private/Language/locallang_db.xlf:tx_typo3forum_domain_model_forum_attachment.name',
            'config' => [
                'type' => 'input',
                'eval' => 'trim',
                'limit' => 255,
            ]
        ],
    ],
];
