<?php
namespace Mittwald\Typo3Forum\Utility;

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

use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;

/**
 * Utility module that contains file system-related functions.
 */
class File {

	/**
	 * Replaces extension path references (EXT:...) inside path with the actual paths
	 * relative to the site root.
	 *
	 * @param string $string The path that is to be parsed
	 * @return string         The parsed path.
	 */
	public static function replaceSiteRelPath($string) {
		return preg_replace_callback(',EXT:([0-9a-z_-]+)/,', function($matches) {
			return ExtensionManagementUtility::siteRelPath($matches[1]);
		}, $string);
	}

}

?>
