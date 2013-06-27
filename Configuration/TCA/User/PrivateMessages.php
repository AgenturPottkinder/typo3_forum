<?php
if (!defined ('TYPO3_MODE')) 	die ('Access denied.');

$TCA['tx_mmforum_domain_model_user_private_messages'] = array(
	'ctrl' => $TCA['tx_mmforum_domain_model_user_private_messages']['ctrl'],
	'interface' => array(
		'showRecordFieldList' => 'sender,recipient,message,recipient_read'
	),
	'types' => array(
		'1' => array('showitem' => 'sender,recipient,message,recipient_read')
	),
	'palettes' => array(
		'1' => array('showitem' => '')
	),
	'columns' => array(
		'sender' => array(
			'exclude' => 0,
			'label'   => 'LLL:EXT:mm_forum/Resources/Private/Language/locallang_db.xml:tx_mmforum_domain_model_user_pm_sender',
			'config'  => array(
				'type' => 'select',
				'foreign_table' => 'fe_users',
				'foreign_class' => 'Tx_MmForum_Domain_Model_User_FrontendUser',
				'maxitems' => 1
			)
		),
		'recipient' => array(
			'exclude' => 0,
			'label'   => 'LLL:EXT:mm_forum/Resources/Private/Language/locallang_db.xml:tx_mmforum_domain_model_user_pm_recipient',
			'config'  => array(
				'type' => 'select',
				'foreign_table' => 'fe_users',
				'foreign_class' => 'Tx_MmForum_Domain_Model_User_FrontendUser',
				'maxitems' => 1
			)
		),
		'message' => array(
			'exclude' => 0,
			'label'   => 'LLL:EXT:mm_forum/Resources/Private/Language/locallang_db.xml:tx_mmforum_domain_model_user_pm_message',
			'config'  => array(
				'type' => 'text'
			)
		),
		'recipient_read' => array(
			'exclude' => 0,
			'label'   => 'LLL:EXT:mm_forum/Resources/Private/Language/locallang_db.xml:tx_mmforum_domain_model_user_pm_recipient_read',
			'config'  => array(
				'type' => 'check'
			)
		),
	)
);
?>