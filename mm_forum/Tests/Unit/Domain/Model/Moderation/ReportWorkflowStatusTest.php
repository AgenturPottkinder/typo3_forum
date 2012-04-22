<?php

/*                                                                    - *
 *  COPYRIGHT NOTICE                                                    *
 *                                                                      *
 *  (c) 2012 Martin Helmich <m.helmich@mittwald.de>                     *
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



class Tx_MmForum_Domain_Model_Moderation_ReportWorkflowStatusTest extends Tx_MmForum_Unit_BaseTestCase {



	/**
	 * @var Tx_MmForum_Domain_Model_Moderation_ReportWorkflowStatus
	 */
	protected $fixture = NULL;



	public function setUp() {
		$this->fixture = new Tx_MmForum_Domain_Model_Moderation_ReportWorkflowStatus();
	}



	public function testConstructorSetsNameInitialAndFinal() {
		$this->fixture = new Tx_MmForum_Domain_Model_Moderation_ReportWorkflowStatus('FOO', TRUE, TRUE);
		$this->assertEquals('FOO', $this->fixture->getName());
		$this->assertTrue($this->fixture->isInitial());
		$this->assertTrue($this->fixture->isFinal());
	}



}