<?php
namespace Mittwald\Typo3Forum\Tests\Unit\Domain\Model\Forum;
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



class AccessTest extends \Mittwald\Typo3Forum\Tests\Unit\BaseTestCase {



	/**
	 * @var \Mittwald\Typo3Forum\Domain\Model\Forum\Access
	 */
	protected $fixture = NULL;



	public function setUp() {
		$this->fixture = new \Mittwald\Typo3Forum\Domain\Model\Forum\Access();
	}



	public function testConstructorSetsOperationLevelAndGroup() {
		$group         = new \Mittwald\Typo3Forum\Domain\Model\User\FrontendUserGroup('GROUP');
		$this->fixture = new \Mittwald\Typo3Forum\Domain\Model\Forum\Access('read', \Mittwald\Typo3Forum\Domain\Model\Forum\Access::LOGIN_LEVEL_ANYLOGIN, $group);

		$this->assertEquals('read', $this->fixture->getOperation());
		$this->assertTrue($this->fixture->isAnyLogin());
		$this->assertTrue($this->fixture->getGroup() === $group);
	}



	public function testSetNegatedSetsNegation() {
		$this->fixture->setNegated(TRUE);
		$this->assertTrue($this->fixture->isNegated());
	}



	public function testSetNegatedUnsetsNegation() {
		$this->fixture->setNegated(FALSE);
		$this->assertFalse($this->fixture->isNegated());
	}



	public function testMatchesNullAgainstEveryone() {
		$this->fixture = new \Mittwald\Typo3Forum\Domain\Model\Forum\Access('read', \Mittwald\Typo3Forum\Domain\Model\Forum\Access::LOGIN_LEVEL_EVERYONE);
		$this->assertTrue($this->fixture->matches(NULL));
	}



	public function testMatchesAnonymousUserAgainstEveryone() {
		$this->fixture = new \Mittwald\Typo3Forum\Domain\Model\Forum\Access('read', \Mittwald\Typo3Forum\Domain\Model\Forum\Access::LOGIN_LEVEL_EVERYONE);
		$this->assertTrue($this->fixture->matches(new \Mittwald\Typo3Forum\Domain\Model\User\AnonymousFrontendUser()));
	}



	public function testMatchesLoggedInUserAgainstEveryone() {
		$this->fixture = new \Mittwald\Typo3Forum\Domain\Model\Forum\Access('read', \Mittwald\Typo3Forum\Domain\Model\Forum\Access::LOGIN_LEVEL_EVERYONE);
		$this->assertTrue($this->fixture->matches(new \Mittwald\Typo3Forum\Domain\Model\User\FrontendUser('martin')));
	}



	public function testMismatchesNullAgainstAnyLogin() {
		$this->fixture = new \Mittwald\Typo3Forum\Domain\Model\Forum\Access('read', \Mittwald\Typo3Forum\Domain\Model\Forum\Access::LOGIN_LEVEL_ANYLOGIN);
		$this->assertFalse($this->fixture->matches(NULL));
	}

	public function testMismatchesAnonymousUserAgainstAnyLogin() {
		$this->fixture = new \Mittwald\Typo3Forum\Domain\Model\Forum\Access('read', \Mittwald\Typo3Forum\Domain\Model\Forum\Access::LOGIN_LEVEL_ANYLOGIN);
		$this->assertFalse($this->fixture->matches(new \Mittwald\Typo3Forum\Domain\Model\User\AnonymousFrontendUser()));
	}



	public function testMatchesLoggedInUserAgainstAnyLogin() {
		$this->fixture = new \Mittwald\Typo3Forum\Domain\Model\Forum\Access('read', \Mittwald\Typo3Forum\Domain\Model\Forum\Access::LOGIN_LEVEL_ANYLOGIN);
		$this->assertTrue($this->fixture->matches(new \Mittwald\Typo3Forum\Domain\Model\User\FrontendUser('martin')));
	}



	public function testMismatchesAnonymousUserAgainstSpecificLogin() {
		$group         = new \Mittwald\Typo3Forum\Domain\Model\User\FrontendUserGroup('GROUP');
		$this->fixture = new \Mittwald\Typo3Forum\Domain\Model\Forum\Access('read', \Mittwald\Typo3Forum\Domain\Model\Forum\Access::LOGIN_LEVEL_SPECIFIC, $group);
		$this->assertFalse($this->fixture->matches(NULL));
	}



	public function testMismatchesAnyUserAgainstSpecificLogin() {
		$group         = new \Mittwald\Typo3Forum\Domain\Model\User\FrontendUserGroup('GROUP');
		$this->fixture = new \Mittwald\Typo3Forum\Domain\Model\Forum\Access('read', \Mittwald\Typo3Forum\Domain\Model\Forum\Access::LOGIN_LEVEL_SPECIFIC, $group);
		$this->assertFalse($this->fixture->matches(new \Mittwald\Typo3Forum\Domain\Model\User\FrontendUser('martin')));
	}



	public function testMatchesGroupMemberUserAgainstSpecificLogin() {
		$group = new \Mittwald\Typo3Forum\Domain\Model\User\FrontendUserGroup('GROUP');
		$user  = new \Mittwald\Typo3Forum\Domain\Model\User\FrontendUser('martin');
		$user->addUsergroup($group);
		$this->fixture = new \Mittwald\Typo3Forum\Domain\Model\Forum\Access('read', \Mittwald\Typo3Forum\Domain\Model\Forum\Access::LOGIN_LEVEL_SPECIFIC, $group);
		$this->assertTrue($this->fixture->matches($user));
	}



}
