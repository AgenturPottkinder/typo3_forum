<?php

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
	 * A forum post. Forum posts are submitted to the access control mechanism and can be
	 * subscribed by users.
	 *
	 * @author     Martin Helmich <m.helmich@mittwald.de>
	 * @package    MmForum
	 * @subpackage Domain_Model_Format
	 * @version    $Id$
	 * @license    GNU Public License, version 2
	 *             http://opensource.org/licenses/gpl-license.php
	 *
	 */

Class Tx_MmForum_Domain_Model_Forum_Post
	Extends    Tx_Extbase_DomainObject_AbstractEntity
	Implements Tx_MmForum_Domain_Model_AccessibleInterface,
	           Tx_MmForum_Domain_Model_NotifiableInterface {





		/*
		 * ATTRIBUTES
		 */





		/**
		 * The post text.
		 * @var string
		 * @validate NotEmpty
		 */
	Protected $text;

		/**
		 * The post author.
		 * @var Tx_MmForum_Domain_Model_User_FrontendUser
		 * @lazy
		 */
	Protected $author;

		/**
		 * The topic.
		 * @var Tx_MmForum_Domain_Model_Forum_Topic
		 */
	Protected $topic;

		/**
		 * Creation date.
		 * @var DateTime
		 */
	Protected $crdate;

		/**
		 * Attachments.
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
		 * Gets the text.
		 * @return string The text
		 *
		 */

	Public Function getText() { Return $this->text; }



		/**
		 *
		 * Gets the post name. This is just an alias for the topic->getTitle method.
		 * @return The post name.
		 *
		 */
	
	Public Function getName() { Return $this->topic->getTitle(); }



		/**
		 *
		 * Alias for getText(). Necessary to implement the NotifiableInterface.
		 * @return string The post text.
		 *
		 */

	Public Function getDescription() { Return $this->getText(); }



		/**
		 *
		 * Gets the post author.
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
		 * Gets the topic.
		 * @return Tx_MmForum_Domain_Model_Forum_Topic A topic
		 *
		 */

	Public Function getTopic() { Return $this->topic; }



		/**
		 *
		 * Gets the forum.
		 * @return Tx_MmForum_Domain_Model_Forum_Forum
		 *
		 */

	Public Function getForum() { Return $this->topic->getForum(); }



		/**
		 *
		 * Gets the post's timestamp.
		 * @return DateTime
		 *
		 */

	Public Function getTimestamp() { Return $this->crdate; }



		/**
		 *
		 * Gets the post's attachments.
		 * @return Tx_Extbase_Persistence_ObjectStorage<Tx_MmForum_Domain_Model_Forum_Attachment>
		 *
		 */

	Public Function getAttachments() { Return $this->attachments; }



		/**
		 *
		 * Overrides the isPropertyDirty method. See http://forge.typo3.org/issues/8952
		 * for further information.
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
		 * Determines if a user may edit this post. This is only possible if EITHER:
		 *
		 * a1.) The user is the author of this post, AND
		 * a2.) This post is the last post in the topic, AND
		 * a3.) The topic generally permits posts to be edited (this would not be the
		 *      case if the topic would e.g. be closed).
		 *
		 * OR:
		 *
		 * b.)  The current user has moderator access to the forum.
		 *
		 * @param Tx_MmForum_Domain_Model_User_FrontendUser $user
		 *                             The user for which the authenication is to be
		 *                             checked.
		 * @return boolean             TRUE, if the user is allowed to edit this post,
		 *                             otherwise FALSE.
		 *
		 */

	Public Function checkEditPostAccess(Tx_MmForum_Domain_Model_User_FrontendUser $user=NULL) {

		If ( $user === NULL)                                        Return FALSE;
		If ( $this->getForum()->checkModerationAccess($user))       Return TRUE;
		If (    $user->getUid() === $this->getAuthor()->getUid()
		     && $this === $this->getTopic()->getLastPost()
		     && $this->getTopic()->_checkAccess($user, 'editPost')) Return TRUE;
		Else                                                        Return FALSE;

	}



		/**
		 *
		 * Determines if a user may delete this post. For deleting posts, the same
		 * conditions apply as for editing posts (see above).
		 *
		 * @param Tx_MmForum_Domain_Model_User_FrontendUser $user
		 *                             The user for which the authenication is to be
		 *                             checked.
		 * @return boolean             TRUE, if the user is allowed to delete this post,
		 *                             otherwise FALSE.
		 *
		 */

	Public Function checkDeletePostAccess(Tx_MmForum_Domain_Model_User_FrontendUser $user=NULL) {
		If ( $user === NULL)                                           Return FALSE;
		If ( $this->getForum()->checkModerationAccess($user))          Return TRUE;
		If (    $user === $this->getAuthor()
		     && $this === $this->getTopic()->getLastPost()
		     && $this->getTopic()->_checkAccess($user, 'deletePost') ) Return TRUE;
		Else                                                           Return FALSE;
	}





		/*
		 * SETTERS
		 */





		/**
		 *
		 * Sets the post author.
		 *
		 * @param Tx_MmForum_Domain_Model_User_FrontendUser $author The post author.
		 * @return void
		 *
		 */

	Public Function setAuthor(Tx_MmForum_Domain_Model_User_FrontendUser $author) {
		$this->author = $author;
	}



		/**
		 *
		 * Sets the post text.
		 *
		 * @param string $text The post text.
		 * @return void
		 *
		 */

	Public Function setText($text) { $this->text = $text; }



		/**
		 *
		 * Sets the attachments.
		 *
		 * @param  Tx_Extbase_Persistence_ObjectStorage $attachments The attachments.
		 * @return void
		 *
		 */

	Public Function setAttachments(Tx_Extbase_Persistence_ObjectStorage $attachments) {
		$this->attachments = $attachments;
	}



		/**
		 *
		 * Adds an attachment.
		 * @param  Tx_MmForum_Domain_Model_Forum_Attachment $attachment The attachment.
		 * @return void
		 *
		 */

	Public Function addAttachment(Tx_MmForum_Domain_Model_Forum_Attachment $attachment) {
		$this->attachments->attach($attachment);
	}



		/**
		 *
		 * Removes an attachment.
		 * @param  Tx_MmForum_Domain_Model_Forum_Attachment $attachment The attachment.
		 * @return void
		 *
		 */

	Public Function removeAttachment(Tx_MmForum_Domain_Model_Forum_Attachment $attachment) {
		$this->attachments->detach($attachment);
	}

}
?>
