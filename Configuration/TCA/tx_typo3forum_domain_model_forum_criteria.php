<?php

$lllPath = 'LLL:EXT:typo3_forum/Resources/Private/Language/locallang_db.xml:tx_typo3forum_domain_model_forum_criteria.';

return [
	'ctrl' => [
		'title' => 'LLL:EXT:typo3_forum/Resources/Private/Language/locallang_db.xml:tx_typo3forum_domain_model_forum_criteria',
		'label' => 'name',
		'tstamp' => 'tstamp',
		'delete' => 'deleted',
		'iconfile' => \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extRelPath('typo3_forum') . 'Resources/Public/Icons/Forum/Criteria.png',
	],
	'interface' => [
		'showRecordFieldList' => 'name,options,default_option',
	],
	'types' => [
		'1' => ['showitem' => 'name,default_option'],
	],
	'columns' => [
		'name' => [
			'label' => 'LLL:EXT:typo3_forum/Resources/Private/Language/locallang_db.xml:tx_typo3forum_domain_model_forum_criteria.name',
			'config' => [
				'type' => 'text',
			],
		],
		'options' => [
			'label' => $lllPath . 'options',
			'config' => [
				'type' => 'inline',
				'foreign_table' => 'tx_typo3forum_domain_model_forum_criteria_options',
				'foreign_field' => 'criteria',
				'maxitems' => 9999,
				'foreign_sortby' => 'sorting',
				'appearance' => [
					'collapseAll' => 1,
					'newRecordLinkPosition' => 'bottom',
					'expandSingle' => 1,
				],
			],
		],
		'default_option' => [
			'label' => $lllPath . 'default_option',
			'config' => [
				'type' => 'select',
				'maxitems' => 1,
				'foreign_table' => 'tx_typo3forum_domain_model_forum_criteria_options',
				'foreign_class' => '\Mittwald\Typo3Forum\Domain\Model\Forum\CriteriaOption',
			],
		],
	],
];
