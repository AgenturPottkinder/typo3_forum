<?php
if (!defined ('TYPO3_MODE')) 	die ('Access denied.');

$TCA['tx_mmforum_domain_model_stats_summary'] = array(
	'ctrl' => $TCA['tx_mmforum_domain_model_stats_summary']['ctrl'],
	'interface' => array(
		'showRecordFieldList' => 'type,amount,tstamp'
	),
	'types' => array(
		'1' => array('showitem' => 'type,amount,tstamp')
	),
	'palettes' => array(
		'1' => array('showitem' => '')
	),
	'columns' => array(
		'type' => array(
			'exclude' => 0,
			'label'   => 'LLL:EXT:mm_forum/Resources/Private/Language/locallang_db.xml:tx_mmforum_domain_model_stats_summary_type',
			'config'  => array(
				'type' => 'radio',
				'items' => Array (
					Array('Post', 'Tx_MmForum_Domain_Model_Forum_Post'),
					Array('Topic', 'Tx_MmForum_Domain_Model_Forum_Topic'),
					Array('User', 'Tx_MmForum_Domain_Model_User_FrontendUser')
				),
				'default' => 'Tx_MmForum_Domain_Model_Forum_Post',
			)
		),
		'amount' => array(
			'exclude' => 0,
			'label'   => 'LLL:EXT:mm_forum/Resources/Private/Language/locallang_db.xml:tx_mmforum_domain_model_stats_summary_amount',
			'config'  => array(
				'type' => 'input'
			)
		),
		'tstamp' => array(
			'exclude' => 0,
			'label'   => 'LLL:EXT:mm_forum/Resources/Private/Language/locallang_db.xml:tx_mmforum_domain_model_stats_summary_tstamp',
			'config' => Array (
				'type' => 'none',
				'format' => 'date',
				'eval' => 'date',
			)
		),
	)
);
?>