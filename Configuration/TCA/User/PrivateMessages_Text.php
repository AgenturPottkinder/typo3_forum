<?php
if (!defined ('TYPO3_MODE')) 	die ('Access denied.');

$TCA['tx_mmforum_domain_model_user_privatemessages_text'] = array(
	'ctrl' => $TCA['tx_mmforum_domain_model_user_privatemessages_text']['ctrl'],
	'interface' => array(
		'showRecordFieldList' => 'message_text'
	),
	'types' => array(
		'1' => array('showitem' => 'message_text')
	),
	'palettes' => array(
		'1' => array('showitem' => '')
	),
	'columns' => array(
		'message_text' => array(
			'exclude' => 0,
			'label'   => 'LLL:EXT:mm_forum/Resources/Private/Language/locallang_db.xml:tx_mmforum_domain_model_user_pm_message_text',
			'config'  => array(
				'type' => 'text'
			)
		),
	)
);
?>