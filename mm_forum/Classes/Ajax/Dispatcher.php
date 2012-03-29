<?php

/*                                                                    - *
 *  COPYRIGHT NOTICE                                                    *
 *                                                                      *
 *  (c) 2012 Martin Helmich <m.helmich@mittwald.de>                     *
 *           Mittwald CM Service GmbH & Co KG                           *
 *           All rights reserved                                        *
 *                                                                      *
 *  This script is part of the TYPO3 project. The TYPO3 project is      *
 *  free software; you can redistribute it and/or modify                *
 *  it under the terms of the GNU General Public License as published   *
 *  by the Free Software Foundation; either version 2 of the License,   *
 *  or (at your option) any later version.                              *
 *                                                                      *
 *  The GNU General Public License can be found at                      *
 *  http://www.gnu.org/copyleft/gpl.html.                               *
 *                                                                      *
 *  This script is distributed in the hope that it will be useful,      *
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of      *
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the       *
 *  GNU General Public License for more details.                        *
 *                                                                      *
 *  This copyright notice MUST APPEAR in all copies of the script!      *
 *                                                                      */



/**
 * 
 * This class implements a simple dispatcher for a mm_form eID script.
 *
 * @author     Martin Helmich <m.helmich@mittwald.de>
 * @package    MmForum
 * @subpackage Ajax
 * @version    $Id: AbstractController.php 39978 2010-11-09 14:19:52Z mhelmich $
 *
 * @copyright  2012 Martin Helmich <m.helmich@mittwald.de>
 *             Mittwald CM Service GmbH & Co. KG
 *             http://www.mittwald.de
 * @license    GNU Public License, version 2
 *             http://opensource.org/licenses/gpl-license.php
 *
 */
final class Tx_MmForum_Ajax_Dispatcher
		implements t3lib_Singleton {



	/*
	 * ATTRIBUTES
	 */



	/**
	 * The current extension name.
	 * CAUTION: This is NOT the extension KEY (so not "mm_forum", but
	 * "MmForum" instead!)
	 *
	 * @var string
	 */
	protected $extensionKey = 'MmForum';


	/**
	 * An instance of the extbase bootstrapping class.
	 * @var Tx_Extbase_Core_Bootstrap
	 */
	protected $extbaseBootstap = NULL;


	/**
	 * An instance of the extbase object manager.
	 * @var Tx_Extbase_Object_ObjectManagerInterface
	 */
	protected $objectManager = NULL;


	/**
	 * An instance of the extbase request builder.
	 * @var Tx_Extbase_MVC_Web_RequestBuilder
	 */
	protected $requestBuilder = NULL;


	/**
	 * An instance of the extbase dispatcher.
	 * @var Tx_Extbase_MVC_Dispatcher
	 */
	protected $dispatcher = NULL;





	/*
	 * INITIALIZATION
	 */



	/**
	 * 
	 * Initialize the dispatcher.
	 * @return void
	 * 
	 */
	protected function init() {
		$this->initTYPO3();
		$this->initExtbase();
	}



	/**
	 * 
	 * Initialize the global TSFE object.
	 * 
	 * Most of the code was adapted from the df_tools extension by Stefan
	 * Galinski.
	 * 
	 * @return void.
	 * 
	 */
	protected function initTYPO3() {
		tslib_eidtools::connectDB();

		# The following code was adapted from the df_tools extension.
		# Credits go to Stefan Galinski.
		$GLOBALS['TSFE'] = t3lib_div::makeInstance('tslib_fe',
						$GLOBALS['TYPO3_CONF_VARS'], (int) $_GET['p'], 0);
		$GLOBALS['TSFE']->sys_page = t3lib_div::makeInstance('t3lib_pageSelect');
		$GLOBALS['TSFE']->getPageAndRootline();
		$GLOBALS['TSFE']->initTemplate();
		$GLOBALS['TSFE']->forceTemplateParsing = TRUE;
		$GLOBALS['TSFE']->initFEuser();
		$GLOBALS['TSFE']->initUserGroups();
		$GLOBALS['TSFE']->getCompressedTCarray();

		$GLOBALS['TSFE']->no_cache = TRUE;
		$GLOBALS['TSFE']->tmpl->start($GLOBALS['TSFE']->rootLine);
		$GLOBALS['TSFE']->no_cache = FALSE;

		$GLOBALS['TSFE']->config = array();
		$GLOBALS['TSFE']->config['config'] = array(
			'sys_language_uid' => intval(t3lib_div::_GP('L')),
			'sys_language_mode' => 'content_fallback;0',
			'sys_language_overlay' => 'hideNonTranslated',
			'sys_language_softMergeIfNotBlank' => '',
			'sys_language_softExclude' => '',
		);
		$GLOBALS['TSFE']->settingLanguage();
	}



	/**
	 * 
	 * Initializes the Extbase framework by instantiating the bootstrap
	 * class and the extbase object manager.
	 * 
	 * @return void
	 * 
	 */
	protected function initExtbase() {
		$this->extbaseBootstap = t3lib_div::makeInstance('Tx_Extbase_Core_Bootstrap');
		$this->objectManager = t3lib_div::makeInstance('Tx_Extbase_Object_ObjectManager');
	}



	/*
	 * DISPATCHING METHODS
	 */



	/**
	 * 
	 * Initializes this class and starts the dispatching process.
	 * @return void
	 * 
	 */
	public function run() {
		$this->init();
		$this->dispatch();
	}



	/**
	 * 
	 * Dispatches a request.
	 * @return void
	 * 
	 */
	public function dispatch() {
		echo $this->extbaseBootstap->run('',
				array(
			'extensionName' => $this->extensionKey,
			'pluginName' => 'Ajax'
		));
	}



}

# Instantiate and start dispatcher.
$dispatcher = t3lib_div::makeInstance('Tx_Extbase_Object_ObjectManager')->get('Tx_MmForum_Ajax_Dispatcher');
$dispatcher->run();
