<?php

return [
	'ctrl' => [
		'title' => 'LLL:EXT:typo3_forum/Resources/Private/Language/locallang_db.xml:tx_typo3forum_domain_model_format_textparser',
		'label' => 'name',
		'type' => 'type',
		'tstamp' => 'tstamp',
		'crdate' => 'crdate',
		'versioningWS' => TRUE,
		'origUid' => 't3_origuid',
		'languageField' => 'sys_language_uid',
		'transOrigPointerField' => 'l18n_parent',
		'transOrigDiffSourceField' => 'l18n_diffsource',
		'delete' => 'deleted',
		'enablecolumns' => [
			'disabled' => 'hidden'
		],
		'iconfile' => 'EXT:typo3_forum/Resources/Public/Icons/Format/Textparser.png'
	],
	'interface' => [
		'showRecordFieldList' => 'type,name,icon_class,bbcode_wrap,regular_expression,regular_expression_replacement,smiley_shortcut,language'
	],
	'types' => [
		'1' => ['showitem' => 'type'],
		'Mittwald\Typo3Forum\Domain\Model\Format\BBCode' => ['showitem' => 'type,name,icon_class,bbcode_wrap,regular_expression,regular_expression_replacement'],
		'Mittwald\Typo3Forum\Domain\Model\Format\QuoteBBCode' => ['showitem' => 'type,name,icon_class'],
		'Mittwald\Typo3Forum\Domain\Model\Format\ListBBCode' => ['showitem' => 'type,name,icon_class'],
		'Mittwald\Typo3Forum\Domain\Model\Format\Smiley' => ['showitem' => 'type,name,icon_class,smiley_shortcut'],
		'Mittwald\Typo3Forum\Domain\Model\Format\SyntaxHighlighting' => ['showitem' => 'type,name,icon,language'],
	],
	'columns' => [
		'sys_language_uid' => [
			'exclude' => TRUE,
			'label' => 'LLL:EXT:lang/Resources/Private/Language/locallang_general.xlf:LGL.language',
			'config' => [
				'type' => 'select',
				'renderType' => 'selectSingle',
				'foreign_table' => 'sys_language',
				'foreign_table_where' => 'ORDER BY sys_language.title',
				'items' => [
					['LLL:EXT:lang/Resources/Private/Language/locallang_general.xlf:LGL.allLanguages', -1],
					['LLL:EXT:lang/Resources/Private/Language/locallang_general.xlf:LGL.default_value', 0]
				],
				'default' => 0,
				'fieldWizard' => [
					'selectIcons' => [
						'disabled' => FALSE,
					],
				],
			]
		],
		'l18n_parent' => [
			'displayCond' => 'FIELD:sys_language_uid:>:0',
			'exclude' => 1,
			'label' => 'LLL:EXT:lang/Resources/Private/Language/locallang_general.xlf:LGL.l18n_parent',
			'config' => [
				'type' => 'select',
				'renderType' => 'selectSingle',
				'items' => [
					['', 0],
				],
				'foreign_table' => 'tx_typo3forum_domain_model_forum_access',
				'foreign_table_where' => 'AND tx_typo3forum_domain_model_forum_access.uid=###REC_FIELD_l18n_parent### AND tx_typo3forum_domain_model_forum_access.sys_language_uid IN (-1,0)',
			],
		],
		'l18n_diffsource' => [
			'config' => [
				'type' => 'passthrough'],
		],
		't3ver_label' => [
			'displayCond' => 'FIELD:t3ver_label:REQ:true',
			'label' => 'LLL:EXT:lang/Resources/Private/Language/locallang_general.xlf:LGL.versionLabel',
			'config' => [
				'type' => 'none',
				'cols' => 27
			],
		],
		'hidden' => [
			'exclude' => 1,
			'label' => 'LLL:EXT:lang/Resources/Private/Language/locallang_general.xlf:LGL.hidden',
			'config' => [
				'type' => 'check'
			],
		],
		'type' => [
			'exclude' => 1,
			'label' => 'LLL:EXT:typo3_forum/Resources/Private/Language/locallang_db.xml:tx_typo3forum_domain_model_format_textparser.type',
			'config' => [
				'type' => 'select',
				'renderType' => 'selectSingle',
				'items' => [
					['LLL:EXT:typo3_forum/Resources/Private/Language/locallang_db.xml:tx_typo3forum_domain_model_format_textparser.type.bbcode', 'Mittwald\Typo3Forum\Domain\Model\Format\BBCode'],
					['LLL:EXT:typo3_forum/Resources/Private/Language/locallang_db.xml:tx_typo3forum_domain_model_format_textparser.type.quote', 'Mittwald\Typo3Forum\Domain\Model\Format\QuoteBBCode'],
					['LLL:EXT:typo3_forum/Resources/Private/Language/locallang_db.xml:tx_typo3forum_domain_model_format_textparser.type.list', 'Mittwald\Typo3Forum\Domain\Model\Format\ListBBCode'],
					['LLL:EXT:typo3_forum/Resources/Private/Language/locallang_db.xml:tx_typo3forum_domain_model_format_textparser.type.smiley', 'Mittwald\Typo3Forum\Domain\Model\Format\Smiley'],
					['LLL:EXT:typo3_forum/Resources/Private/Language/locallang_db.xml:tx_typo3forum_domain_model_format_textparser.type.syntax', 'Mittwald\Typo3Forum\Domain\Model\Format\SyntaxHighlighting'],
				],
			],
		],
		'name' => [
			'exclude' => 1,
			'label' => 'LLL:EXT:typo3_forum/Resources/Private/Language/locallang_db.xml:tx_typo3forum_domain_model_format_textparser.name',
			'config' => [
				'type' => 'input',
				'size' => 30,
				'eval' => 'trim,required'
			],
		],
		'icon_class' => [
			'exclude' => 1,
			'label' => 'LLL:EXT:typo3_forum/Resources/Private/Language/locallang_db.xml:tx_typo3forum_domain_model_format_textparser.icon_class',
			'config' => [
				'type' => 'input',
				'size' => 30,
				'eval' => 'trim,required'
			],
		],
		'bbcode_wrap' => [
			'exclude' => 1,
			'label' => 'LLL:EXT:typo3_forum/Resources/Private/Language/locallang_db.xml:tx_typo3forum_domain_model_format_textparser.bbcode_wrap',
			'config' => [
				'type' => 'input',
				'size' => 30,
				'eval' => 'trim,required'
			],
		],
		'regular_expression' => [
			'exclude' => 1,
			'label' => 'LLL:EXT:typo3_forum/Resources/Private/Language/locallang_db.xml:tx_typo3forum_domain_model_format_textparser.regular_expression',
			'config' => [
				'type' => 'input',
				'size' => 30,
				'eval' => 'trim,required'
			],
		],
		'regular_expression_replacement' => [
			'exclude' => 1,
			'label' => 'LLL:EXT:typo3_forum/Resources/Private/Language/locallang_db.xml:tx_typo3forum_domain_model_format_textparser.regular_expression_replacement',
			'config' => [
				'type' => 'input',
				'size' => 30,
				'eval' => 'trim,required'
			],
		],
		'smiley_shortcut' => [
			'exclude' => 1,
			'label' => 'LLL:EXT:typo3_forum/Resources/Private/Language/locallang_db.xml:tx_typo3forum_domain_model_format_textparser.smiley_shortcut',
			'config' => [
				'type' => 'input',
				'size' => 30,
				'eval' => 'trim,required'
			],
		],
		'language' => [
			'exclude' => 1,
			'label' => 'LLL:EXT:typo3_forum/Resources/Private/Language/locallang_db.xml:tx_typo3forum_domain_model_format_textparser.language',
			'config' => [
				'type' => 'input',
				'size' => 30,
				'eval' => 'trim,required'
			],
		],
	],
];
