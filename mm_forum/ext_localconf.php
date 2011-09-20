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

Tx_Extbase_Utility_Extension::configurePlugin(
	$_EXTKEY,
	'Ajax',
	array(
		'Forum' => 'index',
		'Post' => 'preview'
	),
	array(
		'Forum' => 'index',
		'Post' => 'preview'
	)
);

	# TCE-Main hook for clearing all mm_forum caches
$TYPO3_CONF_VARS['SC_OPTIONS']['t3lib/class.t3lib_tcemain.php']['clearCachePostProc'][]
	= 'EXT:mm_forum/Classes/Cache/CacheManager.php:Tx_MmForum_Cache_CacheManager->clearAll';
	
if (!is_array($TYPO3_CONF_VARS['SYS']['caching']['cacheConfigurations']['mmforum_main'])) {
    $TYPO3_CONF_VARS['SYS']['caching']['cacheConfigurations']['mmforum_main'] = array();
}

if (t3lib_div::int_from_ver(TYPO3_version) < '4006000') {
	if (!isset($TYPO3_CONF_VARS['SYS']['caching']['cacheConfigurations']['mmforum_main']['frontend']))
		$TYPO3_CONF_VARS['SYS']['caching']['cacheConfigurations']['mmforum_main']['frontend'] = 't3lib_cache_frontend_VariableFrontend';
	if (!isset($TYPO3_CONF_VARS['SYS']['caching']['cacheConfigurations']['mmforum_main']['backend']))
		$TYPO3_CONF_VARS['SYS']['caching']['cacheConfigurations']['mmforum_main']['backend'] = 't3lib_cache_backend_DbBackend';
	if (!isset($TYPO3_CONF_VARS['SYS']['caching']['cacheConfigurations']['mmforum_main']['options']))
		$TYPO3_CONF_VARS['SYS']['caching']['cacheConfigurations']['mmforum_main']['options'] = array();
	if (!isset($TYPO3_CONF_VARS['SYS']['caching']['cacheConfigurations']['mmforum_main']['options']['cacheTable']))
		$TYPO3_CONF_VARS['SYS']['caching']['cacheConfigurations']['mmforum_main']['options']['cacheTable'] = 'tx_mmforum_cache';
	if (!isset($TYPO3_CONF_VARS['SYS']['caching']['cacheConfigurations']['mmforum_main']['options']['tagsTable']))
		$TYPO3_CONF_VARS['SYS']['caching']['cacheConfigurations']['mmforum_main']['options']['tagsTable'] = 'tx_mmforum_cache_tags';
}

$TYPO3_CONF_VARS['FE']['eID_include']['mm_forum'] = 'EXT:mm_forum/Classes/Ajax/Dispatcher.php';

	# Add an indexing listener to the post controller.
	# Disabled until we have a working indexer interface ;)
#$TYPO3_CONF_VARS['EXTCONF']['mm_forum']['PostController']['onCreatePost'][]
#	= 'Tx_MmForum_Search_IndexingListener';

?>