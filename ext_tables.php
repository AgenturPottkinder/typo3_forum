<?php

if (!defined('TYPO3_MODE'))
	die('Access denied.');

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin(
	$_EXTKEY, 'Pi1', 'mm_forum'
);
\TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin(
	$_EXTKEY, 'Widget', 'mm_forum Widgets'
);

$extPath = \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extPath($_EXTKEY);

if (TYPO3_MODE === 'BE')
{
	\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::registerExtDirectComponent(
		'MmForum.ForumIndex.DataProvider',
		$extPath . 'Classes/ExtDirect/ForumDataProvider.php:Tx_MmForum_ExtDirect_ForumDataProvider',
		'web', 'user,group'
	);


	\TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerModule(
		$_EXTKEY, 'web', 'tx_mmforum_m1', '', array('Backend' => 'indexForum', 'Forum' => 'update'),
		array(
		'access' => 'user,group',
		'icon' => 'EXT:' . $_EXTKEY . '/ext_icon.gif',
		'labels' => 'LLL:EXT:' . $_EXTKEY . '/Resources/Private/Language/locallang_mod.xml',
		'navigationComponentId' => 'typo3-pagetree',
		)
	);
}

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addStaticFile($_EXTKEY, 'Configuration/TypoScript', 'mm_forum');

$pluginSignature = strtolower(\TYPO3\CMS\Core\Utility\GeneralUtility::underscoredToUpperCamelCase($_EXTKEY)) . '_pi1';
$TCA['tt_content']['types']['list']['subtypes_addlist'][$pluginSignature] = 'pi_flexform';
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPiFlexFormValue($pluginSignature,
	'FILE:EXT:' . $_EXTKEY . '/Configuration/FlexForms/Pi1.xml');

$pluginSignature = strtolower(\TYPO3\CMS\Core\Utility\GeneralUtility::underscoredToUpperCamelCase($_EXTKEY)) . '_widget';
$TCA['tt_content']['types']['list']['subtypes_addlist'][$pluginSignature] = 'pi_flexform';
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPiFlexFormValue($pluginSignature,
																	   'FILE:EXT:' . $_EXTKEY . '/Configuration/FlexForms/Widgets.xml');

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addLLrefForTCAdescr('tx_mmforum_domain_model_forum_forum',
	'EXT:mm_forum/Resources/Private/Language/locallang_csh_tx_mmforum_domain_model_forum_forum.xml');
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::allowTableOnStandardPages('tx_mmforum_domain_model_forum_forum');
$TCA['tx_mmforum_domain_model_forum_forum'] = array(
	'ctrl' => array(
		'title' => 'LLL:EXT:mm_forum/Resources/Private/Language/locallang_db.xml:tx_mmforum_domain_model_forum_forum',
		'label' => 'title',
		'tstamp' => 'tstamp',
		'crdate' => 'crdate',
		'versioningWS' => 2,
		'versioning_followPages' => TRUE,
		'origUid' => 't3_origuid',
		'languageField' => 'sys_language_uid',
		'transOrigPointerField' => 'l18n_parent',
		'transOrigDiffSourceField' => 'l18n_diffsource',
		'delete' => 'deleted',
		'enablecolumns' => array(
			'disabled' => 'hidden'
		),
		'dynamicConfigFile' => \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extPath($_EXTKEY) . 'Configuration/TCA/Forum/Forum.php',
		'iconfile' => \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extRelPath($_EXTKEY) . 'Resources/Public/Icons/Forum/Forum.png'
	)
);

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addLLrefForTCAdescr('tx_mmforum_domain_model_forum_topic',
	'EXT:mm_forum/Resources/Private/Language/locallang_csh_tx_mmforum_domain_model_forum_topic.xml');
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::allowTableOnStandardPages('tx_mmforum_domain_model_forum_topic');
$TCA['tx_mmforum_domain_model_forum_topic'] = array(
	'ctrl' => array(
		'title' => 'LLL:EXT:mm_forum/Resources/Private/Language/locallang_db.xml:tx_mmforum_domain_model_forum_topic',
		'type' => 'type',
		'label' => 'subject',
		'tstamp' => 'tstamp',
		'crdate' => 'crdate',
		'versioningWS' => 2,
		'versioning_followPages' => TRUE,
		'origUid' => 't3_origuid',
		'languageField' => 'sys_language_uid',
		'transOrigPointerField' => 'l18n_parent',
		'transOrigDiffSourceField' => 'l18n_diffsource',
		'delete' => 'deleted',
		'enablecolumns' => array(
			'disabled' => 'hidden'
		),
		'dynamicConfigFile' => \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extPath($_EXTKEY) . 'Configuration/TCA/Forum/Topic.php',
		'iconfile' => \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extRelPath($_EXTKEY) . 'Resources/Public/Icons/Forum/Topic.png'
	)
);

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addLLrefForTCAdescr('tx_mmforum_domain_model_forum_post',
	'EXT:mm_forum/Resources/Private/Language/locallang_csh_tx_mmforum_domain_model_forum_post.xml');
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::allowTableOnStandardPages('tx_mmforum_domain_model_forum_post');
$TCA['tx_mmforum_domain_model_forum_post'] = array(
	'ctrl' => array(
		'title' => 'LLL:EXT:mm_forum/Resources/Private/Language/locallang_db.xml:tx_mmforum_domain_model_forum_post',
		'label' => 'text',
		'tstamp' => 'tstamp',
		'crdate' => 'crdate',
		'versioningWS' => 2,
		'versioning_followPages' => TRUE,
		'origUid' => 't3_origuid',
		'languageField' => 'sys_language_uid',
		'transOrigPointerField' => 'l18n_parent',
		'transOrigDiffSourceField' => 'l18n_diffsource',
		'delete' => 'deleted',
		'enablecolumns' => array(
			'disabled' => 'hidden'
		),
		'dynamicConfigFile' => \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extPath($_EXTKEY) . 'Configuration/TCA/Forum/Post.php',
		'iconfile' => \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extRelPath($_EXTKEY) . 'Resources/Public/Icons/Forum/Post.png'
	)
);

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addLLrefForTCAdescr('tx_mmforum_domain_model_format_textparser',
	'EXT:mm_forum/Resources/Private/Language/locallang_csh_tx_mmforum_domain_model_format_textparser.xml');
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::allowTableOnStandardPages('tx_mmforum_domain_model_format_textparser');
$TCA['tx_mmforum_domain_model_format_textparser'] = array(
	'ctrl' => array(
		'title' => 'LLL:EXT:mm_forum/Resources/Private/Language/locallang_db.xml:tx_mmforum_domain_model_format_textparser',
		'label' => 'name',
		'type' => 'type',
		'tstamp' => 'tstamp',
		'crdate' => 'crdate',
		'versioningWS' => 2,
		'versioning_followPages' => TRUE,
		'origUid' => 't3_origuid',
		'languageField' => 'sys_language_uid',
		'transOrigPointerField' => 'l18n_parent',
		'transOrigDiffSourceField' => 'l18n_diffsource',
		'delete' => 'deleted',
		'enablecolumns' => array(
			'disabled' => 'hidden'
		),
		'dynamicConfigFile' => \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extPath($_EXTKEY) . 'Configuration/TCA/Format/Textparser.php',
		'iconfile' => \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extRelPath($_EXTKEY) . 'Resources/Public/Icons/Format/Textparser.png'
	)
);

$tempColumns = array(
	'crdate' => array(
		'exclude' => 1,
		'config' => array('type' => 'passthrough')
	),
	'tx_mmforum_post_count' => array(
		'exclude' => 1,
		'label' => 'LLL:EXT:mm_forum/Resources/Private/Language/locallang_db.xml:fe_users.tx_mmforum_post_count',
		'config' => array('type' => 'none'),
	),
	'tx_mmforum_helpful_count' => array(
		'exclude' => 1,
		'label' => 'LLL:EXT:mm_forum/Resources/Private/Language/locallang_db.xml:fe_users.tx_mmforum_helpful_count',
		'config' => array('type' => 'none'),
	),
	'tx_mmforum_topic_subscriptions' => array(
		'exclude' => 1,
		'label' => 'LLL:EXT:mm_forum/Resources/Private/Language/locallang_db.xml:fe_users.tx_mmforum_topic_subscriptions',
		'config' => array(
			'type' => 'select',
			'foreign_table' => 'tx_mmforum_domain_model_forum_topic',
			'MM' => 'tx_mmforum_domain_model_user_topicsubscription',
			'multiple' => TRUE,
			'maxitems' => 9999,
			'minitems' => 0
		)
	),
	'tx_mmforum_forum_subscriptions' => array(
		'exclude' => 1,
		'label' => 'LLL:EXT:mm_forum/Resources/Private/Language/locallang_db.xml:fe_users.tx_mmforum_forum_subscriptions',
		'config' => array(
			'type' => 'select',
			'foreign_table' => 'tx_mmforum_domain_model_forum_forum',
			'MM' => 'tx_mmforum_domain_model_user_forumsubscription',
			'multiple' => TRUE,
			'maxitems' => 9999,
			'minitems' => 0
		)
	),
	'tx_mmforum_signature' => array(
		'exclude' => 1,
		'label' => 'LLL:EXT:mm_forum/Resources/Private/Language/locallang_db.xml:fe_users.tx_mmforum_signature',
		'config' => array(
			'type' => 'text'
		)
	),
	'tx_mmforum_userfield_values' => array(
		'exclude' => 1,
		'label' => 'LLL:EXT:mm_forum/Resources/Private/Language/locallang_db.xml:fe_users.tx_mmforum_userfield_values',
		'config' => array(
			'type' => 'inline',
			'foreign_table' => 'tx_mmforum_domain_model_user_userfield_value',
			'foreign_field' => 'user',
			'maxitems' => 9999,
			'appearance' => array(
				'collapse' => 0,
				'newRecordLinkPosition' => 'bottom',
			),
		)
	),
	'tx_mmforum_read_topics' => array(
		'exclude' => 1,
		'label' => 'LLL:EXT:mm_forum/Resources/Private/Language/locallang_db.xml:fe_users.tx_mmforum_read_topics',
		'config' => array(
			'type' => 'select',
			'foreign_table' => 'tx_mmforum_domain_model_forum_topic',
			'MM' => 'tx_mmforum_domain_model_user_readtopic',
			'multiple' => TRUE,
			'minitems' => 0
		)
	),
	'tx_mmforum_use_gravatar' => array(
		'exclude' => 0,
		'label' => 'LLL:EXT:mm_forum/Resources/Private/Language/locallang_db.xml:fe_users.use_gravatar',
		'config' => array(
			'type' => 'check'
		)
	),
	'tx_mmforum_contact' => array(
		'exclude' => 0,
		'label' => 'LLL:EXT:mm_forum/Resources/Private/Language/locallang_db.xml:fe_users.contact',
		'config' => array(
			'type' => 'display'
		)
	),
);
if (version_compare(TYPO3_branch, '6.1', '<')) {
	\TYPO3\CMS\Core\Utility\GeneralUtility::loadTCA('fe_users');
}
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTCAcolumns('fe_users', $tempColumns, 1);
$TCA['fe_users']['types']['Tx_MmForum_Domain_Model_User_FrontendUser'] = $TCA['fe_users']['types']['0'];
$TCA['fe_users']['types']['Tx_MmForum_Domain_Model_User_FrontendUser']['showitem'] .=
	',--div--;LLL:EXT:mm_forum/Resources/Private/Language/locallang_db.xml:fe_users.tx_mmforum.tab.settings,'
	. ' tx_mmforum_post_count, tx_mmforum_helpful_count, tx_mmforum_topic_subscriptions, tx_mmforum_forum_subscriptions,'
	. ' tx_mmforum_signature, tx_mmforum_userfield_values, tx_mmforum_use_gravatar, tx_mmforum_contact';
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTcaSelectItem('fe_users', 'tx_extbase_type',
	array('LLL:EXT:mm_forum/Resources/Private/Language/locallang_db.xml:fe_users.tx_extbase_type.mm_forum', 'Tx_MmForum_Domain_Model_User_FrontendUser'));

$TCA['fe_groups']['types']['Tx_MmForum_Domain_Model_User_FrontendUserGroup'] = $TCA['fe_groups']['types']['0'];
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTcaSelectItem('fe_groups', 'tx_extbase_type',
	array('LLL:EXT:mm_forum/Resources/Private/Language/locallang_db.xml:fe_groups.tx_extbase_type.mm_forum', 'Tx_MmForum_Domain_Model_User_FrontendUserGroup'));

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addLLrefForTCAdescr('tx_mmforum_domain_model_forum_access',
	'EXT:mm_forum/Resources/Private/Language/locallang_csh_tx_mmforum_domain_model_forum_access.xml');
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::allowTableOnStandardPages('tx_mmforum_domain_model_forum_access');
$TCA['tx_mmforum_domain_model_forum_access'] = array(
	'ctrl' => array(
		'title' => 'LLL:EXT:mm_forum/Resources/Private/Language/locallang_db.xml:tx_mmforum_domain_model_forum_access',
		'label' => 'operation',
		'type' => 'login_level',
		'tstamp' => 'tstamp',
		'crdate' => 'crdate',
		'versioningWS' => 2,
		'versioning_followPages' => TRUE,
		'origUid' => 't3_origuid',
		'languageField' => 'sys_language_uid',
		'transOrigPointerField' => 'l18n_parent',
		'transOrigDiffSourceField' => 'l18n_diffsource',
		'delete' => 'deleted',
		'enablecolumns' => array(
			'disabled' => 'hidden'
		),
		'dynamicConfigFile' => \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extPath($_EXTKEY) . 'Configuration/TCA/Forum/Access.php',
		'iconfile' => \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extRelPath($_EXTKEY) . 'Resources/Public/Icons/Forum/Access.png'
	)
);

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addLLrefForTCAdescr('tx_mmforum_domain_model_user_userfield_userfield',
	'EXT:mm_forum/Resources/Private/Language/locallang_csh_tx_mmforum_domain_model_user_userfield_userfield.xml');
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::allowTableOnStandardPages('tx_mmforum_domain_model_user_userfield_userfield');
$TCA['tx_mmforum_domain_model_user_userfield_userfield'] = array(
	'ctrl' => array(
		'title' => 'LLL:EXT:mm_forum/Resources/Private/Language/locallang_db.xml:tx_mmforum_domain_model_user_userfield_userfield',
		'label' => 'name',
		'type' => 'type',
		'tstamp' => 'tstamp',
		'crdate' => 'crdate',
		'versioningWS' => 2,
		'versioning_followPages' => TRUE,
		'origUid' => 't3_origuid',
		'languageField' => 'sys_language_uid',
		'transOrigPointerField' => 'l18n_parent',
		'transOrigDiffSourceField' => 'l18n_diffsource',
		'delete' => 'deleted',
		'enablecolumns' => array(
			'disabled' => 'hidden'
		),
		'dynamicConfigFile' => \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extPath($_EXTKEY) . 'Configuration/TCA/User/Userfield/Userfield.php',
		'iconfile' => \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extRelPath($_EXTKEY) . 'Resources/Public/Icons/User/Userfield/Userfield.png'
	)
);

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addLLrefForTCAdescr('tx_mmforum_domain_model_user_userfield_value',
	'EXT:mm_forum/Resources/Private/Language/locallang_csh_tx_mmforum_domain_model_user_userfield_value.xml');
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::allowTableOnStandardPages('tx_mmforum_domain_model_user_userfield_value');
$TCA['tx_mmforum_domain_model_user_userfield_value'] = array(
	'ctrl' => array(
		'title' => 'LLL:EXT:mm_forum/Resources/Private/Language/locallang_db.xml:tx_mmforum_domain_model_user_userfield_value',
		'label' => 'uid',
		'type' => 'user',
		'tstamp' => 'tstamp',
		'crdate' => 'crdate',
		'versioningWS' => 2,
		'versioning_followPages' => TRUE,
		'origUid' => 't3_origuid',
		'languageField' => 'sys_language_uid',
		'transOrigPointerField' => 'l18n_parent',
		'transOrigDiffSourceField' => 'l18n_diffsource',
		'delete' => 'deleted',
		'enablecolumns' => array(
			'disabled' => 'hidden'
		),
		'dynamicConfigFile' => \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extPath($_EXTKEY) . 'Configuration/TCA/User/Userfield/Value.php',
		'iconfile' => \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extRelPath($_EXTKEY) . 'Resources/Public/Icons/User/Userfield/Value.png'
	)
);

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addLLrefForTCAdescr('tx_mmforum_domain_model_forum_attachment',
	'EXT:mm_forum/Resources/Private/Language/locallang_csh_tx_mmforum_domain_model_forum_attachment.xml');
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::allowTableOnStandardPages('tx_mmforum_domain_model_forum_attachment');
$TCA['tx_mmforum_domain_model_forum_attachment'] = array(
	'ctrl' => array(
		'title' => 'LLL:EXT:mm_forum/Resources/Private/Language/locallang_db.xml:tx_mmforum_domain_model_forum_attachment',
		'label' => 'name',
		'tstamp' => 'tstamp',
		'crdate' => 'crdate',
		'delete' => 'deleted',
		'enablecolumns' => array('disabled' => 'hidden'),
		'dynamicConfigFile' => \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extPath($_EXTKEY) . 'Configuration/TCA/Forum/Attachment.php',
		'iconfile' => \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extRelPath($_EXTKEY) . 'Resources/Public/Icons/Forum/Attachment.png'
	)
);

#\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addLLrefForTCAdescr('tx_mmforum_domain_model_forum_forum','EXT:mm_forum/Resources/Private/Language/locallang_csh_tx_mmforum_domain_model_forum_forum.xml');
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::allowTableOnStandardPages('tx_mmforum_domain_model_moderation_report');
$TCA['tx_mmforum_domain_model_moderation_report'] = array(
	'ctrl' => array(
		'title' => 'LLL:EXT:mm_forum/Resources/Private/Language/locallang_db.xml:tx_mmforum_domain_model_moderation_report',
		'label' => 'post',
		'tstamp' => 'tstamp',
		'crdate' => 'crdate',
		'delete' => 'deleted',
		'enablecolumns' => array(
			'disabled' => 'hidden'
		),
		'dynamicConfigFile' => \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extPath($_EXTKEY) . 'Configuration/TCA/Moderation/Report.php',
		'iconfile' => \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extRelPath($_EXTKEY) . 'Resources/Public/Icons/Moderation/Report.png'
	)
);

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::allowTableOnStandardPages('tx_mmforum_domain_model_moderation_reportcomment');
$TCA['tx_mmforum_domain_model_moderation_reportcomment'] = array(
	'ctrl' => array(
		'title' => 'LLL:EXT:mm_forum/Resources/Private/Language/locallang_db.xml:tx_mmforum_domain_model_moderation_reportcomment',
		'label' => 'text',
		'tstamp' => 'tstamp',
		'crdate' => 'crdate',
		'delete' => 'deleted',
		'enablecolumns' => array(
			'disabled' => 'hidden'
		),
		'dynamicConfigFile' => \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extPath($_EXTKEY) . 'Configuration/TCA/Moderation/ReportComment.php',
		'iconfile' => \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extRelPath($_EXTKEY) . 'Resources/Public/Icons/Moderation/ReportComment.png'
	)
);

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::allowTableOnStandardPages('tx_mmforum_domain_model_moderation_reportworkflowstatus');
$TCA['tx_mmforum_domain_model_moderation_reportworkflowstatus'] = array(
	'ctrl' => array(
		'title' => 'LLL:EXT:mm_forum/Resources/Private/Language/locallang_db.xml:tx_mmforum_domain_model_moderation_reportworkflowstatus',
		'label' => 'name',
		'type' => 'login_level',
		'tstamp' => 'tstamp',
		'crdate' => 'crdate',
		'versioningWS' => 2,
		'versioning_followPages' => TRUE,
		'origUid' => 't3_origuid',
		'languageField' => 'sys_language_uid',
		'transOrigPointerField' => 'l18n_parent',
		'transOrigDiffSourceField' => 'l18n_diffsource',
		'delete' => 'deleted',
		'enablecolumns' => array(
			'disabled' => 'hidden'
		),
		'dynamicConfigFile' => \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extPath($_EXTKEY) . 'Configuration/TCA/Moderation/ReportWorkflowStatus.php',
		'iconfile' => \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extRelPath($_EXTKEY) . 'Resources/Public/Icons/Moderation/ReportWorkflowStatus.png'
	)
);




\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::allowTableOnStandardPages('tx_mmforum_domain_model_forum_criteria');
$TCA['tx_mmforum_domain_model_forum_criteria'] = array(
	'ctrl' => array(
		'title' => 'LLL:EXT:mm_forum/Resources/Private/Language/locallang_db.xml:tx_mmforum_domain_model_forum_criteria',
		'label' => 'name',
		'tstamp' => 'tstamp',
		'delete' => 'deleted',
		'icon' => \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extRelPath($_EXTKEY) . 'Resources/Public/Icons/Forum/Access.png',
		'dynamicConfigFile' => \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extPath($_EXTKEY) . 'Configuration/TCA/Forum/Criteria.php',
	)
);

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::allowTableOnStandardPages('tx_mmforum_domain_model_forum_criteria_options');
$TCA['tx_mmforum_domain_model_forum_criteria_options'] = array(
	'ctrl' => array(
		'title' => 'LLL:EXT:mm_forum/Resources/Private/Language/locallang_db.xml:tx_mmforum_domain_model_forum_criteria_options',
		'label' => 'name',
		'tstamp' => 'tstamp',
		'delete' => 'deleted',
		'icon' => \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extRelPath($_EXTKEY) . 'Resources/Public/Icons/Forum/Access.png',
		'dynamicConfigFile' => \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extPath($_EXTKEY) . 'Configuration/TCA/Forum/CriteriaOption.php',
	)
);

?>