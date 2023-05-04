<?php

$EM_CONF[$_EXTKEY] = [
    'title' => 'typo3_forum',
    'description' => 'Forum extension',
    'category' => 'plugin',
    'author' => 'Agentur Pottkinder',
    'author_email' => 'support@agentur-pottkinder.de',
    'author_company' => 'Agentur Pottkinder',
    'state' => 'stable',
    'uploadfolder' => 0,
    'createDirs' => 'typo3temp/typo3_forum,typo3temp/typo3_forum/gravatar, typo3temp/typo3_forum/workflowstatus',
    'modify_tables' => 'fe_users',
    'clearCacheOnLoad' => 0,

    // NOTE: DO NOT CHANGE this version number manually.
    // This is done by the build-release.sh script.
    'version' => '1.0-dev',
    'constraints' => [
        'depends' => [
            'typo3' => '11.5',
            'static_info_tables' => '',
            'php' => '7.4',
        ]
    ],
];
