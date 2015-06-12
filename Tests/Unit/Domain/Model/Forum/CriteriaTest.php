<?php
/*                                                                    - *
 *  COPYRIGHT NOTICE                                                    *
 *                                                                      *
 *  (c) 2013 Ruven Fehling <r.fehling@mittwald.de>                      *
 *           Mittwald CM Service GmbH & Co KG                           *
 *           All rights reserved                                        *
 *                                                                      *
 *  This script is part of the TYPO3 project. The TYPO3 project is      *
 *  free software; you can redistribute it and/or modify                *
 *  it under the terms of the GNU General public License as published   *
 *  by the Free Software Foundation; either version 2 of the License,   *
 *  or (at your option) any later version.                              *
 *                                                                      *
 *  The GNU General public License can be found at                      *
 *  http://www.gnu.org/copyleft/gpl.html.                               *
 *                                                                      *
 *  This script is distributed in the hope that it will be useful,      *
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of      *
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the       *
 *  GNU General public License for more details.                        *
 *                                                                      *
 *  This copyright notice MUST APPEAR in all copies of the script!      *
 *                                                                      */

class Tx_Typo3Forum_Domain_Model_Forum_CriteriaTest extends Tx_Typo3Forum_Unit_BaseTestCase {


	/**
	 * @var Tx_Typo3Forum_Domain_Model_Forum_Criteria
	 */
	private $criteria;

	/**
	 * @var Tx_Typo3Forum_Domain_Model_Forum_CriteriaOption
	 */
	private $option;

	/**
	 * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<Tx_Typo3Forum_Domain_Model_Forum_CriteriaOption>
	 */
	private $optionStorage;


	public function setUp() {
		$this->criteria = $this->objectManager->create('Tx_Typo3Forum_Domain_Model_Forum_Criteria');
		$this->option = $this->objectManager->create('Tx_Typo3Forum_Domain_Model_Forum_CriteriaOption');
		$this->optionStorage = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
	}


	public function testCriteriaSetNameSetsName() {
		$name = 'TYPO3-Version';
		$this->criteria->setName($name);
		$this->assertEquals($name, $this->criteria->getName());
	}


	public function testSetOptionName() {
		$keyword = 'test';
		$this->option->setName($keyword);
		$this->assertEquals($keyword, $this->option->getName());
	}

	/**
	 * @depends testSetOptionName
	 */
	public function testInsertOptionIntoCriteriaObject() {
		$this->option->setName('test');
		$this->optionStorage->attach($this->option);

		$this->criteria->setOptions($this->optionStorage);
		$this->assertEquals($this->optionStorage, $this->criteria->getOptions());
	}


}