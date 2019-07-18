<?php

return [
	'ctrl' => [
		'title' => 'LLL:EXT:typo3_forum/Resources/Private/Language/locallang_db.xml:tx_typo3forum_domain_model_forum_attachment',
		'label' => 'name',
		'tstamp' => 'tstamp',
		'crdate' => 'crdate',
		'delete' => 'deleted',
		'enablecolumns' => ['disabled' => 'hidden'],
		'iconfile' => 'EXT:typo3_forum/Resources/Public/Icons/Forum/Attachment.png'
	],
	'interface' => [
		'showRecordFieldList' => 'filename,real_filename,mime_type,download_count'
	],
	'types' => [
		'1' => ['showitem' => 'filename,real_filename,mime_type,download_count'],
	],
	'columns' => [
		't3ver_label' => [
			'displayCond' => 'FIELD:t3ver_label:REQ:true',
			'label' => 'LLL:EXT:lang/Resources/Private/Language/locallang_general.xlf:LGL.versionLabel',
			'config' => [
				'type' => 'none',
				'cols' => 27
			],
		],
		'hidden' => [
			'exclude' => 1,
			'label' => 'LLL:EXT:lang/Resources/Private/Language/locallang_general.xlf:LGL.hidden',
			'config' => [
				'type' => 'check'
			],
		],
		'crdate' => [
			'exclude' => 1,
			'label' => 'LLL:EXT:lang/Resources/Private/Language/locallang_general.xlf:LGL.creationDate',
			'config' => [
				'type' => 'passthrough'
			],
		],
		'post' => [
			'exclude' => 1,
			'label' => 'LLL:EXT:typo3_forum/Resources/Private/Language/locallang_db.xml:tx_typo3forum_domain_model_forum_post.topic',
			'config' => [
				'type' => 'select',
				'renderType' => 'selectSingle',
				'foreign_class' => '\Mittwald\Typo3Forum\Domain\Model\Forum\Post',
				'foreign_table' => 'tx_typo3forum_domain_model_forum_post',
				'maxitems' => 1
			],
		],
		'filename' => [
			'exclude' => 1,
			'label' => 'LLL:EXT:typo3_forum/Resources/Private/Language/locallang_db.xml:tx_typo3forum_domain_model_forum_attachment.filename',
			'config' => [
				'type' => 'input',
				'size' => 30,
				'eval' => 'trim'
			],
		],
		'real_filename' => [
			'exclude' => 1,
			'label' => 'LLL:EXT:typo3_forum/Resources/Private/Language/locallang_db.xml:tx_typo3forum_domain_model_forum_attachment.real_filename',
			'config' => [
				'type' => 'group',
				'internal_type' => 'file',
				'uploadfolder' => 'uploads/tx_typo3forum/attachments/',
				'minitems' => 1,
				'maxitems' => 1,
				'allowed' => '*',
				'disallowed' => ''
			],
		],
		'mime_type' => [
			'exclude' => 1,
			'label' => 'LLL:EXT:typo3_forum/Resources/Private/Language/locallang_db.xml:tx_typo3forum_domain_model_forum_attachment.mime_type',
			'config' => [
				'type' => 'input',
				'size' => 30,
				'eval' => 'trim'
			],
		],
		'download_count' => [
			'exclude' => 1,
			'label' => 'LLL:EXT:typo3_forum/Resources/Private/Language/locallang_db.xml:tx_typo3forum_domain_model_forum_attachment.download_count',
			'config' => [
				'type' => 'none'
			],
		],
	],
];
