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
	 * Utility module for TypoScript related functions.
	 *
	 * @author     Martin Helmich <m.helmich@mittwald.de>
	 * @package    MmForum
	 * @subpackage Utility
	 * @version    $Id$
	 *
	 * @copyright  2010 Martin Helmich <m.helmich@mittwald.de>
	 *             Mittwald CM Service GmbH & Co. KG
	 *             http://www.mittwald.de
	 * @license    GNU Public License, version 2
	 *             http://opensource.org/licenses/gpl-license.php
	 *
	 */

Class Tx_MmForum_Utility_TypoScript {



		/**
		 *
		 * Loads the typoscript configuration from a certain setup path.
		 *
		 * @param  string $configurationPath The typoscript path
		 * @return  array                    The typoscript configuration for the
		 *                                   specified path.
		 *
		 */

	Public Static Function loadTyposcriptFromPath($configurationPath) {
		$configurationManager = Tx_Extbase_Dispatcher::getConfigurationManager();
		$setup = $configurationManager->loadTypoScriptSetup();

		$pathSegments = t3lib_div::trimExplode('.', $configurationPath);

		$lastSegment = array_pop($pathSegments);
		ForEach ($pathSegments As $segment) {
			If (!array_key_exists($segment . '.', $setup))
				Throw New Tx_MmForum_Domain_Exception_TextParser_Exception (
					'TypoScript object path "' . htmlspecialchars($configurationPath) . '" does not exist' , 1253191023);
			$setup = $setup[$segment . '.'];
		} Return $setup[$lastSegment.'.'];
	}

}

?>