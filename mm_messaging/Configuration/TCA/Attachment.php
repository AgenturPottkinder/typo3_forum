<?php
if (!defined ('TYPO3_MODE')) 	die ('Access denied.');

$TCA['tx_mmmessaging_domain_model_attachment'] = array(
	'ctrl' => $TCA['tx_mmmessaging_domain_model_attachment']['ctrl'],
	'interface' => array(
		'showRecordFieldList' => 'name,type,size'
	),
	'types' => array(
		'1' => array('showitem' => 'name,type,size')
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
				'foreign_table' => 'tx_mmmessaging_domain_model_attachment',
				'foreign_table_where' => 'AND tx_mmmessaging_domain_model_attachment.uid=###REC_FIELD_l18n_parent### AND tx_mmmessaging_domain_model_attachment.sys_language_uid IN (-1,0)',
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
		'name' => array(
			'exclude' => 1,
			'label'   => 'LLL:EXT:mm_messaging/Resources/Private/Language/locallang_db.xml:tx_mmmessaging_domain_model_attachment.name',
			'config'  => array(
				'type' => 'input',
				'size' => 30,
				'eval' => 'trim,required'
			)
		),
		'type' => array(
			'exclude' => 1,
			'label'   => 'LLL:EXT:mm_messaging/Resources/Private/Language/locallang_db.xml:tx_mmmessaging_domain_model_attachment.type',
			'config'  => array(
				'type' => 'input',
				'size' => 30,
				'eval' => 'trim,required'
			)
		),
		'size' => array(
			'exclude' => 1,
			'label'   => 'LLL:EXT:mm_messaging/Resources/Private/Language/locallang_db.xml:tx_mmmessaging_domain_model_attachment.size',
			'config'  => array(
				'type' => 'input',
				'size' => 30,
				'eval' => 'trim,required'
			)
		),
		'message' => array(
			'config' => array(
				'type' => 'passthrough',
			)
		),
	),
);
?>