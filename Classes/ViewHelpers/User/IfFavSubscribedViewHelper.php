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
 * ViewHelper that renders its contents, when a certain user has subscribed
 * a specific object.
 *
 * @author     Martin Helmich <m.helmich@mittwald.de>
 * @package    MmForum
 * @subpackage ViewHelpers_User
 * @version    $Id$
 *
 * @copyright  2010 Martin Helmich <m.helmich@mittwald.de>
 *             Mittwald CM Service GmbH & Co. KG
 *             http://www.mittwald.de
 * @license    GNU Public License, version 2
 *             http://opensource.org/licenses/gpl-license.php
 *
 */

class Tx_MmForum_ViewHelpers_User_IfFavSubscribedViewHelper extends \TYPO3\CMS\Fluid\ViewHelpers\IfViewHelper {



	/**
	 *
	 * Renders the contents of this view helper, when a user has subscribed a
	 * specific subscribeable object.
	 *
	 * @param Tx_MmForum_Domain_Model_SubscribeableInterface $object
	 *                             The object that needs to be subscribed in order
	 *                             for the contents to be rendered.
	 * @param Tx_MmForum_Domain_Model_User_FrontendUser      $user
	 * @return string
	 *
	 */

	public function render(Tx_MmForum_Domain_Model_SubscribeableInterface $object,
	                       Tx_MmForum_Domain_Model_User_FrontendUser      $user = NULL) {
		if ($user === NULL) {
			$user =& \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('Tx_MmForum_Domain_Repository_User_FrontendUserRepository')->findCurrent();
		}
		\TYPO3\CMS\Extbase\Utility\DebuggerUtility::var_dump($object);
		foreach ($object->getFavSubscribers() As $subscriber) {
			if ($subscriber->getUid() == $user->getUid()) {
				return $this->renderThenChild();
			}
		}
		return $this->renderElseChild();
	}

}

?>
