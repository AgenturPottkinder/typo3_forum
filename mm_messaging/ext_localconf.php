<?php
if (!defined ('TYPO3_MODE')) 	die ('Access denied.');

Tx_Extbase_Utility_Extension::configurePlugin(
	$_EXTKEY,
	'Pi1',
	array(
		'Message' => 'index, show, new, create, edit, update, delete, archive',
		'Attachment' => 'index, show, new, create, edit, update, delete',
	),
	array(
		'Message' => 'index, show, create, update, delete, archive',
		'Attachment' => 'create, update, delete',
	)
);

?>