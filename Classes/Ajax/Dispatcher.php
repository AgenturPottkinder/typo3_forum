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

use TYPO3\CMS\Core\Http\HtmlResponse;
use TYPO3\CMS\Core\SingletonInterface;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Utility\RootlineUtility;
use TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface;
use TYPO3\CMS\Extbase\Core\Bootstrap;
use TYPO3\CMS\Extbase\Object\ObjectManager;
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
     * @var ConfigurationManagerInterface
     */
    private $configurationManager;

    /**
     * Initialize the dispatcher.
     */
    private function init()
    {
        $this->objectManager = GeneralUtility::makeInstance(ObjectManager::class);
        $this->configurationManager = $this->objectManager->get(ConfigurationManagerInterface::class);
        $this->initTSFE(GeneralUtility::_GP('id'));
    }

    /**
     * @param int  $pageUid
     * @param null $rootLine
     * @param null $pageData
     * @param null $rootlineFull
     * @param null $sysLanguage
     */
    public function initTSFE($pageUid, $rootLine = null, $pageData = null, $rootlineFull = null, $sysLanguage = null): void
    {
        static $cacheTSFE = [];
        static $lastTsSetupPid = null;

        // Fetch page if needed
        if ($pageData === null) {
            $sysPageObj = $this->objectManager->get(\TYPO3\CMS\Frontend\Page\PageRepository::class);

            $pageData = $sysPageObj->getPage_noCheck($pageUid);
        }

        // create time tracker if needed
        if (empty($GLOBALS['TT'])) {
            $GLOBALS['TT'] = new \TYPO3\CMS\Core\TimeTracker\TimeTracker(false);
            $GLOBALS['TT']->start();
        }

        if ($rootLine === null) {
            /**
             * @var RootlineUtility $rootlineUtilty
             */
            $rootlineUtilty = GeneralUtility::makeInstance(RootlineUtility::class, $pageUid);
            $rootlineUtilty->get();
//            $sysPageObj = $this->objectManager->get(\TYPO3\CMS\Frontend\Page\PageRepository::class);
//            $rootLine   = $sysPageObj->getRootLine($pageUid);

            // save full rootline, we need it in TSFE
            $rootlineFull = $rootlineUtilty->get();
        }

        // Only setup tsfe if current instance must be changed
        if ($lastTsSetupPid !== $pageUid) {

            // Cache TSFE if possible to prevent reinit (is still slow but we need the TSFE)
            if (empty($cacheTSFE[$pageUid])) {
                $GLOBALS['TSFE']       = $this->objectManager->get(
                    \TYPO3\CMS\Frontend\Controller\TypoScriptFrontendController::class,
                    $GLOBALS['TYPO3_CONF_VARS'],
                    $pageUid,
                    0
                );
                $GLOBALS['TSFE']->cObj = $this->objectManager->get(\TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer::class);

                $this->configurationManager->setContentObject($GLOBALS['TSFE']->cObj);

                $TSObj           = $this->objectManager->get(\TYPO3\CMS\Core\TypoScript\ExtendedTemplateService::class);
                $TSObj->tt_track = 0;
                $TSObj->init();
                $TSObj->runThroughTemplates($rootLine);
                $TSObj->generateConfig();

                $_GET['id'] = $pageUid;
                $GLOBALS['TSFE']->initFEuser();
                $GLOBALS['TSFE']->determineId();

                if (empty($GLOBALS['TSFE']->tmpl)) {
                    $GLOBALS['TSFE']->tmpl = new \stdClass();
                }

                $GLOBALS['TSFE']->tmpl->setup = $TSObj->setup;
                $GLOBALS['TSFE']->initTemplate();
                $GLOBALS['TSFE']->getConfigArray();

                $GLOBALS['TSFE']->baseUrl = $GLOBALS['TSFE']->config['config']['baseURL'];

                $cacheTSFE[$pageUid] = $GLOBALS['TSFE'];
            }

            $GLOBALS['TSFE'] = $cacheTSFE[$pageUid];

            $lastTsSetupPid = $pageUid;
        }

        $GLOBALS['TSFE']->page       = $pageData;
        $GLOBALS['TSFE']->rootLine   = $rootlineFull;
        $GLOBALS['TSFE']->cObj->data = $pageData;
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

        return $this->dispatch();
    }

    /**
     * Dispatches a request.
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function dispatch(): \Psr\Http\Message\ResponseInterface
    {
        $this->extbaseBootstap = $this->objectManager->get(Bootstrap::class);
        $content               = $this->extbaseBootstap->run('', [
            'extensionName' => $this->extensionKey,
            'pluginName'    => 'Ajax',
            'vendorName'    => 'Mittwald'
        ]);

        /* @var HtmlResponse $response */
        return $this->objectManager->get(HtmlResponse::class, $content);
    }
}
