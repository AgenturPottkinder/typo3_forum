<?php
if (!defined ('TYPO3_MODE')) 	die ('Access denied.');

$TCA['tx_mmforum_domain_model_forum_post'] = array(
	'ctrl' => $TCA['tx_mmforum_domain_model_forum_post']['ctrl'],
	'interface' => array(
		'showRecordFieldList' => 'text,author,topic,attachments, helpful_count, supporters'
	),
	'types' => array(
		'1' => array('showitem' => 'text,author,topic,attachments, helpful_count, supporters')
	),
	'palettes' => array(
		'1' => array('showitem' => '')
	),
	'columns' => array(
		'sys_language_uid' => array(
			'exclude' => 1,
			'label' => 'LLL:EXT:lang/locallang_general.php:LGL.language',
			'config' => array(
				'type' => 'select',
				'foreign_table' => 'sys_language',
				'foreign_table_where' => 'ORDER BY sys_language.title',
				'items' => array(
					array('LLL:EXT:lang/locallang_general.php:LGL.allLanguages',-1),
					array('LLL:EXT:lang/locallang_general.php:LGL.default_value',0)
				)
			)
		),
		'l18n_parent' => array(
			'displayCond' => 'FIELD:sys_language_uid:>:0',
			'exclude' => 1,
			'label' => 'LLL:EXT:lang/locallang_general.php:LGL.l18n_parent',
			'config' => array(
				'type' => 'select',
				'items' => array(
					array('', 0),
				),
				'foreign_table' => 'tx_mmforum_domain_model_forum_post',
				'foreign_table_where' => 'AND tx_mmforum_domain_model_forum_post.uid=###REC_FIELD_l18n_parent### AND tx_mmforum_domain_model_forum_post.sys_language_uid IN (-1,0)',
			)
		),
		'l18n_diffsource' => array(
			'config'=>array(
				'type'=>'passthrough')
		),
		't3ver_label' => array(
			'displayCond' => 'FIELD:t3ver_label:REQ:true',
			'label' => 'LLL:EXT:lang/locallang_general.php:LGL.versionLabel',
			'config' => array(
				'type'=>'none',
				'cols' => 27
			)
		),
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
		'text' => array(
			'exclude' => 0,
			'label'   => 'LLL:EXT:mm_forum/Resources/Private/Language/locallang_db.xml:tx_mmforum_domain_model_forum_post.text',
			'config'  => array(
				'type' => 'text'
			)
		),
		'rendered_text' => array(
			'exclude' => 1,
			'label'   => 'LLL:EXT:mm_forum/Resources/Private/Language/locallang_db.xml:tx_mmforum_domain_model_forum_post.rendered_text',
			'config'  => array(
				'type' => 'text'
			)
		),
		'author' => array(
			'exclude' => 1,
			'label'   => 'LLL:EXT:mm_forum/Resources/Private/Language/locallang_db.xml:tx_mmforum_domain_model_forum_post.author',
			'config'  => array(
				'type' => 'select',
				'foreign_table' => 'fe_users',
				'foreign_class' => 'Mittwald\\MmForum\\Domain\\Model\\User\\FrontendUser',
				'maxitems' => 1
			)
		),
		'author_name' => array(
			'exclude' => 1,
			'label'   => 'LLL:EXT:mm_forum/Resources/Private/Language/locallang_db.xml:tx_mmforum_domain_model_forum_post.author_name',
			'config'  => array(
				'type' => 'text'
			)
		),
		'topic' => array(
			'exclude' => 1,
			'label'   => 'LLL:EXT:mm_forum/Resources/Private/Language/locallang_db.xml:tx_mmforum_domain_model_forum_post.topic',
			'config' => array(
				'type' => 'select',
				'foreign_class' => 'Mittwald\\MmForum\\Domain\\Model\\Forum\\Topic',
				'foreign_table' => 'tx_mmforum_domain_model_forum_topic',
				'maxitems' => 1
			)
		),
		'attachments' => array(
			'exclude' => 1,
			'label'   => 'LLL:EXT:mm_forum/Resources/Private/Language/locallang_db.xml:tx_mmforum_domain_model_forum_post.attachments',
			'config' => array(
				'type' => 'inline',
				'foreign_table' => 'tx_mmforum_domain_model_forum_attachment',
				'foreign_field' => 'post',
				'maxitems' => 10
			)
		),
		'helpful_count' => array(
			'exclude' => 0,
			'label'   => 'LLL:EXT:mm_forum/Resources/Private/Language/locallang_db.xml:tx_mmforum_domain_model_forum_post.helpful_count',
			'config'  => array(
				'type' => 'none'
			)
		),
		'supporters' => array(
			'exclude' => 0,
			'label'   => 'LLL:EXT:mm_forum/Resources/Private/Language/locallang_db.xml:tx_mmforum_domain_model_forum_topic.supporters',
			'config' => array(
				'type' => 'select',
				'foreign_table' => 'fe_users',
				'foreign_class' => 'Mittwald\\MmForum\\Domain\\Model\\User\\FrontendUser',
				'MM' => 'tx_mmforum_domain_model_user_supportpost',
				'MM_opposite_field' => 'tx_mmforum_support_posts',
				'maxitems' => PHP_INT_MAX
			)
		),
	),
);
?>