<?php
namespace Mittwald\MmForum\ViewHelpers\Control;


/*                                                                    - *
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
 * ViewHelper that renders a big button.
 *
 * @author     Martin Helmich <m.helmich@mittwald.de>
 * @package    MmForum
 * @subpackage ViewHelpers_Control
 * @version    $Id$
 *
 * @copyright  2010 Martin Helmich <m.helmich@mittwald.de>
 *             Mittwald CM Service GmbH & Co. KG
 *             http://www.mittwald.de
 * @license    GNU Public License, version 2
 *             http://opensource.org/licenses/gpl-license.php
 *
 */
class BigButtonViewHelper extends \TYPO3\CMS\Fluid\ViewHelpers\Link\ActionViewHelper {



	protected $iconBaseClass = 'tx-mmforum-icon-32-';



	public function initializeArguments() {
		parent::initializeArguments();

		$this->registerArgument('iconAction', 'string', 'Deprecated!');
		$this->registerArgument('icon', 'string', 'Icon name');
		$this->registerArgument('iconClass', 'string', 'Classname for the icon');
	}



	public function initialize() {
		parent::initialize();
		$this->tag->addAttribute('class', 'tx-mmforum-button-big');
	}



	public function renderChildren() {
		$content   = parent::renderChildren();
		$iconClass = NULL;

		if ($this->arguments['iconClass']) {
			$iconClass = $this->arguments['iconClass'];
		} elseif ($this->arguments['icon']) {
			$iconClass = $this->iconBaseClass . $this->arguments['icon'];
		} elseif ($this->arguments['iconAction']) {
			$iconClass = $this->iconBaseClass . $this->arguments['iconAction'];
		} elseif ($this->arguments['action']) {
			$iconClass = $this->iconBaseClass . $this->arguments['action'];
		}

		$this->tag->addAttribute('title', $content);

		if ($iconClass) {
			$content = '<div class="' . $iconClass . '"></div><div class="tx-mmforum-button-text">' . $content . '</div>';
		}
		return $content;
	}



}

?>
