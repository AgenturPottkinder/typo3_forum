<?php

/*                                                                    - *
 *  COPYRIGHT NOTICE                                                    *
 *                                                                      *
 *  (c) 2012 Martin Helmich <m.helmich@mittwald.de>                     *
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
 * @copyright  2012 Martin Helmich <m.helmich@mittwald.de>
 *             Mittwald CM Service GmbH & Co. KG
 *             http://www.mittwald.de
 * @license    GNU Public License, version 2
 *             http://opensource.org/licenses/gpl-license.php
 *
 */
class Tx_MmForum_Domain_Repository_User_FrontendUserRepository
	extends Tx_Extbase_Domain_Repository_FrontendUserRepository {



	/**
	 * Finds the user that is currently logged in, or NULL if no user is logged in.
	 *
	 * @return Tx_MmForum_Domain_Model_User_FrontendUser
	 *                             The user that is currently logged in, or NULL if
	 *                             no user is logged in.
	 */
	public function findCurrent() {
		$currentUserUid = (int)$GLOBALS['TSFE']->fe_user->user['uid'];
		return $currentUserUid ? $this->findByUid($currentUserUid) : new Tx_MmForum_Domain_Model_User_AnonymousFrontendUser();
	}



	/**
	 * Returns an anonymous frontend user.
	 * @return Tx_MmForum_Domain_Model_User_AnonymousFrontendUser An anonymous frontend user.
	 */
	public function findAnonymous() {
		return new Tx_MmForum_Domain_Model_User_AnonymousFrontendUser();
	}



	/**
	 * Finds a user by his/her username. Please note that in difference to the usual
	 * findBy* methods, this method does NOT return an array of values, but instead
	 * a single user object, or NULL. This behaviour is due to the fact that
	 * usernames are supposed to be unique; consequently, in any case this method
	 * should not return more than one user.
	 *
	 * Technically, this method is just an alias for "findOneByUsername".
	 *
	 * @param  string $username                          The username.
	 * @return Tx_MmForum_Domain_Model_User_FrontendUser The frontend user with the specified username.
	 */
	public function findByUsername($username) {
		return $this->findOneByUsername($username);
	}



	/**
	 * Finds users for the user index view. Sorting and page navigation to be
	 * handled in controller/view.
	 *
	 * @return Tx_MmForum_Domain_Model_User_FrontendUser[] All users.
	 */
	public function findForIndex() {
		return $this->findAll();
	}



}
