<?php

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::allowTableOnStandardPages('tx_typo3forum_domain_model_forum_ads');

$lllPath = 'LLL:EXT:typo3_forum/Resources/Private/Language/locallang_db.xml:tx_typo3forum_domain_model_forum_ads.';

return [
	'ctrl' => [
		'title' => 'LLL:EXT:typo3_forum/Resources/Private/Language/locallang_db.xml:tx_typo3forum_domain_model_forum_ads',
		'label' => 'name',
		'tstamp' => 'tstamp',
		'delete' => 'deleted',
		'dynamicConfigFile' => \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extPath($_EXTKEY) . 'Configuration/TCA/Forum/Ads.php',
	],
	'interface' => [
		'showRecordFieldList' => 'name,alt_text,url,path,active,category,groups'
	],
	'types' => [
		'1' => ['showitem' => 'name,alt_text,url,path,active,category,groups'],
	],
	'columns' => [
		'name' => [
			'label' => $lllPath . 'name',
			'config' => [
				'type' => 'input',
			],
		],
		'alt_text' => [
			'label' => $lllPath . 'alt',
			'config' => [
				'type' => 'text',
			],
		],
		'url' => [
			'label' => $lllPath . 'url',
			'config' => [
				'type' => 'input',
			],
		],
		'path' => [
			'label' => $lllPath . 'path',
			'config' => [
				'type' => 'input',
			],
		],
		'active' => [
			'label' => $lllPath . 'active',
			'config' => [
				'type' => 'check',
			],
		],
		'category' => [
			'label' => $lllPath . 'category',
			'config' => [
				'type' => 'radio',
				'items' => [
					['all', 0],
					['forum only', 1],
					['topic only', 2],
				],
				'default' => 0,
			],
		],
	],
];
