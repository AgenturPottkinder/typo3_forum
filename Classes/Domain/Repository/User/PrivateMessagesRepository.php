<?php
namespace Mittwald\MmForum\Domain\Repository\User;

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
class PrivateMessagesRepository extends \TYPO3\CMS\Extbase\Persistence\Repository {


	/**
	 * Find all messages between user X and user Y
	 *
	 * @param \Mittwald\MmForum\Domain\Model\User\FrontendUser $userX
	 * @param \Mittwald\MmForum\Domain\Model\User\FrontendUser $userY
	 * @param int $limit
	 * @return \Mittwald\MmForum\Domain\Model\User\PrivateMessages[]
	 */
	public function findMessagesBetweenUser(\Mittwald\MmForum\Domain\Model\User\FrontendUser $userX,
											\Mittwald\MmForum\Domain\Model\User\FrontendUser $userY, $limit=0) {
		$query = $this->createQuery();
		$constraintsX = array();
		$constraintsY = array();
		$constraintsX[] = $query->equals('feuser',$userY);
		$constraintsX[] = $query->equals('opponent',$userX);
		$constraintsX[] = $query->equals('type',1);
		$constraintsX[] = $query->equals('feuser.disable',0);
		$constraintsY[] = $query->equals('feuser',$userX);
		$constraintsY[] = $query->equals('opponent',$userY);
		$constraintsY[] = $query->equals('type',1);
		$constraintsY[] = $query->equals('opponent.disable',0);
		$query->matching($query->logicalOr($query->logicalAnd($constraintsX),$query->logicalAnd($constraintsY)));
		$query->setOrderings(array('crdate'=> 'DESC'));
		if($limit > 0) {
			$query->setLimit($limit);
		}
		return $query->execute();
	}


	/**
	 * Find all started conversations for user
	 *
	 * @TODO: Should be overworked when default SQL functions will be added to Extbase (group by, distinct etc)
	 * @param \Mittwald\MmForum\Domain\Model\User\FrontendUser $user
	 * @param int $limit
	 * @return \Mittwald\MmForum\Domain\Model\User\FrontendUser[]
	 */
	public function findStartedConversations(\Mittwald\MmForum\Domain\Model\User\FrontendUser $user, $limit=0) {
		$query = $this->createQuery();
		$constraintsX = array();
		$constraintsY = array();
		$userResult = array();
		$userInArray = array();
		$constraintsX[] = $query->equals('feuser',$user);
		$constraintsX[] = $query->equals('type',0);
		$constraintsX[] = $query->equals('opponent.disable',0);
		$constraintsY[] = $query->equals('feuser',$user);
		$constraintsY[] = $query->equals('type',1);
		$constraintsY[] = $query->equals('opponent.disable',0);
		$query->matching($query->logicalOr($query->logicalAnd($constraintsX),$query->logicalAnd($constraintsY)));
		if($limit > 0) {
			$query->setLimit($limit);
		}
		$query->setOrderings(array('crdate' => 'DESC'));
		$result = $query->execute();
		//Parse result for the user ListBox
		foreach($result AS $entry) {
			if(array_search($entry->getOpponent()->getUid(),$userInArray) === false) {
				$userInArray[] = $entry->getOpponent()->getUid();
				$userResult[] = $entry;
			}
		}
		return $userResult;
	}




	/**
	 * Find all messages this user got
	 * @param \Mittwald\MmForum\Domain\Model\User\FrontendUser $user
	 * @param int $limit
	 * @return \Mittwald\MmForum\Domain\Model\User\PrivateMessages[]
	 */
	public function findReceivedMessagesForUser(\Mittwald\MmForum\Domain\Model\User\FrontendUser $user, $limit=0) {
		$query = $this->createQuery();
		$constraints = array();
		$constraints[] = $query->equals('opponent',$user);
		$constraints[] = $query->equals('type',1);
		$constraints[] = $query->equals('feuser.disable',0);
		$query->matching($query->logicalAnd($constraints));
		$query->setOrderings(array('crdate'=> 'DESC'));
		if($limit > 0) {
			$query->setLimit($limit);
		}
		return $query->execute();
	}

}