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
		 * children
		 * @var Tx_Extbase_Persistence_ObjectStorage<Tx_MmForum_Domain_Model_Forum_Forum>
		 */
	Protected $children;

		/**
		 * topics
		 * @var Tx_Extbase_Persistence_ObjectStorage<Tx_MmForum_Domain_Model_Forum_Topic>
		 */
	Protected $topics;

		/**
		 * acls
		 * @var Tx_Extbase_Persistence_ObjectStorage<Tx_MmForum_Domain_Model_Forum_Access>
		 */
	Protected $acls;

		/**
		 * lastTopic
		 * @var Tx_MmForum_Domain_Model_Forum_Topic
		 */
	Protected $lastTopic;

		/**
		 * lastPost
		 * @var Tx_MmForum_Domain_Model_Forum_Post
		 */
	Protected $lastPost;

		/**
		 * A forum
		 * @var Tx_MmForum_Domain_Model_Forum_Forum
		 */
	Protected $forum;

		/**
		 * @var Tx_Extbase_Persistence_ObjectStorage<Tx_MmForum_Domain_Model_User_FrontendUser>
		 */
	Protected $subscribers;





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
		 * Getter for title
		 * @return string The title of the forum
		 *
		 */

	Public Function getTitle() {
		Return $this->title;
	}



		/**
		 *
		 * Getter for description
		 * @return string A description for the forum
		 *
		 */

	Public Function getDescription() {
		Return $this->description;
	}



		/**
		 *
		 * Getter for children
		 * @return Tx_Extbase_Persistence_ObjectStorage<Tx_MmForum_Domain_Model_Forum_Forum> children
		 *
		 */

	Public Function getChildren() {
		Return $this->children;
	}



		/**
		 *
		 * Getter for topics
		 * @return Tx_Extbase_Persistence_ObjectStorage<Tx_MmForum_Domain_Model_Forum_Topic> topics
		 *
		 */

	Public Function getTopics() {
		Return $this->topics;
	}



		/**
		 *
		 * Getter for acls
		 * @return Tx_Extbase_Persistence_ObjectStorage<Tx_MmForum_Domain_Model_Forum_Access> acls
		 *
		 */

	Public Function getAcls() {
		Return $this->acls;
	}



		/**
		 *
		 * Getter for lastTopic
		 * @return Tx_MmForum_Domain_Model_Forum_Topic lastTopic
		 *
		 */

	Public Function getLastTopic() {
		Return $this->lastTopic;
	}



		/**
		 *
		 * Getter for lastPost
		 * @return Tx_MmForum_Domain_Model_Forum_Post lastPost
		 *
		 */

	Public Function getLastPost() {
		Return $this->lastPost;
	}



		/**
		 *
		 * Getter for forum
		 * @return Tx_MmForum_Domain_Model_Forum_Forum The parent forum
		 *
		 */

	Public Function getForum() {
		Return $this->forum;
	}

		/**
		 *
		 * Alias for getForum().
		 * @return Tx_MmForum_Domain_Model_Forum_Forum The parent forum
		 *
		 */
	Public Function getParent() {
		Return $this->getForum();
	}



		/**
		 *
		 * @todo   Performance!
		 * @return integer The number of topics in this forum
		 *
		 */

	Public Function getTopicCount() {
		$count = count($this->topics);
		ForEach($this->getChildren() As $child)
			$count += $child->getTopicCount();
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
		ForEach($this->getTopics() As $topic)
			$count += $topic->getPostCount();
		ForEach($this->getChildren() As $child)
			$count += $child->getPostCount();
		Return $count;
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

	Public Function checkReadAccess(Tx_MmForum_Domain_Model_User_FrontendUser $user=NULL) {
		Return $this->_checkAccess($user, 'read');
	}

	Public Function checkNewPostAccess(Tx_MmForum_Domain_Model_User_FrontendUser $user=NULL) {
		Return $this->_checkAccess($user, 'newPost');
	}

	Public Function checkNewTopicAccess(Tx_MmForum_Domain_Model_User_FrontendUser $user=NULL) {
		Return $this->_checkAccess($user, 'newTopic');
	}

	Public Function checkModerationAccess(Tx_MmForum_Domain_Model_User_FrontendUser $user=NULL) {
		If($user === NULL) Return FALSE;
		Return $this->_checkAccess($user, 'moderate');
	}





		/*
		 * SETTERS
		 */





		/**
		 *
		 * Setter for title
		 *
		 * @param string $title The title of the forum
		 * @return void
		 *
		 */

	Public Function setTitle($title) {
		$this->title = $title;
	}



		/**
		 *
		 * Setter for description
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
		 * Adds a Forum
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
		 * Removes a Forum
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
		 * Adds a Topic
		 *
		 * @param Tx_MmForum_Domain_Model_Forum_Topic The Topic to be added
		 * @return void
		 *
		 */

	Public Function addTopic(Tx_MmForum_Domain_Model_Forum_Topic $topic) {

		If($this->lastTopic->getTimestamp() <= $topic->getTimestamp())
			$this->setLastTopic($topic);
		If($this->lastPost->getTimestamp() <= $topic->getLastPost()->getTimestamp())
			$this->setLastPost($topic->getLastPost());

		$this->topics->attach($topic);
	}



		/**
		 *
		 * Removes a Topic
		 *
		 * @param Tx_MmForum_Domain_Model_Forum_Topic The Topic to be removed
		 * @return void
		 *
		 */

	Public Function removeTopic(Tx_MmForum_Domain_Model_Forum_Topic $topic) {
		$this->topics->detach($topic);
	}



		/**
		 *
		 * Setter for acls
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
		 * Adds a Access
		 *
		 * @param Tx_MmForum_Domain_Model_Forum_Access The Access to be added
		 * @return void
		 *
		 */

	Public Function addAcl(Tx_MmForum_Domain_Model_Forum_Access $acl) {
		$this->acls->attach($acl);
	}



		/**
		 *
		 * Removes a Access
		 *
		 * @param Tx_MmForum_Domain_Model_Forum_Access The Access to be removed
		 * @return void
		 *
		 */

	Public Function removeAcl(Tx_MmForum_Domain_Model_Forum_Access $acl) {
		$this->acls->detach($acl);
	}



		/**
		 *
		 * Setter for lastTopic
		 *
		 * @param Tx_MmForum_Domain_Model_Forum_Topic $lastTopic lastTopic
		 * @return void
		 *
		 */

	Public Function setLastTopic(Tx_MmForum_Domain_Model_Forum_Topic $lastTopic) {

		$this->lastTopic = NULL;
		$this->_memorizePropertyCleanState('lastTopic');
		$this->lastTopic = $lastTopic;

		If($this->getParent() && $this->getParent()->getLastTopic()->getTimestamp() < $lastTopic->getTimestamp())
			$this->getParent()->setLastTopic ($lastTopic);
	}



		/**
		 *
		 * Setter for lastPost
		 *
		 * @param Tx_MmForum_Domain_Model_Forum_Post $lastPost lastPost
		 * @return void
		 *
		 */

	Public Function setLastPost(Tx_MmForum_Domain_Model_Forum_Post $lastPost) {

		$this->lastPost = NULL;
		$this->_memorizePropertyCleanState('lastPost');
		$this->lastPost = $lastPost;

		If($this->getParent() && $this->getParent()->getLastPost()->getTimestamp() < $lastPost->getTimestamp())
			$this->getParent()->setLastPost($lastPost);
	}

}
?>