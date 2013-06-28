<?php
/*                                                                    - *
 *  COPYRIGHT NOTICE                                                    *
 *                                                                      *
 *  (c) 2013 Ruven Fehling <r.fehling@mittwald.de>                     *
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
 * @author     Ruven Fehling <r.fehling@mittwald.de>
 * @package    MmForum
 * @subpackage Domain_Model_User
 * @version    $Id$
 * @license    GNU public License, version 2
 *             http://opensource.org/licenses/gpl-license.php

 */

class Tx_MmForum_Domain_Model_User_PrivateMessages extends \TYPO3\CMS\Extbase\DomainObject\AbstractEntity {

	/**
	 * ATTRIBUTES
	 */

	/**
	 * The creation date of pm
	 * @var DateTime
	 */
	public $tstamp;

	/**
	 * User who send this message
	 * @var Tx_MmForum_Domain_Model_User_FrontendUser
	 */
	public $feuser;


	/**
	 * The type of pm (0=sender, 1=recipient)
	 * @var int
	 */
	public $type;

	/**
	 * Flag if recipient already read this message
	 * @var int
	 */
	public $userRead;


	/**
	 * The message of this pm
	 * @var Tx_MmForum_Domain_Model_User_PrivateMessagesText
	 */
	public $message;


	/**
	 * GETTER
	 */

	/**
	 * Get the date this message has been sent
	 * @return DateTime
	 */
	public function getTstamp() {
		return $this->tstamp;
	}

	/**
	 * Get the type of this pm
	 * @return int
	 */
	public function getType() {
		return $this->type;
	}

	/**
	 * Get the User who send this message
	 * @return Tx_MmForum_Domain_Model_User_FrontendUser The User who send this message
	 */
	public function getFeuser() {
		return $this->feuser;
	}


	/**
	 * Get if the recipient already read this message
	 * @return int The flag
	 */
	public function getUserRead() {
		return intval($this->userRead);
	}

	/**
	 * Gets the message of this pm
	 * @return Tx_MmForum_Domain_Model_User_PrivateMessagesText
	 */
	public function getMessage() {
		return $this->message;
	}


	/**
	 * SETTER
	 */

	/**
	 * Get the date this message has been sent
	 * @param DateTime $tstamp
	 * @return void
	 */
	public function setTstamp(DateTime $tstamp) {
		$this->tstamp = $tstamp;
	}

	/**
	 * Get the type of this pm
	 * @param int $type
	 * @return void
	 */
	public function setType($type) {
		$this->type = $type;
	}

	/**
	 * Sets the sender
	 * @param Tx_MmForum_Domain_Model_User_FrontendUser $feuser
	 * @return void
	 */
	public function setFeuser(Tx_MmForum_Domain_Model_User_FrontendUser $feuser) {
		$this->feuser = $feuser;
	}


	/**
	 * Sets the flag
	 * @param int $userRead
	 * @return void
	 */
	public function setUserRead($userRead) {
		$this->userRead = $userRead;
	}


	/**
	 * Sets the message of this pm
	 * @param Tx_MmForum_Domain_Model_User_PrivateMessagesText $message
	 */
	public function setMessage(Tx_MmForum_Domain_Model_User_PrivateMessagesText $message) {
		$this->message = $message;
	}
}