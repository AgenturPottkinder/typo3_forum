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
	 * Creation date of this post
	 * @var DateTime
	 */
	public $tstamp;

	/**
	 * User who send this message
	 * @var Tx_MmForum_Domain_Model_User_FrontendUser
	 */
	public $sender;

	/**
	 * User who get this message
	 * @var Tx_MmForum_Domain_Model_User_FrontendUser
	 */
	public $recipient;

	/**
	 * The submitted message
	 * @var string
	 */
	public $message;

	/**
	 * Flag if recipient already read this message
	 * @var int
	 */
	public $recipientRead;


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
	 * Get the User who send this message
	 * @return Tx_MmForum_Domain_Model_User_FrontendUser The User who send this message
	 */
	public function getSender() {
		return $this->sender;
	}

	/**
	 * Get the User who received this message
	 * @return Tx_MmForum_Domain_Model_User_FrontendUser The User who received this message
	 */
	public function getRecipient() {
		return $this->recipient;
	}

	/**
	 * Get the message of this pm
	 * @return string The message
	 */
	public function getMessage() {
		return $this->message;
	}

	/**
	 * Get if the recipient already read this message
	 * @return int The flag
	 */
	public function getRecipientRead() {
		return intval($this->recipientRead);
	}


	/**
	 * SETTER
	 */

	/**
	 * Sets the sender
	 * @param Tx_MmForum_Domain_Model_User_FrontendUser $sender
	 * @return void
	 */
	public function setSender(Tx_MmForum_Domain_Model_User_FrontendUser $sender) {
		$this->sender = $sender;
	}

	/**
	 * Sets the recipient
	 * @param Tx_MmForum_Domain_Model_User_FrontendUser $recipient
	 * @return void
	 */
	public function setRecipient(Tx_MmForum_Domain_Model_User_FrontendUser $recipient) {
		$this->recipient = $recipient;
	}

	/**
	 * Sets the message
	 * @param string $message
	 * @return void
	 */
	public function setMessage($message) {
		$this->message = $message;
	}

	/**
	 * Sets the flag
	 * @param int $recipientRead
	 * @return void
	 */
	public function setRecipientRead($recipientRead) {
		$this->recipientRead = $recipientRead;
	}
}