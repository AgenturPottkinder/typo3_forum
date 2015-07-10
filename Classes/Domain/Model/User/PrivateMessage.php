<?php
namespace Mittwald\Typo3Forum\Domain\Model\User;

/*                                                                      *
 *  COPYRIGHT NOTICE                                                    *
 *                                                                      *
 *  (c) 2015 Mittwald CM Service GmbH & Co KG                           *
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

use TYPO3\CMS\Extbase\DomainObject\AbstractEntity;
use TYPO3\CMS\Extbase\Persistence\Generic\LazyLoadingProxy;

class PrivateMessage extends AbstractEntity {

	const TYPE_SENDER = 0;

	const TYPE_RECIPIENT = 1;

	/**
	 * The creation date of pm
	 * @var \DateTime
	 */
	public $crdate;

	/**
	 * User who read this message
	 * @var \Mittwald\Typo3Forum\Domain\Model\User\FrontendUser
	 */
	public $feuser;

	/**
	 * Opponent user of this message
	 * @var \Mittwald\Typo3Forum\Domain\Model\User\FrontendUser
	 */
	public $opponent;


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
	 * @var \Mittwald\Typo3Forum\Domain\Model\User\PrivateMessageText
	 */
	public $message;


	/**
	 * GETTER
	 */

	/**
	 * Get the date this message has been sent
	 * @return \DateTime
	 */
	public function getCrdate() {
		return $this->crdate;
	}

	/**
	 * Get the date this message has been sent
	 *
	 * @param \DateTime $crdate
	 * @return void
	 */
	public function setCrdate(\DateTime $crdate) {
		$this->crdate = $crdate;
	}

	/**
	 * Get the type of this pm
	 * @return int
	 */
	public function getType() {
		return $this->type;
	}

	/**
	 * Get the type of this pm
	 *
	 * @param int $type
	 * @return void
	 */
	public function setType($type) {
		$this->type = $type;
	}

	/**
	 * Get the User who read this message
	 * @return FrontendUser The User who read this message
	 */
	public function getFeuser() {
		if ($this->feuser instanceof LazyLoadingProxy) {
			$this->feuser->_loadRealInstance();
		}
		if ($this->feuser === NULL) {
			$this->feuser = new AnonymousFrontendUser();
		}

		return $this->feuser;
	}

	/**
	 * Sets the user
	 *
	 * @param FrontendUser $feuser
	 *
	 * @return void
	 */
	public function setFeuser(FrontendUser $feuser) {
		$this->feuser = $feuser;
	}


	/**
	 * SETTER
	 */

	/**
	 * Get the other User who is involved in this message
	 * @return FrontendUser The other User who is involved in this message
	 */
	public function getOpponent() {
		if ($this->opponent instanceof LazyLoadingProxy) {
			$this->opponent->_loadRealInstance();
		}
		if ($this->opponent === NULL) {
			$this->opponent = new AnonymousFrontendUser();
		}

		return $this->opponent;
	}

	/**
	 * Sets the opponent user
	 *
	 * @param FrontendUser $opponent
	 *
	 * @return void
	 */
	public function setOpponent(FrontendUser $opponent) {
		$this->opponent = $opponent;
	}

	/**
	 * Get if the recipient already read this message
	 * @return int The flag
	 */
	public function getUserRead() {
		return (int) $this->userRead;
	}

	/**
	 * Sets the flag
	 *
	 * @param int $userRead
	 *
	 * @return void
	 */
	public function setUserRead($userRead) {
		$this->userRead = $userRead;
	}

	/**
	 * Gets the message of this pm
	 * @return PrivateMessageText
	 */
	public function getMessage() {
		return $this->message;
	}

	/**
	 * Sets the message of this pm
	 *
	 * @param PrivateMessageText $message
	 */
	public function setMessage(PrivateMessageText $message) {
		$this->message = $message;
	}
}
