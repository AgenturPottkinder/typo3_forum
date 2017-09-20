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
use TYPO3\CMS\Core\TypoScript\ExtendedTemplateService;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Object\ObjectManager;
use TYPO3\CMS\Frontend\Controller\TypoScriptFrontendController;
use TYPO3\CMS\Frontend\Page\PageRepository;
use TYPO3\CMS\Frontend\Utility\EidUtility;
use TYPO3\CMS\Extbase\Core\Bootstrap;
use TYPO3\CMS\Extbase\Object\ObjectManagerInterface;

final class Dispatcher implements SingletonInterface
{
    /**
     * @var string
     */
    private $extensionKey = 'Typo3Forum';

    /**
     * An instance of the extbase bootstrapping class.
     * @var Bootstrap
     */
    private $extbaseBootstap = null;

    /**
     * An instance of the extbase object manager.
     * @var ObjectManagerInterface
     */
    private $objectManager = null;

    /**
     * Initialize the dispatcher.
     */
    private function init()
    {
        $this->initializeTsfe();
        $this->initTYPO3();
        $this->initExtbase();
    }

    /**
     * Initializes TSFE.
     */
    private function initializeTsfe()
    {
        $GLOBALS['TSFE'] = GeneralUtility::makeInstance(
            TypoScriptFrontendController::class,
            null,
            GeneralUtility::_GP('id'),
            GeneralUtility::_GP('type'),
            true,
            GeneralUtility::_GP('cHash')
        );
        $GLOBALS['TSFE']->initFEuser();
        $GLOBALS['TSFE']->initUserGroups();
        EidUtility::initTCA();
        $GLOBALS['TSFE']->checkAlternativeIdMethods();
        $GLOBALS['TSFE']->determineId();
        $GLOBALS['TSFE']->sys_page = GeneralUtility::makeInstance(PageRepository::class);
        $GLOBALS['TSFE']->initTemplate();
        $GLOBALS['TSFE']->newCObj();
    }

    /**
     * Initialize the global TSFE object.
     *
     * Most of the code was adapted from the df_tools extension by Stefan
     * Galinski.
     */
    private function initTYPO3()
    {
        // The following code was adapted from the df_tools extension.
        // Credits go to Stefan Galinski.
        $GLOBALS['TSFE']->getPageAndRootline();
        $GLOBALS['TSFE']->forceTemplateParsing = true;
        $GLOBALS['TSFE']->no_cache = true;
        $GLOBALS['TSFE']->tmpl->start($GLOBALS['TSFE']->rootLine);
        $GLOBALS['TSFE']->no_cache = false;

        $language = '';
        if (isset($GLOBALS['TSFE']->tmpl->setup['config.']['language'])) {
            $language = $GLOBALS['TSFE']->tmpl->setup['config.']['language'];
        }
        $sys_language_uid = 0;
        if (isset($GLOBALS['TSFE']->tmpl->setup['config.']['sys_language_uid'])) {
            $sys_language_uid = $GLOBALS['TSFE']->tmpl->setup['config.']['sys_language_uid'];
        }
        $linkVars = '';
        if (isset($GLOBALS['TSFE']->tmpl->setup['config.']['linkVars'])) {
            $linkVars = $GLOBALS['TSFE']->tmpl->setup['config.']['linkVars'];
        }
        $locale_all = '';
        if (isset($GLOBALS['TSFE']->tmpl->setup['config.']['locale_all'])) {
            $locale_all = $GLOBALS['TSFE']->tmpl->setup['config.']['locale_all'];
        }

        $GLOBALS['TSFE']->config = [];
        $GLOBALS['TSFE']->config['config'] = [
            'sys_language_mode' => 'content_fallback;0',
            'sys_language_overlay' => 'hideNonTranslated',
            'sys_language_softMergeIfNotBlank' => '',
            'sys_language_softExclude' => '',
            'language' => $language,
            'sys_language_uid' => $sys_language_uid,
            'linkVars' => $linkVars,
            'locale_all' => $locale_all,
        ];

        $GLOBALS['TSFE']->settingLanguage();
        $GLOBALS['TSFE']->settingLocale();
        $GLOBALS['TSFE']->calculateLinkVars();
    }

    /**
     * Initializes the Extbase framework by instantiating the bootstrap
     * class and the extbase object manager.
     *
     * @return void
     */
    private function initExtbase()
    {
        $this->extbaseBootstap = GeneralUtility::makeInstance(Bootstrap::class);
        $this->extbaseBootstap->initialize([
            'extensionName' => $this->extensionKey,
            'pluginName' => 'Ajax',
            'vendorName' => 'Mittwald'
        ]);
        $this->objectManager = GeneralUtility::makeInstance(ObjectManager::class);
    }

    /**
     * @param integer $pageUid
     */
    private function loadTS($pageUid = 0)
    {
        $sysPageObj = GeneralUtility::makeInstance(PageRepository::class);

        $rootLine = $sysPageObj->getRootLine($pageUid);

        $typoscriptParser = GeneralUtility::makeInstance(ExtendedTemplateService::class);
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
     */
    public function processRequest()
    {
        $this->init();
        echo $this->dispatch();
    }

    /**
     * Dispatches a request.
     * @return string
     */
    public function dispatch()
    {
        return $this->extbaseBootstap->run('', [
            'extensionName' => $this->extensionKey,
            'pluginName' => 'Ajax',
            'vendorName' => 'Mittwald'
        ]);
    }
}
