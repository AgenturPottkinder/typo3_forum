<?php

return [
	'ctrl' => [
		'title' => 'LLL:EXT:typo3_forum/Resources/Private/Language/locallang_db.xml:tx_typo3forum_domain_model_user_userfield_userfield',
		'label' => 'name',
		'type' => 'type',
		'tstamp' => 'tstamp',
		'crdate' => 'crdate',
		'delete' => 'deleted',
		'enablecolumns' => [
			'disabled' => 'hidden',
		],
		'iconfile' => 'EXT:typo3_forum/Resources/Public/Icons/User/Userfield/Userfield.png',
	],
	'interface' => [
		'showRecordFieldList' => 'type,name,typoscript_path,map_to_user_object',
	],
	'types' => [
		'0' => ['showitem' => 'type,name,map_to_user_object'],
		'Mittwald\Typo3Forum\Domain\Model\User\Userfield\TyposcriptUserfield' => ['showitem' => 'type,name,typoscript_path,map_to_user_object'],
		'Mittwald\Typo3Forum\Domain\Model\User\Userfield\TextUserfield' => ['showitem' => 'type,name,map_to_user_object'],
	],
	'columns' => [
		'hidden' => [
			'exclude' => 1,
			'label' => 'LLL:EXT:lang/Resources/Private/Language/locallang_general.xlf:LGL.hidden',
			'config' => [
				'type' => 'check',
			],
		],
		'type' => [
			'label' => 'LLL:EXT:typo3_forum/Resources/Private/Language/locallang_db.xml:tx_typo3forum_domain_model_user_userfield_userfield.type',
			'config' => [
				'type' => 'select',
				'renderType' => 'selectSingle',
				'items' => [
					['LLL:EXT:typo3_forum/Resources/Private/Language/locallang_db.xml:tx_typo3forum_domain_model_user_userfield_userfield.type.undefined', 0],
					['LLL:EXT:typo3_forum/Resources/Private/Language/locallang_db.xml:tx_typo3forum_domain_model_user_userfield_userfield.type.typoscript', 'Mittwald\Typo3Forum\Domain\Model\User\Userfield\TyposcriptUserfield'],
					['LLL:EXT:typo3_forum/Resources/Private/Language/locallang_db.xml:tx_typo3forum_domain_model_user_userfield_userfield.type.text', 'Mittwald\Typo3Forum\Domain\Model\User\Userfield\TextUserfield'],
				],
			],
		],
		'name' => [
			'exclude' => 1,
			'label' => 'LLL:EXT:typo3_forum/Resources/Private/Language/locallang_db.xml:tx_typo3forum_domain_model_user_userfield_userfield.name',
			'config' => [
				'type' => 'input',
				'size' => 30,
				'eval' => 'trim,required',
			],
		],
		'typoscript_path' => [
			'exclude' => 1,
			'label' => 'LLL:EXT:typo3_forum/Resources/Private/Language/locallang_db.xml:tx_typo3forum_domain_model_user_userfield_userfield.typoscript_path',
			'config' => [
				'type' => 'input',
				'size' => 30,
				'eval' => 'trim,required',
			],
		],
		'map_to_user_object' => [
			'exclude' => 1,
			'label' => 'LLL:EXT:typo3_forum/Resources/Private/Language/locallang_db.xml:tx_typo3forum_domain_model_user_userfield_userfield.map_to_user_object',
			'config' => [
				'type' => 'input',
				'size' => 30,
				'eval' => 'trim',
				'checkbox' => TRUE,
			],
		],
	],
];

