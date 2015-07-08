<?php
if (!defined ('TYPO3_MODE')) 	die ('Access denied.');

$TCA['tx_typo3forum_domain_model_forum_tag'] = array(
	'ctrl' => $TCA['tx_typo3forum_domain_model_forum_tag']['ctrl'],
	'interface' => array(
		'showRecordFieldList' => 'name,tstamp,crdate,topic_count,feuser'
	),
	'types' => array(
		'1' => array('showitem' => 'name,tstamp,crdate,topic_count,feuser')
	),
	'palettes' => array(
		'1' => array('showitem' => '')
	),
	'columns' => array(
		'name' => array(
			'exclude' => 0,
			'label'   => 'LLL:EXT:typo3_forum/Resources/Private/Language/locallang_db.xml:tx_typo3forum_domain_model_forum_tag_name',
			'config'  => array(
				'type' => 'input'
			)
		),
		'tstamp' => array(
			'exclude' => 0,
			'label'   => 'LLL:EXT:typo3_forum/Resources/Private/Language/locallang_db.xml:tx_typo3forum_domain_model_forum_tag_tstamp',
			'config' => Array (
				'type' => 'none',
				'format' => 'date',
				'eval' => 'date',
			)
		),
		'crdate' => array(
			'exclude' => 0,
			'label'   => 'LLL:EXT:typo3_forum/Resources/Private/Language/locallang_db.xml:tx_typo3forum_domain_model_forum_tag_crdate',
			'config' => Array (
				'type' => 'none',
				'format' => 'date',
				'eval' => 'date',
			)
		),
		'topic_count' => array(
			'exclude' => 0,
			'label' => 'LLL:EXT:typo3_forum/Resources/Private/Language/locallang_db.xml:tx_typo3forum_domain_model_forum_tag_topicCount',
			'config' => array(
				'type' => 'none',
			),
		),
		'feuser' => array(
			'exclude' => 0,
			'label' => 'LLL:EXT:typo3_forum/Resources/Private/Language/locallang_db.xml:tx_typo3forum_domain_model_forum_tag',
			'config' => array(
				'type'          => 'select',
				'size'          => 10,
				'maxitems'      => 99999,
				'foreign_table' => 'fe_users',
				'MM' => 'tx_typo3forum_domain_model_forum_tag_user'
			),
		),
	)
);