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
 * @author     Ruven Fehling <r.fehling@mittwald.de>
 * @package    MmForum
 * @subpackage Domain_Model_Forum
 * @version    $Id$
 * @license    GNU public License, version 2
 *             http://opensource.org/licenses/gpl-license.php

 */

class Tx_MmForum_Domain_Model_Forum_CriteriaOption extends \TYPO3\CMS\Extbase\DomainObject\AbstractEntity {

	/**
	 * The criteria object.
	 * @var Tx_MmForum_Domain_Model_Forum_Criteria
	 */
	protected $criteria;

	/**
	 * The name of the option
	 * @var string
	 */
	protected $name;


	/**
	 * Get the criteria object.
	 * @return Tx_MmForum_Domain_Model_Forum_Criteria
	 */
	public function getCriteria() {
		return $this->criteria;
	}

	/**
	 * Gets the value of criteria.
	 * @return string The name of a option.
	 */
	public function getName() {
		return $this->name;
	}


	/**
	 * Sets the criteria object.
	 *
	 * @param Tx_MmForum_Domain_Model_Forum_Criteria $criteria The criteria object
	 * @return void
	 */
	public function setCriteria(Tx_MmForum_Domain_Model_Forum_Criteria $criteria) {
		$this->criteria = $criteria;
	}

	/**
	 * Sets the value.
	 *
	 * @param string $name The name of a option
	 * @return void
	 */
	public function setName($name) {
		$this->name = $name;
	}

}