<?php

return [
    'ctrl' => [
        'title' => 'LLL:EXT:typo3_forum/Resources/Private/Language/locallang_db.xlf:tx_typo3forum_domain_model_format_textparser',
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
            'disabled' => 'hidden'
        ],
        'iconfile' => 'EXT:typo3_forum/Resources/Public/Icons/Format/Textparser.png'
    ],
    'types' => [
        '1' => ['showitem' => 'type'],
        'Mittwald\Typo3Forum\Domain\Model\Format\BBCode' => ['showitem' => 'type,name,editor_icon_class,bbcode_wrap,regular_expression,regular_expression_replacement,regular_expression_replacement_blocked,groups'],
        'Mittwald\Typo3Forum\Domain\Model\Format\Smiley' => ['showitem' => 'type,name,editor_icon_class,alias,image_path'],
        'Mittwald\Typo3Forum\Domain\Model\Format\SyntaxHighlighting' => ['showitem' => 'type,name,alias,editor_icon_class'],
    ],
    'columns' => [
        'sys_language_uid' => [
            'exclude' => true,
            'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.language',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'foreign_table' => 'sys_language',
                'foreign_table_where' => 'ORDER BY sys_language.title',
                'items' => [
                    ['LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.allLanguages', -1],
                    ['LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.default_value', 0]
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
            'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.l18n_parent',
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
                'type' => 'passthrough'],
        ],
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
        'type' => [
            'exclude' => 1,
            'label' => 'LLL:EXT:typo3_forum/Resources/Private/Language/locallang_db.xlf:tx_typo3forum_domain_model_format_textparser.type',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'items' => [
                    ['LLL:EXT:typo3_forum/Resources/Private/Language/locallang_db.xlf:tx_typo3forum_domain_model_format_textparser.type.bbcode', 'Mittwald\Typo3Forum\Domain\Model\Format\BBCode'],
                    ['LLL:EXT:typo3_forum/Resources/Private/Language/locallang_db.xlf:tx_typo3forum_domain_model_format_textparser.type.smiley', 'Mittwald\Typo3Forum\Domain\Model\Format\Smiley'],
                    ['LLL:EXT:typo3_forum/Resources/Private/Language/locallang_db.xlf:tx_typo3forum_domain_model_format_textparser.type.syntax', 'Mittwald\Typo3Forum\Domain\Model\Format\SyntaxHighlighting'],
                ],
            ],
        ],
        'name' => [
            'exclude' => 1,
            'label' => 'LLL:EXT:typo3_forum/Resources/Private/Language/locallang_db.xlf:tx_typo3forum_domain_model_format_textparser.name',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim,required'
            ],
        ],
        'image_path' => [
            'exclude' => 1,
            'label' => 'LLL:EXT:typo3_forum/Resources/Private/Language/locallang_db.xlf:tx_typo3forum_domain_model_format_textparser.image_path',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim,required'
            ],
        ],
        'editor_icon_class' => [
            'exclude' => 1,
            'label' => 'LLL:EXT:typo3_forum/Resources/Private/Language/locallang_db.xlf:tx_typo3forum_domain_model_format_textparser.editor_icon_class',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim,required'
            ],
        ],
        'bbcode_wrap' => [
            'exclude' => 1,
            'label' => 'LLL:EXT:typo3_forum/Resources/Private/Language/locallang_db.xlf:tx_typo3forum_domain_model_format_textparser.bbcode_wrap',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim,required'
            ],
        ],
        'regular_expression' => [
            'exclude' => 1,
            'label' => 'LLL:EXT:typo3_forum/Resources/Private/Language/locallang_db.xlf:tx_typo3forum_domain_model_format_textparser.regular_expression',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim,required'
            ],
        ],
        'regular_expression_replacement' => [
            'exclude' => 1,
            'label' => 'LLL:EXT:typo3_forum/Resources/Private/Language/locallang_db.xlf:tx_typo3forum_domain_model_format_textparser.regular_expression_replacement',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim,required'
            ],
        ],
        'regular_expression_replacement_blocked' => [
            'exclude' => 1,
            'label' => 'LLL:EXT:typo3_forum/Resources/Private/Language/locallang_db.xlf:tx_typo3forum_domain_model_format_textparser.regular_expression_replacement_blocked',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim'
            ],
        ],
        'groups' => [
            'label' => 'LLL:EXT:typo3_forum/Resources/Private/Language/locallang_db.xlf:tx_typo3forum_domain_model_format_textparser.groups',
            'exclude' => 1,
            'config' => [
                'type' => 'select',
                'renderType' => 'selectMultipleSideBySide',
                'foreign_table' => 'fe_groups',
            ],
        ],
        'alias' => [
            'exclude' => 1,
            'label' => 'LLL:EXT:typo3_forum/Resources/Private/Language/locallang_db.xlf:tx_typo3forum_domain_model_format_textparser.alias',
            'config' => [
                'type' => 'input',
                'eval' => 'trim,required'
            ],
        ],
    ],
];
