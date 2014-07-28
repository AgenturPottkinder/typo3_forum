<?php
namespace Mittwald\MmForum\Domain\Repository\Stats;

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
 * @subpackage Domain_Repository_Stats
 * @version    $Id$
 *
 * @copyright  2013 Ruven Fehling <r.fehling@mittwald.de>
 *             Mittwald CM Service GmbH & Co. KG
 *             http://www.mittwald.de
 * @license    GNU Public License, version 2
 *             http://opensource.org/licenses/gpl-license.php
 *
 */
class SummaryRepository extends \TYPO3\CMS\Extbase\Persistence\Repository {

	/**
	 * The amount of types the summary includes (post,topic,user = 3)
	 * @var int
	 */
	private $summaryItems = 3;

	/**
	 * Get the latest items of the summary
	 * @return \Mittwald\MmForum\Domain\Model\Stats\Summary[]
	 */
	public function findLatestSummaryItems() {
		$query = $this->createQuery();
		$ordering = array('tstamp' => \TYPO3\CMS\Extbase\Persistence\QueryInterface::ORDER_DESCENDING,
						  'type' => \TYPO3\CMS\Extbase\Persistence\QueryInterface::ORDER_ASCENDING,
						 );
		$query->setOrderings($ordering);
		$query->setLimit($this->summaryItems);
		return $query->execute();
	}

}