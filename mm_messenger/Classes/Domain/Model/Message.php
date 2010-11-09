<?php

/***************************************************************
*  Copyright notice
*
*  (c) 2010 Martin Helmich <m.helmich@mittwald.de>, Mittwald CM Service GmbH & Co. KG
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
class Tx_MmMessenger_Domain_Model_Message extends Tx_Extbase_DomainObject_AbstractEntity {
	
	/**
	 * The subject of the message
	 * @var string
	 * @validate NotEmpty
	 */
	protected $subject;
	
	/**
	 * The message content
	 * @var string
	 * @validate NotEmpty
	 */
	protected $text;
	
	/**
	 * Whether the message has been read
	 * @var boolean
	 */
	protected $read;
	
	
	
	/**
	 * Setter for subject
	 *
	 * @param string $subject The subject of the message
	 * @return void
	 */
	public function setSubject($subject) {
		$this->subject = $subject;
	}

	/**
	 * Getter for subject
	 *
	 * @return string The subject of the message
	 */
	public function getSubject() {
		return $this->subject;
	}
	
	/**
	 * Setter for text
	 *
	 * @param string $text The message content
	 * @return void
	 */
	public function setText($text) {
		$this->text = $text;
	}

	/**
	 * Getter for text
	 *
	 * @return string The message content
	 */
	public function getText() {
		return $this->text;
	}
	
	/**
	 * Setter for read
	 *
	 * @param boolean $read Whether the message has been read
	 * @return void
	 */
	public function setRead($read) {
		$this->read = $read;
	}

	/**
	 * Getter for read
	 *
	 * @return boolean Whether the message has been read
	 */
	public function getRead() {
		return $this->read;
	}
	
	/**
	 * Returns the boolean state of read
	 *
	 * @return boolean The state of read
	 */
	public function isRead() {
		return $this->getRead();
	}
	
}
?>