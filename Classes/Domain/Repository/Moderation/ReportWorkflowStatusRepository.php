<?php

/*                                                                    - *
 *  COPYRIGHT NOTICE                                                    *
 *                                                                      *
 *  (c) 2012 Martin Helmich <m.helmich@mittwald.de>                     *
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
 * Repository class for workflow status objects.
 *
 * @author     Martin Helmich <m.helmich@mittwald.de>
 * @package    Typo3Forum
 * @subpackage Domain_Repository_Moderation
 * @version    $Id$
 *
 * @copyright  2012 Martin Helmich <m.helmich@mittwald.de>
 *             Mittwald CM Service GmbH & Co. KG
 *             http://www.mittwald.de
 * @license    GNU Public License, version 2
 *             http://opensource.org/licenses/gpl-license.php
 *
 */
class Tx_Typo3Forum_Domain_Repository_Moderation_ReportWorkflowStatusRepository
	extends Tx_Typo3Forum_Domain_Repository_AbstractRepository {



	/**
	 *
	 * Finds the initial status that is to be used for new reports.
	 *
	 * @return Tx_Typo3Forum_Domain_Model_Moderation_ReportWorkflowStatus
	 *                             The initial status that is to be used for new
	 *                             reports.
	 *
	 */
	public function findInitial() {
		$query = $this->createQueryWithFallbackStoragePage();
		return $query->matching($query->equals('initial', TRUE))->setLimit(1)->execute()->getFirst();
	}



	/**
	 * @return Tx_Extbase_Persistence_QueryInterface
	 */
	public function createQuery() {
		$query = parent::createQuery();

		$storagePageIds   = $query->getQuerySettings()->getStoragePageIds();
		$storagePageIds[] = 0;

		$query->getQuerySettings()->setStoragePageIds($storagePageIds);
		return $query;
	}



}
