<?php

/*                                                                      *
 *  COPYRIGHT NOTICE                                                    *
 *                                                                      *
 *  (c) 2011 Martin Helmich <m.helmich@mittwald.de>                     *
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
	 * @copyright  2010 Martin Helmich <m.helmich@mittwald.de>
	 *             Mittwald CM Service GmbH & Co. KG
	 *             http://www.mittwald.de
	 * @license    GNU public License, version 2
	 *             http://opensource.org/licenses/gpl-license.php
	 *
	 */

class Tx_MmForum_TextParser_TextParserService
	extends Tx_MmForum_Service_AbstractService {





		/*
		 * ATTRIBUTES
		 */



		
		
		/**
		 * An instance of the Extbase object manager.
		 * @var Tx_Extbase_Object_ObjectManagerInterface
		 */
	protected $objectManager;

		/**
		 * An instance of the mm_forum typoscript reader. Is used to read the
		 * text parser's tyoscript configuration.
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
		 *
		 * Injects the viewHelperVariableContainer.
		 * 
		 * @param  Tx_Fluid_Core_ViewHelper_ViewHelperVariableContainer $viewHelperVariableContainer 
		 *                             The viewHelperVariableContainer.
		 * @return void
		 * 
		 */
	
	public function injectViewHelperVariableContainer(Tx_Fluid_Core_ViewHelper_ViewHelperVariableContainer $viewHelperVariableContainer) {
		$this->viewHelperVariableContainer = $viewHelperVariableContainer;
	}
	
	public function injectTyposcriptReader(Tx_MmForum_Utility_TypoScript $typoscriptReader) {
		$this->typoscriptReader = $typoscriptReader;
	}
	
	public function injectObjectManager(Tx_Extbase_Object_ObjectManagerInterface $objectManager) {
		$this->objectManager = $objectManager;
	}
	
	public function setControllerContext(Tx_Extbase_MVC_Controller_ControllerContext $controllerContext) {
		$this->controllerContext = $controllerContext;
	}





		/*
		 * SERVICE METHODS
		 */





		/**
		 *
		 * Loads the text parser configuration from a certain configuration path.
		 * @param  string $configurationPath The typoscript configuration path.
		 * @return void
		 *
		 */

	public function loadConfiguration($configurationPath = 'plugin.tx_mmforum.settings.textParsing') {
		if($this->settings !== NULL) return;

		$this->settings = $this->typoscriptReader->loadTyposcriptFromPath($configurationPath);
		foreach($this->settings['enabledServices.'] as $key => $className) {
			if(substr($key, -1, 1) === '.') continue;
			
			$newService = $this->objectManager->get($className);
			if(!$newService instanceof Tx_MmForum_TextParser_Service_AbstractTextParserService)
				throw new Tx_MmForum_Domain_Exception_TextParser_Exception('Invalid class; expected an instance of Tx_MmForum_TextParser_Service_AbstractTextParserService!', 1315916625);
			$newService->setSettings((array)$this->settings['enabledServices.'][$key.'.']);
			$newService->setControllerContext($this->controllerContext);
				
			$this->parsingServices[] = $newService;
		}
	}



		/**
		 *
		 * Parses a certain input text.
		 * @param  string $text The text that is to be parsed.
		 * @return string       The parsed text
		 *
		 */

	public function parseText($text) {
		if($this->settings === NULL)
			throw new Tx_MmForum_Domain_Exception_TextParser_Exception
				("The textparser is not configured!", 1284730639);

		foreach($this->parsingServices As &$parsingService)
			$text = $parsingService->getParsedText($text);
		return $text;
	}

}

?>
