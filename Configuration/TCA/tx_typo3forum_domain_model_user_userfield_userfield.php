<?php

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addLLrefForTCAdescr(
	'tx_typo3forum_domain_model_user_userfield_userfield',
	'EXT:typo3_forum/Resources/Private/Language/locallang_csh_tx_typo3forum_domain_model_user_userfield_userfield.xml'
);

$lllPath = 'LLL:EXT:typo3_forum/Resources/Private/Language/locallang_db.xml:tx_typo3forum_domain_model_user_userfield_userfield.';

return [
	'ctrl' => [
		'title' => 'LLL:EXT:typo3_forum/Resources/Private/Language/locallang_db.xml:tx_typo3forum_domain_model_user_userfield_userfield',
		'label' => 'name',
		'type' => 'type',
		'tstamp' => 'tstamp',
		'crdate' => 'crdate',
		'versioningWS' => 2,
		'versioning_followPages' => TRUE,
		'origUid' => 't3_origuid',
		'languageField' => 'sys_language_uid',
		'transOrigPointerField' => 'l18n_parent',
		'transOrigDiffSourceField' => 'l18n_diffsource',
		'delete' => 'deleted',
		'enablecolumns' => [
			'disabled' => 'hidden',
		],
		'iconfile' => \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extRelPath('typo3_forum') . 'Resources/Public/Icons/User/Userfield/Userfield.png',
	],
	'interface' => [
		'showRecordFieldList' => 'type,name,typoscript_path,map_to_user_object',
	],
	'types' => [
		'0' => ['showitem' => 'type,name,map_to_user_object'],
		'\Mittwald\Typo3Forum\Domain\Model\User\Userfield\TyposcriptUserfield' => ['showitem' => 'type,name,typoscript_path,map_to_user_object'],
		'\Mittwald\Typo3Forum\Domain\Model\User\Userfield\TextUserfield' => ['showitem' => 'type,name,map_to_user_object'],
	],
	'columns' => [
		'sys_language_uid' => [
			'exclude' => 1,
			'label' => 'LLL:EXT:lang/locallang_general.php:LGL.language',
			'config' => [
				'type' => 'select',
				'foreign_table' => 'sys_language',
				'foreign_table_where' => 'ORDER BY sys_language.title',
				'items' => [
					['LLL:EXT:lang/locallang_general.php:LGL.allLanguages', -1],
					['LLL:EXT:lang/locallang_general.php:LGL.default_value', 0],
				],
			],
		],
		'l18n_parent' => [
			'displayCond' => 'FIELD:sys_language_uid:>:0',
			'exclude' => 1,
			'label' => 'LLL:EXT:lang/locallang_general.php:LGL.l18n_parent',
			'config' => [
				'type' => 'select',
				'items' => [
					['', 0],
				],
				'foreign_table' => 'tx_typo3forum_domain_model_user_userfield_userfield',
				'foreign_table_where' => 'AND tx_typo3forum_domain_model_user_userfield_userfield.uid=###REC_FIELD_l18n_parent### AND tx_typo3forum_domain_model_user_userfield_userfield.sys_language_uid IN (-1,0]',
			],
		],
		'l18n_diffsource' => [
			'config' => [
				'type' => 'passthrough',
			],
		],
		't3ver_label' => [
			'displayCond' => 'FIELD:t3ver_label:REQ:true',
			'label' => 'LLL:EXT:lang/locallang_general.php:LGL.versionLabel',
			'config' => [
				'type' => 'none',
				'cols' => 27,
			],
		],
		'hidden' => [
			'exclude' => 1,
			'label' => 'LLL:EXT:lang/locallang_general.xml:LGL.hidden',
			'config' => [
				'type' => 'check',
			],
		],
		'type' => [
			'label' => $lllPath . 'type',
			'config' => [
				'type' => 'select',
				'items' => [
					[$lllPath . 'type.undefined', 0],
					[$lllPath . 'type.typoscript', '\Mittwald\Typo3Forum\Domain\Model\User\Userfield\TyposcriptUserfield'],
					[$lllPath . 'type.text', '\Mittwald\Typo3Forum\Domain\Model\User\Userfield\TextUserfield'],
				],
			],
		],
		'name' => [
			'exclude' => 1,
			'label' => $lllPath . 'name',
			'config' => [
				'type' => 'input',
				'size' => 30,
				'eval' => 'trim,required',
			],
		],
		'typoscript_path' => [
			'exclude' => 1,
			'label' => $lllPath . 'typoscript_path',
			'config' => [
				'type' => 'input',
				'size' => 30,
				'eval' => 'trim,required',
			],
		],
		'map_to_user_object' => [
			'exclude' => 1,
			'label' => $lllPath . 'map_to_user_object',
			'config' => [
				'type' => 'input',
				'size' => 30,
				'eval' => 'trim',
				'checkbox' => TRUE,
			],
		],
	],
];

