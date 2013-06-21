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
	public function findForForumView($limit=1) {
		$query = $this->createQuery();
		$constraints = array($query->in('category', array(0, 1)),
			$query->equals('active', 1));
		$query->matching($query->logicalAnd($constraints))
			->setLimit(intval($limit))
			->setOrderings(array('RAND()' => \TYPO3\CMS\Extbase\Persistence\Generic\Query::ORDER_ASCENDING));
		return $query->execute();
	}


	/**
	 * Find all advertisements for the topic view (random sort)
	 * @param int $limit How many results should come back
	 * @return Tx_MmForum_Domain_Model_Forum_Ads[]
	 */
	public function findForTopicView($limit=1) {
		$query = $this->createQuery();
		$constraints = array($query->in('category', array(0, 2)),
			$query->equals('active', 1));
		$query->matching($query->logicalAnd($constraints))
			->setLimit(intval($limit))
			->setOrderings(array('RAND()' => \TYPO3\CMS\Extbase\Persistence\Generic\Query::ORDER_ASCENDING));
		return $query->execute();
	}

}