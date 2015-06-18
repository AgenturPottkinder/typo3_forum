<?php
namespace Mittwald\Typo3Forum\Tests\Unit\Domain\Model\Format;
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



class BBCodeTest extends \Mittwald\Typo3Forum\Tests\Unit\BaseTestCase {



	/**
	 * @var \Mittwald\Typo3Forum\Domain\Model\Format\BBCode
	 */
	protected $fixture = NULL;



	public function setUp() {
		$this->fixture = new \Mittwald\Typo3Forum\Domain\Model\Format\BBCode();
	}



	/**
	 * @test
	 */
	public function setBccodeWrapSetsBbcodeWrap() {
		$this->fixture->setBbcodeWrap('[b]|[/b]');
		$this->assertEquals('[b]', $this->fixture->getLeftBBCode());
		$this->assertEquals('[/b]', $this->fixture->getRightBBCode());
	}



	/**
	 * @test
	 */
	public function setRegularExpressionSetsRegularExpression() {
		$this->fixture->setRegularExpression($e = ',\[b\](.*?)\[\/b\],i');
		$this->assertEquals($e, $this->fixture->getRegularExpression());
	}



	/**
	 * @test
	 */
	public function setRegularExpressionReplacementSetsRegularExpressionReplacement() {
		$this->fixture->setRegularExpressionReplacement($r = '<b>\\1</b>');
		$this->assertEquals($r, $this->fixture->getRegularExpressionReplacement());
	}



	/**
	 * @test
	 */
	public function canBeExportedToMarkitup() {
		$this->fixture->setName('Bold');
		$this->fixture->setBbcodeWrap('[b]|[/b]');
		$this->fixture->setIconClass('icon-bold');

		$this->assertEquals(array('name'    => 'Bold',
		                         'className'=> 'icon-bold',
		                         'openWith' => '[b]',
		                         'closeWith'=> '[/b]'), $this->fixture->exportForMarkItUp());
	}


}
