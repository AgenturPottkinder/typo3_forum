<?php
namespace Mittwald\Typo3Forum\Domain\Model\User\Userfield;
/*                                                                      *
 *  COPYRIGHT NOTICE                                                    *
 *                                                                      *
 *  (c) 2010 Martin Helmich <m.helmich@mittwald.de>                     *
 *           Mittwald CM Service GmbH & Co KG                           *
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



/**
 *
 * A userfield value. This class models an association between userfields,
 * users and a specific userfield value.
 *
 * @author     Martin Helmich <m.helmich@mittwald.de>
 * @package    Typo3Forum
 * @subpackage Domain_Model_User_Userfield
 * @version    $Id$
 *
 * @copyright  2010 Martin Helmich <m.helmich@mittwald.de>
 *             Mittwald CM Service GmbH & Co. KG
 *             http://www.mittwald.de
 * @license    GNU Public License, version 2
 *             http://opensource.org/licenses/gpl-license.php
 *
 */

class Value extends \TYPO3\CMS\Extbase\DomainObject\AbstractValueObject {



	/*
		  * ATTRIBUTES
		  */



	/**
	 * The userfield.
	 * @var \Mittwald\Typo3Forum\Domain\Model\User\Userfield\AbstractUserfield
	 */
	protected $userfield;


	/**
	 * The user.
	 * @var \Mittwald\Typo3Forum\Domain\Model\User\FrontendUser
	 */
	protected $user;


	/**
	 * The value.
	 * @var string
	 */
	protected $value;



	/*
		  * GETTER METHODS
		  */



	/**
	 *
	 * Gets the userfield.
	 * @return \Mittwald\Typo3Forum\Domain\Model\User\Userfield\AbstractUserfield The userfield.
	 *
	 */

	public function getUserfield() {
		return $this->userfield;
	}



	/**
	 *
	 * Gets the user.
	 * @return \Mittwald\Typo3Forum\Domain\Model\User\FrontendUser The user
	 *
	 */
	public function getUser() {
		return $this->user;
	}



	/**
	 *
	 * Gets the value.
	 * @return string The value
	 *
	 */

	public function getValue() {
		return $this->value;
	}

}

?>