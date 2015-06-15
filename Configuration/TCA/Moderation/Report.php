<?php

if (!defined('TYPO3_MODE'))
	die('Access denied.');

$TCA['tx_typo3forum_domain_model_moderation_report'] = array(
	'ctrl' => $TCA['tx_typo3forum_domain_model_moderation_report']['ctrl'],
	'interface' => array(
		'showRecordFieldList' => 'type,reporter,moderator,workflow_status,comments, post, feuser'
	),
	'types' => array(
		'1' => array('showitem' => 'type,reporter,moderator,workflow_status,comments'),
		'\Mittwald\Typo3Forum\Domain\Model\Moderation\UserReport' => array('showitem' => 'type,reporter,moderator,workflow_status,comments, feuser'),
		'\Mittwald\Typo3Forum\Domain\Model\Moderation\PostReport' => array('showitem' => 'type,reporter,moderator,workflow_status,comments, post')
	),
	'palettes' => array(
		'1' => array('showitem' => '')
	),
	'columns' => array(
		'hidden' => array(
			'exclude' => 1,
			'label' => 'LLL:EXT:lang/locallang_general.xml:LGL.hidden',
			'config' => array(
				'type' => 'check'
			)
		),
		'crdate' => array(
			'exclude' => 1,
			'label' => 'LLL:EXT:lang/locallang_general.xml:LGL.crdate',
			'config' => array(
				'type' => 'passthrough'
			)
		),
		'type' => array(
			'exclude' => 1,
			'label'   => 'LLL:EXT:typo3_forum/Resources/Private/Language/locallang_db.xml:tx_typo3forum_domain_model_moderation_report.type',
			'config'  => array(
				'type' => 'select',
				'items' => array(
					array('LLL:EXT:typo3_forum/Resources/Private/Language/locallang_db.xml:tx_typo3forum_domain_model_moderation_report.type.userReport', '\Mittwald\Typo3Forum\Domain\Model\Moderation\UserReport'),
					array('LLL:EXT:typo3_forum/Resources/Private/Language/locallang_db.xml:tx_typo3forum_domain_model_moderation_report.type.postReport', '\Mittwald\Typo3Forum\Domain\Model\Moderation\PostReport'),
				)
			)
		),
		'post' => array(
			'exclude' => 0,
			'label' => 'LLL:EXT:typo3_forum/Resources/Private/Language/locallang_db.xml:tx_typo3forum_domain_model_moderation_report.post',
			'config' => array(
				'type' => 'select',
				'foreign_table' => 'tx_typo3forum_domain_model_forum_post',
				'maxitems' => 1
			)
		),
		'feuser' => array(
			'exclude' => 1,
			'label' => 'LLL:EXT:typo3_forum/Resources/Private/Language/locallang_db.xml:tx_typo3forum_domain_model_moderation_report.user',
			'config' => array(
				'type' => 'select',
				'foreign_table' => 'fe_users',
				'maxitems' => 1
			)
		),
		'reporter' => array(
			'exclude' => 1,
			'label' => 'LLL:EXT:typo3_forum/Resources/Private/Language/locallang_db.xml:tx_typo3forum_domain_model_moderation_report.reporter',
			'config' => array(
				'type' => 'select',
				'foreign_table' => 'fe_users',
				'maxitems' => 1
			)
		),
		'moderator' => array(
			'exclude' => 1,
			'label' => 'LLL:EXT:typo3_forum/Resources/Private/Language/locallang_db.xml:tx_typo3forum_domain_model_moderation_report.moderator',
			'config' => array(
				'type' => 'select',
				'foreign_table' => 'fe_users',
				'maxitems' => 1
			)
		),
		'workflow_status' => array(
			'exclude' => 1,
			'label' => 'LLL:EXT:typo3_forum/Resources/Private/Language/locallang_db.xml:tx_typo3forum_domain_model_moderation_report.workflow_status',
			'config' => array(
				'type' => 'select',
				'foreign_table' => 'tx_typo3forum_domain_model_moderation_reportworkflowstatus',
				'maxitems' => 1
			)
		),
		'comments' => array(
			'exclude' => 1,
			'label' => 'LLL:EXT:typo3_forum/Resources/Private/Language/locallang_db.xml:tx_typo3forum_domain_model_moderation_report.comments',
			'config' => array(
				'type' => 'inline',
				'foreign_table' => 'tx_typo3forum_domain_model_moderation_reportcomment',
				'foreign_field' => 'report',
				'maxitems' => 9999,
				'foreign_sortby' => 'tstamp',
				'appearance' => array(
					'collapseAll' => TRUE,
					'levelLinksPosition' => 'top'
				),
			)
		),
	),
);