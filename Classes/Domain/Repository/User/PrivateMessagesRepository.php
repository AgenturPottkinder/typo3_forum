<?php
/*                                                                    - *
 *  COPYRIGHT NOTICE                                                    *
 *                                                                      *
 *  (c) 2013 Ruven Fehling <r.fehling@mittwald.de>                     *
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
 * Repository class for forum objects.
 *
 * @author     Ruven Fehling <r.fehling@mittwald.de>
 * @package    MmForum
 * @subpackage Domain_Repository_User
 * @version    $Id$
 *
 * @copyright  2013 Ruven Fehling <r.fehling@mittwald.de>
 *             Mittwald CM Service GmbH & Co. KG
 *             http://www.mittwald.de
 * @license    GNU Public License, version 2
 *             http://opensource.org/licenses/gpl-license.php
 *
 */
class Tx_MmForum_Domain_Repository_User_PrivateMessagesRepository extends \TYPO3\CMS\Extbase\Persistence\Repository {


	/**
	 * Find all messages between user X and user Y
	 *
	 * @param Tx_MmForum_Domain_Model_User_FrontendUser $userX
	 * @param Tx_MmForum_Domain_Model_User_FrontendUser $userY
	 * @param int $limit
	 * @return Tx_MmForum_Domain_Model_User_PrivateMessages[]
	 */
	public function findMessagesBetweenUser(Tx_MmForum_Domain_Model_User_FrontendUser $userX,
											Tx_MmForum_Domain_Model_User_FrontendUser $userY, $limit=0) {
		$query = $this->createQuery();
		$constraintsX = array();
		$constraintsY = array();
		$constraintsX[] = $query->equals('feuser',$userX);
		$constraintsX[] = $query->equals('opponent',$userY);
		$constraintsX[] = $query->equals('type',1);
		$constraintsY[] = $query->equals('feuser',$userY);
		$constraintsY[] = $query->equals('opponent',$userX);
		$constraintsY[] = $query->equals('type',0);
		$query->matching($query->logicalOr($query->logicalAnd($constraintsX),$query->logicalAnd($constraintsY)));
		$query->setOrderings(array('tstamp'=> 'DESC'));
		if($limit > 0) {
			$query->setLimit($limit);
		}
		return $query->execute();
	}


	/**
	 * Find all started conversations for user
	 *
	 * @TODO: Should be overworked when default SQL functions will be added to Extbase (group by, distinct etc)
	 * @param Tx_MmForum_Domain_Model_User_FrontendUser $user
	 * @param int $limit
	 * @return Tx_MmForum_Domain_Model_User_FrontendUser[]
	 */
	public function findStartedConversations(Tx_MmForum_Domain_Model_User_FrontendUser $user, $limit=0) {
		$query = $this->createQuery();
		$constraintsX = array();
		$constraintsY = array();
		$userResult = array();
		$userInArray = array();
		$constraintsX[] = $query->equals('feuser',$user);
		$constraintsX[] = $query->equals('type',0);
		$constraintsY[] = $query->equals('opponent',$user);
		$constraintsY[] = $query->equals('type',1);
		$query->matching($query->logicalOr($query->logicalAnd($constraintsX),$query->logicalAnd($constraintsY)));
		if($limit > 0) {
			$query->setLimit($limit);
		}
		$result = $query->execute();
		//Parse result for the user ListBox
		foreach($result AS $entry) {
			if($entry->getFeuser() == $user) continue;
			if(array_search($entry->getFeuser(),$userInArray) === false) {
				$userInArray[] = $entry->getFeuser();
				$userResult[] = $entry;
			}
		}
		return $userResult;
	}




	/**
	 * Find all messages this user got
	 * @param Tx_MmForum_Domain_Model_User_FrontendUser $user
	 * @param int $limit
	 * @return Tx_MmForum_Domain_Model_User_PrivateMessages[]
	 */
	public function findReceivedMessagesForUser(Tx_MmForum_Domain_Model_User_FrontendUser $user, $limit=0) {
		$query = $this->createQuery();
		$constraints = array();
		$constraints[] = $query->equals('feuser',$user);
		$constraints[] = $query->equals('type',1);
		$query->matching($query->logicalAnd($constraints));
		$query->setOrderings(array('tstamp'=> 'DESC'));
		if($limit > 0) {
			$query->setLimit($limit);
		}
		return $query->execute();
	}

}