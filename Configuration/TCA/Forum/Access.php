<?php
if (!defined ('TYPO3_MODE')) 	die ('Access denied.');

$TCA['tx_mmforum_domain_model_forum_access'] = array(
	'ctrl' => $TCA['tx_mmforum_domain_model_forum_access']['ctrl'],
	'interface' => array(
		'showRecordFieldList' => 'login_level,operation,negate,forum,affected_group'
	),
	'types' => array(
		'0' => array('showitem' => 'login_level,operation,negate,forum'),
		'1' => array('showitem' => 'login_level,operation,negate,forum'),
		'2' => array('showitem' => 'login_level,operation,negate,forum,affected_group'),
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
				'foreign_table' => 'tx_mmforum_domain_model_forum_access',
				'foreign_table_where' => 'AND tx_mmforum_domain_model_forum_access.uid=###REC_FIELD_l18n_parent### AND tx_mmforum_domain_model_forum_access.sys_language_uid IN (-1,0)',
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
		'operation' => array(
			'exclude' => 1,
			'label'   => 'LLL:EXT:mm_forum/Resources/Private/Language/locallang_db.xml:tx_mmforum_domain_model_forum_access.operation',
			'config'  => array(
				'type' => 'select',
				'maxitems' => 1,
				'items' => array(
					array('LLL:EXT:mm_forum/Resources/Private/Language/locallang_db.xml:tx_mmforum_domain_model_forum_access.operation.read', 'read'),
					array('LLL:EXT:mm_forum/Resources/Private/Language/locallang_db.xml:tx_mmforum_domain_model_forum_access.operation.newTopic', 'newTopic'),
					array('LLL:EXT:mm_forum/Resources/Private/Language/locallang_db.xml:tx_mmforum_domain_model_forum_access.operation.newPost', 'newPost'),
					array('LLL:EXT:mm_forum/Resources/Private/Language/locallang_db.xml:tx_mmforum_domain_model_forum_access.operation.newAttachment', 'newAttachment'),
					array('LLL:EXT:mm_forum/Resources/Private/Language/locallang_db.xml:tx_mmforum_domain_model_forum_access.operation.editPost', 'editPost'),
					array('LLL:EXT:mm_forum/Resources/Private/Language/locallang_db.xml:tx_mmforum_domain_model_forum_access.operation.deletePost', 'deletePost'),
					array('LLL:EXT:mm_forum/Resources/Private/Language/locallang_db.xml:tx_mmforum_domain_model_forum_access.operation.moderation', 'moderate'),
				)
			)
		),
		'negate' => array(
			'exclude' => 1,
			'label'   => 'LLL:EXT:mm_forum/Resources/Private/Language/locallang_db.xml:tx_mmforum_domain_model_forum_access.negate',
			'config'  => array(
				'type' => 'check',
				'default' => 0
			)
		),
		'forum' => array(
			'exclude' => 1,
			'label'   => 'LLL:EXT:mm_forum/Resources/Private/Language/locallang_db.xml:tx_mmforum_domain_model_forum_forum',
			'config' => array(
				'type' => 'select',
				'foreign_class' => 'Tx_MmForum_Domain_Model_Forum_Forum',
				'foreign_table' => 'tx_mmforum_domain_model_forum_forum',
				'maxitems' => 1
			)
		),
		'affected_group' => array(
			'exclude' => 1,
			'label'   => 'LLL:EXT:mm_forum/Resources/Private/Language/locallang_db.xml:tx_mmforum_domain_model_forum_access.group',
			'config'  => array(
				'type' => 'select',
				'foreign_table' => 'fe_groups',
				'foreign_class' => 'Tx_MmForum_Domain_Model_User_FrontendUserGroup',
				'maxitems' => 1
			)
		),
		'login_level' => array(
			'exclude' => 1,
			'label'   => 'LLL:EXT:mm_forum/Resources/Private/Language/locallang_db.xml:tx_mmforum_domain_model_forum_access.login_level',
			'config'  => array(
				'type' => 'select',
				'items' => array(
					array('LLL:EXT:mm_forum/Resources/Private/Language/locallang_db.xml:tx_mmforum_domain_model_forum_access.login_level.everyone', 0),
					array('LLL:EXT:mm_forum/Resources/Private/Language/locallang_db.xml:tx_mmforum_domain_model_forum_access.login_level.anylogin', 1),
					array('LLL:EXT:mm_forum/Resources/Private/Language/locallang_db.xml:tx_mmforum_domain_model_forum_access.login_level.specific', 2),
				)
			)
		)
	),
);
?>