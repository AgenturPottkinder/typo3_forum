<?php
if (!defined ('TYPO3_MODE')) die ('Access denied.');

Tx_Extbase_Utility_Extension::registerPlugin(
	$_EXTKEY,
	'Pi1',
	'mm_forum Messenger'
);

t3lib_extMgm::addStaticFile($_EXTKEY, 'Configuration/TypoScript', 'mm_forum Messenger');

//$TCA['tt_content']['types']['list']['subtypes_addlist'][$_EXTKEY . '_pi1'] = 'pi_flexform';
//t3lib_extMgm::addPiFlexFormValue($_EXTKEY . '_pi1', 'FILE:EXT:' . $_EXTKEY . '/Configuration/FlexForms/flexform_list.xml');


t3lib_extMgm::addLLrefForTCAdescr('tx_mmmessaging_domain_model_message', 'EXT:mm_messaging/Resources/Private/Language/locallang_csh_tx_mmmessaging_domain_model_message.xml');
t3lib_extMgm::allowTableOnStandardPages('tx_mmmessaging_domain_model_message');
$TCA['tx_mmmessaging_domain_model_message'] = array (
	'ctrl' => array (
		'title'             => 'LLL:EXT:mm_messaging/Resources/Private/Language/locallang_db.xml:tx_mmmessaging_domain_model_message',
		'label' 			=> 'recipient',
		'tstamp' 			=> 'tstamp',
		'crdate' 			=> 'crdate',
		'versioningWS' 		=> 2,
		'versioning_followPages'	=> TRUE,
		'origUid' 			=> 't3_origuid',
		'languageField' 	=> 'sys_language_uid',
		'transOrigPointerField' 	=> 'l18n_parent',
		'transOrigDiffSourceField' 	=> 'l18n_diffsource',
		'delete' 			=> 'deleted',
		'enablecolumns' 	=> array(
			'disabled' => 'hidden'
			),
		'dynamicConfigFile' => t3lib_extMgm::extPath($_EXTKEY) . 'Configuration/TCA/Message.php',
		'iconfile' 			=> t3lib_extMgm::extRelPath($_EXTKEY) . 'Resources/Public/Icons/tx_mmmessaging_domain_model_message.gif'
	)
);

t3lib_extMgm::addLLrefForTCAdescr('tx_mmmessaging_domain_model_attachment', 'EXT:mm_messaging/Resources/Private/Language/locallang_csh_tx_mmmessaging_domain_model_attachment.xml');
t3lib_extMgm::allowTableOnStandardPages('tx_mmmessaging_domain_model_attachment');
$TCA['tx_mmmessaging_domain_model_attachment'] = array (
	'ctrl' => array (
		'title'             => 'LLL:EXT:mm_messaging/Resources/Private/Language/locallang_db.xml:tx_mmmessaging_domain_model_attachment',
		'label' 			=> 'name',
		'tstamp' 			=> 'tstamp',
		'crdate' 			=> 'crdate',
		'versioningWS' 		=> 2,
		'versioning_followPages'	=> TRUE,
		'origUid' 			=> 't3_origuid',
		'languageField' 	=> 'sys_language_uid',
		'transOrigPointerField' 	=> 'l18n_parent',
		'transOrigDiffSourceField' 	=> 'l18n_diffsource',
		'delete' 			=> 'deleted',
		'enablecolumns' 	=> array(
			'disabled' => 'hidden'
			),
		'dynamicConfigFile' => t3lib_extMgm::extPath($_EXTKEY) . 'Configuration/TCA/Attachment.php',
		'iconfile' 			=> t3lib_extMgm::extRelPath($_EXTKEY) . 'Resources/Public/Icons/tx_mmmessaging_domain_model_attachment.gif'
	)
);

?>