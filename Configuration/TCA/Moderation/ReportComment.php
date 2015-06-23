<?php
if (!defined ('TYPO3_MODE')) 	die ('Access denied.');

$TCA['tx_typo3forum_domain_model_moderation_reportcomment'] = array(
	'ctrl' => $TCA['tx_typo3forum_domain_model_moderation_reportcomment']['ctrl'],
	'interface' => array(
		'showRecordFieldList' => 'report,author,text,tstamp'
	),
	'types' => array(
		'1' => array('showitem' => 'report,author,text')
	),
	'palettes' => array(
		'1' => array('showitem' => '')
	),
	'columns' => array(
		'hidden' => array(
			'exclude' => 1,
			'label'   => 'LLL:EXT:lang/locallang_general.xml:LGL.hidden',
			'config'  => array(
				'type' => 'check'
			)
		),
		'tstamp' => array(
			'exclude' => 1,
			'label'   => 'LLL:EXT:lang/locallang_general.xml:LGL.tstamp',
			'config'  => array(
				'type' => 'passthrough'
			)
		),
		'report' => array(
			'exclude' => 1,
			'label'   => 'LLL:EXT:typo3_forum/Resources/Private/Language/locallang_db.xml:tx_typo3forum_domain_model_moderation_reportcomment.report',
			'config' => array(
				'type' => 'select',
				'foreign_table' => 'tx_typo3forum_domain_model_moderation_report',
				'maxitems' => 1
			)
		),
		'author' => array(
			'exclude' => 1,
			'label'   => 'LLL:EXT:typo3_forum/Resources/Private/Language/locallang_db.xml:tx_typo3forum_domain_model_moderation_reportcomment.author',
			'config' => array(
				'type' => 'select',
				'foreign_table' => 'fe_users',
				'maxitems' => 1
			)
		),
		'text' => array(
			'exclude' => 1,
			'label'   => 'LLL:EXT:typo3_forum/Resources/Private/Language/locallang_db.xml:tx_typo3forum_domain_model_moderation_report.moderator',
			'config' => array(
				'type' => 'text'
			)
		),
	),
);