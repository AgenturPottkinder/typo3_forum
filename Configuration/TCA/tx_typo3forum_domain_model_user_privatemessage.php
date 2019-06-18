<?php

return [
	'ctrl' => [
		'title' => 'LLL:EXT:typo3_forum/Resources/Private/Language/locallang_db.xml:tx_typo3forum_domain_model_user_privatemessage',
		'label' => 'uid',
		'tstamp' => 'tstamp',
		'crdate' => 'crdate',
		'delete' => 'deleted',
		'iconfile' => 'EXT:typo3_forum/Resources/Public/Icons/User/pm.png',
	],
	'interface' => [
		'showRecordFieldList' => 'message, feuser, opponent, type, user_read, crdate'
	],
	'types' => [
		'1' => ['showitem' => 'message, feuser, opponent, type, user_read, crdate'],
	],
	'columns' => [
		'message' => [
			'label' => 'LLL:EXT:typo3_forum/Resources/Private/Language/locallang_db.xml:tx_typo3forum_domain_model_user_privatemessage.message',
			'config' => [
				'type' => 'inline',
				'foreign_table' => 'tx_typo3forum_domain_model_user_privatemessage_text',
				'foreign_class' => '\Mittwald\Typo3Forum\Domain\Model\User\PrivateMessageText',
				'maxitems' => 1,
				'appearance' => [
					'collapseAll' => 1,
					'newRecordLinkPosition' => 'bottom',
					'expandSingle' => 1,
				],
			],
		],
		'feuser' => [
			'label' => 'LLL:EXT:typo3_forum/Resources/Private/Language/locallang_db.xml:tx_typo3forum_domain_model_user_privatemessage.feuser',
			'config' => [
				'type' => 'select',
				'renderType' => 'selectSingle',
				'foreign_table' => 'fe_users',
				'foreign_class' => '\Mittwald\Typo3Forum\Domain\Model\User\FrontendUser',
				'maxitems' => 1
			],
		],
		'opponent' => [
			'label' => 'LLL:EXT:typo3_forum/Resources/Private/Language/locallang_db.xml:tx_typo3forum_domain_model_user_privatemessage.opponent',
			'config' => [
				'type' => 'select',
				'renderType' => 'selectSingle',
				'foreign_table' => 'fe_users',
				'foreign_class' => '\Mittwald\Typo3Forum\Domain\Model\User\FrontendUser',
				'maxitems' => 1
			],
		],
		'type' => [
			'label' => 'LLL:EXT:typo3_forum/Resources/Private/Language/locallang_db.xml:tx_typo3forum_domain_model_user_privatemessage.type',
			'config' => [
				'type' => 'radio',
				'items' => [
					['sender', \Mittwald\Typo3Forum\Domain\Model\User\PrivateMessage::TYPE_SENDER],
					['recipient', \Mittwald\Typo3Forum\Domain\Model\User\PrivateMessage::TYPE_RECIPIENT],
				],
				'default' => 0,
			],
		],
		'user_read' => [
			'label' => 'LLL:EXT:typo3_forum/Resources/Private/Language/locallang_db.xml:tx_typo3forum_domain_model_user_privatemessage.user_read',
			'config' => [
				'type' => 'check'
			],
		],
		'crdate' => [
			'exclude' => 1,
			'label' => 'LLL:EXT:typo3_forum/Resources/Private/Language/locallang_db.xml:tstamp',
			'config' => [
				'type' => 'none',
				'format' => 'date',
				'eval' => 'date',
			],
		],
	],
];
