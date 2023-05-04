<?php

return [
    'ctrl' => [
        'title' => 'LLL:EXT:typo3_forum/Resources/Private/Language/locallang_db.xlf:tx_typo3forum_domain_model_forum_color',
        'label' => 'name',
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
        'iconfile' => 'EXT:typo3_forum/Resources/Public/Icons/Forum/Criteria.png',
    ],
    'types' => [
        '1' => ['showitem' => 'name,primary_color,secondary_color'],
    ],
    'columns' => [
        'name' => [
            'label' => 'LLL:EXT:typo3_forum/Resources/Private/Language/locallang_db.xlf:tx_typo3forum_domain_model_forum_color.name',
            'config' => [
                'type' => 'input',
            ]
        ],
        'primary_color' => [
            'label' => 'LLL:EXT:typo3_forum/Resources/Private/Language/locallang_db.xlf:tx_typo3forum_domain_model_forum_color.primary_color',
            'config' => [
                'type' => 'input',
                'renderType' => 'colorpicker',
                'required' => true,
            ]
        ],
        'secondary_color' => [
            'label' => 'LLL:EXT:typo3_forum/Resources/Private/Language/locallang_db.xlf:tx_typo3forum_domain_model_forum_color.secondary_color',
            'config' => [
                'type' => 'input',
                'renderType' => 'colorpicker',
                'required' => true,
            ]
        ],
    ]
];
