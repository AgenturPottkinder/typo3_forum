<?php
namespace Mittwald\Typo3Forum\Ajax;

/*                                                                      *
 *  COPYRIGHT NOTICE                                                    *
 *                                                                      *
 *  (c) 2015 Mittwald CM Service GmbH & Co KG                           *
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

use TYPO3\CMS\Core\SingletonInterface;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Frontend\Utility\EidUtility;
use TYPO3\CMS\Extbase\Core\Bootstrap;
use TYPO3\CMS\Extbase\Mvc\Dispatcher as ExtbaseDispatcher;
use TYPO3\CMS\Extbase\Mvc\Web\RequestBuilder;
use TYPO3\CMS\Extbase\Object\ObjectManagerInterface;

final class Dispatcher implements SingletonInterface {

	/**
	 * @var string
	 */
	protected $extensionKey = 'Typo3Forum';


	/**
	 * An instance of the extbase bootstrapping class.
	 * @var Bootstrap
	 */
	protected $extbaseBootstap = NULL;


	/**
	 * An instance of the extbase object manager.
	 * @var ObjectManagerInterface
	 */
	protected $objectManager = NULL;


	/**
	 * An instance of the extbase request builder.
	 * @var RequestBuilder
	 */
	protected $requestBuilder = NULL;


	/**
	 * An instance of the extbase dispatcher.
	 * @var ExtbaseDispatcher
	 */
	protected $dispatcher = NULL;

	/**
	 * Initialize the dispatcher.
	 */
	protected function init() {
        $this->initializeTsfe();
		$this->initTYPO3();
		$this->initExtbase();
	}

	/**
	 * Initializes TSFE.
	 */
	protected function initializeTsfe() {
		$GLOBALS['TSFE'] = GeneralUtility::makeInstance('TYPO3\CMS\Frontend\Controller\TypoScriptFrontendController', $GLOBALS['TYPO3_CONF_VARS'], GeneralUtility::_GP('id'), GeneralUtility::_GP('type'), true);
		$GLOBALS['TSFE']->initFEuser();
		$GLOBALS['TSFE']->initUserGroups();
		EidUtility::initTCA();
		$GLOBALS['TSFE']->checkAlternativeIdMethods();
		$GLOBALS['TSFE']->determineId();
		$GLOBALS['TSFE']->sys_page =  GeneralUtility::makeInstance('TYPO3\CMS\Frontend\Page\PageRepository');
		$GLOBALS['TSFE']->initTemplate();
		$GLOBALS['TSFE']->newCObj();
	}

	/**
	 * Initialize the global TSFE object.
	 *
	 * Most of the code was adapted from the df_tools extension by Stefan
	 * Galinski.
	 */
	protected function initTYPO3() {
		


		// The following code was adapted from the df_tools extension.
		// Credits go to Stefan Galinski.
		$GLOBALS['TSFE']->getPageAndRootline();
		$GLOBALS['TSFE']->forceTemplateParsing = TRUE;
		$GLOBALS['TSFE']->no_cache = TRUE;
		$GLOBALS['TSFE']->tmpl->start($GLOBALS['TSFE']->rootLine);
		$GLOBALS['TSFE']->no_cache = FALSE;

        $language = '';
        if(isset($GLOBALS['TSFE']->tmpl->setup['config.']['language'])) {
            $language = $GLOBALS['TSFE']->tmpl->setup['config.']['language'];
        }
        $sys_language_uid = 0;
        if(isset($GLOBALS['TSFE']->tmpl->setup['config.']['sys_language_uid'])) {
            $sys_language_uid = $GLOBALS['TSFE']->tmpl->setup['config.']['sys_language_uid'];
        }
        $locale_all = '';
        if(isset($GLOBALS['TSFE']->tmpl->setup['config.']['locale_all'])) {
            $locale_all = $GLOBALS['TSFE']->tmpl->setup['config.']['locale_all'];
        }

		$GLOBALS['TSFE']->config = [];
		$GLOBALS['TSFE']->config['config'] = ['sys_language_mode' => 'content_fallback;0',
			'sys_language_overlay' => 'hideNonTranslated',
			'sys_language_softMergeIfNotBlank' => '',
			'sys_language_softExclude' => '',
			'language' => $language,
            'sys_language_uid' => $sys_language_uid,
            'locale_all' => $locale_all,
		];

		$GLOBALS['TSFE']->settingLanguage();
        $GLOBALS['TSFE']->settingLocale();
	}


	/**
	 * Initializes the Extbase framework by instantiating the bootstrap
	 * class and the extbase object manager.
	 *
	 * @return void
	 */
	protected function initExtbase() {
		$this->extbaseBootstap = GeneralUtility::makeInstance('TYPO3\CMS\Extbase\Core\Bootstrap');
		$this->extbaseBootstap->initialize(['extensionName' => $this->extensionKey, 'pluginName' => 'ajax', 'vendorName' => 'Mittwald']);
		$this->objectManager = GeneralUtility::makeInstance('TYPO3\CMS\Extbase\Object\ObjectManager');
	}


	/**
	 * @param integer $pageUid
	 */
	protected function loadTS($pageUid = 0) {
		$sysPageObj =  GeneralUtility::makeInstance('TYPO3\CMS\Frontend\Page\PageRepository');

		$rootLine = $sysPageObj->getRootLine($pageUid);

		$typoscriptParser = GeneralUtility::makeInstance('TYPO3\CMS\Core\TypoScript\ExtendedTemplateService');
		$typoscriptParser->tt_track = 0;
		$typoscriptParser->init();
		$typoscriptParser->runThroughTemplates($rootLine);
		$typoscriptParser->generateConfig();

		return $typoscriptParser->setup;
	}


	/*
	 * DISPATCHING METHODS
	 */


	/**
	 * Initializes this class and starts the dispatching process.
	 * @return string
	 */
	public function run() {
		$this->init();
		return $this->dispatch();
	}


	/**
	 * Dispatches a request.
	 * @return string
	 */
	public function dispatch() {
		return $this->extbaseBootstap->run('', ['extensionName' => $this->extensionKey, 'pluginName' => 'Ajax', 'vendorName' => 'Mittwald']);
	}


}

// Instantiate and start dispatcher.
/** @var Dispatcher $dispatcher */
$dispatcher = GeneralUtility::makeInstance('TYPO3\CMS\Extbase\Object\ObjectManager')->get('Mittwald\Typo3Forum\Ajax\Dispatcher');
echo $dispatcher->run();
