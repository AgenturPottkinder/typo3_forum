<?php
if (!defined ('TYPO3_MODE')) 	die ('Access denied.');

$TCA['tx_mmforum_domain_model_user_privatemessages'] = array(
	'ctrl' => $TCA['tx_mmforum_domain_model_user_privatemessages']['ctrl'],
	'interface' => array(
		'showRecordFieldList' => 'message, feuser, opponent, type, user_read, crdate'
	),
	'types' => array(
		'1' => array('showitem' => 'message, feuser, opponent, type, user_read, crdate')
	),
	'palettes' => array(
		'1' => array('showitem' => '')
	),
	'columns' => array(
		'message' => array(
			'exclude' => 0,
			'label'   => 'LLL:EXT:mm_forum/Resources/Private/Language/locallang_db.xml:tx_mmforum_domain_model_user_pm_message',
			'config'  => array(
				'type' => 'inline',
				'foreign_table' => 'tx_mmforum_domain_model_user_privatemessages_text',
				'foreign_class' => 'Tx_MmForum_Domain_Model_User_PrivateMessagesText',
				'maxitems'      => 1,
				'appearance' => array(
					'collapseAll' => 1,
					'newRecordLinkPosition' => 'bottom',
					'expandSingle' => 1,
				),
			)
		),
		'feuser' => array(
			'exclude' => 0,
			'label'   => 'LLL:EXT:mm_forum/Resources/Private/Language/locallang_db.xml:tx_mmforum_domain_model_user_pm_feuser',
			'config'  => array(
				'type' => 'inline',
				'foreign_table' => 'fe_users',
				'foreign_class' => 'Tx_MmForum_Domain_Model_User_FrontendUser',
				'maxitems' => 1
			)
		),
		'opponent' => array(
			'exclude' => 0,
			'label'   => 'LLL:EXT:mm_forum/Resources/Private/Language/locallang_db.xml:tx_mmforum_domain_model_user_pm_opponent',
			'config'  => array(
				'type' => 'inline',
				'foreign_table' => 'fe_users',
				'foreign_class' => 'Tx_MmForum_Domain_Model_User_FrontendUser',
				'maxitems' => 1
			)
		),
		'type' => array(
			'exclude' => 0,
			'label'   => 'LLL:EXT:mm_forum/Resources/Private/Language/locallang_db.xml:tx_mmforum_domain_model_user_pm_recipient_type',
			'config'  => array(
				'type' => 'radio',
				'items' => Array (
					Array('sender', 0),
					Array('recipient', 1),
				),
				'default' => 0,
			)
		),
		'user_read' => array(
			'exclude' => 0,
			'label'   => 'LLL:EXT:mm_forum/Resources/Private/Language/locallang_db.xml:tx_mmforum_domain_model_user_pm_recipient_read',
			'config'  => array(
				'type' => 'check'
			)
		),
		'crdate' => Array (
			'exclude' => 1,
			'label' => 'LLL:EXT:mm_forum/Resources/Private/Language/locallang_db.xml:tstamp',
			'config' => Array (
				'type' => 'none',
				'format' => 'date',
				'eval' => 'date',
			)
		),
	)
);
?>