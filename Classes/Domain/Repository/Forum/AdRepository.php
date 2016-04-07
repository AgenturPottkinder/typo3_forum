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

use TYPO3\CMS\Extbase\Persistence\Generic\Query;
use TYPO3\CMS\Extbase\Persistence\Repository;

class AdRepository extends Repository {

	/**
	 * Find all advertisements for the forum view (random sort)
	 *
	 * @param int $limit How many results should come back
	 *
	 * @return \Mittwald\Typo3Forum\Domain\Model\Forum\Ad[]
	 */
	public function findForForumView($limit = 0) {
		return $this->findAdsByCategories([0, 1], $limit);
	}

	/**
	 * Find all advertisements of a specific category
	 * @TODO: RAND() will be available in extbase with 7.4 -> http://forge.typo3.org/issues/14026
	 *
	 * @param array $categories Which categories should be shown? (0=all,1=forum,2=topic)
	 * @param int $limit How many results should come back
	 *
	 * @return \Mittwald\Typo3Forum\Domain\Model\Forum\Ad[]
	 */
	private function findAdsByCategories(array $categories = [], $limit = 1) {
		if (empty($categories))
			$categories = [0];

		if ($limit < 1) {
			$limit = 1;
		}

		$sql = 'SELECT * FROM tx_typo3forum_domain_model_forum_ad
			   WHERE category IN (' . implode(',', $categories) . ') AND active=1
			   ORDER BY RAND()
			   LIMIT ' . $limit;

		/** @var Query $query */
		$query = $this->createQuery();
		$query->getQuerySettings()->setReturnRawQueryResult(TRUE);
		$query->statement($sql);

		return $query->execute();
	}

	/**
	 * Find all advertisements for the topic view (random sort)
	 *
	 * @param int $limit How many results should come back
	 *
	 * @return \Mittwald\Typo3Forum\Domain\Model\Forum\Ad[]
	 */
	public function findForTopicView($limit = 0) {
		return $this->findAdsByCategories([0, 2], $limit);
	}


}
