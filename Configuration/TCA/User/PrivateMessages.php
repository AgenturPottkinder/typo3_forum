<?php
if (!defined('TYPO3_MODE')) die ('Access denied.');

$TCA['tx_typo3forum_domain_model_user_privatemessages'] = array(
	'ctrl' => $TCA['tx_typo3forum_domain_model_user_privatemessages']['ctrl'],
	'interface' => array(
		'showRecordFieldList' => 'message, feuser, opponent, type, user_read, crdate'
	),
	'types' => array(
		'1' => array('showitem' => 'message, feuser, opponent, type, user_read, crdate')
	),
	'palettes' => array(
		'1' => array('showitem' => '')
	),
	'columns' => array(
		'message' => array(
			'exclude' => 0,
			'label' => 'LLL:EXT:typo3_forum/Resources/Private/Language/locallang_db.xml:tx_typo3forum_domain_model_user_pm_message',
			'config' => array(
				'type' => 'inline',
				'foreign_table' => 'tx_typo3forum_domain_model_user_privatemessages_text',
				'foreign_class' => '\Mittwald\Typo3Forum\Domain\Model\User\PrivateMessagesText',
				'maxitems' => 1,
				'appearance' => array(
					'collapseAll' => 1,
					'newRecordLinkPosition' => 'bottom',
					'expandSingle' => 1,
				),
			)
		),
		'feuser' => array(
			'exclude' => 0,
			'label' => 'LLL:EXT:typo3_forum/Resources/Private/Language/locallang_db.xml:tx_typo3forum_domain_model_user_pm_feuser',
			'config' => array(
				'type' => 'select',
				'foreign_table' => 'fe_users',
				'foreign_class' => '\Mittwald\Typo3Forum\Domain\Model\User\FrontendUser',
				'maxitems' => 1
			)
		),
		'opponent' => array(
			'exclude' => 0,
			'label' => 'LLL:EXT:typo3_forum/Resources/Private/Language/locallang_db.xml:tx_typo3forum_domain_model_user_pm_opponent',
			'config' => array(
				'type' => 'select',
				'foreign_table' => 'fe_users',
				'foreign_class' => '\Mittwald\Typo3Forum\Domain\Model\User\FrontendUser',
				'maxitems' => 1
			)
		),
		'type' => array(
			'exclude' => 0,
			'label' => 'LLL:EXT:typo3_forum/Resources/Private/Language/locallang_db.xml:tx_typo3forum_domain_model_user_pm_recipient_type',
			'config' => array(
				'type' => 'radio',
				'items' => array(
					array('sender', \Mittwald\Typo3Forum\Domain\Model\User\PrivateMessages::TYPE_SENDER),
					array('recipient', \Mittwald\Typo3Forum\Domain\Model\User\PrivateMessages::TYPE_RECIPIENT),
				),
				'default' => 0,
			)
		),
		'user_read' => array(
			'exclude' => 0,
			'label' => 'LLL:EXT:typo3_forum/Resources/Private/Language/locallang_db.xml:tx_typo3forum_domain_model_user_pm_recipient_read',
			'config' => array(
				'type' => 'check'
			)
		),
		'crdate' => array(
			'exclude' => 1,
			'label' => 'LLL:EXT:typo3_forum/Resources/Private/Language/locallang_db.xml:tstamp',
			'config' => array(
				'type' => 'none',
				'format' => 'date',
				'eval' => 'date',
			)
		),
	)
);
