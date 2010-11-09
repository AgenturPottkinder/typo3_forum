<?php
if (!defined ('TYPO3_MODE')) 	die ('Access denied.');

$TCA['tx_mmmessaging_domain_model_message'] = array(
	'ctrl' => $TCA['tx_mmmessaging_domain_model_message']['ctrl'],
	'interface' => array(
		'showRecordFieldList' => 'recipient,sender,subject,text,user_read,attachments,anwers,archived'
	),
	'types' => array(
		'1' => array('showitem' => 'recipient,sender,subject,text,user_read,attachments,anwers,archived')
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
				'foreign_table' => 'tx_mmmessaging_domain_model_message',
				'foreign_table_where' => 'AND tx_mmmessaging_domain_model_message.uid=###REC_FIELD_l18n_parent### AND tx_mmmessaging_domain_model_message.sys_language_uid IN (-1,0)',
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
		'crdate' => array(
			'config'=>array(
				'type'=>'passthrough')
		),
		'recipient' => array(
			'exclude' => 1,
			'label'   => 'LLL:EXT:mm_messaging/Resources/Private/Language/locallang_db.xml:tx_mmmessaging_domain_model_message.recipient',
			'config'  => array(
				'type' => 'select',
				'foreign_table' => 'fe_users',
				'foreign_class' => 'Tx_MmForum_Domain_Model_User_FrontendUser',
				'maxitems' => 1,
				'minitems' => 1
			)
		),
		'sender' => array(
			'exclude' => 1,
			'label'   => 'LLL:EXT:mm_messaging/Resources/Private/Language/locallang_db.xml:tx_mmmessaging_domain_model_message.sender',
			'config'  => array(
				'type' => 'select',
				'foreign_table' => 'fe_users',
				'foreign_class' => 'Tx_MmForum_Domain_Model_User_FrontendUser',
				'maxitems' => 1,
				'minitems' => 1
			)
		),
		'subject' => array(
			'exclude' => 1,
			'label'   => 'LLL:EXT:mm_messaging/Resources/Private/Language/locallang_db.xml:tx_mmmessaging_domain_model_message.subject',
			'config'  => array(
				'type' => 'input',
				'size' => 30,
				'eval' => 'trim,required'
			)
		),
		'text' => array(
			'exclude' => 1,
			'label'   => 'LLL:EXT:mm_messaging/Resources/Private/Language/locallang_db.xml:tx_mmmessaging_domain_model_message.text',
			'config'  => array(
				'type' => 'input',
				'size' => 30,
				'eval' => 'trim,required'
			)
		),
		'user_read' => array(
			'exclude' => 1,
			'label'   => 'LLL:EXT:mm_messaging/Resources/Private/Language/locallang_db.xml:tx_mmmessaging_domain_model_message.user_read',
			'config'  => array(
				'type' => 'check',
				'default' => 0
			)
		),
		'attachments' => array(
			'exclude' => 0,
			'label'   => 'LLL:EXT:mm_messaging/Resources/Private/Language/locallang_db.xml:tx_mmmessaging_domain_model_message.attachments',
			'config'  => array(
				'type' => 'inline',
				'foreign_table' => 'tx_mmmessaging_domain_model_attachment',
				'foreign_field' => 'message',
				'maxitems'      => 9999,
				'appearance' => array(
					'collapse' => 0,
					'newRecordLinkPosition' => 'bottom',
				),
			)
		),
		'anwers' => array(
			'exclude' => 0,
			'label'   => 'LLL:EXT:mm_messaging/Resources/Private/Language/locallang_db.xml:tx_mmmessaging_domain_model_message.anwers',
			'config'  => array(
				'type' => 'inline',
				'foreign_table' => 'tx_mmmessaging_domain_model_message',
				'foreign_field' => 'message',
				'maxitems'      => 9999,
				'appearance' => array(
					'collapse' => 0,
					'newRecordLinkPosition' => 'bottom',
				),
			)
		),
		'archived' => array(
			'exclude' => 1,
			'label'   => 'LLL:EXT:mm_messaging/Resources/Private/Language/locallang_db.xml:tx_mmmessaging_domain_model_message.archived',
			'config'  => array(
				'type' => 'check',
				'default' => 0
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