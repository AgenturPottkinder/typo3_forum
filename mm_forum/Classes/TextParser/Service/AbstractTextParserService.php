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
	 * Abstract base class for all kinds of text parsing services.
	 *
	 * @author     Martin Helmich <m.helmich@mittwald.de>
	 * @package    MmForum
	 * @subpackage TextParser_Service
	 * @version    $Id$
	 *
	 * @copyright  2010 Martin Helmich <m.helmich@mittwald.de>
	 *             Mittwald CM Service GmbH & Co. KG
	 *             http://www.mittwald.de
	 * @license    GNU Public License, version 2
	 *             http://opensource.org/licenses/gpl-license.php
	 *
	 */

Abstract Class Tx_MmForum_TextParser_Service_AbstractTextParserService
	Extends Tx_MmForum_Service_AbstractService {

		/**
		 * A variable container. This needs to be injected if this service is called
		 * from a view helper context.
		 * @var Tx_Fluid_Core_ViewHelper_ViewHelperVariableContainer
		 */
	Protected $viewHelperVariableContainer;



		/**
		 *
		 * Creates a new instance of this service.
		 *
		 */

	Public Function __construct() {}


	
		/**
		 *
		 * Injects the variable container.
		 * @param  Tx_Fluid_Core_ViewHelper_ViewHelperVariableContainer $viewHelperVariableContainer
		 *                             The variable container.
		 * @return void
		 *
		 */

	Public Function injectViewHelperVariableContainer(Tx_Fluid_Core_ViewHelper_ViewHelperVariableContainer $viewHelperVariableContainer) {
		$this->viewHelperVariableContainer = $viewHelperVariableContainer;
	}



		/**
		 *
		 * Renders the parsed text.
		 *
		 * @param  string $text The text to be parsed.
		 * @return string       The parsed text.
		 *
		 */

	Abstract Function getParsedText($text);

}

?>