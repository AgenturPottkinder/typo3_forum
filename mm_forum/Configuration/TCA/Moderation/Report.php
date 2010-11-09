<?php
if (!defined ('TYPO3_MODE')) 	die ('Access denied.');

$TCA['tx_mmforum_domain_model_moderation_report'] = array(
	'ctrl' => $TCA['tx_mmforum_domain_model_moderation_report']['ctrl'],
	'interface' => array(
		'showRecordFieldList' => 'post,reporter,moderator,workflow_status,comments'
	),
	'types' => array(
		'1' => array('showitem' => 'post,reporter,moderator,workflow_status,comments')
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
		'crdate' => array(
			'exclude' => 1,
			'label'   => 'LLL:EXT:lang/locallang_general.xml:LGL.crdate',
			'config'  => array(
				'type' => 'passthrough'
			)
		),
		'post' => array(
			'exclude' => 1,
			'label'   => 'LLL:EXT:mm_forum/Resources/Private/Language/locallang_db.xml:tx_mmforum_domain_model_moderation_report.post',
			'config' => array(
				'type' => 'select',
				'foreign_table' => 'tx_mmforum_domain_model_forum_post',
				'maxitems' => 1
			)
		),
		'reporter' => array(
			'exclude' => 1,
			'label'   => 'LLL:EXT:mm_forum/Resources/Private/Language/locallang_db.xml:tx_mmforum_domain_model_moderation_report.reporter',
			'config' => array(
				'type' => 'select',
				'foreign_table' => 'fe_users',
				'maxitems' => 1
			)
		),
		'moderator' => array(
			'exclude' => 1,
			'label'   => 'LLL:EXT:mm_forum/Resources/Private/Language/locallang_db.xml:tx_mmforum_domain_model_moderation_report.moderator',
			'config' => array(
				'type' => 'select',
				'foreign_table' => 'fe_users',
				'maxitems' => 1
			)
		),
		'workflow_status' => array(
			'exclude' => 1,
			'label'   => 'LLL:EXT:mm_forum/Resources/Private/Language/locallang_db.xml:tx_mmforum_domain_model_moderation_report.workflow_status',
			'config' => array(
				'type' => 'select',
				'foreign_table' => 'tx_mmforum_domain_model_moderation_reportworkflowstatus',
				'maxitems' => 1
			)
		),
		'comments' => array(
			'exclude' => 1,
			'label'   => 'LLL:EXT:mm_forum/Resources/Private/Language/locallang_db.xml:tx_mmforum_domain_model_moderation_report.comments',
			'config' => array(
				'type' => 'inline',
				'foreign_table' => 'tx_mmforum_domain_model_moderation_reportcomment',
				'foreign_field' => 'report',
				'maxitems' => 9999,
				'appearance' => array(
					'collapse' => 0,
					'newRecordLinkPosition' => 'bottom',
				),
			)
		),
	),
);
?>