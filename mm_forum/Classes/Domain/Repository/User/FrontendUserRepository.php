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
	 * Repository class for frontend suers.
	 *
	 * @author     Martin Helmich <m.helmich@mittwald.de>
	 * @package    MmForum
	 * @subpackage Domain_Repository_User
	 * @version    $Id$
	 *
	 * @copyright  2010 Martin Helmich <m.helmich@mittwald.de>
	 *             Mittwald CM Service GmbH & Co. KG
	 *             http://www.mittwald.de
	 * @license    GNU Public License, version 2
	 *             http://opensource.org/licenses/gpl-license.php
	 *
	 */

Class Tx_MmForum_Domain_Repository_User_FrontendUserRepository
	Extends Tx_Extbase_Domain_Repository_FrontendUserRepository {



		/**
		 *
		 * Finds the user that is currently logged in, or NULL if no user is logged in.
		 *
		 * @return Tx_MmForum_Domain_Model_User_FrontendUser
		 *                             The user that is currently logged in, or NULL if
		 *                             no user is logged in.
		 *
		 */

	Public Function findCurrent() {
		$currentUserUid = $GLOBALS['TSFE']->fe_user->user['uid'];
		Return $currentUserUid ? $this->findByUid($currentUserUid) : NULL;
	}



		/**
		 *
		 * Finds a user by his/her username. Please note that in difference to the usual
		 * findBy* methods, this method does NOT return an array of values, but instead
		 * a single user object, or NULL. This behaviour is due to the fact that
		 * usernames are supposed to be unique; consequently, in any case this method
		 * should not return more than one user.
		 *
		 * @param  string $username    The username.
		 * @return Tx_MmForum_Domain_Model_User_FrontendUser
		 *                             The frontend user with the specified username.
		 *
		 */

	Public Function findByUsername($username) {
		$users = parent::findByUsername($username);
		If(count($users) == 0) Return NULL;
		Else Return $users[0];
	}



		/**
		 *
		 * Finds users for the user index view. Sorting and page navigation is possible.
		 *
		 * @param  integer $usersPerPage The number of users to be displayed on one page.
		 * @param  integer $page         The current page.
		 * @param  string  $orderBy      The name of the property that is to be used for
		 *                               ordering the users.
		 * @param  string  $orderMode    Ordering mode. May either be ascending or
		 *                               descending.
		 * @return Array<Tx_MmForum_Domain_Model_User_FrontendUser>
		 *                               The selected subset of users.
		 *
		 */

	Public Function findForIndex($usersPerPage=30, $page=1, $orderBy='username', $orderMode=Tx_Extbase_Persistence_Query::ORDER_ASCENDING) {
		$query = $this->createQuery();
		Return $query->setOrderings(Array($orderBy => $orderMode))
			->setLimit($usersPerPage)
			->setOffset(($page-1)*$usersPerPage)
			->execute();
	}

}

?>