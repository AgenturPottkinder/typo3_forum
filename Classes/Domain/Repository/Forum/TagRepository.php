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
 * @package    Typo3Forum
 * @subpackage Domain_Repository_Forum
 * @version    $Id$
 *
 * @copyright  2012 Ruven Fehling <r.fehling@mittwald.de>
 *             Mittwald CM Service GmbH & Co. KG
 *             http://www.mittwald.de
 * @license    GNU Public License, version 2
 *             http://opensource.org/licenses/gpl-license.php
 *
 */
class Tx_Typo3Forum_Domain_Repository_Forum_TagRepository extends \TYPO3\CMS\Extbase\Persistence\Repository {



	/**
	 * Find all ordered by topic count
	 * @return Tx_Typo3Forum_Domain_Model_Forum_Tag[]
	 */
	public function findAllOrderedByCounter() {
		$query = $this->createQuery();
		$query->setOrderings(array('topic_count' => 'DESC'));
		return $query->execute();
	}


	/**
	 * Find a tag with a specific name
	 * @param $name
	 * @return Tx_Typo3Forum_Domain_Model_Forum_Tag[]
	 */
	public function findTagWithSpecificName($name) {
		$query = $this->createQuery();
		$query->matching($query->equals('name',$name));
		$query->setLimit(1);
		return $query->execute();
	}


	/**
	 * Find a tag including a specific name
	 * @param string $name
	 * @return Tx_Typo3Forum_Domain_Model_Forum_Tag[]
	 */
	public function findTagLikeAName($name) {
		$query = $this->createQuery();
		$query->getQuerySettings()->setRespectStoragePage(FALSE);
		$pids = $query->getQuerySettings()->getStoragePageIds();
		$pid = intval($pids[0]);
		$constraints = array();
		$constraints[] = $query->like('name',"%".$name."%",false);
		$constraints[] = $query->equals('pid',$pid);

		$query->matching($query->logicalAnd($constraints));
		return $query->execute();
	}

	/**
	 * Find all tags of a specific user
	 * @param Tx_Typo3Forum_Domain_Model_User_FrontendUser $user
	 * @return Tx_Typo3Forum_Domain_Model_Forum_Tag[]
	 */
	public function findTagsOfUser(Tx_Typo3Forum_Domain_Model_User_FrontendUser $user) {
		$query = $this->createQuery();
		$query->matching($query->equals('feuser.uid',$user));
		$query->setOrderings(array('name' => 'ASC'));
		return $query->execute();
	}

}