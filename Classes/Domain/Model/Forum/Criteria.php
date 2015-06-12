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
 * @package    Typo3Forum
 * @subpackage Domain_Model_Forum
 * @version    $Id$
 * @license    GNU public License, version 2
 *             http://opensource.org/licenses/gpl-license.php

 */

class Tx_Typo3Forum_Domain_Model_Forum_Criteria extends \TYPO3\CMS\Extbase\DomainObject\AbstractEntity {

		/**
	 * The name of the criteria
	 * @var string
	 */
	protected $name;


	/**
	 * The options of a criteria
	 * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<Tx_Typo3Forum_Domain_Model_Forum_CriteriaOption>
	 */
	protected $options;


	/**
	 * The default option
	 * @var Tx_Typo3Forum_Domain_Model_Forum_CriteriaOption
	 */
	protected $defaultOption;


	/**
	 * Gets the absolute name of this criteria.
	 * @return string The name of criteria.
	 */
	public function getName() {
		return $this->name;
	}


	/**
	 * Get all options of this criteria.
	 * @return	\TYPO3\CMS\Extbase\Persistence\ObjectStorage<Tx_Typo3Forum_Domain_Model_Forum_CriteriaOption>
	 *			All options of criteria.
	 */
	public function getOptions() {
		return $this->options;
	}


	/**
	 * Get the default option
	 * @return Tx_Typo3Forum_Domain_Model_Forum_CriteriaOption
	 */
	public function getDefaultOption() {
		return $this->defaultOption;
	}

	/**
	 * Set a whole object storage as options for this criteria
	 * @param \TYPO3\CMS\Extbase\Persistence\ObjectStorage $options
	 */
	public function setOptions(\TYPO3\CMS\Extbase\Persistence\ObjectStorage $options) {
		$this->options = $options;
	}

	/**
	 * Sets the name.
	 *
	 * @param string $name The name of a criteria
	 * @return void
	 */
	public function setName($name) {
		$this->name = $name;
	}


	/**
	 * Set the default option
	 * @param Tx_Typo3Forum_Domain_Model_Forum_CriteriaOption $defaultOption
	 * @return void
	 */
	public function setDefaultOption(Tx_Typo3Forum_Domain_Model_Forum_CriteriaOption $defaultOption) {
		$this->defaultOption = $defaultOption;
	}
}