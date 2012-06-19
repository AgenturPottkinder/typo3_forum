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
 * @author     Martin Helmich <m.helmich@mittwald.de>
 * @package    MmForum
 * @subpackage Controller
 * @version    $Id: AbstractController.php 52309 2011-09-20 18:54:26Z mhelmich $
 *
 * @copyright  2012 Martin Helmich <m.helmich@mittwald.de>
 *             Mittwald CM Service GmbH & Co. KG
 *             http://www.mittwald.de
 * @license    GNU Public License, version 2
 *             http://opensource.org/licenses/gpl-license.php
 *
 */
abstract class Tx_MmForum_Controller_AbstractBackendController extends Tx_Extbase_MVC_Controller_ActionController {



	/**
	 * The current extension key (Upper camel case, not lower underscored!)
	 *
	 * @var string
	 */
	protected $extensionName = 'MmForum';



	/**
	 * The backend template class.
	 *
	 * @var template
	 */
	protected $template = NULL;



	/**
	 * A page renderer
	 *
	 * @var t3lib_PageRenderer
	 */
	protected $pageRenderer = NULL;



	/**
	 * The current page UID
	 *
	 * @var integer
	 */
	protected $pageId = NULL;



	/**
	 * @var Tx_Extbase_Object_Container_Container
	 */
	protected $objectContainer = NULL;



	/**
	 * @param template $template
	 */
	public function injectTemplate(template $template) {
		$this->template     = $template;
		$this->pageRenderer = $this->template->getPageRenderer();
	}



	/**
	 * @param Tx_Extbase_Object_Container_Container $objectContainer
	 */
	public function injectObjectContainer(Tx_Extbase_Object_Container_Container $objectContainer) {
		$this->objectContainer = $objectContainer;
	}



	/**
	 * Processes a general request. The result can be returned by altering the given response.
	 *
	 * @param Tx_Extbase_MVC_RequestInterface  $request  The request object
	 * @param Tx_Extbase_MVC_ResponseInterface $response The response, modified by this handler
	 *
	 * @throws Tx_Extbase_MVC_Exception_UnsupportedRequestType if the controller doesn't support the current request type
	 * @return void
	 */
	public function processRequest(Tx_Extbase_MVC_RequestInterface $request,
	                               Tx_Extbase_MVC_ResponseInterface $response) {
		$GLOBALS['SOBE']      = new stdClass();
		$GLOBALS['SOBE']->doc = $this->template;

		parent::processRequest($request, $response);

		$pageHeader = $this->template->startpage($GLOBALS['LANG']->sL('LLL:EXT:mm_forum/Resources/Private/Language/locallang_mod.xml:module.title'));
		$pageEnd    = $this->template->endPage();

		$response->setContent($pageHeader . $response->getContent() . $pageEnd);
	}



	/**
	 * Initializes the controller before invoking an action method.
	 *
	 * @return void
	 */
	protected function initializeAction() {
		$this->registerAlternativeImplementations();

		$this->pageId = intval(t3lib_div::_GP('id'));

		$this->pageRenderer->addInlineLanguageLabelArray(array('title'      => $GLOBALS['LANG']->getLL('title'),
		                                                      'path'        => $GLOBALS['LANG']->sL('LLL:EXT:lang/locallang_core.xml:labels.path'),
		                                                      'table'       => $GLOBALS['LANG']->sL('LLL:EXT:lang/locallang_core.xml:labels.table'),
		                                                      'depth'       => $GLOBALS['LANG']->sL('LLL:EXT:lang/locallang_mod_web_perm.xml:Depth'),
		                                                      'depth_0'     => $GLOBALS['LANG']->sL('LLL:EXT:lang/locallang_core.xml:labels.depth_0'),
		                                                      'depth_1'     => $GLOBALS['LANG']->sL('LLL:EXT:lang/locallang_core.xml:labels.depth_1'),
		                                                      'depth_2'     => $GLOBALS['LANG']->sL('LLL:EXT:lang/locallang_core.xml:labels.depth_2'),
		                                                      'depth_3'     => $GLOBALS['LANG']->sL('LLL:EXT:lang/locallang_core.xml:labels.depth_3'),
		                                                      'depth_4'     => $GLOBALS['LANG']->sL('LLL:EXT:lang/locallang_core.xml:labels.depth_4'),
		                                                      'depth_infi'  => $GLOBALS['LANG']->sL('LLL:EXT:lang/locallang_core.xml:labels.depth_infi'),));

		$this->pageRenderer->addInlineLanguageLabelFile('EXT:mm_forum/Resources/Private/Language/locallang_mod.xml');

		$this->pageRenderer->loadExtJS();
		$this->pageRenderer->enableExtJSQuickTips();

		$this->pageRenderer->addJsFile(t3lib_extMgm::extRelPath('lang') . 'res/js/be/typo3lang.js');
		$this->pageRenderer->addJsFile($this->backPath . '../t3lib/js/extjs/ux/Ext.ux.FitToParent.js');
		$this->includeJavascriptFromPath('Resources/Public/Javascript/Backend/ExtJS/');
		$this->includeJavascriptFromPath('Resources/Public/Javascript/Backend/ForumIndex/');

		$this->includeCssFromPath('Resources/Public/Javascript/Backend/ExtJS/');
		$this->pageRenderer->addCssFile(t3lib_extMgm::extRelPath('mm_forum') . 'Resources/Public/Stylesheets/mm_forum-backend.css');
	}



	/**
	 *
	 */
	protected function registerAlternativeImplementations() {
		//		$this->objectContainer->registerImplementation(
		//			'Tx_MmForum_Service_Authentication_AuthenticationServiceInterface',
		//			'Tx_MmForum_Service_Authentication_BackendAuthenticationService');
	}



	/**
	 * @param $path
	 */
	protected function includeJavascriptFromPath($path) {
		$resourcePath    = t3lib_extMgm::extRelPath('mm_forum') . $path;
		$absResourcePath = t3lib_extMgm::extPath('mm_forum') . $path;

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
		$resourcePath    = t3lib_extMgm::extRelPath('mm_forum') . $path;
		$absResourcePath = t3lib_extMgm::extPath('mm_forum') . $path;

		$cssFiles = glob($absResourcePath . '*.css');

		foreach ($cssFiles as $cssFile) {
			$cssFile = str_replace($absResourcePath, $resourcePath, $cssFile);
			$this->pageRenderer->addCssFile($cssFile);
		}
	}



}