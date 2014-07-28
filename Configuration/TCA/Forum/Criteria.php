<?php
if (!defined ('TYPO3_MODE')) 	die ('Access denied.');

$TCA['tx_mmforum_domain_model_forum_criteria'] = array(
	'ctrl' => $TCA['tx_mmforum_domain_model_forum_criteria']['ctrl'],
	'interface' => array(
		'showRecordFieldList' => 'name,options,default_option'
	),
	'types' => array(
		'1' => array('showitem' => 'name,default_option')
	),
	'palettes' => array(
		'1' => array('showitem' => '')
	),
	'columns' => array(
		'name' => array(
			'exclude' => 0,
			'label'   => 'LLL:EXT:mm_forum/Resources/Private/Language/locallang_db.xml:tx_mmforum_domain_model_forum_criteria_name',
			'config'  => array(
				'type' => 'text'
			)
		),
		'options' => array(
			'exclude' => 0,
			'label'   => 'LLL:EXT:mm_forum/Resources/Private/Language/locallang_db.xml:tx_mmforum_domain_model_forum_criteria_options',
			'config' => array(
				'type' => 'inline',
				'foreign_table' => 'tx_mmforum_domain_model_forum_criteria_options',
				'foreign_field' => 'criteria',
				'maxitems'      => 9999,
				'foreign_sortby' => 'sorting',
				'appearance' => array(
					'collapseAll' => 1,
					'newRecordLinkPosition' => 'bottom',
					'expandSingle' => 1,
				),
			)
		),
		'default_option' => array(
			'exclude' => 0,
			'label'   => 'LLL:EXT:mm_forum/Resources/Private/Language/locallang_db.xml:tx_mmforum_domain_model_forum_criteria_default_option',
			'config'  => array(
				'type'          => 'select',
				'maxitems'      => 1,
				'foreign_table' => 'tx_mmforum_domain_model_forum_criteria_options',
				'foreign_class' => 'Mittwald\\MmForum\\Domain\\Model\\Forum\\CriteriaOption',
			)
		),
	)
);
?>