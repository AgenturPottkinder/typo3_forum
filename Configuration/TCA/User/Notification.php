<?php
if (!defined ('TYPO3_MODE')) 	die ('Access denied.');

$TCA['tx_mmforum_domain_model_user_notification'] = array(
	'ctrl' => $TCA['tx_mmforum_domain_model_user_notification']['ctrl'],
	'interface' => array(
		'showRecordFieldList' => 'feuser,post,tag,user_read,type,crdate'
	),
	'types' => array(
		'1' => array('showitem' => 'feuser,post,tag,user_read,type,crdate')
	),
	'palettes' => array(
		'1' => array('showitem' => '')
	),
	'columns' => array(
		'crdate' => Array (
			'exclude' => 1,
			'label' => 'LLL:EXT:mm_forum/Resources/Private/Language/locallang_db.xml:tx_mmforum_domain_model_user_notification_crdate',
			'config' => Array (
				'type' => 'none',
				'format' => 'date',
				'eval' => 'date',
			)
		),
		'feuser' => array(
			'exclude' => 0,
			'label'   => 'LLL:EXT:mm_forum/Resources/Private/Language/locallang_db.xml:tx_mmforum_domain_model_user_notification_feuser',
			'config'  => array(
				'type' => 'select',
				'foreign_table' => 'fe_users',
				'foreign_class' => 'Tx_MmForum_Domain_Model_User_FrontendUser',
				'maxitems' => 1
			)
		),
		'post' => array(
			'exclude' => 0,
			'label'   => 'LLL:EXT:mm_forum/Resources/Private/Language/locallang_db.xml:tx_mmforum_domain_model_user_notification_post',
			'config'  => array(
				'type' => 'select',
				'foreign_table' => 'tx_mmforum_domain_model_forum_post',
				'foreign_class' => 'Tx_MmForum_Domain_Model_Forum_Post',
				'maxitems' => 1
			)
		),
		'tag' => array(
			'exclude' => 0,
			'label'   => 'LLL:EXT:mm_forum/Resources/Private/Language/locallang_db.xml:tx_mmforum_domain_model_user_notification_tag',
			'config'  => array(
				'type' => 'select',
				'foreign_table' => 'tx_mmforum_domain_model_forum_tag',
				'foreign_class' => 'Tx_MmForum_Domain_Model_Forum_Tag',
				'maxitems' => 1
			)
		),
		'user_read' => array(
			'exclude' => 0,
			'label'   => 'LLL:EXT:mm_forum/Resources/Private/Language/locallang_db.xml:tx_mmforum_domain_model_user_notification_userRead',
			'config'  => array(
				'type' => 'check'
			)
		),
		'type' => Array (
			'exclude' => 1,
			'label' => 'LLL:EXT:mm_forum/Resources/Private/Language/locallang_db.xml:tx_mmforum_domain_model_user_notification_type',
			'config' => Array (
				'type' => 'select',
				'items' => array(
					array('LLL:EXT:mm_forum/Resources/Private/Language/locallang_db.xml:tx_mmforum_domain_model_forum_post', 'Tx_MmForum_Domain_Model_Forum_Post'),
				),
			)
		),
	)
);
?>