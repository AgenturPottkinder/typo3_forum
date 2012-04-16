<?php

/***************************************************************
 *  Copyright notice
 *
 *  (c) 2011 Martin Helmich <typo3@martin-helmich.de>
 *
 *  All rights reserved
 *
 *  This script is part of the TYPO3 project. The TYPO3 project is
 *  free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 2 of the License, or
 *  (at your option) any later version.
 *
 *  The GNU General Public License can be found at
 *  http://www.gnu.org/copyleft/gpl.html.
 *
 *  This script is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  This copyright notice MUST APPEAR in all copies of the script!
 ***************************************************************/

/**
 * Test case for class Tx_Alumnilist_Domain_Model_Year.
 *
 * @version $Id$
 * @copyright Copyright belongs to the respective authors
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 *
 * @package TYPO3
 * @subpackage Alumni List
 *
 * @author Martin Helmich <typo3@martin-helmich.de>
 */
class Tx_Alumnilist_Domain_Model_YearTest extends Tx_Extbase_Tests_Unit_BaseTestCase {
	/**
	 * @var Tx_Alumnilist_Domain_Model_Year
	 */
	protected $fixture;

	public function setUp() {
//		$this->fixture = $this->objectManager->create('Tx_Alumnilist_Domain_Model_Year');
	}

	public function tearDown() {
//		unset($this->fixture);
	}


	/**
	 * @test
	 */
	public function initialValueOfCoursesIsEmptySet() {
//		$newObjectStorage = new Tx_Extbase_Persistence_ObjectStorage();
//		$this->assertEquals(
//			$newObjectStorage,
//			$this->fixture->getCourses()
//		);
	}

}