<?php
namespace Mittwald\Typo3Forum\ViewHelpers\Moderation;
/*                                                                    - *
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

use TYPO3\CMS\Fluid\ViewHelpers\CObjectViewHelper;

class ReportIconViewHelper extends CObjectViewHelper {

	/**
	 * Initializes the view helper arguments.
	 * @return void
	 */
	public function initializeArguments() {

	}

	/**
	 *
	 * Renders the report icon.
	 *
	 * @param \Mittwald\Typo3Forum\Domain\Model\Moderation\Report $report
	 *                                                               The report for which the icon is to be rendered.
	 * @param integer                                   $width      Image width
	 * @param string                                    $alt        Alt text
	 * @return string             The rendered icon.
	 *
	 */
	public function render(\Mittwald\Typo3Forum\Domain\Model\Moderation\Report $report = NULL, $width = NULL, $alt = "") {
		return parent::render('plugin.tx_typo3forum.renderer.icons.report', $this->getDataArray($report));
	}

	/**
	 *
	 * Generates a data array that will be passed to the typoscript object for
	 * rendering the icon.
	 * @param \Mittwald\Typo3Forum\Domain\Model\Moderation\Report $report
	 *                             The report for which the icon is to be displayed.
	 * @return array               The data array for the typoscript object.
	 *
	 */
	protected function getDataArray(\Mittwald\Typo3Forum\Domain\Model\Moderation\Report $report = NULL) {
		if ($report === NULL) {
			return [];
		} else {
			return ['statusIcon' => $report->getWorkflowStatus()->getIconFullpath()];
		}
	}
}
