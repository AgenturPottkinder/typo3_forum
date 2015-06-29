<?php
namespace Mittwald\Typo3Forum\Domain\Model\Stats;

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

class Summary extends AbstractEntity {

	/**
	 * Type of summary
	 * @var string
	 */
	protected $type;

	/**
	 * Amount of the summary
	 * @var int
	 */
	protected $amount;


	/**
	 * Timestamp of summary
	 * @var int
	 */
	protected $tstamp;


	/**
	 * Get the type of this summary
	 * @return string
	 */
	public function getType() {
		return $this->type;
	}

	/**
	 * @param string $type
	 */
	public function setType($type) {
		$this->type = $type;
	}

	/**
	 * Get the amount of this summary
	 * @return int
	 */
	public function getAmount() {
		return $this->amount;
	}

	/**
	 * @param int $amount
	 */
	public function setAmount($amount) {
		$this->amount = $amount;
	}

	/**
	 * Get the amount of this summary nicely formatted
	 * @return string
	 */
	public function getAmountNice() {
		return number_format($this->amount, 0, '', '.');
	}

	/**
	 * Get the timestamp of this summary
	 * @return int
	 */
	public function getTstamp() {
		return $this->tstamp;
	}
}