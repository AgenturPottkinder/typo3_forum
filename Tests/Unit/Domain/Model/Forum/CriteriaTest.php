<?php
namespace Mittwald\Typo3Forum\Tests\Unit\Domain\Model\Forum;
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

class CriteriaTest extends \Mittwald\Typo3Forum\Tests\Unit\BaseTestCase {


	/**
	 * @var \Mittwald\Typo3Forum\Domain\Model\Forum\Criteria
	 */
	private $criteria;

	/**
	 * @var \Mittwald\Typo3Forum\Domain\Model\Forum\CriteriaOption
	 */
	private $option;

	/**
	 * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\Mittwald\Typo3Forum\Domain\Model\Forum\CriteriaOption>
	 */
	private $optionStorage;


	public function setUp() {
		$this->criteria = $this->objectManager->create('Mittwald\Typo3Forum\Domain\Model\Forum\Criteria');
		$this->option = $this->objectManager->create('Mittwald\Typo3Forum\Domain\Model\Forum\CriteriaOption');
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