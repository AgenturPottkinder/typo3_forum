<?php

$EM_CONF[$_EXTKEY] = [
	'title' => 'typo3_forum',
	'description' => 'Forum extension',
	'category' => 'plugin',
	'author' => 'Mittwald CM Service',
	'author_email' => 'support@mittwald.de',
	'author_company' => 'Mittwald CM Service',
	'dependencies' => 'cms,extbase,fluid,sr_feuser_register,static_info_tables',
	'state' => 'stable',
	'uploadfolder' => 0,
	'createDirs' => 'typo3temp/typo3_forum,typo3temp/typo3_forum/gravatar',
	'modify_tables' => 'fe_users',
	'clearCacheOnLoad' => 0,
	'version' => '1.0.0',
	'constraints' => [
		'depends' => [
			'typo3' => '6.2.0-7.99.99',
			'static_info_tables' => '',
			'php' => '5.4',
		],
		'suggests' => [
			'sr_feuser_register' => '',
		],
	],
];
