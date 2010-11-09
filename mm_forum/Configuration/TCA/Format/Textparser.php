<?php
if (!defined ('TYPO3_MODE')) 	die ('Access denied.');

$TCA['tx_mmforum_domain_model_format_textparser'] = array(
	'ctrl' => $TCA['tx_mmforum_domain_model_format_textparser']['ctrl'],
	'interface' => array(
		'showRecordFieldList' => 'type,name,icon,bbcode_wrap,regular_expression,regular_expression_replacement,smilie_shortcut,language'
	),
	'types' => array(
		'1' => array('showitem' => 'type'),
		'Tx_MmForum_Domain_Model_Format_BBCode' => array('showitem' => 'type,name,icon,bbcode_wrap,regular_expression,regular_expression_replacement'),
		'Tx_MmForum_Domain_Model_Format_QuoteBBCode' => array('showitem' => 'type,name,icon'),
		'Tx_MmForum_Domain_Model_Format_ListBBCode' => array('showitem' => 'type,name,icon'),
		'Tx_MmForum_Domain_Model_Format_Smilie' => array('showitem' => 'type,name,icon,smilie_shortcut'),
		'Tx_MmForum_Domain_Model_Format_SyntaxHighlighting' => array('showitem' => 'type,name,icon,language')
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
				'foreign_table' => 'tx_mmforum_domain_model_forum_access',
				'foreign_table_where' => 'AND tx_mmforum_domain_model_forum_access.uid=###REC_FIELD_l18n_parent### AND tx_mmforum_domain_model_forum_access.sys_language_uid IN (-1,0)',
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
			'label'   => 'LLL:EXT:mm_forum/Resources/Private/Language/locallang_db.xml:tx_mmforum_domain_model_format_textparser.type',
			'config'  => array(
				'type' => 'select',
				'items' => array(
					array('LLL:EXT:mm_forum/Resources/Private/Language/locallang_db.xml:tx_mmforum_domain_model_format_textparser.type.bbcode', 'Tx_MmForum_Domain_Model_Format_BBCode'),
					array('LLL:EXT:mm_forum/Resources/Private/Language/locallang_db.xml:tx_mmforum_domain_model_format_textparser.type.quote', 'Tx_MmForum_Domain_Model_Format_QuoteBBCode'),
					array('LLL:EXT:mm_forum/Resources/Private/Language/locallang_db.xml:tx_mmforum_domain_model_format_textparser.type.list', 'Tx_MmForum_Domain_Model_Format_ListBBCode'),
					array('LLL:EXT:mm_forum/Resources/Private/Language/locallang_db.xml:tx_mmforum_domain_model_format_textparser.type.smilie', 'Tx_MmForum_Domain_Model_Format_Smilie'),
					array('LLL:EXT:mm_forum/Resources/Private/Language/locallang_db.xml:tx_mmforum_domain_model_format_textparser.type.syntax', 'Tx_MmForum_Domain_Model_Format_SyntaxHighlighting'),
				)
			)
		),
		'name' => array(
			'exclude' => 1,
			'label'   => 'LLL:EXT:mm_forum/Resources/Private/Language/locallang_db.xml:tx_mmforum_domain_model_format_textparser.name',
			'config'  => array(
				'type' => 'input',
				'size' => 30,
				'eval' => 'trim,required'
			)
		),
		'icon' => array(
			'exclude' => 1,
			'label'   => 'LLL:EXT:mm_forum/Resources/Private/Language/locallang_db.xml:tx_mmforum_domain_model_format_textparser.icon',
			'config'  => array(
				'type' => 'group',
				'internal_type' => 'file',
				'uploadfolder' => 'uploads/tx_mmforum/textparser/',
				'minitems' => 1,
				'maxitems' => 1,
				'allowed' => '*',
				'disallowed' => ''
			)
		),
		'bbcode_wrap' => array(
			'exclude' => 1,
			'label'   => 'LLL:EXT:mm_forum/Resources/Private/Language/locallang_db.xml:tx_mmforum_domain_model_format_textparser.bbcode_wrap',
			'config'  => array(
				'type' => 'input',
				'size' => 30,
				'eval' => 'trim,required'
			)
		),
		'regular_expression' => array(
			'exclude' => 1,
			'label'   => 'LLL:EXT:mm_forum/Resources/Private/Language/locallang_db.xml:tx_mmforum_domain_model_format_textparser.regular_expression',
			'config'  => array(
				'type' => 'input',
				'size' => 30,
				'eval' => 'trim,required'
			)
		),
		'regular_expression_replacement' => array(
			'exclude' => 1,
			'label'   => 'LLL:EXT:mm_forum/Resources/Private/Language/locallang_db.xml:tx_mmforum_domain_model_format_textparser.regular_expression_replacement',
			'config'  => array(
				'type' => 'input',
				'size' => 30,
				'eval' => 'trim,required'
			)
		),
		'smilie_shortcut' => array(
			'exclude' => 1,
			'label'   => 'LLL:EXT:mm_forum/Resources/Private/Language/locallang_db.xml:tx_mmforum_domain_model_format_textparser.smilie_shortcut',
			'config'  => array(
				'type' => 'input',
				'size' => 30,
				'eval' => 'trim,required'
			)
		),
		'language' => array(
			'exclude' => 1,
			'label'   => 'LLL:EXT:mm_forum/Resources/Private/Language/locallang_db.xml:tx_mmforum_domain_model_format_textparser.language',
			'config'  => array(
				'type' => 'input',
				'size' => 30,
				'eval' => 'trim,required'
			)
		),
	),
);
?>