<?php
namespace Mittwald\Typo3Forum\Domain\Model\Forum;

/*                                                                    - *
 *  COPYRIGHT NOTICE                                                    *
 *                                                                      *
 *  (c) 2015 Mittwald CM Service GmbH & Co KG                           *
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

use TYPO3\CMS\Extbase\DomainObject\AbstractEntity;

class CriteriaOption extends AbstractEntity {

	/**
	 * The criteria object.
	 * @var \Mittwald\Typo3Forum\Domain\Model\Forum\Criteria
	 */
	protected $criteria;

	/**
	 * The name of the option
	 * @var string
	 */
	protected $name;

	/**
	 * The sorting value of this option
	 * @var int
	 */
	protected $sorting;

	/**
	 * Get the criteria object.
	 * @return Criteria
	 */
	public function getCriteria() {
		return $this->criteria;
	}

	/**
	 * Sets the criteria object.
	 *
	 * @param Criteria $criteria The criteria object
	 * @return void
	 */
	public function setCriteria(Criteria $criteria) {
		$this->criteria = $criteria;
	}

	/**
	 * Gets the value of criteria.
	 * @return string The name of a option.
	 */
	public function getName() {
		return $this->name;
	}

	/**
	 * Sets the value.
	 *
	 * @param string $name The name of a option
	 *
	 * @return void
	 */
	public function setName($name) {
		$this->name = $name;
	}

	/**
	 * Gets the sorting value of this option
	 * @return int
	 */
	public function getSorting() {
		return $this->sorting;
	}

}