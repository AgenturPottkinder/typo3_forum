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
	 * Default panel for rendering all kinds of editor buttons.
	 *
	 * @author     Martin Helmich <m.helmich@mittwald.de>
	 * @package    MmForum
	 * @subpackage TextParser_Panel
	 * @version    $Id$
	 *
	 * @copyright  2010 Martin Helmich <m.helmich@mittwald.de>
	 *             Mittwald CM Service GmbH & Co. KG
	 *             http://www.mittwald.de
	 * @license    GNU Public License, version 2
	 *             http://opensource.org/licenses/gpl-license.php
	 *
	 */

Abstract Class Tx_MmForum_TextParser_Panel_DefaultPanel
	Extends Tx_MmForum_TextParser_Panel_AbstractPanel {



		/**
		 *
		 * Renders the panel.
		 *
		 * @return string The rendered panel.
		 * @todo   Make the output of this function configurable somehow.
		 *
		 */

	Public Function render() {
		$items = $this->getItems();

		$content  = '<div class="tx-mmforum-panel">';
		$content .= '<div class="tx-mmforum-panel-title">'.htmlspecialchars($this->getTitle()).'</div> ';
		$content .= '<div class="tx-mmforum-panel-items">';

		ForEach($items As $item)
			$content .= $this->buildItemButton($item);

		$content .= '</div></div>';

		Return $content;
	}

}

?>