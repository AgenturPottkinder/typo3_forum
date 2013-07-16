<?php

/*                                                                      *
 *  COPYRIGHT NOTICE                                                    *
 *                                                                      *
 *  (c) 2013 Martin Helmich <m.helmich@mittwald.de>                     *
 *           Sebastian Gieselmann <s.gieselmann@mittwald.de>            *
 *           Ruven Fehling <r.fehling@mittwald.de>                      *
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
 * @author     Sebastian Gieselmann <s.gieselmann@mittwald.de>
 * @author     Ruven Fehling <r.fehling@mittwald.de>
 * @package    MmForum
 * @subpackage Ajax
 * @version    $Id$
 *
 * @copyright  2012 Martin Helmich <m.helmich@mittwald.de>
 *             Mittwald CM Service GmbH & Co. KG
 *             http://www.mittwald.de
 * @license    GNU Public License, version 2
 *             http://opensource.org/licenses/gpl-license.php
 *
 */
final class Tx_MmForum_Ajax_Dispatcher implements \TYPO3\CMS\Core\SingletonInterface {


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
	 * @var \TYPO3\CMS\Extbase\Object\ObjectManagerInterface
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
	 * Initialize the dispatcher.
	 * @return void
	 */
	protected function init() {
		$this->initTYPO3();
		$this->initExtbase();
	}


	/**
	 * Initialize the global TSFE object.
	 *
	 * Most of the code was adapted from the df_tools extension by Stefan
	 * Galinski.
	 *
	 * @todo add language support!
	 *
	 * @return void.
	 */
	protected function initTYPO3() {
		if (version_compare(TYPO3_branch, '6.1', '<')) {
			\TYPO3\CMS\Frontend\Utility\EidUtility::connectDB();
		}

		$lang = "de";

		// The following code was adapted from the df_tools extension.
		// Credits go to Stefan Galinski.
		$GLOBALS['TSFE'] = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\CMS\Frontend\Controller\TypoScriptFrontendController',
			$GLOBALS['TYPO3_CONF_VARS'], (int)$_GET['id'], 0);
		$GLOBALS['TSFE']->sys_page = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\CMS\Frontend\Page\PageRepository');
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
		$GLOBALS['TSFE']->config['config'] = array('sys_language_mode' => 'content_fallback;0',
			'sys_language_overlay' => 'hideNonTranslated',
			'sys_language_softMergeIfNotBlank' => '',
			'sys_language_softExclude' => '',
			'language' => $lang,
		);
		$GLOBALS['TSFE']->settingLanguage();
	}


	/**
	 * Initializes the Extbase framework by instantiating the bootstrap
	 * class and the extbase object manager.
	 *
	 * @return void
	 */
	protected function initExtbase() {
		$this->extbaseBootstap = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\CMS\Extbase\Core\Bootstrap');
		$this->objectManager = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\CMS\Extbase\Object\ObjectManager');
	}


	/*
	 * DISPATCHING METHODS
	 */


	/**
	 * Initializes this class and starts the dispatching process.
	 * @return void
	 */
	public function run() {
		$this->init();
		$this->dispatch();
	}


	/**
	 * Dispatches a request.
	 * @return void
	 */
	public function dispatch() {
		echo $this->extbaseBootstap->run('', array('extensionName' => $this->extensionKey,
			'pluginName' => 'Ajax'));
	}


}

// Instantiate and start dispatcher.
$dispatcher = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\CMS\Extbase\Object\ObjectManager')->get('Tx_MmForum_Ajax_Dispatcher');
$dispatcher->run();
