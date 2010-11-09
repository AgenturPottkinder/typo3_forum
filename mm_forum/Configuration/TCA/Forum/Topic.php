<?php
if (!defined ('TYPO3_MODE')) 	die ('Access denied.');

$TCA['tx_mmforum_domain_model_forum_topic'] = array(
	'ctrl' => $TCA['tx_mmforum_domain_model_forum_topic']['ctrl'],
	'interface' => array(
		'showRecordFieldList' => 'subject,posts,author,subscribers,last_post,forum'
	),
	'types' => array(
		'1' => array('showitem' => 'subject,posts,author,subscribers,last_post,forum')
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
				'foreign_table' => 'tx_mmforum_domain_model_forum_post',
				'foreign_field' => 'topic',
				'maxitems'      => 9999,
				'appearance' => array(
					'collapse' => 0,
					'newRecordLinkPosition' => 'bottom',
				),
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
	),
);
?>