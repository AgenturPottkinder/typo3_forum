<?php

/*                                                                      *
 *  COPYRIGHT NOTICE                                                    *
 *                                                                      *
 *  (c) 2010 Martin Helmich <m.helmich@mittwald.de>                     *
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
	 * Abstract base class for all mm_forum repositories. This provides basic
	 * methods for the use of hard-coded SQL queries to the database. Although
	 * this is not the way that was intended by extbase, however at the moment
	 * there is no way to model complex joins using the extbase methods
	 * (see http://forge.typo3.org/issues/10212), so we'll have to do this by
	 * ourselves.
	 *
	 * @author     Martin Helmich <m.helmich@mittwald.de>
	 * @package    MmForum
	 * @subpackage Domain_Repository_User
	 * @version    $Id$
	 *
	 * @copyright  2010 Martin Helmich <m.helmich@mittwald.de>
	 *             Mittwald CM Service GmbH & Co. KG
	 *             http://www.mittwald.de
	 * @license    GNU Public License, version 2
	 *             http://opensource.org/licenses/gpl-license.php
	 *
	 */

Abstract Class Tx_MmForum_Domain_Repository_AbstractRepository
	Extends Tx_Extbase_Persistence_Repository {



		/**
		 *
		 * Returns the list of parent page-UIDs.
		 * @return array<integer> The list of parent page-UIDs
		 *
		 */

	Protected Function getPidList() {
		$extbaseFrameworkConfiguration = Tx_Extbase_Dispatcher::getExtbaseFrameworkConfiguration();
		Return t3lib_div::intExplode(',', $extbaseFrameworkConfiguration['persistence']['storagePid']);
	}



		/**
		 *
		 * Generates a custom MySQL query.
		 *
		 * @param  string $sql A SQL query template
		 * @return string      The SQL query.
		 *
		 */

	Protected Function getQuery($sql) {
		Return str_replace('###PIDS###', implode(',',$this->getPidList()), $sql);
	}



		/**
		 *
		 * Generates a customer MySQL query with page navigation.
		 *
		 * @param  string  $sql          A SQL query template
		 * @param  integer $page         The current page
		 * @param  integer $itemsPerPage The amount of items per page
		 * @return string                The SQL query
		 *
		 */
	
	Protected Function getPaginatedQuery($sql, $page, $itemsPerPage) {
		Return $this->getQuery($sql) . " LIMIT ".intval(($page-1)*$itemsPerPage).", ".intval($itemsPerPage);
	}

}

?>