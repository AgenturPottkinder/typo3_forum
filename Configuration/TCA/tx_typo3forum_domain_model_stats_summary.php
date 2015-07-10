<?php

$lllPath = 'LLL:EXT:typo3_forum/Resources/Private/Language/locallang_db.xml:tx_typo3forum_domain_model_stats_summary.';

return [
	'ctrl' => [
		'title' => 'LLL:EXT:typo3_forum/Resources/Private/Language/locallang_db.xml:tx_typo3forum_domain_model_stats_summary',
		'label' => 'type',
		'label_alt' => 'tstamp',
		'label_alt_force' => true,
		'tstamp' => 'tstamp',
		'delete' => 'deleted',
		'sortby' => 'tstamp DESC',
		'hideTable' => TRUE,
		'iconfile' => \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extRelPath('typo3_forum') . 'Resources/Public/Icons/Stats/summary.png',
	],
	'interface' => [
		'showRecordFieldList' => 'type,amount,tstamp',
	],
	'types' => [
		'1' => ['showitem' => 'type,amount,tstamp'],
	],
	'columns' => [
		'type' => [
			'label' => $lllPath . 'type',
			'config' => [
				'type' => 'radio',
				'items' => [
					['Post', '0'],
					['Topic', '1'],
					['User', '2'],
				],
				'default' => '0',
			],
		],
		'amount' => [
			'label' => $lllPath . 'amount',
			'config' => [
				'type' => 'input',
			],
		],
		'tstamp' => [
			'label' => $lllPath . 'tstamp',
			'config' => [
				'type' => 'none',
				'format' => 'date',
				'eval' => 'date',
			],
		],
	],
];
