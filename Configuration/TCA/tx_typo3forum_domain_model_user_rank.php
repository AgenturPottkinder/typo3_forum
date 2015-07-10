<?php

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::allowTableOnStandardPages('tx_typo3forum_domain_model_user_rank');

$lllPath = 'LLL:EXT:typo3_forum/Resources/Private/Language/locallang_db.xml:tx_typo3forum_domain_model_user_rank.';

return [
	'ctrl' => [
		'title' => 'LLL:EXT:typo3_forum/Resources/Private/Language/locallang_db.xml:tx_typo3forum_domain_model_user_rank',
		'label' => 'name',
		'sortby' => 'point_limit',
	],
	'interface' => [
		'showRecordFieldList' => 'name,point_limit,user_count',
	],
	'types' => [
		'1' => ['showitem' => 'name,point_limit,user_count'],
	],
	'columns' => [
		'name' => [
			'label' => $lllPath . 'name',
			'config' => [
				'type' => 'input',
			],
		],
		'point_limit' => [
			'label' => $lllPath . 'point_limit',
			'config' => [
				'type' => 'input',
			],
		],
		'user_count' => [
			'exclude' => 1,
			'label' => $lllPath . 'user_count',
			'config' => [
				'type' => 'none',
			],
		],
	],
];
