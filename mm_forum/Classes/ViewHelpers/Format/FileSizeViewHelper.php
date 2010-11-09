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
	 * ViewHelper that formats an integer value into a file size. The unit
	 * which is to be used for the file size (B, KiB, MiB, ...) is determined
	 * automatically.
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

Class Tx_MmForum_ViewHelpers_Format_FileSizeViewHelper
	Extends Tx_Fluid_Core_ViewHelper_AbstractViewHelper {

		/**
		 * Diffentently scaled units for file sizes.
		 * @var array
		 */
	Protected $suffixes = Array(
		0 => 'B', 1 => 'KiB', 2 => 'MiB', 3 => 'GiB', 4 => 'TiB'
	);



		/**
		 *
		 * Renders the file size.
		 *
		 * @param  integer $decimals           Amount of decimal places (default 2)
		 * @param  integer $decimalSeparator   Decimal separator (default ',')
		 * @param  integer $thousandsSeparator Thousands seperator (default '.')
		 * @return  string                     The formatted file size.
		 *
		 */

	Public Function render($decimals = 2, $decimalSeparator = ',', $thousandsSeparator = '.') {
		$fileSize = $this->renderChildren(); $suffix = 0;
		While($fileSize >= 1024) { $fileSize /= 1024; $suffix ++; }
		Return number_format($fileSize, $decimals, $decimalSeparator, $thousandsSeparator).' '.$this->suffixes[$suffix];
	}

}

?>
