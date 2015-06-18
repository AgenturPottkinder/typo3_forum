<?php
namespace Mittwald\Typo3Forum\Domain\Model\User;
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
 * @package    Typo3Forum
 * @subpackage Domain_Model_User
 * @version    $Id$
 * @license    GNU public License, version 2
 *             http://opensource.org/licenses/gpl-license.php

 */

class PrivateMessages extends \TYPO3\CMS\Extbase\DomainObject\AbstractEntity {

	/**
	 * ATTRIBUTES
	 */

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
	 * @var \Mittwald\Typo3Forum\Domain\Model\User\PrivateMessagesText
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
	 * Get the type of this pm
	 * @return int
	 */
	public function getType() {
		return $this->type;
	}

	/**
	 * Get the User who read this message
	 * @return \Mittwald\Typo3Forum\Domain\Model\User\FrontendUser The User who read this message
	 */
	public function getFeuser() {
		if ($this->feuser instanceof \TYPO3\CMS\Extbase\Persistence\Generic\LazyLoadingProxy) {
			$this->feuser->_loadRealInstance();
		}
		if ($this->feuser === NULL) {
			$this->feuser = new \Mittwald\Typo3Forum\Domain\Model\User\AnonymousFrontendUser();
		}
		return $this->feuser;

	}


	/**
	 * Get the other User who is involved in this message
	 * @return \Mittwald\Typo3Forum\Domain\Model\User\FrontendUser The other User who is involved in this message
	 */
	public function getOpponent() {
		if ($this->opponent instanceof \TYPO3\CMS\Extbase\Persistence\Generic\LazyLoadingProxy) {
			$this->opponent->_loadRealInstance();
		}
		if ($this->opponent === NULL) {
			$this->opponent = new \Mittwald\Typo3Forum\Domain\Model\User\AnonymousFrontendUser();
		}
		return $this->opponent;
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
	 * @return \Mittwald\Typo3Forum\Domain\Model\User\PrivateMessagesText
	 */
	public function getMessage() {
		return $this->message;
	}


	/**
	 * SETTER
	 */

	/**
	 * Get the date this message has been sent
	 * @param \DateTime $crdate
	 * @return void
	 */
	public function setCrdate(\DateTime $crdate) {
		$this->crdate = $crdate;
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
	 * Sets the user
	 * @param \Mittwald\Typo3Forum\Domain\Model\User\FrontendUser $feuser
	 * @return void
	 */
	public function setFeuser(\Mittwald\Typo3Forum\Domain\Model\User\FrontendUser $feuser) {
		$this->feuser = $feuser;
	}


	/**
	 * Sets the opponent user
	 * @param \Mittwald\Typo3Forum\Domain\Model\User\FrontendUser $opponent
	 * @return void
	 */
	public function setOpponent(\Mittwald\Typo3Forum\Domain\Model\User\FrontendUser $opponent) {
		$this->opponent = $opponent;
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
	 * @param \Mittwald\Typo3Forum\Domain\Model\User\PrivateMessagesText $message
	 */
	public function setMessage(\Mittwald\Typo3Forum\Domain\Model\User\PrivateMessagesText $message) {
		$this->message = $message;
	}
}