<?php
if (!defined ('TYPO3_MODE')) 	die ('Access denied.');

$TCA['tx_typo3forum_domain_model_forum_criteria_options'] = array(
	'ctrl' => $TCA['tx_typo3forum_domain_model_forum_criteria_options']['ctrl'],
	'interface' => array(
		'showRecordFieldList' => 'name,criteria,sorting'
	),
	'types' => array(
		'1' => array('showitem' => 'name,criteria,sorting'),
	),
	'palettes' => array(
		'1' => array('showitem' => '')
	),
	'columns' => array(
		'name' => array(
			'exclude' => 0,
			'label'   => 'LLL:EXT:typo3_forum/Resources/Private/Language/locallang_db.xml:tx_typo3forum_domain_model_forum_criteria_options_name',
			'config'  => array(
				'type' => 'text'
			)
		),
		'criteria' => array(
			'exclude' => 0,
			'label'   => 'LLL:EXT:typo3_forum/Resources/Private/Language/locallang_db.xml:tx_typo3forum_domain_model_forum_criteria_options_criteria',
			'config' => array(
				'type' => 'select',
				'foreign_table' => 'tx_typo3forum_domain_model_forum_criteria',
				'maxitems'      => 1,
			)
		),
		'sorting' =>  array(
			'exclude' => 0,
			'label'   => 'LLL:EXT:typo3_forum/Resources/Private/Language/locallang_db.xml:tx_typo3forum_domain_model_forum_criteria_options_sorting',
			'config'  => array(
				'type' => 'input',
				'size' => 11,
				'default' => 0,
				'eval' => 'num'
			),
		),
	)
);