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
class Tx_MmForum_Domain_Repository_User_RankRepository extends \TYPO3\CMS\Extbase\Persistence\Repository {



	/**
	 * Find the rank of a specific user
	 * @param Tx_MmForum_Domain_Model_User_FrontendUser $user
	 * @return Tx_MmForum_Domain_Model_User_Rank[]
	 */
	public function findRankByUser(Tx_MmForum_Domain_Model_User_FrontendUser $user) {
		$query = $this->createQuery();
		$query->matching($query->lessThan('point_limit',$user->getPoints()));
		$query->setOrderings(array('point_limit' => 'DESC'));
		$query->setLimit(1);
		return $query->execute();
	}


	/**
	 * Find the rank for a given amount of points
	 * @param int $points
	 * @return Tx_MmForum_Domain_Model_User_Rank[]
	 */
	public function findRankByPoints($points) {
		$query = $this->createQuery();
		$query->matching($query->greaterThan('point_limit',intval($points)));
		$query->setOrderings(array('point_limit' => 'ASC'));
		$query->setLimit(1);
		return $query->execute();
	}

}