<?php
if (!defined ('TYPO3_MODE')) 	die ('Access denied.');

Tx_Extbase_Utility_Extension::configurePlugin(
	$_EXTKEY,
	'Pi1',
	array(
		'Forum' => 'index, show, new, create, edit, update, delete',
		'Topic' => 'index, show, new, create, edit, update, delete',
		'Post' => 'show, new, create, edit, update, delete',
		'User' => 'index, subscribe, show',
		'Report' => 'new, create',
		'Moderation' => 'editTopic, updateTopic'
	),
	array(
		'Forum' => 'index, show, new, create, edit, update, delete',
		'Topic' => 'index, show, new, create, edit, update, delete',
		'Post' => 'show, new, create, edit, update, delete',
		'User' => 'index, subscribe, show',
		'Report' => 'new, create',
		'Moderation' => 'editTopic, updateTopic'
	)
);

?>