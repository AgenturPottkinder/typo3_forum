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
	 * ViewHelper that performs text parsing operations on text input.
	 *
	 * @author     Martin Helmich <m.helmich@mittwald.de>
	 * @package    MmForum
	 * @subpackage ViewHelpers_Format
	 * @version    $Id$
	 *
	 * @copyright  2010 Martin Helmich <m.helmich@mittwald.de>
	 *             Mittwald CM Service GmbH & Co. KG
	 *             http://www.mittwald.de
	 * @license    GNU Public License, version 2
	 *             http://opensource.org/licenses/gpl-license.php
	 *
	 */

Class Tx_MmForum_ViewHelpers_Format_TextParserViewHelper
	Extends Tx_Fluid_Core_ViewHelper_AbstractViewHelper {





		/**
		 * The text parser service
		 * @var Tx_MmForum_TextParser_TextParserService
		 */
	Protected $textParserService;

	
	


		/**
		 *
		 * Initializes this view helper.
		 * @return void
		 *
		 */

	Public Function initialize() {
		parent::initialize();
		$this->textParserService =&
			t3lib_div::makeInstance('Tx_MmForum_TextParser_TextParserService');
		$this->textParserService->injectViewHelperVariableContainer($this->viewHelperVariableContainer);
	}



		/**
		 *
		 * Renders the input text.
		 *
		 * @param  string $configuration The configuration path
		 * @param  string $content       The content to be rendered. If NULL, the node
		 *                               content will be rendered instead.
		 * @return string                The rendered text
		 *
		 */

	Public Function render($configuration='plugin.tx_mmforum.settings.textParsing', $content=NULL) {
		$this->textParserService->loadConfiguration($configuration);
		Return $this->textParserService->parseText($content ? $content : trim($this->renderChildren()));
	}

}

?>
