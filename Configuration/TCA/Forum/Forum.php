<?php
if (!defined ('TYPO3_MODE')) 	die ('Access denied.');

$TCA['tx_mmforum_domain_model_forum_forum'] = array(
	'ctrl' => $TCA['tx_mmforum_domain_model_forum_forum']['ctrl'],
	'interface' => array(
		'showRecordFieldList' => 'title,description,children,sorting,topics,acls,criteria,last_topic,last_post,subscribers,displayed_pid'
	),
	'types' => array(
		'1' => array('showitem' => 'title,description,children,sorting,topics,acls,criteria,last_topic,last_post,subscribers,readers,displayed_pid')
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
				'foreign_table' => 'tx_mmforum_domain_model_forum_forum',
				'foreign_table_where' => 'AND tx_mmforum_domain_model_forum_forum.uid=###REC_FIELD_l18n_parent### AND tx_mmforum_domain_model_forum_forum.sys_language_uid IN (-1,0)',
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
		'title' => array(
			'exclude' => 1,
			'label'   => 'LLL:EXT:mm_forum/Resources/Private/Language/locallang_db.xml:tx_mmforum_domain_model_forum_forum.title',
			'config'  => array(
				'type' => 'input',
				'size' => 30,
				'eval' => 'trim,required'
			)
		),
		'description' => array(
			'exclude' => 1,
			'label'   => 'LLL:EXT:mm_forum/Resources/Private/Language/locallang_db.xml:tx_mmforum_domain_model_forum_forum.description',
			'config'  => array(
				'type' => 'input',
				'size' => 30,
				'eval' => 'trim'
			)
		),
		'children' => array(
			'exclude' => 0,
			'label'   => 'LLL:EXT:mm_forum/Resources/Private/Language/locallang_db.xml:tx_mmforum_domain_model_forum_forum.children',
			'config'  => array(
				'type' => 'inline',
				'foreign_table' => 'tx_mmforum_domain_model_forum_forum',
				'foreign_field' => 'forum',
				'foreign_sortby' => 'sorting, uid',
				'maxitems'      => 9999,
				'appearance' => array(
					'collapse' => 0,
					'newRecordLinkPosition' => 'bottom',
				),
			)
		),
		'topics' => array(
			'exclude' => 0,
			'label'   => 'LLL:EXT:mm_forum/Resources/Private/Language/locallang_db.xml:tx_mmforum_domain_model_forum_forum.topics',
			'config'  => array(
				'type' => 'inline',
				'foreign_table' => 'tx_mmforum_domain_model_forum_topic',
				'foreign_field' => 'forum',
				'maxitems'      => 9999,
				'appearance' => array(
					'collapse' => 0,
					'newRecordLinkPosition' => 'bottom',
				),
			)
		),
		'criteria' => array(
			'exclude' => 0,
			'label' => 'LLL:EXT:mm_forum/Resources/Private/Language/locallang_db.xml:tx_mmforum_domain_model_forum_criteria',
			'config' => array(
				'type'          => 'select',
				'size'          => 10,
				'maxitems'      => 99999,
				'foreign_table' => 'tx_mmforum_domain_model_forum_criteria',
				'MM' => 'tx_mmforum_domain_model_forum_criteria_forum'
			),
		),
		'topic_count' => array(
			'exclude' => 0,
			'label'   => 'LLL:EXT:mm_forum/Resources/Private/Language/locallang_db.xml:tx_mmforum_domain_model_forum_forum.topic_count',
			'config'  => array(
				'type' => 'display'
			)
		),
		'post_count' => array(
			'exclude' => 0,
			'label'   => 'LLL:EXT:mm_forum/Resources/Private/Language/locallang_db.xml:tx_mmforum_domain_model_forum_forum.post_count',
			'config'  => array(
				'type' => 'display'
			)
		),
		'acls' => array(
			'exclude' => 0,
			'label'   => 'LLL:EXT:mm_forum/Resources/Private/Language/locallang_db.xml:tx_mmforum_domain_model_forum_forum.acls',
			'config'  => array(
				'type' => 'inline',
				'foreign_table' => 'tx_mmforum_domain_model_forum_access',
				'foreign_field' => 'forum',
				'maxitems'      => 9999,
				'appearance' => array(
					'collapse' => 0,
					'newRecordLinkPosition' => 'bottom',
				),
			)
		),
		'last_topic' => array(
			'exclude' => 0,
			'label'   => 'LLL:EXT:mm_forum/Resources/Private/Language/locallang_db.xml:tx_mmforum_domain_model_forum_forum.last_topic',
			'config'  => array(
				'type' => 'none',
				'foreign_table' => 'tx_mmforum_domain_model_forum_topic',
				'minitems' => 0,
				'maxitems' => 1
			)
		),
		'last_post' => array(
			'exclude' => 0,
			'label'   => 'LLL:EXT:mm_forum/Resources/Private/Language/locallang_db.xml:tx_mmforum_domain_model_forum_forum.last_post',
			'config'  => array(
				'type' => 'none',
				'foreign_table' => 'tx_mmforum_domain_model_forum_post',
				'minitems' => 0,
				'maxitems' => 1
			)
		),
		'forum' => array(
			'exclude' => 0,
			'label'   => 'LLL:EXT:mm_forum/Resources/Private/Language/locallang_db.xml:tx_mmforum_domain_model_forum_forum.forum',
			'config' => array(
				'type' => 'select',
				'foreign_class' => 'Mittwald\\MmForum\\Domain\\Model\\Forum\\Forum',
				'foreign_table' => 'tx_mmforum_domain_model_forum_forum',
				'maxitems' => 1
			)
		),
		'subscribers' => array(
			'exclude' => 0,
			'label'   => 'LLL:EXT:mm_forum/Resources/Private/Language/locallang_db.xml:tx_mmforum_domain_model_forum_forum.subscribers',
			'config' => array(
				'type' => 'inline',
				'foreign_table' => 'fe_users',
				'MM' => 'tx_mmforum_domain_model_user_forumsubscription',
				'MM_opposite_field' => 'tx_mmforum_forum_subscriptions',
				'maxitems' => 9999,
				'size' => 10
			)
		),
		'readers' => array(
			'exclude' => 0,
			'label'   => 'LLL:EXT:mm_forum/Resources/Private/Language/locallang_db.xml:tx_mmforum_domain_model_forum_forum.readers',
			'config' => array(
				'type' => 'inline',
				'foreign_table' => 'fe_users',
				'foreign_class' => 'Mittwald\\MmForum\\Domain\\Model\\User\\FrontendUser',
				'MM' => 'tx_mmforum_domain_model_user_readforum',
				'MM_opposite_field' => 'tx_mmforum_read_forum',
				'size' => 10
			)
		),
		'displayed_pid' =>  array(
			'exclude' => 0,
			'label'   => 'LLL:EXT:mm_forum/Resources/Private/Language/locallang_db.xml:tx_mmforum_domain_model_forum_forum.displayed_pid',
			'config'  => array(
				'type' => 'none',
			),
		),
		'sorting' =>  array(
			'exclude' => 0,
			'label'   => 'LLL:EXT:mm_forum/Resources/Private/Language/locallang_db.xml:tx_mmforum_domain_model_forum_forum.sorting',
			'config'  => array(
				'type' => 'input',
				'size' => 11,
				'default' => 0,
				'eval' => 'num'
			),
		),
	),
);
?>