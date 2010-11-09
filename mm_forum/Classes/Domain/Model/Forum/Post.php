<?php

/***************************************************************
*  Copyright notice
*
*  (c) 2010 Martin Helmich <m.helmich@mittwald.de>, Mittwald CM Service
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
 * Post
 *
 * @version $Id$
 * @copyright Copyright belongs to the respective authors
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 */
Class Tx_MmForum_Domain_Model_Forum_Post
	Extends Tx_Extbase_DomainObject_AbstractEntity
	Implements Tx_MmForum_Domain_Model_AccessibleInterface{





		/*
		 * ATTRIBUTES
		 */





		/**
		 * text
		 * @var string
		 */
	Protected $text;

		/**
		 * author
		 * @var Tx_MmForum_Domain_Model_User_FrontendUser
		 * @lazy
		 */
	Protected $author;

		/**
		 * A topic
		 * @var Tx_MmForum_Domain_Model_Forum_Topic
		 */
	Protected $topic;

		/**
		 * Creation date
		 * @var DateTime
		 */
	Protected $crdate;

		/**
		 * Attachments
		 * @var Tx_Extbase_Persistence_ObjectStorage<Tx_MmForum_Domain_Model_Forum_Attachment>
		 */
	Protected $attachments;





		/*
		 * CONSTRUCTOR
		 */





		/**
		 *
		 * Creates a new post.
		 *
		 */
	
	Public Function __construct() {
		$this->attachments = new Tx_Extbase_Persistence_ObjectStorage();
		$this->crdate      = new DateTime();
	}





		/*
		 * GETTERS
		 */





		/**
		 *
		 * Getter for text
		 * @return string text
		 *
		 */

	Public Function getText() {
		Return $this->text;
	}



		/**
		 *
		 * Getter for author
		 * @return Tx_MmForum_Domain_Model_User_FrontendUser author
		 *
		 */

	Public Function getAuthor() {
		If($this->author InstanceOf Tx_Extbase_Persistence_LazyLoadingProxy)
			$this->author->_loadRealInstance();
		Return $this->author;
	}



		/**
		 *
		 * Getter for topic
		 * @return Tx_MmForum_Domain_Model_Forum_Topic A topic
		 *
		 */

	Public Function getTopic() {
		Return $this->topic;
	}



		/**
		 *
		 * Gets the forum
		 * @return Tx_MmForum_Domain_Model_Forum_Forum
		 *
		 */

	Public Function getForum() {
		Return $this->topic->getForum();
	}



		/**
		 *
		 * Gets the post's timestamp
		 * @return DateTime
		 *
		 */

	Public Function getTimestamp() {
		Return $this->crdate;
	}



		/**
		 *
		 * Gets the post's attachments
		 * @return Tx_Extbase_Persistence_ObjectStorage<Tx_MmForum_Domain_Model_Forum_Attachment>
		 *
		 */

	Public Function getAttachments() {
		Return $this->attachments;
	}



		/**
		 *
		 * @param mixed $previousValue
		 * @param mixed $currentValue
		 * @return boolean
		 *
		 */

	Protected Function isPropertyDirty($previousValue, $currentValue) {
		If (   $currentValue InstanceOf Tx_MmForum_Domain_Model_Forum_Forum
		    || $currentValue InstanceOf Tx_MmForum_Domain_Model_Forum_Topic ) Return FALSE;
		Else Return parent::isPropertyDirty ($previousValue, $currentValue);
	}



		/**
		 *
		 * Performs an access check for this post.
		 *
		 * @param Tx_MmForum_Domain_Model_User_FrontendUser $user
		 * @param string $accessType
		 * @return boolean
		 *
		 */

	Public Function  _checkAccess(Tx_MmForum_Domain_Model_User_FrontendUser $user=NULL, $accessType = 'read') {
		Switch($accessType) {
			Case 'editPost': Return $this->checkEditPostAccess($user);
			Default: Return $this->topic->_checkAccess($user, $accessType);
		}
	}



		/**
		 *
		 * Determines if a user may edit this post.
		 *
		 * @param Tx_MmForum_Domain_Model_User_FrontendUser $user
		 * @return boolean
		 *
		 */

	Public Function checkEditPostAccess(Tx_MmForum_Domain_Model_User_FrontendUser $user=NULL) {

		If($user === NULL)
			Return FALSE;
		If($this->getForum()->checkModerationAccess($user))
			Return TRUE;
		If($user->getUid() === $this->getAuthor()->getUid() && $this->getTopic()->_checkAccess($user, 'editPost'))
			Return TRUE;

	}





		/*
		 * SETTERS
		 */





		/**
		 *
		 * Setter for author
		 *
		 * @param Tx_MmForum_Domain_Model_User_FrontendUser $author author
		 * @return void
		 *
		 */

	Public Function setAuthor(Tx_MmForum_Domain_Model_User_FrontendUser $author) {
		$this->author = $author;
	}



		/**
		 *
		 * Setter for text
		 *
		 * @param string $text text
		 * @return void
		 *
		 */

	Public Function setText($text) {
		$this->text = $text;
	}



		/**
		 *
		 * Adds an attachment
		 * @param Tx_MmForum_Domain_Model_Forum_Attachment $attachment
		 *
		 */

	Public Function addAttachment(Tx_MmForum_Domain_Model_Forum_Attachment $attachment) {
		$this->attachments->attach($attachment);
	}



		/**
		 *
		 * Removes an attachment
		 * @param Tx_MmForum_Domain_Model_Forum_Attachment $attachment
		 *
		 */

	Public Function removeAttachment(Tx_MmForum_Domain_Model_Forum_Attachment $attachment) {
		$this->attachments->detach($attachment);
	}

}
?>
