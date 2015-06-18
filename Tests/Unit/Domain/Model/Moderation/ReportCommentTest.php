<?php
namespace Mittwald\Typo3Forum\Tests\Unit\Domain\Model\Moderation;
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



class ReportCommentTest extends \Mittwald\Typo3Forum\Tests\Unit\BaseTestCase {



	/**
	 * @var \Mittwald\Typo3Forum\Domain\Model\Moderation\ReportComment
	 */
	protected $fixture = NULL;


	/**
	 * @var \Mittwald\Typo3Forum\Domain\Model\User\FrontendUser
	 */
	protected $user = NULL;



	public function setUp() {
		$this->user    = new \Mittwald\Typo3Forum\Domain\Model\User\FrontendUser('martin');
		$this->fixture = new \Mittwald\Typo3Forum\Domain\Model\Moderation\ReportComment($this->user, 'FOO');
	}



	public function testConstructorSetsAuthorAndTest() {
		$this->assertTrue($this->fixture->getAuthor() === $this->user);
		$this->assertEquals('FOO', $this->fixture->getText());
	}



	public function testSetAuthorSetsAuthor() {
		$this->fixture->setAuthor($user = new \Mittwald\Typo3Forum\Domain\Model\User\FrontendUser());
		$this->assertTrue($this->fixture->getAuthor() === $user);
	}



	public function testSetTextSetsText() {
		$this->fixture->setText('BAR');
		$this->assertEquals('BAR', $this->fixture->getText());
	}



	public function testSetReportSetsReport() {
		$this->fixture->setReport($report = new \Mittwald\Typo3Forum\Domain\Model\Moderation\Report());
		$this->assertTrue($this->fixture->getReport() === $report);
	}



}