<?php
if (!defined ('TYPO3_MODE')) 	die ('Access denied.');

$TCA['tx_mmforum_domain_model_forum_criteria_options'] = array(
	'ctrl' => $TCA['tx_mmforum_domain_model_forum_criteria_options']['ctrl'],
	'interface' => array(
		'showRecordFieldList' => 'criteria_uid,name'
	),
	'types' => array(
		'1' => array('showitem' => 'criteria_uid,name')
	),
	'palettes' => array(
		'1' => array('showitem' => '')
	),
	'columns' => array(
		'criteria_uid' => array(
			'exclude' => 0,
			'label'   => 'LLL:EXT:mm_forum/Resources/Private/Language/locallang_db.xml:tx_mmforum_domain_model_forum_criteria_options_criteria_uid',
			'config' => array(
				'type' => 'select',
				'foreign_class' => 'Tx_MmForum_Domain_Model_Forum_Criteria',
				'foreign_table' => 'tx_mmforum_domain_model_forum_criteria',
				'maxitems' => 1
			)
		),
		'name' => array(
			'exclude' => 0,
			'label'   => 'LLL:EXT:mm_forum/Resources/Private/Language/locallang_db.xml:tx_mmforum_domain_model_forum_criteria_options_name',
			'config'  => array(
				'type' => 'text'
			)
		),
	)
);
?>