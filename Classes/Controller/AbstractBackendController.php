<?php
namespace Mittwald\Typo3Forum\Controller;

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

use TYPO3\CMS\Core\Page\PageRenderer;
use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;
use TYPO3\CMS\Extbase\Mvc\RequestInterface;
use TYPO3\CMS\Extbase\Mvc\ResponseInterface;

abstract class AbstractBackendController extends ActionController {

	/**
	 * @var string
	 */
	protected $extensionName = 'Typo3Forum';

	/**
	 * @var \TYPO3\CMS\Backend\Template\DocumentTemplate
	 * @inject
	 */
	protected $documentTemplate = NULL;

	/**
	 * @var \TYPO3\CMS\Lang\LanguageService
	 */
	protected $languageService;

	/**
	 * @var \TYPO3\CMS\Extbase\Object\Container\Container
	 * @inject
	 */
	protected $objectContainer = NULL;

	/**
	 * @var PageRenderer
	 */
	protected $pageRenderer = NULL;

	/**
	 * @var integer
	 */
	protected $pageId = NULL;

	/**
	 *
	 */
	public function initializeObject() {
		$this->pageRenderer = $this->documentTemplate->getPageRenderer();
		$this->languageService = $GLOBALS['LANG'];
	}

	/**
	 * Processes a general request. The result can be returned by altering the given response.
	 *
	 * @param RequestInterface $request The request object
	 * @param ResponseInterface $response The response, modified by this handler
	 */
	public function processRequest(RequestInterface $request, ResponseInterface $response) {
		$GLOBALS['SOBE'] = new \stdClass();
		$GLOBALS['SOBE']->doc = $this->documentTemplate;

		parent::processRequest($request, $response);

		$pageHeader = $this->documentTemplate->startpage($this->languageService->sL('LLL:EXT:typo3_forum/Resources/Private/Language/locallang_mod.xml:module.title'));
		$pageEnd = $this->documentTemplate->endPage();

		$response->setContent($pageHeader . $response->getContent() . $pageEnd);
	}


	/**
	 * Initializes the controller before invoking an action method.
	 *
	 * @return void
	 */
	protected function initializeAction() {

		$this->pageId = intval(GeneralUtility::_GP('id'));

		$this->pageRenderer->addInlineLanguageLabelArray(array('title' => $this->languageService->getLL('title'),
			'path' => $this->languageService->sL('LLL:EXT:lang/locallang_core.xml:labels.path'),
			'table' => $this->languageService->sL('LLL:EXT:lang/locallang_core.xml:labels.table'),
			'depth' => $this->languageService->sL('LLL:EXT:lang/locallang_mod_web_perm.xml:Depth'),
			'depth_0' => $this->languageService->sL('LLL:EXT:lang/locallang_core.xml:labels.depth_0'),
			'depth_1' => $this->languageService->sL('LLL:EXT:lang/locallang_core.xml:labels.depth_1'),
			'depth_2' => $this->languageService->sL('LLL:EXT:lang/locallang_core.xml:labels.depth_2'),
			'depth_3' => $this->languageService->sL('LLL:EXT:lang/locallang_core.xml:labels.depth_3'),
			'depth_4' => $this->languageService->sL('LLL:EXT:lang/locallang_core.xml:labels.depth_4'),
			'depth_infi' => $this->languageService->sL('LLL:EXT:lang/locallang_core.xml:labels.depth_infi'),));

		$this->pageRenderer->addInlineLanguageLabelFile('EXT:typo3_forum/Resources/Private/Language/locallang_mod.xml');

		$this->pageRenderer->loadExtJS();
		$this->pageRenderer->enableExtJSQuickTips();

		$this->pageRenderer->addJsFile(ExtensionManagementUtility::extRelPath('lang') . 'res/js/be/typo3lang.js');
		$this->pageRenderer->addJsFile($this->backPath . 'js/extjs/ux/Ext.ux.FitToParent.js');
		$this->includeJavascriptFromPath('Resources/Public/Javascript/Backend/ExtJS/');
		$this->includeJavascriptFromPath('Resources/Public/Javascript/Backend/ForumIndex/');

		$this->includeCssFromPath('Resources/Public/Javascript/Backend/ExtJS/');
		$this->pageRenderer->addCssFile(ExtensionManagementUtility::extRelPath('typo3_forum') . 'Resources/Public/Stylesheets/typo3_forum-backend.css');
	}

	/**
	 * @param $path
	 */
	protected function includeJavascriptFromPath($path) {
		$resourcePath = ExtensionManagementUtility::extRelPath('typo3_forum') . $path;
		$absResourcePath = ExtensionManagementUtility::extPath('typo3_forum') . $path;

		$jsFiles = glob($absResourcePath . '*.js');

		foreach ($jsFiles as $jsFile) {
			$jsFile = str_replace($absResourcePath, $resourcePath, $jsFile);
			$this->pageRenderer->addJsFile($jsFile, 'text/javascript', FALSE);
		}
	}


	/**
	 * @param $path
	 */
	protected function includeCssFromPath($path) {
		$resourcePath = ExtensionManagementUtility::extRelPath('typo3_forum') . $path;
		$absResourcePath = ExtensionManagementUtility::extPath('typo3_forum') . $path;

		$cssFiles = glob($absResourcePath . '*.css');

		foreach ($cssFiles as $cssFile) {
			$cssFile = str_replace($absResourcePath, $resourcePath, $cssFile);
			$this->pageRenderer->addCssFile($cssFile);
		}
	}


}
