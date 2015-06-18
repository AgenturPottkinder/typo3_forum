<?php
namespace Mittwald\Typo3Forum\ViewHelpers\Format;
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
 * @package    Typo3Forum
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

class FileSizeViewHelper extends \TYPO3\CMS\Fluid\Core\ViewHelper\AbstractViewHelper {



	/**
	 * Diffentently scaled units for file sizes.
	 * @var array
	 */
	protected $suffixes = array(0 => 'B',
	                            1 => 'KiB',
	                            2 => 'MiB',
	                            3 => 'GiB',
	                            4 => 'TiB');



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

?>
