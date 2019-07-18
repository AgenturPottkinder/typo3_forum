<?php

return [
	'ctrl' => [
		'title' => 'LLL:EXT:typo3_forum/Resources/Private/Language/locallang_db.xml:tx_typo3forum_domain_model_forum_criteria_options',
		'label' => 'name',
		'tstamp' => 'tstamp',
		'delete' => 'deleted',
		'sortby' => 'sorting',
		'iconfile' => 'EXT:typo3_forum/Resources/Public/Icons/Forum/CriteriaOption.png',
	],
	'interface' => [
		'showRecordFieldList' => 'name,criteria,sorting'
	],
	'types' => [
		'1' => ['showitem' => 'name,criteria,sorting'],
	],
	'columns' => [
		'name' => [
			'label' => 'LLL:EXT:typo3_forum/Resources/Private/Language/locallang_db.xml:tx_typo3forum_domain_model_forum_criteria_options.name',
			'config' => [
				'type' => 'text',
			],
		],
		'criteria' => [
			'label' => 'LLL:EXT:typo3_forum/Resources/Private/Language/locallang_db.xml:tx_typo3forum_domain_model_forum_criteria_options.criteria',
			'config' => [
				'type' => 'select',
				'renderType' => 'selectSingle',
				'foreign_table' => 'tx_typo3forum_domain_model_forum_criteria',
				'maxitems' => 1,
			],
		],
		'sorting' => [
			'label' => 'LLL:EXT:typo3_forum/Resources/Private/Language/locallang_db.xml:tx_typo3forum_domain_model_forum_criteria_options.sorting',
			'config' => [
				'type' => 'input',
				'size' => 11,
				'default' => 0,
				'eval' => 'num',
			],
		],
	],
];
