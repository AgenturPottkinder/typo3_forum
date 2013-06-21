<?php
if (!defined ('TYPO3_MODE')) 	die ('Access denied.');

$TCA['tx_mmforum_domain_model_forum_ads'] = array(
	'ctrl' => $TCA['tx_mmforum_domain_model_forum_ads']['ctrl'],
	'interface' => array(
		'showRecordFieldList' => 'name,alt_text,url,path,active,category,groups'
	),
	'types' => array(
		'1' => array('showitem' => 'name,alt_text,url,path,active,category,groups')
	),
	'palettes' => array(
		'1' => array('showitem' => '')
	),
	'columns' => array(
		'name' => array(
			'exclude' => 0,
			'label'   => 'LLL:EXT:mm_forum/Resources/Private/Language/locallang_db.xml:tx_mmforum_domain_model_forum_ads_name',
			'config'  => array(
				'type' => 'input'
			)
		),
		'alt_text' => array(
			'exclude' => 0,
			'label'   => 'LLL:EXT:mm_forum/Resources/Private/Language/locallang_db.xml:tx_mmforum_domain_model_forum_ads_alt',
			'config'  => array(
				'type' => 'text'
			)
		),
		'url' => array(
			'exclude' => 0,
			'label'   => 'LLL:EXT:mm_forum/Resources/Private/Language/locallang_db.xml:tx_mmforum_domain_model_forum_ads_url',
			'config'  => array(
				'type' => 'input'
			)
		),
		'path' => array(
			'exclude' => 0,
			'label'   => 'LLL:EXT:mm_forum/Resources/Private/Language/locallang_db.xml:tx_mmforum_domain_model_forum_ads_path',
			'config'  => array(
				'type' => 'input'
			)
		),
		'active' => array(
			'exclude' => 0,
			'label'   => 'LLL:EXT:mm_forum/Resources/Private/Language/locallang_db.xml:tx_mmforum_domain_model_forum_ads_active',
			'config'  => array(
				'type' => 'check'
			)
		),
		'category' => array(
			'exclude' => 0,
			'label'   => 'LLL:EXT:mm_forum/Resources/Private/Language/locallang_db.xml:tx_mmforum_domain_model_forum_ads_category',
			'config'  => array(
				'type' => 'radio',
				'items' => Array (
					Array('all', 0),
					Array('forum only', 1),
					Array('topic only', 2)
				),
				'default' => 0,
			)
		),
	)
);
?>