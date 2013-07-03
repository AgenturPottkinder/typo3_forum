<?php
if (!defined ('TYPO3_MODE')) 	die ('Access denied.');

$TCA['tx_mmforum_domain_model_user_rank'] = array(
	'ctrl' => $TCA['tx_mmforum_domain_model_user_rank']['ctrl'],
	'interface' => array(
		'showRecordFieldList' => 'name,point_limit,user_count'
	),
	'types' => array(
		'1' => array('showitem' => 'name,point_limit,user_count')
	),
	'palettes' => array(
		'1' => array('showitem' => '')
	),
	'columns' => array(
		'name' => array(
			'exclude' => 0,
			'label'   => 'LLL:EXT:mm_forum/Resources/Private/Language/locallang_db.xml:tx_mmforum_domain_model_user_rank_name',
			'config'  => array(
				'type' => 'input'
			)
		),
		'point_limit' => array(
			'exclude' => 0,
			'label'   => 'LLL:EXT:mm_forum/Resources/Private/Language/locallang_db.xml:tx_mmforum_domain_model_user_rank_limit',
			'config'  => array(
				'type' => 'input'
			)
		),
		'user_count' => Array (
			'exclude' => 1,
			'label' => 'LLL:EXT:mm_forum/Resources/Private/Language/locallang_db.xml:tx_mmforum_domain_model_user_rank_userCount',
			'config' => Array (
				'type' => 'none'
			)
		),
	)
);
?>