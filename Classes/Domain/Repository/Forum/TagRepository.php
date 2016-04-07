<?php
namespace Mittwald\Typo3Forum\Domain\Repository\Forum;

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

class TagRepository extends Repository {


	/**
	 * Find all ordered by topic count
	 * @return \Mittwald\Typo3Forum\Domain\Model\Forum\Tag[]
	 */
	public function findAllOrderedByCounter() {
		$query = $this->createQuery();
		$query->setOrderings(['topic_count' => 'DESC']);

		return $query->execute();
	}


	/**
	 * Find a tag with a specific name
	 *
	 * @param $name
	 *
	 * @return \Mittwald\Typo3Forum\Domain\Model\Forum\Tag[]
	 */
	public function findTagWithSpecificName($name) {
		$query = $this->createQuery();
		$query->matching($query->equals('name', $name));
		$query->setLimit(1);

		return $query->execute();
	}


	/**
	 * Find a tag including a specific name
	 *
	 * @param string $name
	 *
	 * @return \Mittwald\Typo3Forum\Domain\Model\Forum\Tag[]
	 */
	public function findTagLikeAName($name) {
		$query = $this->createQuery();
		$query->getQuerySettings()->setRespectStoragePage(FALSE);
		$pids = $query->getQuerySettings()->getStoragePageIds();
		$pid = (int)$pids[0];
		$constraints = [];
		$constraints[] = $query->like('name', "%" . $name . "%", false);
		$constraints[] = $query->equals('pid', $pid);

		$query->matching($query->logicalAnd($constraints));

		return $query->execute();
	}

	/**
	 * Find all tags of a specific user
	 *
	 * @param FrontendUser $user
	 *
	 * @return \Mittwald\Typo3Forum\Domain\Model\Forum\Tag[]
	 */
	public function findTagsOfUser(FrontendUser $user) {
		$query = $this->createQuery();
		$query->matching($query->equals('feuser.uid', $user));
		$query->setOrderings(['name' => 'ASC']);

		return $query->execute();
	}

}
