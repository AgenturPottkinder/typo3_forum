<?php
/*                                                                    - *
 *  COPYRIGHT NOTICE                                                    *
 *                                                                      *
 *  (c) 2012 Ruven Fehling <r.fehling@mittwald.de>                     *
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
 * @subpackage Domain_Repository_Forum
 * @version    $Id$
 *
 * @copyright  2013 Ruven Fehling <r.fehling@mittwald.de>
 *             Mittwald CM Service GmbH & Co. KG
 *             http://www.mittwald.de
 * @license    GNU Public License, version 2
 *             http://opensource.org/licenses/gpl-license.php
 *
 */
class Tx_MmForum_Domain_Repository_Forum_AdsRepository extends \TYPO3\CMS\Extbase\Persistence\Repository {

	/**
	 * Find all advertisements for the forum view (random sort)
	 * @param int $limit How many results should come back
	 * @return Tx_MmForum_Domain_Model_Forum_Ads[]
	 */
	public function findForForumView($limit = 0) {
		return $this->findAdsByCategories(array(0, 1), $limit);
	}


	/**
	 * Find all advertisements for the topic view (random sort)
	 * @param int $limit How many results should come back
	 * @return Tx_MmForum_Domain_Model_Forum_Ads[]
	 */
	public function findForTopicView($limit = 0) {
		return $this->findAdsByCategories(array(0, 2), $limit);
	}


	/**
	 * Find all advertisements of a specific category
	 * @TODO: If extbase 6.3 is released, use ORDER BY RAND()
	 *
	 * @param array $categories Which categories should be shown? (0=all,1=forum,2=topic)
	 * @param int How many results should come back
	 * @return Tx_MmForum_Domain_Model_Forum_Ads[]
	 */
	private function findAdsByCategories(array $categories = array(), $limit = 0) {
		if(empty($categories)) $categories = array(0);

		$query = $this->createQuery();
		$constraints = array($query->in('category', $categories),
			$query->equals('active', 1));

		//work around for ORDER BY RAND() due to bug: http://forge.typo3.org/issues/14026
		$count = $this->countAdsByConstraint($constraints);
		$rows = mt_rand(0, max(0, ($count - 1))) - $limit;
		if ($rows < 0) $rows = 0;

		$query->matching($query->logicalAnd($constraints))->setOffset($rows);
		if ($limit > 0) {
			$query->setLimit(intval($limit));
		}
		return $query->execute();
	}

	/**
	 * Count all Ads
	 * @param $constraints
	 * @return int The amount of ads
	 *
	 * @deprecated: will be removed when extbase 6.3 is released
	 */
	private function countAdsByConstraint($constraints) {
		$query = $this->createQuery();
		return $query->matching($query->logicalAnd($constraints))->execute()->count();
	}

}