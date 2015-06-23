<?php
if (!defined ('TYPO3_MODE')) 	die ('Access denied.');

$TCA['tx_typo3forum_domain_model_format_textparser'] = array(
	'ctrl' => $TCA['tx_typo3forum_domain_model_format_textparser']['ctrl'],
	'interface' => array(
		'showRecordFieldList' => 'type,name,icon_class,bbcode_wrap,regular_expression,regular_expression_replacement,smilie_shortcut,language'
	),
	'types' => array(
		'1' => array('showitem' => 'type'),
		'\Mittwald\Typo3Forum\Domain\Model\Format\BBCode' => array('showitem' => 'type,name,icon_class,bbcode_wrap,regular_expression,regular_expression_replacement'),
		'\Mittwald\Typo3Forum\Domain\Model\Format\QuoteBBCode' => array('showitem' => 'type,name,icon_class'),
		'\Mittwald\Typo3Forum\Domain\Model\Format\ListBBCode' => array('showitem' => 'type,name,icon_class'),
		'\Mittwald\Typo3Forum\Domain\Model\Format\Smilie' => array('showitem' => 'type,name,icon_class,smilie_shortcut'),
		'\Mittwald\Typo3Forum\Domain\Model\Format\SyntaxHighlighting' => array('showitem' => 'type,name,icon,language')
	),
	'palettes' => array(
		'1' => array('showitem' => '')
	),
	'columns' => array(
		'sys_language_uid' => array(
			'exclude' => 1,
			'label' => 'LLL:EXT:lang/locallang_general.php:LGL.language',
			'config' => array(
				'type' => 'select',
				'foreign_table' => 'sys_language',
				'foreign_table_where' => 'ORDER BY sys_language.title',
				'items' => array(
					array('LLL:EXT:lang/locallang_general.php:LGL.allLanguages',-1),
					array('LLL:EXT:lang/locallang_general.php:LGL.default_value',0)
				)
			)
		),
		'l18n_parent' => array(
			'displayCond' => 'FIELD:sys_language_uid:>:0',
			'exclude' => 1,
			'label' => 'LLL:EXT:lang/locallang_general.php:LGL.l18n_parent',
			'config' => array(
				'type' => 'select',
				'items' => array(
					array('', 0),
				),
				'foreign_table' => 'tx_typo3forum_domain_model_forum_access',
				'foreign_table_where' => 'AND tx_typo3forum_domain_model_forum_access.uid=###REC_FIELD_l18n_parent### AND tx_typo3forum_domain_model_forum_access.sys_language_uid IN (-1,0)',
			)
		),
		'l18n_diffsource' => array(
			'config'=>array(
				'type'=>'passthrough')
		),
		't3ver_label' => array(
			'displayCond' => 'FIELD:t3ver_label:REQ:true',
			'label' => 'LLL:EXT:lang/locallang_general.php:LGL.versionLabel',
			'config' => array(
				'type'=>'none',
				'cols' => 27
			)
		),
		'hidden' => array(
			'exclude' => 1,
			'label'   => 'LLL:EXT:lang/locallang_general.xml:LGL.hidden',
			'config'  => array(
				'type' => 'check'
			)
		),
		'type' => array(
			'exclude' => 1,
			'label'   => 'LLL:EXT:typo3_forum/Resources/Private/Language/locallang_db.xml:tx_typo3forum_domain_model_format_textparser.type',
			'config'  => array(
				'type' => 'select',
				'items' => array(
					array('LLL:EXT:typo3_forum/Resources/Private/Language/locallang_db.xml:tx_typo3forum_domain_model_format_textparser.type.bbcode', '\Mittwald\Typo3Forum\Domain\Model\Format\BBCode'),
					array('LLL:EXT:typo3_forum/Resources/Private/Language/locallang_db.xml:tx_typo3forum_domain_model_format_textparser.type.quote', '\Mittwald\Typo3Forum\Domain\Model\Format\QuoteBBCode'),
					array('LLL:EXT:typo3_forum/Resources/Private/Language/locallang_db.xml:tx_typo3forum_domain_model_format_textparser.type.list', '\Mittwald\Typo3Forum\Domain\Model\Format\ListBBCode'),
					array('LLL:EXT:typo3_forum/Resources/Private/Language/locallang_db.xml:tx_typo3forum_domain_model_format_textparser.type.smilie', '\Mittwald\Typo3Forum\Domain\Model\Format\Smilie'),
					array('LLL:EXT:typo3_forum/Resources/Private/Language/locallang_db.xml:tx_typo3forum_domain_model_format_textparser.type.syntax', '\Mittwald\Typo3Forum\Domain\Model\Format\SyntaxHighlighting'),
				)
			)
		),
		'name' => array(
			'exclude' => 1,
			'label'   => 'LLL:EXT:typo3_forum/Resources/Private/Language/locallang_db.xml:tx_typo3forum_domain_model_format_textparser.name',
			'config'  => array(
				'type' => 'input',
				'size' => 30,
				'eval' => 'trim,required'
			)
		),
		'icon_class' => array(
			'exclude' => 1,
			'label'   => 'LLL:EXT:typo3_forum/Resources/Private/Language/locallang_db.xml:tx_typo3forum_domain_model_format_textparser.icon_class',
			'config'  => array(
				'type' => 'input',
				'size' => 30,
				'eval' => 'trim,required'
			)
		),
		'bbcode_wrap' => array(
			'exclude' => 1,
			'label'   => 'LLL:EXT:typo3_forum/Resources/Private/Language/locallang_db.xml:tx_typo3forum_domain_model_format_textparser.bbcode_wrap',
			'config'  => array(
				'type' => 'input',
				'size' => 30,
				'eval' => 'trim,required'
			)
		),
		'regular_expression' => array(
			'exclude' => 1,
			'label'   => 'LLL:EXT:typo3_forum/Resources/Private/Language/locallang_db.xml:tx_typo3forum_domain_model_format_textparser.regular_expression',
			'config'  => array(
				'type' => 'input',
				'size' => 30,
				'eval' => 'trim,required'
			)
		),
		'regular_expression_replacement' => array(
			'exclude' => 1,
			'label'   => 'LLL:EXT:typo3_forum/Resources/Private/Language/locallang_db.xml:tx_typo3forum_domain_model_format_textparser.regular_expression_replacement',
			'config'  => array(
				'type' => 'input',
				'size' => 30,
				'eval' => 'trim,required'
			)
		),
		'smilie_shortcut' => array(
			'exclude' => 1,
			'label'   => 'LLL:EXT:typo3_forum/Resources/Private/Language/locallang_db.xml:tx_typo3forum_domain_model_format_textparser.smilie_shortcut',
			'config'  => array(
				'type' => 'input',
				'size' => 30,
				'eval' => 'trim,required'
			)
		),
		'language' => array(
			'exclude' => 1,
			'label'   => 'LLL:EXT:typo3_forum/Resources/Private/Language/locallang_db.xml:tx_typo3forum_domain_model_format_textparser.language',
			'config'  => array(
				'type' => 'input',
				'size' => 30,
				'eval' => 'trim,required'
			)
		),
	),
);