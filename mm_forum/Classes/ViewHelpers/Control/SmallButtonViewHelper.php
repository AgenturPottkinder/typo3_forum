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
	 * ViewHelper that renders a small button.
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

Class Tx_MmForum_ViewHelpers_Control_SmallButtonViewHelper
	Extends Tx_Fluid_Core_ViewHelper_AbstractViewHelper {



		/**
		 *
		 * Renders the small button.
		 *
		 * @param  string $controller  Target controller
		 * @param  string $action      Target action
		 * @param   array $arguments   Additional link action
		 * @param  string $iconAction  Optional different action name for use in the icon
		 *                             filename.
		 * @return                     The HTML code of the big button.
		 *
		 */

	Public Function render($controller, $action, $arguments = Array(), $iconAction=NULL) {
		$arguments = Array ( 'controller'  => $controller,
		                     'action'      => $action,
		                     'arguments'   => $arguments,
		                     'buttonLabel' => $this->renderChildren(),
		                     'iconAction'  => $iconAction ? $iconAction : $action,
		                     'imgPath'     => t3lib_extMgm::siteRelPath('mm_forum').'Resources/Public/Images' );
		Return $this->viewHelperVariableContainer->getView()->renderPartial(
			'Control/SmallButton', '', $arguments, $this->viewHelperVariableContainer);
	}

}

?>
