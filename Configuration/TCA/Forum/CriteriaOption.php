<?php
if (!defined ('TYPO3_MODE')) 	die ('Access denied.');

$TCA['tx_mmforum_domain_model_forum_criteria_options'] = array(
	'ctrl' => $TCA['tx_mmforum_domain_model_forum_criteria_options']['ctrl'],
	'interface' => array(
		'showRecordFieldList' => 'criteria_uid,value'
	),
	'types' => array(
		'1' => array('showitem' => 'criteria_uid,value')
	),
	'palettes' => array(
		'1' => array('showitem' => '')
	),
	'columns' => array(
		'uid' => array(
			'exclude' => 0,
			'label' => 'LLL:EXT:myext/locallang_db.xml:tx_locations.usage_mm',
			'config' => array(
				'type' => 'select',
				'foreign_table' => 'tx_mmforum_domain_model_forum_topic',
				'MM_opposite_field' => 'uid',
				'MM' => 'tx_mmforum_domain_model_forum_criteria_topic_options',
				'maxitems' => 9999,
				'size' => 10
			),
		),
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
		'value' => array(
			'exclude' => 0,
			'label'   => 'LLL:EXT:mm_forum/Resources/Private/Language/locallang_db.xml:tx_mmforum_domain_model_forum_criteria_options_value',
			'config'  => array(
				'type' => 'text'
			)
		),
	)
);
?>