<?php
namespace Mittwald\Typo3Forum\Domain\Repository\User;

/*                                                                    - *
 *  COPYRIGHT NOTICE                                                    *
 *                                                                      *
 *  (c) 2015 Mittwald CM Service GmbH & Co KG                           *
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

use Mittwald\Typo3Forum\Domain\Model\User\FrontendUser;
use TYPO3\CMS\Extbase\Persistence\Repository;

class PrivateMessageRepository extends Repository {

	/**
	 * Find all messages between user X and user Y
	 *
	 * @param FrontendUser $userX
	 * @param FrontendUser $userY
	 * @param int $limit
	 *
	 * @return \Mittwald\Typo3Forum\Domain\Model\User\PrivateMessage[]
	 */
	public function findMessagesBetweenUser(FrontendUser $userX, FrontendUser $userY, $limit = 0) {
		$query = $this->createQuery();
		$query->matching($query->logicalAnd(
			$query->equals('type', 1),
			$query->logicalOr(
				$query->logicalAnd(
					$query->equals('feuser', $userY),
					$query->equals('opponent', $userX),
					$query->equals('feuser.disable', 0)
				),
				$query->logicalAnd(
					$query->equals('feuser', $userX),
					$query->equals('opponent', $userY),
					$query->equals('opponent.disable', 0)
				)
			)
		));
		$query->setOrderings(['crdate' => 'DESC']);
		if ($limit > 0) {
			$query->setLimit($limit);
		}

		return $query->execute();
	}

	/**
	 * Find all started conversations for user
	 *
	 * @TODO: Should be overworked when default SQL functions will be added to Extbase (group by, distinct etc)
	 *
	 * @param FrontendUser $user
	 * @param int $limit
	 *
	 * @return FrontendUser[]
	 */
	public function findStartedConversations(FrontendUser $user, $limit = 0) {
		$query = $this->createQuery();
		$constraintsX = [];
		$constraintsY = [];
		$userResult = [];
		$userInArray = [];
		$constraintsX[] = $query->equals('feuser', $user);
		$constraintsX[] = $query->equals('type', 0);
		$constraintsX[] = $query->equals('opponent.disable', 0);
		$constraintsY[] = $query->equals('feuser', $user);
		$constraintsY[] = $query->equals('type', 1);
		$constraintsY[] = $query->equals('opponent.disable', 0);
		$query->matching($query->logicalOr($query->logicalAnd($constraintsX), $query->logicalAnd($constraintsY)));
		if ($limit > 0) {
			$query->setLimit($limit);
		}
		$query->setOrderings(['crdate' => 'DESC']);
		$result = $query->execute();
		//Parse result for the user ListBox
		foreach ($result as $entry) {
			if (array_search($entry->getOpponent()->getUid(), $userInArray) === false) {
				$userInArray[] = $entry->getOpponent()->getUid();
				$userResult[] = $entry;
			}
		}

		return $userResult;
	}

	/**
	 * Find all messages this user got
	 *
	 * @param FrontendUser $user
	 * @param int $limit
	 *
	 * @return \Mittwald\Typo3Forum\Domain\Model\User\PrivateMessage[]
	 */
	public function findReceivedMessagesForUser(FrontendUser $user, $limit = 0) {
		$query = $this->createQuery();
		$constraints = [];
		$constraints[] = $query->equals('opponent', $user);
		$constraints[] = $query->equals('type', 1);
		$constraints[] = $query->equals('feuser.disable', 0);
		$query->matching($query->logicalAnd($constraints));
		$query->setOrderings(['crdate' => 'DESC']);
		if ($limit > 0) {
			$query->setLimit($limit);
		}

		return $query->execute();
	}

}
