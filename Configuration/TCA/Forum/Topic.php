<?php
if (!defined ('TYPO3_MODE')) 	die ('Access denied.');

$TCA['tx_mmforum_domain_model_forum_topic'] = array(
	'ctrl' => $TCA['tx_mmforum_domain_model_forum_topic']['ctrl'],
	'interface' => array(
		'showRecordFieldList' => 'type,subject,posts,author,subscribers,last_post,forum,target,question,criteria_options,solution,fav_subscribers,tags'
	),
	'types' => array(
		'1' => array('showitem' => 'type,subject,forum,last_post,target'),
		'0' => array('showitem' => 'type,subject,posts,author,subscribers,last_post,forum,readers,question,solution,fav_subscribers,tags')
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
				'foreign_table' => 'tx_mmforum_domain_model_forum_topic',
				'foreign_table_where' => 'AND tx_mmforum_domain_model_forum_topic.uid=###REC_FIELD_l18n_parent### AND tx_mmforum_domain_model_forum_topic.sys_language_uid IN (-1,0)',
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
		'crdate' => array(
			'exclude' => 1,
			'label'   => 'LLL:EXT:lang/locallang_general.xml:LGL.crdate',
			'config'  => array(
				'type' => 'passthrough'
			)
		),
		'hidden' => array(
			'exclude' => 1,
			'label'   => 'LLL:EXT:lang/locallang_general.xml:LGL.hidden',
			'config'  => array(
				'type' => 'check'
			)
		),
		'type' => array(
			'exclude' => 1,
			'label'   => 'LLL:EXT:mm_forum/Resources/Private/Language/locallang_db.xml:tx_mmforum_domain_model_forum_topic.type',
			'config'  => array(
				'type' => 'select',
				'maxitems' => 1,
				'minitems' => 1,
				'default' => 0,
				'items' => array(
					array('LLL:EXT:mm_forum/Resources/Private/Language/locallang_db.xml:tx_mmforum_domain_model_forum_topic.type.0', 0),
					array('LLL:EXT:mm_forum/Resources/Private/Language/locallang_db.xml:tx_mmforum_domain_model_forum_topic.type.1', 1),
				),
			)
		),
		'subject' => array(
			'exclude' => 1,
			'label'   => 'LLL:EXT:mm_forum/Resources/Private/Language/locallang_db.xml:tx_mmforum_domain_model_forum_topic.subject',
			'config'  => array(
				'type' => 'input',
				'size' => 30,
				'eval' => 'trim,required'
			)
		),
		'posts' => array(
			'exclude' => 0,
			'label'   => 'LLL:EXT:mm_forum/Resources/Private/Language/locallang_db.xml:tx_mmforum_domain_model_forum_topic.posts',
			'config'  => array(
				'type' => 'inline',
				'foreign_sortby' => 'uid',
				'foreign_table' => 'tx_mmforum_domain_model_forum_post',
				'foreign_field' => 'topic',
				'maxitems'      => 9999,
				'appearance' => array(
					'collapse' => 0,
					'newRecordLinkPosition' => 'bottom',
				),
			)
		),
		'post_count' => array(
			'exclude' => 1,
			'label'   => 'LLL:EXT:mm_forum/Resources/Private/Language/locallang_db.xml:tx_mmforum_domain_model_forum_topic.post_count',
			'config'  => array(
				'type' => 'display'
			)
		),
		'author' => array(
			'exclude' => 0,
			'label'   => 'LLL:EXT:mm_forum/Resources/Private/Language/locallang_db.xml:tx_mmforum_domain_model_forum_topic.author',
			'config'  => array(
				'type' => 'select',
				'foreign_table' => 'fe_users',
				'foreign_class' => 'Tx_MmForum_Domain_Model_User_FrontendUser',
				'maxitems' => 1
			)
		),
		'last_post' => array(
			'exclude' => 0,
			'label'   => 'LLL:EXT:mm_forum/Resources/Private/Language/locallang_db.xml:tx_mmforum_domain_model_forum_topic.last_post',
			'config'  => array(
				'type' => 'select',
				'foreign_table' => 'tx_mmforum_domain_model_forum_post',
				'minitems' => 1,
				'maxitems' => 1,
			)
		),
		'last_post_crdate' => array(
			'exclude' => 0,
			'label'   => 'LLL:EXT:mm_forum/Resources/Private/Language/locallang_db.xml:tx_mmforum_domain_model_forum_topic.last_post_crdate',
			'config'  => array(
				'type' => 'display',
			)
		),
		'solution' => array(
			'exclude' => 0,
			'label'   => 'LLL:EXT:mm_forum/Resources/Private/Language/locallang_db.xml:tx_mmforum_domain_model_forum_topic.solution',
			'config' => array(
				'type' => 'select',
				'foreign_class' => 'Tx_MmForum_Domain_Model_Forum_Post',
				'foreign_table' => 'tx_mmforum_domain_model_forum_post',
				'maxitems' => 1
			),
		),
		'forum' => array(
			'exclude' => 0,
			'label'   => 'LLL:EXT:mm_forum/Resources/Private/Language/locallang_db.xml:tx_mmforum_domain_model_forum_forum',
			'config' => array(
				'type' => 'select',
				'foreign_class' => 'Tx_MmForum_Domain_Model_Forum_Forum',
				'foreign_table' => 'tx_mmforum_domain_model_forum_forum',
				'maxitems' => 1
			)
		),
		'closed' => array(
			'exclude' => 0,
			'label' => 'LLL:EXT:mm_forum/Resources/Private/Language/locallang_db.xml:tx_mmforum_domain_model_forum_topic.closed',
			'config' => array(
				'type' => 'check'
			)
		),
		'sticky' => array(
			'exclude' => 0,
			'label' => 'LLL:EXT:mm_forum/Resources/Private/Language/locallang_db.xml:tx_mmforum_domain_model_forum_topic.sticky',
			'config' => array(
				'type' => 'check'
			)
		),
		'question' => array(
			'exclude' => 0,
			'label' => 'LLL:EXT:mm_forum/Resources/Private/Language/locallang_db.xml:tx_mmforum_domain_model_forum_topic.question',
			'config' => array(
				'type' => 'check'
			)
		),
		'criteria_options' => array(
			'exclude' => 0,
			'label' => 'LLL:EXT:mm_forum/Resources/Private/Language/locallang_db.xml:tx_mmforum_domain_model_forum_criteria_options',
			'config' => array(
				'type'          => 'select',
				'size'          => 10,
				'maxitems'      => 99999,
				'foreign_table' => 'tx_mmforum_domain_model_forum_criteria_options',
				'MM' => 'tx_mmforum_domain_model_forum_criteria_topic_options'
			),
		),
		'tags' => array(
			'exclude' => 0,
			'label' => 'LLL:EXT:mm_forum/Resources/Private/Language/locallang_db.xml:tx_mmforum_domain_model_forum_topic.tags',
			'config' => array(
				'type'          => 'select',
				'size'          => 10,
				'maxitems'      => 99999,
				'foreign_table' => 'tx_mmforum_domain_model_forum_tag',
				'MM' => 'tx_mmforum_domain_model_forum_tag_topic'
			),
		),
		'subscribers' => array(
			'exclude' => 0,
			'label'   => 'LLL:EXT:mm_forum/Resources/Private/Language/locallang_db.xml:tx_mmforum_domain_model_forum_topic.subscribers',
			'config' => array(
				'type' => 'select',
				'foreign_table' => 'fe_users',
				'foreign_class' => 'Tx_MmForum_Domain_Model_User_FrontendUser',
				'MM' => 'tx_mmforum_domain_model_user_topicsubscription',
				'MM_opposite_field' => 'tx_mmforum_topic_subscriptions',
				'maxitems' => 9999,
				'size' => 10
			)
		),
		'fav_subscribers' => array(
			'exclude' => 0,
			'label'   => 'LLL:EXT:mm_forum/Resources/Private/Language/locallang_db.xml:tx_mmforum_domain_model_forum_topic.fav_subscribers',
			'config' => array(
				'type' => 'select',
				'foreign_table' => 'fe_users',
				'foreign_class' => 'Tx_MmForum_Domain_Model_User_FrontendUser',
				'MM' => 'tx_mmforum_domain_model_user_topicfavsubscription',
				'MM_opposite_field' => 'tx_mmforum_topic_favsubscriptions',
				'maxitems' => 9999,
				'size' => 10
			)
		),
		'target' => array(
			'exclude' => 0,
			'label'   => 'LLL:EXT:mm_forum/Resources/Private/Language/locallang_db.xml:tx_mmforum_domain_model_forum_topic.target',
			'config'  => array(
				'type' => 'select',
				'foreign_table' => 'tx_mmforum_domain_model_forum_topic',
				'minitems' => 1,
				'maxitems' => 1,
			)
		),
		'readers' => array(
			'exclude' => 0,
			'label'   => 'LLL:EXT:mm_forum/Resources/Private/Language/locallang_db.xml:tx_mmforum_domain_model_forum_topic.readers',
			'config' => array(
				'type' => 'select',
				'foreign_table' => 'fe_users',
				'foreign_class' => 'Tx_MmForum_Domain_Model_User_FrontendUser',
				'MM' => 'tx_mmforum_domain_model_user_readtopic',
				'MM_opposite_field' => 'tx_mmforum_read_topics',
				'size' => 10
			)
		),
	),
);
?>