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
use TYPO3\CMS\Extbase\Persistence\QueryResultInterface;
use TYPO3\CMS\Extbase\Persistence\Repository;

class RankRepository extends Repository {

	/**
	 * Find the rank of a specific user
	 *
	 * @param FrontendUser $user
	 *
	 * @return \Mittwald\Typo3Forum\Domain\Model\User\Rank[]
	 */
	public function findRankByUser(FrontendUser $user) {
		$query = $this->createQuery();
		$query->matching($query->lessThan('point_limit', $user->getPoints()));
		$query->setOrderings(['point_limit' => 'DESC']);
		$query->setLimit(1);

		return $query->execute();
	}

	/**
	 * Find the rank for a given amount of points
	 *
	 * @param int $points
	 *
	 * @deprecated
	 * @return \Mittwald\Typo3Forum\Domain\Model\User\Rank[]
	 */
	public function findRankByPoints($points) {
		$query = $this->createQuery();
		$query->matching($query->greaterThan('point_limit', (int)$points));
		$query->setOrderings(['point_limit' => 'ASC']);
		$query->setLimit(1);

		return $query->execute();
	}

	/**
	 * Find one rank for a given amount of points
	 *
	 * @param int $points
	 *
	 * @return \Mittwald\Typo3Forum\Domain\Model\User\Rank
	 */
	public function findOneRankByPoints($points) {
		$query = $this->createQuery();
		$query->matching($query->greaterThan('point_limit', (int)$points));
		$query->setOrderings(['point_limit' => 'ASC']);
		$query->setLimit(1);

		$result = $query->execute();
		if ($result instanceof QueryResultInterface) {
			return $result->getFirst();
		} elseif (is_array($result)) {
			return isset($result[0]) ? $result[0] : NULL;
		}
		return NULL;
	}

	/**
	 * Find all rankings for the ranking overview
	 *
	 * @return \Mittwald\Typo3Forum\Domain\Model\User\Rank[]
	 */
	public function findAllForRankingOverview() {
		$query = $this->createQuery();
		$query->setOrderings(['point_limit' => 'ASC']);

		return $query->execute();
	}
}
