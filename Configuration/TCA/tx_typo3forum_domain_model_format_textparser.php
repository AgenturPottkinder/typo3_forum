<?php

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addLLrefForTCAdescr(
	'tx_typo3forum_domain_model_format_textparser',
	'EXT:typo3_forum/Resources/Private/Language/locallang_csh_tx_typo3forum_domain_model_format_textparser.xml'
);

$lllPath = 'LLL:EXT:typo3_forum/Resources/Private/Language/locallang_db.xml:tx_typo3forum_domain_model_format_textparser.';

return [
	'ctrl' => [
		'title' => 'LLL:EXT:typo3_forum/Resources/Private/Language/locallang_db.xml:tx_typo3forum_domain_model_format_textparser',
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
			'disabled' => 'hidden'
		],
		'iconfile' => \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extRelPath('typo3_forum') . 'Resources/Public/Icons/Format/Textparser.png'
	],
	'interface' => [
		'showRecordFieldList' => 'type,name,icon_class,bbcode_wrap,regular_expression,regular_expression_replacement,smiley_shortcut,language'
	],
	'types' => [
		'1' => ['showitem' => 'type'],
		'\Mittwald\Typo3Forum\Domain\Model\Format\BBCode' => ['showitem' => 'type,name,icon_class,bbcode_wrap,regular_expression,regular_expression_replacement'],
		'\Mittwald\Typo3Forum\Domain\Model\Format\QuoteBBCode' => ['showitem' => 'type,name,icon_class'],
		'\Mittwald\Typo3Forum\Domain\Model\Format\ListBBCode' => ['showitem' => 'type,name,icon_class'],
		'\Mittwald\Typo3Forum\Domain\Model\Format\Smiley' => ['showitem' => 'type,name,icon_class,smiley_shortcut'],
		'\Mittwald\Typo3Forum\Domain\Model\Format\SyntaxHighlighting' => ['showitem' => 'type,name,icon,language'],
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
				'foreign_table' => 'tx_typo3forum_domain_model_forum_access',
				'foreign_table_where' => 'AND tx_typo3forum_domain_model_forum_access.uid=###REC_FIELD_l18n_parent### AND tx_typo3forum_domain_model_forum_access.sys_language_uid IN (-1,0]',
			],
		],
		'l18n_diffsource' => [
			'config' => [
				'type' => 'passthrough'],
		],
		't3ver_label' => [
			'displayCond' => 'FIELD:t3ver_label:REQ:true',
			'label' => 'LLL:EXT:lang/locallang_general.php:LGL.versionLabel',
			'config' => [
				'type' => 'none',
				'cols' => 27
			],
		],
		'hidden' => [
			'exclude' => 1,
			'label' => 'LLL:EXT:lang/locallang_general.xml:LGL.hidden',
			'config' => [
				'type' => 'check'
			],
		],
		'type' => [
			'exclude' => 1,
			'label' => $lllPath . 'type',
			'config' => [
				'type' => 'select',
				'items' => [
					[$lllPath . 'type.bbcode', '\Mittwald\Typo3Forum\Domain\Model\Format\BBCode'],
					[$lllPath . 'type.quote', '\Mittwald\Typo3Forum\Domain\Model\Format\QuoteBBCode'],
					[$lllPath . 'type.list', '\Mittwald\Typo3Forum\Domain\Model\Format\ListBBCode'],
					[$lllPath . 'type.smiley', '\Mittwald\Typo3Forum\Domain\Model\Format\Smiley'],
					[$lllPath . 'type.syntax', '\Mittwald\Typo3Forum\Domain\Model\Format\SyntaxHighlighting'],
				],
			],
		],
		'name' => [
			'exclude' => 1,
			'label' => $lllPath . 'name',
			'config' => [
				'type' => 'input',
				'size' => 30,
				'eval' => 'trim,required'
			],
		],
		'icon_class' => [
			'exclude' => 1,
			'label' => $lllPath . 'icon_class',
			'config' => [
				'type' => 'input',
				'size' => 30,
				'eval' => 'trim,required'
			],
		],
		'bbcode_wrap' => [
			'exclude' => 1,
			'label' => $lllPath . 'bbcode_wrap',
			'config' => [
				'type' => 'input',
				'size' => 30,
				'eval' => 'trim,required'
			],
		],
		'regular_expression' => [
			'exclude' => 1,
			'label' => $lllPath . 'regular_expression',
			'config' => [
				'type' => 'input',
				'size' => 30,
				'eval' => 'trim,required'
			],
		],
		'regular_expression_replacement' => [
			'exclude' => 1,
			'label' => $lllPath . 'regular_expression_replacement',
			'config' => [
				'type' => 'input',
				'size' => 30,
				'eval' => 'trim,required'
			],
		],
		'smiley_shortcut' => [
			'exclude' => 1,
			'label' => $lllPath . 'smiley_shortcut',
			'config' => [
				'type' => 'input',
				'size' => 30,
				'eval' => 'trim,required'
			],
		],
		'language' => [
			'exclude' => 1,
			'label' => $lllPath . 'language',
			'config' => [
				'type' => 'input',
				'size' => 30,
				'eval' => 'trim,required'
			],
		],
	],
];
