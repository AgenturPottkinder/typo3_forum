<?php

/*                                                                    - *
 *  COPYRIGHT NOTICE                                                    *
 *                                                                      *
 *  (c) 2013 Ruven Fehling <r.fehling@mittwald.de>                  *
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
 * ViewHelper that renders a big button.
 *
 * @author     Ruven Fehling <r.fehling@mittwald.de>
 * @package    MmForum
 * @subpackage ViewHelpers_General
 *
 * @copyright  2013 Ruven Fehling <r.fehling@mittwald.de>
 *             http://www.mittwald.de
 * @license    GNU Public License, version 2
 *             http://opensource.org/licenses/gpl-license.php
 *
 */
class Tx_MmForum_ViewHelpers_General_QueryResultReverseViewHelper extends \TYPO3\CMS\Fluid\ViewHelpers\Link\ActionViewHelper {


	public function initializeArguments() {
		parent::initializeArguments();

		$this->registerArgument('array', 'array', 'Array to be reverted', TRUE, array());
	}


	public function render() {
		$oldArray = $this->arguments['array'];
		$newArray = array();
		if($oldArray == false) return array();
		for($j = count($oldArray)-1; $j >= 0; $j--) {
			$newArray[] = $oldArray[$j];
		}
		return $newArray;
	}
}
