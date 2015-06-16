<?php
namespace Mittwald\Typo3Forum\Domain\Repository\Forum;
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
 * @package    Typo3Forum
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
class AdsRepository extends \TYPO3\CMS\Extbase\Persistence\Repository {

	/**
	 * Find all advertisements for the forum view (random sort)
	 * @param int $limit How many results should come back
	 * @return \Mittwald\Typo3Forum\Domain\Model\Forum\Ads[]
	 */
	public function findForForumView($limit = 0) {
		return $this->findAdsByCategories(array(0, 1), $limit);
	}


	/**
	 * Find all advertisements for the topic view (random sort)
	 * @param int $limit How many results should come back
	 * @return \Mittwald\Typo3Forum\Domain\Model\Forum\Ads[]
	 */
	public function findForTopicView($limit = 0) {
		return $this->findAdsByCategories(array(0, 2), $limit);
	}


	/**
	 * Find all advertisements of a specific category
	 * @TODO: If extbase 6.3 is released, don't use a sql statement -> http://forge.typo3.org/issues/14026
	 *
	 * @param array $categories Which categories should be shown? (0=all,1=forum,2=topic)
	 * @param int How many results should come back
	 * @return \Mittwald\Typo3Forum\Domain\Model\Forum\Ads[]
	 */
	private function findAdsByCategories(array $categories = array(), $limit = 1) {
		if(empty($categories)) $categories = array(0);

		if ($limit < 1) {
			$limit = 1;
		}

		$sql ='SELECT * FROM tx_typo3forum_domain_model_forum_ads
			   WHERE category IN ('.implode(',',$categories).') AND active=1
			   ORDER BY RAND()
			   LIMIT '.$limit;

		$query = $this->createQuery();
		$query->getQuerySettings()->setReturnRawQueryResult(TRUE);
		$query->statement($sql);
		return $query->execute();

	}



}