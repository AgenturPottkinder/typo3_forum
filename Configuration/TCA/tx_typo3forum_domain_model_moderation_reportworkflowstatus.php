<?php

return [
    'ctrl' => [
        'title' => 'LLL:EXT:typo3_forum/Resources/Private/Language/locallang_db.xlf:tx_typo3forum_domain_model_moderation_reportworkflowstatus',
        'label' => 'name',
        'tstamp' => 'tstamp',
        'crdate' => 'crdate',
        'versioningWS' => true,
        'origUid' => 't3_origuid',
        'languageField' => 'sys_language_uid',
        'delete' => 'deleted',
        'enablecolumns' => [
            'disabled' => 'hidden'
        ],
        'iconfile' => 'EXT:typo3_forum/Resources/Public/Icons/Moderation/ReportWorkflowStatus.png'
    ],
    'types' => [
        '1' => ['showitem' => 'name,followup_status,initial,final'],
    ],
    'columns' => [
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
        'name' => [
            'label' => 'LLL:EXT:typo3_forum/Resources/Private/Language/locallang_db.xlf:tx_typo3forum_domain_model_moderation_reportworkflowstatus.name',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim'
            ],
        ],
        'followup_status' => [
            'exclude' => 1,
            'label' => 'LLL:EXT:typo3_forum/Resources/Private/Language/locallang_db.xlf:tx_typo3forum_domain_model_moderation_reportworkflowstatus.followup_status',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectMultipleSideBySide',
                'foreign_table' => 'tx_typo3forum_domain_model_moderation_reportworkflowstatus',
                'MM' => 'tx_typo3forum_domain_model_moderation_reportworkflowstatus_mm',
                'maxitems' => 9999,
                'size' => 5
            ],
        ],
        'initial' => [
            'label' => 'LLL:EXT:typo3_forum/Resources/Private/Language/locallang_db.xlf:tx_typo3forum_domain_model_moderation_reportworkflowstatus.initial',
            'config' => [
                'type' => 'check'
            ],
        ],
        'final' => [
            'label' => 'LLL:EXT:typo3_forum/Resources/Private/Language/locallang_db.xlf:tx_typo3forum_domain_model_moderation_reportworkflowstatus.final',
            'config' => [
                'type' => 'check'
            ],
        ],
    ],
];
