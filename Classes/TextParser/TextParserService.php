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
 *  it under the terms of the GNU General public License as published   *
 *  by the Free Software Foundation; either version 2 of the License,   *
 *  or (at your option) any later version.                              *
 *                                                                      *
 *  The GNU General public License can be found at                      *
 *  http://www.gnu.org/copyleft/gpl.html.                               *
 *                                                                      *
 *  This script is distributed in the hope that it will be useful,      *
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of      *
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the       *
 *  GNU General public License for more details.                        *
 *                                                                      *
 *  This copyright notice MUST APPEAR in all copies of the script!      *
 *                                                                      */



/**
 *
 * Service class for parsing text values for display. This service handles
 * for example the rendering of bb codes, smilies, etc.
 *
 * @author     Martin Helmich <m.helmich@mittwald.de>
 * @package    MmForum
 * @subpackage TextParser
 * @version    $Id$
 *
 * @copyright  2012 Martin Helmich <m.helmich@mittwald.de>
 *             Mittwald CM Service GmbH & Co. KG
 *             http://www.mittwald.de
 * @license    GNU public License, version 2
 *             http://opensource.org/licenses/gpl-license.php
 *
 */
class Tx_MmForum_TextParser_TextParserService extends Tx_MmForum_Service_AbstractService {



	/*
	 * ATTRIBUTES
	 */



	/**
	 * An instance of the Extbase object manager.
	 * @var \TYPO3\CMS\Extbase\Object\ObjectManagerInterface
	 */
	protected $objectManager;



	/**
	 * An instance of the mm_forum typoscript reader. Is used to read the
	 * text parser's tyoscript configuration.
	 *
	 * @var Tx_MmForum_Utility_TypoScript
	 */
	protected $typoscriptReader;



	/**
	 * An array of the parsing services that are to be used to render text input.
	 * @var array<Tx_MmForum_TextParser_Service_AbstractTextParserService>
	 */
	protected $parsingServices;



	/**
	 * The viewHelper variable container. This needs to be set when this service is
	 * called from a viewHelper context.
	 *
	 * @var Tx_Fluid_Core_ViewHelper_ViewHelperVariableContainer
	 */
	protected $viewHelperVariableContainer;



	/**
	 * The current controller context.
	 * @var Tx_Extbase_MVC_Controller_ControllerContext
	 */
	protected $controllerContext;



	/*
	 * INITIALIZATION
	 */



	/**
	 * Injects the viewHelperVariableContainer.
	 *
	 * @param  \TYPO3\CMS\Fluid\Core\ViewHelper\ViewHelperVariableContainer $viewHelperVariableContainer
	 *                             The viewHelperVariableContainer.
	 * @return void
	 */
	public function injectViewHelperVariableContainer(\TYPO3\CMS\Fluid\Core\ViewHelper\ViewHelperVariableContainer $viewHelperVariableContainer) {
		$this->viewHelperVariableContainer = $viewHelperVariableContainer;
	}



	/**
	 * Injects the mm_forum typoscript reader.
	 * @param Tx_MmForum_Utility_TypoScript $typoscriptReader The typoscript reader.
	 */
	public function injectTyposcriptReader(Tx_MmForum_Utility_TypoScript $typoscriptReader) {
		$this->typoscriptReader = $typoscriptReader;
	}



	/**
	 * Injects an instance of the Extbase object manager.
	 * @param \TYPO3\CMS\Extbase\Object\ObjectManagerInterface $objectManager An instance of the Extbase object manager.
	 */
	public function injectObjectManager(\TYPO3\CMS\Extbase\Object\ObjectManagerInterface $objectManager) {
		$this->objectManager = $objectManager;
	}



	/**
	 * Sets the current Extbase controller context.
	 * @param \TYPO3\CMS\Extbase\Mvc\Controller\ControllerContext $controllerContext
	 */
	public function setControllerContext(\TYPO3\CMS\Extbase\Mvc\Controller\ControllerContext $controllerContext) {
		$this->controllerContext = $controllerContext;
	}



	/*
	 * SERVICE METHODS
	 */



	/**
	 * Loads the text parser configuration from a certain configuration path.
	 *
	 * @param  string $configurationPath The typoscript configuration path.
	 * @return void
	 */
	public function loadConfiguration($configurationPath = 'plugin.tx_mmforum.settings.textParsing') {
		if ($this->settings !== NULL) {
			return;
		}

		$this->settings = $this->typoscriptReader->loadTyposcriptFromPath($configurationPath);
		$mmforumGlobalSettings = $this->typoscriptReader->loadTyposcriptFromPath('plugin.tx_mmforum.settings');
		foreach ($this->settings['enabledServices.'] as $key => $className) {
			if (substr($key, -1, 1) === '.') {
				continue;
			}

			/** @var $newService Tx_MmForum_TextParser_Service_AbstractTextParserService */
			$newService = $this->objectManager->get($className);
			if ($newService instanceof Tx_MmForum_TextParser_Service_AbstractTextParserService) {
				$settings = array();
				$globalSettingsToCopy = array('pids.');
				foreach($globalSettingsToCopy as $settingToCopy) {
					if (isset($mmforumGlobalSettings[$settingToCopy])) {
						$settings = array_merge($settings, array(rtrim($settingToCopy, '.') => $mmforumGlobalSettings[$settingToCopy]));
					}
				}
				$settings = array_merge($settings, (array)$this->settings['enabledServices.'][$key . '.']);
				$newService->setSettings($settings);
				$newService->setControllerContext($this->controllerContext);
				$this->parsingServices[] = $newService;
			} else {
				throw new Tx_MmForum_Domain_Exception_TextParser_Exception('Invalid class; expected an instance of Tx_MmForum_TextParser_Service_AbstractTextParserService!', 1315916625);
			}
		}
	}



	/**
	 * Parses a certain input text.
	 *
	 * @param  string $text The text that is to be parsed.
	 * @return string       The parsed text
	 */
	public function parseText($text) {
		if ($this->settings === NULL) {
			throw new Tx_MmForum_Domain_Exception_TextParser_Exception
			("The textparser is not configured!", 1284730639);
		}

		foreach ($this->parsingServices as &$parsingService) {
			/** @var $parsingService Tx_MmForum_TextParser_Service_AbstractTextParserService */
			$text = $parsingService->getParsedText($text);
		}
		return $text;
	}



}
