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

class Criteria extends AbstractEntity {

	/**
	 * The name of the criteria
	 * @var string
	 */
	protected $name;


	/**
	 * The options of a criteria
	 * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\Mittwald\Typo3Forum\Domain\Model\Forum\CriteriaOption>
	 */
	protected $options;


	/**
	 * The default option
	 * @var \Mittwald\Typo3Forum\Domain\Model\Forum\CriteriaOption
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
	 * Sets the name.
	 *
	 * @param string $name The name of a criteria
	 *
	 * @return void
	 */
	public function setName($name) {
		$this->name = $name;
	}

	/**
	 * Get all options of this criteria.
	 * @return \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\Mittwald\Typo3Forum\Domain\Model\Forum\CriteriaOption>
	 *            All options of criteria.
	 */
	public function getOptions() {
		return $this->options;
	}

	/**
	 * Set a whole object storage as options for this criteria
	 *
	 * @param \TYPO3\CMS\Extbase\Persistence\ObjectStorage $options
	 */
	public function setOptions(\TYPO3\CMS\Extbase\Persistence\ObjectStorage $options) {
		$this->options = $options;
	}

	/**
	 * Get the default option
	 * @return CriteriaOption
	 */
	public function getDefaultOption() {
		return $this->defaultOption;
	}

	/**
	 * Set the default option
	 *
	 * @param CriteriaOption $defaultOption
	 *
	 * @return void
	 */
	public function setDefaultOption(CriteriaOption $defaultOption) {
		$this->defaultOption = $defaultOption;
	}
}
