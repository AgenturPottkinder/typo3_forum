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
	 * A forum. Forums can be infinitely nested and contain a number of topics. Forums
	 * are submitted to the access control mechanism and can be subscribed by users.
	 *
	 * @author     Martin Helmich <m.helmich@mittwald.de>
	 * @package    MmForum
	 * @subpackage Domain_Model_Forum
	 * @version    $Id$
	 * @license    GNU Public License, version 2
	 *             http://opensource.org/licenses/gpl-license.php
	 *
	 */

Class Tx_MmForum_Domain_Model_Forum_Forum
	Extends    Tx_Extbase_DomainObject_AbstractEntity
	Implements Tx_MmForum_Domain_Model_AccessibleInterface,
	           Tx_MmForum_Domain_Model_SubscribeableInterface {





		/*
		 * ATTRIBUTES
		 */





		/**
		 * The title of the forum
		 * @var string
		 * @validate NotEmpty
		 */
	Protected $title;

		/**
		 * A description for the forum
		 * @var string
		 */
	Protected $description;

		/**
		 * The child forums
		 * @var Tx_Extbase_Persistence_ObjectStorage<Tx_MmForum_Domain_Model_Forum_Forum>
		 */
	Protected $children;

		/**
		 * The topics in this forum
		 * @var Tx_Extbase_Persistence_ObjectStorage<Tx_MmForum_Domain_Model_Forum_Topic>
		 */
	Protected $topics;

		/**
		 * All access rules
		 * @var Tx_Extbase_Persistence_ObjectStorage<Tx_MmForum_Domain_Model_Forum_Access>
		 */
	Protected $acls;

		/**
		 * The last topic.
		 * @var Tx_MmForum_Domain_Model_Forum_Topic
		 */
	Protected $lastTopic;

		/**
		 * The last post
		 * @var Tx_MmForum_Domain_Model_Forum_Post
		 * @lazy
		 */
	Protected $lastPost;

		/**
		 * The parent forum
		 * @var Tx_MmForum_Domain_Model_Forum_Forum
		 */
	Protected $forum;

		/**
		 * All subscribers of this forum
		 * @var Tx_Extbase_Persistence_ObjectStorage<Tx_MmForum_Domain_Model_User_FrontendUser>
		 */
	Protected $subscribers;

	Private $_modifiedParent = FALSE;





		/*
		 * CONSTRUCTOR
		 */





		/**
		 *
		 * Constructor. Initializes all Tx_Extbase_Persistence_ObjectStorage instances.
		 *
		 */

	Public Function __construct() {
		$this->children = new Tx_Extbase_Persistence_ObjectStorage();
		$this->topics = new Tx_Extbase_Persistence_ObjectStorage();
		$this->acls = new Tx_Extbase_Persistence_ObjectStorage();
	}





		/*
		 * GETTERS
		 */





		/**
		 *
		 * Gets the forum title
		 * @return string The title of the forum
		 *
		 */

	Public Function getTitle() { Return $this->title; }



		/**
		 *
		 * Gets the forum description
		 * @return string A description for the forum
		 *
		 */

	Public Function getDescription() {Return $this->description; }



		/**
		 *
		 * Gets all child forums
		 * @return Tx_Extbase_Persistence_ObjectStorage<Tx_MmForum_Domain_Model_Forum_Forum>
		 *                             All child forums
		 *
		 */

	Public Function getChildren() { Return $this->children; }



		/**
		 *
		 * Gets all topics
		 * @return Tx_Extbase_Persistence_ObjectStorage<Tx_MmForum_Domain_Model_Forum_Topic>
		 *                             All topics in this forum
		 *
		 */

	Public Function getTopics() { Return $this->topics; }



		/**
		 *
		 * Gets all access rules.
		 * @return Tx_Extbase_Persistence_ObjectStorage<Tx_MmForum_Domain_Model_Forum_Access>
		 *                             All access rules for this forum.
		 *
		 */

	Public Function getAcls() { Return $this->acls; }



		/**
		 *
		 * Gets the last topic.
		 * @return Tx_MmForum_Domain_Model_Forum_Topic The last topic
		 *
		 */

	Public Function getLastTopic() { Return $this->lastTopic; }



		/**
		 *
		 * Gets the last post.
		 * @return Tx_MmForum_Domain_Model_Forum_Post The last post
		 *
		 */

	Public Function getLastPost() {
		If($this->lastPost InstanceOf Tx_Extbase_Persistence_LazyLoadingProxy) {
			$this->lastPost->_loadRealInstance();
		} Return $this->lastPost;
	}



		/**
		 *
		 * Gets the parent forum.
		 * @return Tx_MmForum_Domain_Model_Forum_Forum The parent forum
		 *
		 */

	Public Function getForum() { Return $this->forum; }

		/**
		 *
		 * Alias for getForum().
		 * @return Tx_MmForum_Domain_Model_Forum_Forum The parent forum
		 *
		 */
	Public Function getParent() { Return $this->getForum(); }



		/**
		 *
		 * Gets the amount of topics in this forum.
		 *
		 * @todo   Performance!
		 * @return integer The number of topics in this forum
		 *
		 */

	Public Function getTopicCount() {
		$count = count($this->topics);
		ForEach($this->getChildren() As $child) $count += $child->getTopicCount();
		Return $count;
	}



		/**
		 *
		 * Gets the amount of posts in this forum and all subforums.
		 *
		 * @todo   Make this performant!
		 * @return integer The amount of posts in this forum and all subforums.
		 *
		 */

	Public Function getPostCount() {
		ForEach($this->getTopics() As $topic)   $count += $topic->getPostCount();
		ForEach($this->getChildren() As $child) $count += $child->getPostCount();
		Return (int)$count;
	}



		/**
		 *
		 * Gets all users who have subscribes to this forum.
		 * @return Tx_Extbase_Persistence_ObjectStorage<Tx_MmForum_Domain_Model_User_FrontendUser>
		 *                             All subscribers of this forum.
		 *
		 */

	Public Function getSubscribers() { Return $this->subscribers; }



		/**
		 *
		 * Determines if this forum (i.e. all topics in it) has been read by the
		 * currently logged in user.
		 * @param Tx_MmForum_Domain_Model_User_FrontendUser $user
		 *                             The user.
		 * @return boolean             TRUE, if all topics in this forum have been read,
		 *                             otherwise FALSE.
		 *
		 */
	Public Function hasBeenReadByUser(Tx_MmForum_Domain_Model_User_FrontendUser $user=NULL) {
		ForEach($this->getTopics() As $topic) {
			If(!$topic->hasBeenReadByUser($user)) Return FALSE;
		} Return TRUE;
	}



		/**
		 *
		 * Performs an access check for this forum.
		 *
		 * *INTERAL USE ONLY!*
		 *
		 * @param  Tx_MmForum_Domain_Model_User_FrontendUser $user
		 *                             The user that is to be checked against the access
		 *                             rules of this forum.
		 * @param  string $accessType  The operation
		 * @return boolean             TRUE, if the user has access to the requested
		 *                             operation, otherwise FALSE.
		 *
		 */
	
	Public Function _checkAccess(Tx_MmForum_Domain_Model_User_FrontendUser $user=NULL, $accessType='read') {
		If(count($this->acls) == 0) {
			If($this->getParent() != NULL) Return $this->getParent()->_checkAccess($user, $accessType);
			Else Return $accessType == 'read';
		} Else {
			$found = FALSE;
			ForEach($this->acls As $acl) {
				If($acl->getOperation() !== $accessType) Continue;
				Else {
					If($acl->isEveryone() || ($user !== NULL && (($acl->getGroup !== NULL && $user->isInGroup($acl->getGroup())) || $acl->isAnyLogin()))) {
						If($acl->isNegated()) Return FALSE; Else $found = TRUE;
					}
				}
			} Return $found ? TRUE : ($this->getParent() != NULL ? $this->getParent()->_checkAccess($user, $accessType) : FALSE);
		}
	}



		/**
		 *
		 * Checks if a user has read access to this forum.
		 *
		 * @param  Tx_MmForum_Domain_Model_User_FrontendUser $user
		 *                             The user that is to be checked.
		 * @return boolean             TRUE if the user has read access, otherwise FALSE.
		 *
		 */

	Public Function checkReadAccess(Tx_MmForum_Domain_Model_User_FrontendUser $user=NULL) {
		Return $this->_checkAccess($user, 'read');
	}



		/**
		 *
		 * Checks if a user has access to create new posts in this forum.
		 *
		 * @param  Tx_MmForum_Domain_Model_User_FrontendUser $user
		 *                             The user that is to be checked.
		 * @return boolean             TRUE if the user has access, otherwise FALSE.
		 *
		 */

	Public Function checkNewPostAccess(Tx_MmForum_Domain_Model_User_FrontendUser $user=NULL) {
		Return $this->_checkAccess($user, 'newPost');
	}



		/**
		 *
		 * Checks if a user has access to create new topics in this forum.
		 *
		 * @param  Tx_MmForum_Domain_Model_User_FrontendUser $user
		 *                             The user that is to be checked.
		 * @return boolean             TRUE if the user has access, otherwise FALSE.
		 *
		 */

	Public Function checkNewTopicAccess(Tx_MmForum_Domain_Model_User_FrontendUser $user=NULL) {
		Return $this->_checkAccess($user, 'newTopic');
	}



		/**
		 *
		 * Checks if a user has access to moderate in this forum.
		 *
		 * @param  Tx_MmForum_Domain_Model_User_FrontendUser $user
		 *                             The user that is to be checked.
		 * @return boolean             TRUE if the user has access, otherwise FALSE.
		 *
		 */

	Public Function checkModerationAccess(Tx_MmForum_Domain_Model_User_FrontendUser $user=NULL) {
		If($user === NULL) Return FALSE;
		Return $this->_checkAccess($user, 'moderate');
	}





		/*
		 * SETTERS
		 */





		/**
		 *
		 * Sets the title
		 *
		 * @param string $title The title of the forum
		 * @return void
		 *
		 */

	Public Function setTitle($title) { $this->title = $title; }



		/**
		 *
		 * Sets the description
		 *
		 * @param string $description A description for the forum
		 * @return void
		 *
		 */

	Public Function setDescription($description) {
		$this->description = $description;
	}



		/**
		 *
		 * Adds a child forum
		 *
		 * @param Tx_MmForum_Domain_Model_Forum_Forum The Forum to be added
		 * @return void
		 *
		 */

	Public Function addChild(Tx_MmForum_Domain_Model_Forum_Forum $child) {
		$this->children->attach($child);
	}



		/**
		 *
		 * Removes a child forum
		 *
		 * @param Tx_MmForum_Domain_Model_Forum_Forum The Forum to be removed
		 * @return void
		 *
		 */

	Public Function removeChild(Tx_MmForum_Domain_Model_Forum_Forum $child) {
		$this->children->detach($child);
	}



		/**
		 *
		 * Adds a topic
		 *
		 * @param Tx_MmForum_Domain_Model_Forum_Topic The Topic to be added
		 * @return void
		 *
		 */

	Public Function addTopic(Tx_MmForum_Domain_Model_Forum_Topic $topic) {

		If($this->lastTopic === NULL || $this->lastTopic->getTimestamp() <= $topic->getTimestamp())
			$this->setLastTopic($topic);
		If($this->lastPost === NULL || $this->lastPost->getTimestamp() <= $topic->getLastPost()->getTimestamp())
			$this->setLastPost($topic->getLastPost());

		$this->topics->attach($topic);
	}



		/**
		 *
		 * Removes a topic
		 *
		 * @param Tx_MmForum_Domain_Model_Forum_Topic The Topic to be removed
		 * @return void
		 *
		 */

	Public Function removeTopic(Tx_MmForum_Domain_Model_Forum_Topic $topic) {
		$this->topics->detach($topic);

		If($this->lastTopic === $topic)
			$this->resetLastTopic();
		If($this->lastPost->getTopic() === $topic)
			$this->setLastPost($this->lastTopic->getLastPost());
	}



		/**
		 *
		 * Sets the access rules for this forum
		 *
		 * @param Tx_Extbase_Persistence_ObjectStorage<Tx_MmForum_Domain_Model_Forum_Access> $acls acls
		 * @return void
		 *
		 */

	Public Function setAcls(Tx_Extbase_Persistence_ObjectStorage $acls) {
		$this->acls = $acls;
	}



		/**
		 *
		 * Adds a new access rule
		 *
		 * @param Tx_MmForum_Domain_Model_Forum_Access The access rule to be added
		 * @return void
		 *
		 */

	Public Function addAcl(Tx_MmForum_Domain_Model_Forum_Access $acl) {
		$this->acls->attach($acl);
	}



		/**
		 *
		 * Removes a access rule
		 *
		 * @param Tx_MmForum_Domain_Model_Forum_Access The access rule to be removed
		 * @return void
		 *
		 */

	Public Function removeAcl(Tx_MmForum_Domain_Model_Forum_Access $acl) {
		$this->acls->detach($acl);
	}



		/**
		 *
		 * Sets the last topic.
		 *
		 * @param Tx_MmForum_Domain_Model_Forum_Topic $lastTopic The last topic
		 * @return void
		 *
		 */

	Public Function setLastTopic(Tx_MmForum_Domain_Model_Forum_Topic $lastTopic) {

		$this->lastTopic = NULL;
		$this->_memorizePropertyCleanState('lastTopic');
		$this->lastTopic = $lastTopic;

		If($this->getParent() && ($this->getParent()->getLastTopic() === NULL || $this->getParent()->getLastTopic()->getTimestamp() < $lastTopic->getTimestamp())) {
			$this->getParent()->setLastTopic ($lastTopic);
			$this->_modifiedParent = TRUE;
		}
	}



		/**
		 *
		 * Sets the last post.
		 *
		 * @param Tx_MmForum_Domain_Model_Forum_Post $lastPost The last post.
		 * @return void
		 *
		 */

	Public Function setLastPost(Tx_MmForum_Domain_Model_Forum_Post $lastPost) {
		$this->lastPost = NULL;
		$this->_memorizePropertyCleanState('lastPost');
		$this->lastPost = $lastPost;

		If($this->getParent() && ($this->getParent()->getLastPost() === NULL || $this->getParent()->getLastPost()->getTimestamp() < $lastPost->getTimestamp())) {
			$this->getParent()->setLastPost($lastPost);
			$this->_modifiedParent = TRUE;
		}
	}



		/**
		 *
		 * Resets the last posts.
		 * @return void
		 *
		 */
	
	Public Function resetLastPost() {
		$lastPost = NULL;
		ForEach($this->topics As $topic) {
			If($lastPost === NULL || $topic->getLastPost()->getTimestamp() < $lastPost->getTimestamp())
				$lastPost = $topic->getLastPost();
		} $this->setLastPost($lastPost);
	}

	Public Function resetLastTopic() {
		$lastTopic = NULL;
		ForEach($this->topics As $topic) {
			If($lastTopic === NULL || $topic->getLastPost()->getTimestamp() < $lastTopic->getTimestamp())
				$lastTopic = $topic;
		} $this->setLastTopic($lastTopic);
	}

}
?>