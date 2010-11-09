<?php
if (!defined ('TYPO3_MODE')) 	die ('Access denied.');

Tx_Extbase_Utility_Extension::configurePlugin(
	$_EXTKEY,
	'Pi1',
	array(
		'Message' => 'index, show, new, create, edit, update, delete',
	),
	array(
		'Message' => 'create, update, delete',
	)
);

?>