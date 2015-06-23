<?php
if (!defined ('TYPO3_MODE')) 	die ('Access denied.');

$TCA['tx_typo3forum_domain_model_stats_summary'] = array(
	'ctrl' => $TCA['tx_typo3forum_domain_model_stats_summary']['ctrl'],
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
			'label'   => 'LLL:EXT:typo3_forum/Resources/Private/Language/locallang_db.xml:tx_typo3forum_domain_model_stats_summary_type',
			'config'  => array(
				'type' => 'radio',
				'items' => Array (
					Array('Post', '0'),
					Array('Topic', '1'),
					Array('User', '2')
				),
				'default' => '0',
			)
		),
		'amount' => array(
			'exclude' => 0,
			'label'   => 'LLL:EXT:typo3_forum/Resources/Private/Language/locallang_db.xml:tx_typo3forum_domain_model_stats_summary_amount',
			'config'  => array(
				'type' => 'input'
			)
		),
		'tstamp' => array(
			'exclude' => 0,
			'label'   => 'LLL:EXT:typo3_forum/Resources/Private/Language/locallang_db.xml:tx_typo3forum_domain_model_stats_summary_tstamp',
			'config' => Array (
				'type' => 'none',
				'format' => 'date',
				'eval' => 'date',
			)
		),
	)
);