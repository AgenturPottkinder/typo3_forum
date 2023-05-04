<?php

$tempColumns = [
    'tx_typo3forum_user_mod' => [
        'label' => 'LLL:EXT:typo3_forum/Resources/Private/Language/locallang_db.xlf:fe_groups.user_mod',
        'config' => [
            'type' => 'check',
        ],
    ],
];
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTCAcolumns('fe_groups', $tempColumns);
$GLOBALS['TCA']['fe_groups']['types']['0']['showitem'] .=
    ',--div--;LLL:EXT:typo3_forum/Resources/Private/Language/locallang_db.xlf:fe_users.tx_typo3forum.tab.settings,' .
    'tx_typo3forum_user_mod';
