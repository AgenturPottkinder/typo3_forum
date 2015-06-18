<?php
namespace Mittwald\Typo3Forum\ViewHelpers\Bootstrap;
/*                                                                    - *
 *  COPYRIGHT NOTICE                                                    *
 *                                                                      *
 *  (c) 2012 Martin Helmich <typo3@martin-helmich.de>                   *
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
 * @author     Martin Helmich <m.helmich@mittwald.de>
 * @package    Typo3Forum
 * @subpackage ViewHelpers_Control
 * @version    $Id: BigButtonViewHelper.php 52309 2011-09-20 18:54:26Z mhelmich $
 *
 * @copyright  2012 Martin Helmich <typo3@martin-helmich.de>
 *             http://www.martin-helmich.de
 * @license    GNU Public License, version 2
 *             http://opensource.org/licenses/gpl-license.php
 *
 */
class ButtonViewHelper extends \TYPO3\CMS\Fluid\ViewHelpers\Link\ActionViewHelper {



	public function initializeArguments() {
		parent::initializeArguments();

		$this->registerArgument('primary', 'boolean', 'Primary button', FALSE, FALSE);
		$this->registerArgument('label', 'string', 'Button label', TRUE);
		$this->registerArgument('icon', 'string', 'Icon', FALSE, NULL);
	}



	public function initialize() {
		parent::initialize();

		$class = 'btn';

		if ($this->arguments['primary'] === TRUE) {
			$class .= ' btn-primary';
		}

		$this->tag->addAttribute('class', $class);
	}



	public function renderChildren() {
		if ($this->arguments['icon']) {
			$content = '<i class="tx-typo3forum-icon-16-' . $this->arguments['icon'] . '"></i> ';
		} else {
			$content = '';
		}
		$content .= \TYPO3\CMS\Extbase\Utility\LocalizationUtility::translate($this->arguments['label'], 'typo3_forum');
		return $content;
	}



}
