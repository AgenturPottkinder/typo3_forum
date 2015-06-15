<?php
if (!defined ('TYPO3_MODE')) 	die ('Access denied.');

$TCA['tx_typo3forum_domain_model_forum_attachment'] = array(
	'ctrl' => $TCA['tx_typo3forum_domain_model_forum_attachment']['ctrl'],
	'interface' => array(
		'showRecordFieldList' => 'filename,real_filename,mime_type,download_count'
	),
	'types' => array(
		'1' => array('showitem' => 'filename,real_filename,mime_type,download_count')
	),
	'palettes' => array(
		'1' => array('showitem' => '')
	),
	'columns' => array(
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
		'post' => array(
			'exclude' => 1,
			'label'   => 'LLL:EXT:typo3_forum/Resources/Private/Language/locallang_db.xml:tx_typo3forum_domain_model_forum_post.topic',
			'config' => array(
				'type' => 'select',
				'foreign_class' => '\Mittwald\Typo3Forum\Domain\Model\Forum\Post',
				'foreign_table' => 'tx_typo3forum_domain_model_forum_post',
				'maxitems' => 1
			)
		),
		'filename' => array(
			'exclude' => 1,
			'label'   => 'LLL:EXT:typo3_forum/Resources/Private/Language/locallang_db.xml:tx_typo3forum_domain_model_forum_attachment.filename',
			'config'  => array(
				'type' => 'input',
				'size' => 30,
				'eval' => 'trim'
			)
		),
		'real_filename' => array(
			'exclude' => 1,
			'label'   => 'LLL:EXT:typo3_forum/Resources/Private/Language/locallang_db.xml:tx_typo3forum_domain_model_forum_attachment.real_filename',
			'config'  => array(
				'type' => 'group',
				'internal_type' => 'file',
				'uploadfolder' => 'uploads/tx_typo3forum/attachments/',
				'minitems' => 1,
				'maxitems' => 1,
				'allowed' => '*',
				'disallowed' => ''
			)
		),
		'mime_type' => array(
			'exclude' => 1,
			'label'   => 'LLL:EXT:typo3_forum/Resources/Private/Language/locallang_db.xml:tx_typo3forum_domain_model_forum_attachment.mime_type',
			'config' => array(
				'type' => 'input',
				'size' => 30,
				'eval' => 'trim'
			)
		),
		'download_count' => array(
			'exclude' => 1,
			'label'   => 'LLL:EXT:typo3_forum/Resources/Private/Language/locallang_db.xml:tx_typo3forum_domain_model_forum_attachment.download_count',
			'config' => array(
				'type' => 'none'
			)
		),
	),
);
?>