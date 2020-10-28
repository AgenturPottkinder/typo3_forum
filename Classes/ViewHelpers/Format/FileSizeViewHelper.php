<?php
namespace Mittwald\Typo3Forum\ViewHelpers\Format;
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

use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper;

/**
 * ViewHelper that formats an integer value into a file size. The unit
 * which is to be used for the file size (B, KiB, MiB, ...) is determined
 * automatically.
 */
class FileSizeViewHelper extends AbstractViewHelper {

	/**
	 * Diffentently scaled units for file sizes.
	 * @var array
	 */
	protected $suffixes = [0 => 'B',
	                            1 => 'KiB',
	                            2 => 'MiB',
	                            3 => 'GiB',
	                            4 => 'TiB'];

	/**
	 * Renders the file size.
	 *
	 * @param int    $decimals
	 * @param string $decimalSeparator
	 * @param string $thousandsSeparator
	 *
	 * @return string
	 */
	public function render($decimals = 2, $decimalSeparator = ',', $thousandsSeparator = '.') {
		$fileSize = $this->renderChildren();
		$suffix   = 0;
		while ($fileSize >= 1024) {
			$fileSize /= 1024;
			$suffix++;
		}
		return number_format($fileSize, $decimals, $decimalSeparator,
		                     $thousandsSeparator) . ' ' . $this->suffixes[$suffix];
	}
}
