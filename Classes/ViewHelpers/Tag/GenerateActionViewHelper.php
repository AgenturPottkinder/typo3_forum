<?php
/*                                                                      *
 *  COPYRIGHT NOTICE                                                    *
 *                                                                      *
 *  (c) 2013 Ruven Fehling <r.fehling@mittwald.de>          *
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
 * ViewHelper that renders a user's avatar.
 *
 * @author     Ruven Fehling <r.fehling@mittwald.de>
 * @package    MmForum
 * @subpackage ViewHelpers_Tag
 * @version    $Id$
 *
 * @copyright  2013 Ruven Fehling <r.fehling@mittwald.de>
 *             Mittwald CM Service GmbH & Co. KG
 *             http://www.mittwald.de
 * @license    GNU Public License, version 2
 *             http://opensource.org/licenses/gpl-license.php
 *
 */

class Tx_MmForum_ViewHelpers_Tag_GenerateActionViewHelper extends \TYPO3\CMS\Fluid\Core\ViewHelper\AbstractTagBasedViewHelper  {


	/**
	 * Arguments initialization
	 *
	 * @return void
	 */
	public function initializeArguments() {
		parent::initializeArguments();

		$this->registerTagAttribute('currentUser', 'Tx_MmForum_Domain_Model_User_FrontendUser', 'a');
		$this->registerTagAttribute('subscribedUser', 'Tx_MmForum_Domain_Model_User_FrontendUser[]', 'a');
	}

	/**
	 * Render the link for adding or removing a tag
	 *
	 * @return string
	 */
	public function render() {
		$currentUser = $this->arguments['currentUser'];
		$subscribedUser = $this->arguments['subscribedUser'];

		$new = 1;
		if(!empty($subscribedUser)) {
			foreach($subscribedUser AS $user) {
				if($currentUser == $user) {
					$new = 0;
					break;
				}
			}
		}

		return $new;
	}

}

?>