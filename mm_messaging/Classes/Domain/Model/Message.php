<?php

/***************************************************************
*  Copyright notice
*
*  (c) 2010 Martin Helmich <m.helmich@mittwald.de>, Mittwald CM Service GmbH & Co. KG
*  			Ruven Fehling <r.fehling@mittwald.de>, Mittwald CM Service GmbH & Co. KG
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
 * Message
 *
 * @version $Id$
 * @copyright Copyright belongs to the respective authors
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 */
class Tx_MmMessaging_Domain_Model_Message extends Tx_Extbase_DomainObject_AbstractEntity {
	
	/**
	 * userID from the recipient
	 * @var Tx_MmForum_Domain_Model_User_FrontendUser
	 */
	protected $recipient;
	
	/**
	 * userID from the sender
	 * @var Tx_MmForum_Domain_Model_User_FrontendUser
	 */
	protected $sender;
	
	/**
	 * subject of the message
	 * @var string
	 * @validate NotEmpty
	 */
	protected $subject;
	
	/**
	 * text of the message
	 * @var string
	 * @validate NotEmpty
	 */
	protected $text;
	
	/**
	 * userRead
	 * @var boolean
	 */
	protected $userRead;
	
	/**
	 * attachments
	 * @var Tx_Extbase_Persistence_ObjectStorage<Tx_MmMessaging_Domain_Model_Attachment>
	 */
	protected $attachments;
	
	/**
	 * anwers
	 * @var Tx_Extbase_Persistence_ObjectStorage<Tx_MmMessaging_Domain_Model_Message>
	 */
	protected $anwers;

	/**
	 * @var DateTime
	 */
	protected $crdate;

	/**
	 * @var boolean
	 */
	protected $archived;
       
	
	/**
	 * Constructor. Initializes all Tx_Extbase_Persistence_ObjectStorage instances.
	 */
	public function __construct() {
		$this->attachments = new Tx_Extbase_Persistence_ObjectStorage();
		
		$this->anwers = new Tx_Extbase_Persistence_ObjectStorage();
	}
	
	/**
	 * Setter for recipient
	 *
	 * @param Tx_MmForum_Domain_Model_User_FrontendUser $recipient userID from the recipient
	 * @return void
	 */
	public function setRecipient($recipient) {
		$this->recipient = $recipient;
	}

	/**
	 * Getter for recipient
	 *
	 * @return Tx_MmForum_Domain_Model_User_FrontendUser userID from the recipient
	 */
	public function getRecipient() {
		return $this->recipient;
	}
	
	/**
	 * Setter for sender
	 *
	 * @param Tx_MmForum_Domain_Model_User_FrontendUser $sender userID from the sender
	 * @return void
	 */
	public function setSender($sender) {
		$this->sender = $sender;
	}

	/**
	 * Getter for sender
	 *
	 * @return Tx_MmForum_Domain_Model_User_FrontendUser userID from the sender
	 */
	public function getSender() {
		return $this->sender;
	}
	
	/**
	 * Setter for subject
	 *
	 * @param string $subject subject of the message
	 * @return void
	 */
	public function setSubject($subject) {
		$this->subject = $subject;
	}

	/**
	 * Getter for subject
	 *
	 * @return string subject of the message
	 */
	public function getSubject() {
		return $this->subject;
	}
	
	/**
	 * Setter for text
	 *
	 * @param string $text text of the message
	 * @return void
	 */
	public function setText($text) {
		$this->text = $text;
	}

	/**
	 * Getter for text
	 *
	 * @return string text of the message
	 */
	public function getText() {
		return $this->text;
	}
	
	/**
	 * Setter for userRead
	 *
	 * @param boolean $userRead userRead
	 * @return void
	 */
	public function setUserRead($userRead) {
		$this->userRead = $userRead;
	}

	/**
	 * Getter for userRead
	 *
	 * @return boolean userRead
	 */
	public function getUserRead() {
		return $this->userRead;
	}
	
	/**
	 * Returns the boolean state of userRead
	 *
	 * @return boolean The state of userRead
	 */
	public function isUserRead() {
		return $this->getUserRead();
	}

	public function isUserUnread() {
		return !$this->isUserRead();
	}
	
	/**
	 * Setter for attachments
	 *
	 * @param Tx_Extbase_Persistence_ObjectStorage<Tx_MmMessaging_Domain_Model_Attachment> $attachments attachments
	 * @return void
	 */
	public function setAttachments(Tx_Extbase_Persistence_ObjectStorage $attachments) {
		$this->attachments = $attachments;
	}

	/**
	 * Getter for attachments
	 *
	 * @return Tx_Extbase_Persistence_ObjectStorage<Tx_MmMessaging_Domain_Model_Attachment> attachments
	 */
	public function getAttachments() {
		return $this->attachments;
	}
	
	/**
	 * Adds a Attachment
	 *
	 * @param Tx_MmMessaging_Domain_Model_Attachment The Attachment to be added
	 * @return void
	 */
	public function addAttachment(Tx_MmMessaging_Domain_Model_Attachment $attachment) {
		$this->attachments->attach($attachment);
	}
	
	/**
	 * Removes a Attachment
	 *
	 * @param Tx_MmMessaging_Domain_Model_Attachment The Attachment to be removed
	 * @return void
	 */
	public function removeAttachment(Tx_MmMessaging_Domain_Model_Attachment $attachment) {
		$this->attachments->detach($attachment);
	}
	
	/**
	 * Setter for anwers
	 *
	 * @param Tx_Extbase_Persistence_ObjectStorage<Tx_MmMessaging_Domain_Model_Message> $anwers anwers
	 * @return void
	 */
	public function setAnwers(Tx_Extbase_Persistence_ObjectStorage $anwers) {
		$this->anwers = $anwers;
	}

	/**
	 * Getter for anwers
	 *
	 * @return Tx_Extbase_Persistence_ObjectStorage<Tx_MmMessaging_Domain_Model_Message> anwers
	 */
	public function getAnwers() {
		return $this->anwers;
	}
	
	/**
	 * Adds a Message
	 *
	 * @param Tx_MmMessaging_Domain_Model_Message The Message to be added
	 * @return void
	 */
	public function addAnwer(Tx_MmMessaging_Domain_Model_Message $anwer) {
		$this->anwers->attach($anwer);
	}
	
	/**
	 * Removes a Message
	 *
	 * @param Tx_MmMessaging_Domain_Model_Message The Message to be removed
	 * @return void
	 */
	public function removeAnwer(Tx_MmMessaging_Domain_Model_Message $anwer) {
		$this->anwers->detach($anwer);
	}

	public function getCrdate() {
		return $this->crdate;
	}

	public function isArchived() {
		return $this->archived;
	}

	public function setArchived($archived=TRUE) {
		$this->archived = $archived;
	}  
	
}
?>