<?php
if (!defined ('TYPO3_MODE')) 	die ('Access denied.');

$TCA['tx_mmforum_domain_model_user_userfield_userfield'] = array(
	'ctrl' => $TCA['tx_mmforum_domain_model_user_userfield_userfield']['ctrl'],
	'interface' => array(
		'showRecordFieldList' => 'type,name,typoscript_path,map_to_user_object'
	),
	'types' => array(
		'0' => array('showitem' => 'type,name,map_to_user_object'),
		'Tx_MmForum_Domain_Model_User_Userfield_TyposcriptUserfield' => array('showitem' => 'type,name,typoscript_path,map_to_user_object'),
		'Tx_MmForum_Domain_Model_User_Userfield_TextUserfield' => array('showitem' => 'type,name,map_to_user_object')
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
				'foreign_table' => 'tx_mmforum_domain_model_user_userfield_userfield',
				'foreign_table_where' => 'AND tx_mmforum_domain_model_user_userfield_userfield.uid=###REC_FIELD_l18n_parent### AND tx_mmforum_domain_model_user_userfield_userfield.sys_language_uid IN (-1,0)',
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
			'label'   => 'LLL:EXT:mm_forum/Resources/Private/Language/locallang_db.xml:tx_mmforum_domain_model_user_userfield_userfield.type',
			'config' => array(
				'type' => 'select',
				'items' => array(
					array('LLL:EXT:mm_forum/Resources/Private/Language/locallang_db.xml:tx_mmforum_domain_model_user_userfield_userfield.type.undefined', 0),
					array('LLL:EXT:mm_forum/Resources/Private/Language/locallang_db.xml:tx_mmforum_domain_model_user_userfield_userfield.type.typoscript', 'Tx_MmForum_Domain_Model_User_Userfield_TyposcriptUserfield'),
					array('LLL:EXT:mm_forum/Resources/Private/Language/locallang_db.xml:tx_mmforum_domain_model_user_userfield_userfield.type.text', 'Tx_MmForum_Domain_Model_User_Userfield_TextUserfield')

				)
			)
		),
		'name' => array(
			'exclude' => 1,
			'label'   => 'LLL:EXT:mm_forum/Resources/Private/Language/locallang_db.xml:tx_mmforum_domain_model_user_userfield_userfield.name',
			'config'  => array(
				'type' => 'input',
				'size' => 30,
				'eval' => 'trim,required'
			)
		),
		'typoscript_path' => array(
			'exclude' => 1,
			'label'   => 'LLL:EXT:mm_forum/Resources/Private/Language/locallang_db.xml:tx_mmforum_domain_model_user_userfield_userfield.typoscript_path',
			'config'  => array(
				'type' => 'input',
				'size' => 30,
				'eval' => 'trim,required'
			)
		),
		'map_to_user_object' => array(
			'exclude' => 1,
			'label'   => 'LLL:EXT:mm_forum/Resources/Private/Language/locallang_db.xml:tx_mmforum_domain_model_user_userfield_userfield.map_to_user_object',
			'config'  => array(
				'type' => 'input',
				'size' => 30,
				'eval' => 'trim',
				'checkbox' => TRUE
			)
		),
	),
);
?>