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
	 * Write me!
	 *
	 * @author     Martin Helmich <m.helmich@mittwald.de>
	 * @package    MmForum
	 * @subpackage Domain_Model_Forum
	 * @version    $Id$
	 * @license    GNU Public License, version 2
	 *             http://opensource.org/licenses/gpl-license.php
	 *
	 */

Class Tx_MmForum_Domain_Model_Forum_Topic
	Extends    Tx_Extbase_DomainObject_AbstractEntity
	Implements Tx_MmForum_Domain_Model_AccessibleInterface,
	           Tx_MmForum_Domain_Model_SubscribeableInterface {





		/*
		 * ATTRIBUTES
		 */





		/**
		 * The subject
		 * @var string
		 * @validate NotEmpty
		 */
	Protected $subject;
	
		/**
		 * The posts in this topic
		 * @var Tx_Extbase_Persistence_ObjectStorage<Tx_MmForum_Domain_Model_Forum_Post>
		 */
	Protected $posts;
	
		/**
		 * The user who created the topic
		 * @var Tx_MmForum_Domain_Model_User_FrontendUser
		 */
	Protected $author;

		/**
		 * @var Tx_Extbase_Persistence_ObjectStorage<Tx_MmForum_Domain_Model_User_FrontendUser>
		 * @lazy
		 */
	Protected $subscribers;
	
		/**
		 * A pointer to the last post in this topic.
		 * @var Tx_MmForum_Domain_Model_Forum_Post
		 * @lazy
		 */
	Protected $lastPost;
	
		/**
		 * The forum in which this topic is located
		 * @var Tx_MmForum_Domain_Model_Forum_Forum
		 */
	Protected $forum;

		/**
		 * Defines whether this topic is closed
		 * @var boolean
		 */
	Protected $closed;

		/**
		 * Defines whether this topic is sticky
		 * @var boolean
		 */
	Protected $sticky;

		/**
		 * The topic date.
		 * @var DateTime
		 */
	Protected $crdate;





		/*
		 * CONSTRUCTOR
		 */





		/**
		 *
		 * Constructor. Initializes all Tx_Extbase_Persistence_ObjectStorage instances.
		 *
		 */

	Public Function __construct() {
		$this->posts = new Tx_Extbase_Persistence_ObjectStorage();
		$this->crdate = new DateTime();
	}





		/*
		 * GETTER METHODS
		 */





		/**
		 *
		 * Gets the topic subject.
		 * @return string The subject
		 *
		 */

	Public Function getSubject() {
		Return $this->subject;
	}



		/**
		 *
		 * Alias for getSubject. Necessary to implement the SubscribeableInterface.
		 * @return string The subject
		 *
		 */
	
	Public Function getTitle() {
		Return $this->getSubject();
	}



		/**
		 *
		 * Gets the topic author
		 * @return Tx_MmForum_Domain_Model_User_FrontendUser author
		 *
		 */

	Public Function getAuthor() {
		Return $this->author;
	}



		/**
		 *
		 * Gets all users who have subscribes to this forum.
		 * @return Tx_Extbase_Persistence_ObjectStorage<Tx_MmForum_Domain_Model_User_FrontendUser>
		 *
		 */

	Public Function getSubscribers() {
		Return $this->subscribers;
	}



		/**
		 *
		 * Gets all posts.
		 * @return Tx_Extbase_Persistence_ObjectStorage<Tx_MmForum_Domain_Model_Forum_Post> posts
		 *
		 */

	Public Function getPosts() {
		Return $this->posts;
	}



		/**
		 *
		 * Gets the post count
		 * @todo   Performance!
		 * @return integer Post count
		 *
		 */

	Public Function getPostCount() {
		Return count($this->posts);
	}



		/**
		 *
		 * Gets the reply count.
		 * @return integer Reply count
		 *
		 */

	Public Function getReplyCount() {
		Return $this->getPostCount() - 1;
	}



		/**
		 *
		 * Gets whether the topic is closed.
		 * @return boolean
		 *
		 */

	Public Function isClosed() {
		Return $this->closed;
	}



		/**
		 *
		 * Gets the last post.
		 * @return Tx_MmForum_Domain_Model_Forum_Post lastPost
		 *
		 */

	Public Function getLastPost() {
		Return $this->lastPost;
	}



		/**
		 *
		 * Gets the forum
		 * @return Tx_MmForum_Domain_Model_Forum_Forum A forum
		 *
		 */

	Public Function getForum() {
		Return $this->forum;
	}



		/**
		 *
		 * Gets the creation time of this topic.
		 * @return DateTime
		 * 
		 */

	Public Function getTimestamp() {
		Return $this->crdate;
	}



		/**
		 *
		 * Checks if this topic is sticky.
		 * @return boolean
		 *
		 */

	Public Function isSticky() {
		Return $this->sticky;
	}



		/**
		 *
		 * Checks if a user may perform a certain operation (read, answer...) with this
		 * topic.
		 *
		 * @param Tx_MmForum_Domain_Model_User_FrontendUser $user
		 * @param string $accessType
		 * @return boolean
		 *
		 */

	Public Function _checkAccess ( Tx_MmForum_Domain_Model_User_FrontendUser $user=NULL,
	                               $accessType = 'read' ) {

		Switch($accessType) {
			Case 'newPost': Return $this->checkNewPostAccess($user);
			Default: Return $this->forum->_checkAccess($user, $accessType);
		}
	}



		/**
		 *
		 * Checks if a user may reply to this topic.
		 *
		 * @param Tx_MmForum_Domain_Model_User_FrontendUser $user
		 * @return boolean
		 *
		 */

	Public Function checkNewPostAccess(Tx_MmForum_Domain_Model_User_FrontendUser $user=NULL) {

		If($user === NULL) Return FALSE;
		Return $this->getForum()->checkModerationAccess($user)
			? TRUE : ($this->isClosed() ? FALSE : $this->getForum()->checkNewPostAccess($user));

	}

	
	
		/**
		 *
		 * Workaround to prevent endless-recursive object persisting.
		 * 
		 * @param mixed $previousValue
		 * @param mixed $currentValue
		 * @return boolean 
		 * 
		 */
	
	Protected Function isPropertyDirty($previousValue, $currentValue) {
		If($currentValue InstanceOf Tx_MmForum_Domain_Model_Forum_Forum) Return FALSE;
		Else Return parent::isPropertyDirty ($previousValue, $currentValue);
	}





		/*
		 * SETTER METHODS
		 */





		/**
		 *
		 * Adds a Post
		 *
		 * @param Tx_MmForum_Domain_Model_Forum_Post The Post to be added
		 * @return void
		 *
		 */

	Public Function addPost(Tx_MmForum_Domain_Model_Forum_Post $post) {
		$this->posts->attach($post);

		If($this->lastPost === NULL || $this->lastPost->getTimestamp() < $post->getTimestamp())
			$this->setLastPost($post);
		If($this->forum->getLastPost() === NULL || $this->forum->getLastPost()->getTimestamp() < $post->getTimestamp())
			$this->forum->setLastPost($post);
	}


	
		/**
		 *
		 * Removes a Post
		 *
		 * @param Tx_MmForum_Domain_Model_Forum_Post The Post to be removed
		 * @return void
		 *
		 */

	Public Function removePost(Tx_MmForum_Domain_Model_Forum_Post $post) {
		$this->posts->detach($post);
	}



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
		 * Setter for lastPost
		 *
		 * @param Tx_MmForum_Domain_Model_Forum_Post $lastPost lastPost
		 * @return void
		 *
		 */

	Protected Function setLastPost(Tx_MmForum_Domain_Model_Forum_Post $lastPost) {
		$this->lastPost = $lastPost;
	}



		/**
		 *
		 * Setter for subject
		 *
		 * @param string $subject subject
		 * @return void
		 *
		 */

	Public Function setSubject($subject) {
		$this->subject = $subject;
	}



		/**
		 *
		 * Sets the forum
		 * @param Tx_MmForum_Domain_Model_Forum_Forum $forum
		 *
		 */

	Public Function setForum(Tx_MmForum_Domain_Model_Forum_Forum $forum) {
		$this->forum = $forum;
	}
	
}
?>
