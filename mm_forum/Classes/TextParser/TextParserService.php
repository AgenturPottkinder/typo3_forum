<?php

/*                                                                      *
 *  COPYRIGHT NOTICE                                                    *
 *                                                                      *
 *  (c) 2010 Martin Helmich <m.helmich@mittwald.de>                     *
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
	 * @license    GNU Public License, version 2
	 *             http://opensource.org/licenses/gpl-license.php
	 *
	 */

Class Tx_MmForum_TextParser_TextParserService
	Extends Tx_MmForum_Service_AbstractService {





		/*
		 * ATTRIBUTES
		 */





		/**
		 * An array of the parsing services that are to be used to render text input.
		 * @var array<Tx_MmForum_TextParser_Service_AbstractTextParserService>
		 */
	Protected $parsingServices;

		/**
		 * The viewHelper variable container. This needs to be set when this service is
		 * called from a viewHelper context.
		 * @var Tx_Fluid_Core_ViewHelper_ViewHelperVariableContainer
		 */
	Protected $viewHelperVariableContainer;
	
	
	
	
	
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
	
	Public Function injectViewHelperVariableContainer(Tx_Fluid_Core_ViewHelper_ViewHelperVariableContainer $viewHelperVariableContainer) {
		$this->viewHelperVariableContainer = $viewHelperVariableContainer;
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

	Public Function loadConfiguration($configurationPath = 'plugin.tx_mmforum.settings.textParsing') {
		If($this->settings !== NULL) Return;

		$this->settings = Tx_MmForum_Utility_TypoScript::loadTyposcriptFromPath($configurationPath);
		ForEach($this->settings['enabledServices.'] As $className) {
			$newService = t3lib_div::makeInstance($className);
			$newService->injectViewHelperVariableContainer($this->viewHelperVariableContainer);
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

	Public Function parseText($text) {
		If($this->settings === NULL)
			Throw New Tx_MmForum_Domain_Exception_TextParser_Exception
				("The textparser is not configured!", 1284730639);

		ForEach($this->parsingServices As &$parsingService)
			$text = $parsingService->getParsedText($text);
		Return $text;
	}

}

?>
