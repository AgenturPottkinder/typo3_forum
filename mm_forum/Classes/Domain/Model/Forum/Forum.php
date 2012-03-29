<?php

/*                                                                    - *
 *  COPYRIGHT NOTICE                                                    *
 *                                                                      *
 *  (c) 2012 Martin Helmich <m.helmich@mittwald.de>                     *
 *           Mittwald CM Service GmbH & Co KG                           *
 *           All rights reserved                                        *
 *                                                                      *
 *  This script is part of the TYPO3 project. The TYPO3 project is      *
 *  free software; you can redistribute it and/or modify                *
 *  it under the terms of the GNU General public License as published   *
 *  by the Free Software Foundation; either version 2 of the License,   *
 *  or (at your option) any later version.                              *
 *                                                                      *
 *  The GNU General public License can be found at                      *
 *  http://www.gnu.org/copyleft/gpl.html.                               *
 *                                                                      *
 *  This script is distributed in the hope that it will be useful,      *
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of      *
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the       *
 *  GNU General public License for more details.                        *
 *                                                                      *
 *  This copyright notice MUST APPEAR in all copies of the script!      *
 *                                                                      */



/**
 * A forum. Forums can be infinitely nested and contain a number of topics. Forums
 * are submitted to the access control mechanism and can be subscribed by users.
 *
 * @author     Martin Helmich <m.helmich@mittwald.de>
 * @package    MmForum
 * @subpackage Domain_Model_Forum
 * @version    $Id$
 * @license    GNU public License, version 2
 *             http://opensource.org/licenses/gpl-license.php

 */
class Tx_MmForum_Domain_Model_Forum_Forum
	extends Tx_Extbase_DomainObject_AbstractEntity
	implements Tx_MmForum_Domain_Model_AccessibleInterface,
	           Tx_MmForum_Domain_Model_SubscribeableInterface
{



	/*
	 * ATTRIBUTES
	 */



	/**
	 * The title of the forum
	 *
	 * @var string
	 * @validate NotEmpty
	 */
	protected $title;



	/**
	 * A description for the forum
	 *
	 * @var string
	 */
	protected $description;



	/**
	 * The child forums
	 *
	 * @var Tx_Extbase_Persistence_ObjectStorage<Tx_MmForum_Domain_Model_Forum_Forum>
	 */
	protected $children;



	/**
	 * The VISIBLE child forums of this forum, i.e. all forums that the
	 * currently logged in user has read access to.
	 *
	 * @var ArrayObject<Tx_MmForum_Domain_Model_Forum_Forum>
	 */
	protected $visibleChildren = NULL;



	/**
	 * The topics in this forum
	 *
	 * @var Tx_Extbase_Persistence_ObjectStorage<Tx_MmForum_Domain_Model_Forum_Topic>
	 */
	protected $topics;



	/**
	 * Amount of topics in this forum.
	 *
	 * @var int
	 */
	protected $topicCount;



	/**
	 * The amount of post in this forum.
	 *
	 * @var int
	 */
	protected $postCount;



	/**
	 * All access rules
	 *
	 * @var Tx_Extbase_Persistence_ObjectStorage<Tx_MmForum_Domain_Model_Forum_Access>
	 */
	protected $acls;



	/**
	 * The last topic.
	 *
	 * @var Tx_MmForum_Domain_Model_Forum_Topic
	 */
	protected $lastTopic;



	/**
	 * The last post
	 *
	 * @var Tx_MmForum_Domain_Model_Forum_Post
	 * @lazy
	 */
	protected $lastPost;



	/**
	 * The parent forum
	 *
	 * @var Tx_MmForum_Domain_Model_Forum_Forum
	 */
	protected $forum;



	/**
	 * All subscribers of this forum
	 *
	 * @var Tx_Extbase_Persistence_ObjectStorage<Tx_MmForum_Domain_Model_User_FrontendUser>
	 */
	protected $subscribers;



	/**
	 * @var bool
	 */
	private $_modifiedParent = FALSE;



	/**
	 * An instance of the Extbase object manager.
	 *
	 * @var Tx_Extbase_Object_ObjectManagerInstance
	 */
	protected $objectManager = NULL;



	/*
	  * CONSTRUCTOR
	  */



	/**
	 * Constructor. Initializes all Tx_Extbase_Persistence_ObjectStorage instances.
	 */
	public function __construct()
	{
		$this->children = new Tx_Extbase_Persistence_ObjectStorage();
		$this->topics   = new Tx_Extbase_Persistence_ObjectStorage();
		$this->acls     = new Tx_Extbase_Persistence_ObjectStorage();
	}



	/**
	 * @param Tx_Extbase_Object_ObjectManagerInterface $objectManager
	 */
	public function injectObjectManager(Tx_Extbase_Object_ObjectManagerInterface $objectManager)
	{
		$this->objectManager = $objectManager;
	}



	/*
	 * GETTERS
	 */



	/**
	 * Gets the forum title
	 *
	 * @return string The title of the forum
	 */
	public function getTitle()
	{
		return $this->title;
	}



	/**
	 * Gets the forum description
	 *
	 * @return string A description for the forum
	 */
	public function getDescription()
	{
		return $this->description;
	}



	/**
	 * Gets all VISIBLE child forums. This function does NOT simply return
	 * all child forums, but performs an access check on each forum, so
	 * that only forums visible to the current user are returned.
	 *
	 * @return Tx_Extbase_Persistence_ObjectStorage<Tx_MmForum_Domain_Model_Forum_Forum>
	 *                             All visible child forums
	 */
	public function getChildren()
	{
		if ($this->visibleChildren === NULL)
		{
			$this->visibleChildren = new ArrayObject();

			$authenticationService = $this->objectManager->get('Tx_MmForum_Service_Authentication_AuthenticationServiceInterface');
			foreach ($this->children as $child)
			{
				if ($authenticationService->checkAuthorization($child, 'read'))
					$this->visibleChildren->append($child);
			}
		}

		return $this->visibleChildren;
	}



	/**
	 * Gets all topics
	 *
	 * @return Tx_Extbase_Persistence_ObjectStorage<Tx_MmForum_Domain_Model_Forum_Topic>
	 *                             All topics in this forum
	 */
	public function getTopics()
	{
		return $this->topics;
	}



	/**
	 * Gets all access rules.
	 *
	 * @return Tx_Extbase_Persistence_ObjectStorage<Tx_MmForum_Domain_Model_Forum_Access>
	 *                             All access rules for this forum.
	 */
	public function getAcls()
	{
		return $this->acls;
	}



	/**
	 * Gets the last topic.
	 *
	 * @return Tx_MmForum_Domain_Model_Forum_Topic The last topic
	 */
	public function getLastTopic()
	{
		return $this->lastTopic;
	}



	/**
	 * Gets the last post.
	 *
	 * @return Tx_MmForum_Domain_Model_Forum_Post The last post
	 */
	public function getLastPost()
	{
		if ($this->lastPost InstanceOf Tx_Extbase_Persistence_LazyLoadingProxy)
		{
			$this->lastPost->_loadRealInstance();
		}
		return $this->lastPost;
	}



	/**
	 * Gets the parent forum.
	 *
	 * @return Tx_MmForum_Domain_Model_Forum_Forum The parent forum
	 */
	public function getForum()
	{
		return $this->forum;
	}



	/**
	 * Alias for getForum().
	 *
	 * @return Tx_MmForum_Domain_Model_Forum_Forum The parent forum
	 */
	public function getParent()
	{
		return $this->getForum();
	}



	/**
	 * Gets the amount of topics in this forum.
	 *
	 * @return integer The number of topics in this forum
	 */
	public function getTopicCount()
	{
		return $this->topicCount;
	}



	/**
	 * Gets the amount of posts in this forum and all subforums.
	 *
	 * @return integer The amount of posts in this forum and all subforums.
	 */
	public function getPostCount()
	{
		return $this->postCount;
	}



	/**
	 * Gets all users who have subscribes to this forum.
	 *
	 * @return Tx_Extbase_Persistence_ObjectStorage<Tx_MmForum_Domain_Model_User_FrontendUser>
	 *                             All subscribers of this forum.
	 */
	public function getSubscribers()
	{
		return $this->subscribers;
	}



	/**
	 * Determines if this forum (i.e. all topics in it) has been read by the
	 * currently logged in user.
	 *
	 * @param Tx_MmForum_Domain_Model_User_FrontendUser $user
	 *                             The user.
	 *
	 * @return boolean             TRUE, if all topics in this forum have been read,
	 *                             otherwise FALSE.
	 */
	public function hasBeenReadByUser(Tx_MmForum_Domain_Model_User_FrontendUser $user = NULL)
	{
		if ($user === NULL) return TRUE;

		foreach ($this->getTopics() As $topic)
		{
			/** @var $topic Tx_MmForum_Domain_Model_Forum_Topic */
			if (!$topic->hasBeenReadByUser($user))
				return FALSE;
		}
		return TRUE;
	}



	/**
	 * Returns all parent forums in hiearchical order as a flat list (optionally
	 * with or without this forum itself).
	 *
	 * @param  boolean $withSelf TRUE to include this forum into the rootline,
	 *                           otherwise FALSE.
	 *
	 * @return array<Tx_MmForum_Domain_Model_Forum_Forum>
	 */
	public function getRootline($withSelf = TRUE)
	{
		$rootline = $this->forum === NULL ? array() : $this->forum->getRootline(TRUE);

		if ($withSelf === TRUE)
		{
			$rootline[] = $this;
		}

		return $rootline;
	}



	/**
	 * Performs an access check for this forum.
	 * *INTERAL USE ONLY!*
	 *
	 * @param  Tx_MmForum_Domain_Model_User_FrontendUser $user
	 *                                                                The user that is to be checked against the access
	 *                                                                rules of this forum.
	 * @param  string                                    $accessType  The operation
	 *
	 * @return boolean             TRUE, if the user has access to the requested
	 *                             operation, otherwise FALSE.
	 */
	public function _checkAccess(Tx_MmForum_Domain_Model_User_FrontendUser $user = NULL,
	                             $accessType = 'read')
	{

		# If there aren't any access rules defined for this forum, delegate
		# the access check to the parent forum. If there is no parent forum
		# either, simply deny access (except for 'read' operations).
		if (count($this->acls) === 0)
		{
			if ($this->getParent() != NULL)
				return $this->getParent()->_checkAccess($user, $accessType);
			else
				return $accessType === 'read';
		}

		# Iterate over all access rules, until a matching rule is found
		# that explicitly grants or denies access. If no matching rule is
		# found, delegate to the parent object or deny access (grant read
		# access, if no parent is set).
		$found = FALSE;
		foreach ($this->acls As $acl)
		{
			if ($acl->getOperation() !== $accessType)
				Continue;

			if ($acl->isEveryone() || ($user !== NULL && (($acl->getGroup() !== NULL && $user->isInGroup($acl->getGroup())) || $acl->isAnyLogin())))
			{
				if ($acl->isNegated())
					return FALSE;
				else
					$found = TRUE;
			}
		}
		return $found ? TRUE : ($this->getParent() != NULL ? $this->getParent()->_checkAccess($user,
		                                                                                      $accessType) : $accessType === 'read');
	}



	/**
	 * Checks if a user has read access to this forum.
	 *
	 * @param  Tx_MmForum_Domain_Model_User_FrontendUser $user
	 *                             The user that is to be checked.
	 *
	 * @return boolean             TRUE if the user has read access, otherwise FALSE.
	 */
	public function checkReadAccess(Tx_MmForum_Domain_Model_User_FrontendUser $user = NULL)
	{
		return $this->_checkAccess($user, 'read');
	}



	/**
	 * Checks if a user has access to create new posts in this forum.
	 *
	 * @param  Tx_MmForum_Domain_Model_User_FrontendUser $user
	 *                             The user that is to be checked.
	 *
	 * @return boolean             TRUE if the user has access, otherwise FALSE.
	 */
	public function checkNewPostAccess(Tx_MmForum_Domain_Model_User_FrontendUser $user = NULL)
	{
		return $this->_checkAccess($user, 'newPost');
	}



	/**
	 * Checks if a user has access to create new topics in this forum.
	 *
	 * @param  Tx_MmForum_Domain_Model_User_FrontendUser $user
	 *                             The user that is to be checked.
	 *
	 * @return boolean             TRUE if the user has access, otherwise FALSE.
	 */
	public function checkNewTopicAccess(Tx_MmForum_Domain_Model_User_FrontendUser $user = NULL)
	{
		return $this->_checkAccess($user, 'newTopic');
	}



	/**
	 * Checks if a user has access to moderate in this forum.
	 *
	 * @param  Tx_MmForum_Domain_Model_User_FrontendUser $user
	 *                             The user that is to be checked.
	 *
	 * @return boolean             TRUE if the user has access, otherwise FALSE.
	 */
	public function checkModerationAccess(Tx_MmForum_Domain_Model_User_FrontendUser $user = NULL)
	{
		if ($user === NULL)
			return FALSE;
		return $this->_checkAccess($user, 'moderate');
	}



	/*
	 * SETTERS
	 */



	/**
	 * Sets the title
	 *
	 * @param string $title The title of the forum
	 *
	 * @return void
	 */
	public function setTitle($title)
	{
		$this->title = $title;
	}



	/**
	 * Sets the description
	 *
	 * @param string $description A description for the forum
	 *
	 * @return void
	 */
	public function setDescription($description)
	{
		$this->description = $description;
	}



	/**
	 * Adds a child forum
	 *
	 * @param Tx_MmForum_Domain_Model_Forum_Forum The Forum to be added
	 *
	 * @return void
	 */
	public function addChild(Tx_MmForum_Domain_Model_Forum_Forum $child)
	{
		$this->children->attach($child);
	}



	/**
	 * Removes a child forum
	 *
	 * @param Tx_MmForum_Domain_Model_Forum_Forum The Forum to be removed
	 *
	 * @return void
	 */
	public function removeChild(Tx_MmForum_Domain_Model_Forum_Forum $child)
	{
		$this->children->detach($child);
	}



	/**
	 * Adds a topic
	 *
	 * @param Tx_MmForum_Domain_Model_Forum_Topic The Topic to be added
	 *
	 * @return void
	 */
	public function addTopic(Tx_MmForum_Domain_Model_Forum_Topic $topic)
	{

		if ($this->lastTopic === NULL || $this->lastTopic->getTimestamp() <= $topic->getTimestamp())
			$this->setLastTopic($topic);
		if ($this->lastPost === NULL || $this->lastPost->getTimestamp() <= $topic->getLastPost()->getTimestamp())
			$this->setLastPost($topic->getLastPost());

		$this->topics->attach($topic);
		$this->_increaseTopicCount(+1);
		$this->_increasePostCount($topic->getPostCount());
	}



	/**
	 * Removes a topic
	 *
	 * @param Tx_MmForum_Domain_Model_Forum_Topic The Topic to be removed
	 *
	 * @return void
	 */
	public function removeTopic(Tx_MmForum_Domain_Model_Forum_Topic $topic)
	{
		$this->topics->detach($topic);
		$this->_increaseTopicCount(-1);
		$this->_increasePostCount(-$topic->getPostCount());

		if ($this->lastTopic === $topic)
			$this->_resetLastTopic();
		if ($this->lastPost->getTopic() === $topic)
			$this->setLastPost($this->lastTopic->getLastPost());
	}



	/**
	 * Sets the access rules for this forum
	 *
	 * @param Tx_Extbase_Persistence_ObjectStorage<Tx_MmForum_Domain_Model_Forum_Access> $acls acls
	 *
	 * @return void
	 */
	public function setAcls(Tx_Extbase_Persistence_ObjectStorage $acls)
	{
		$this->acls = $acls;
	}



	/**
	 * Adds a new access rule
	 *
	 * @param Tx_MmForum_Domain_Model_Forum_Access The access rule to be added
	 *
	 * @return void
	 */
	public function addAcl(Tx_MmForum_Domain_Model_Forum_Access $acl)
	{
		$this->acls->attach($acl);
	}



	/**
	 * Removes a access rule
	 *
	 * @param Tx_MmForum_Domain_Model_Forum_Access The access rule to be removed
	 *
	 * @return void
	 */
	public function removeAcl(Tx_MmForum_Domain_Model_Forum_Access $acl)
	{
		$this->acls->detach($acl);
	}



	/**
	 * Sets the last topic.
	 *
	 * @param Tx_MmForum_Domain_Model_Forum_Topic $lastTopic The last topic
	 *
	 * @return void
	 */
	public function setLastTopic(Tx_MmForum_Domain_Model_Forum_Topic $lastTopic)
	{
		$this->lastTopic = NULL;
		$this->_memorizePropertyCleanState('lastTopic');
		$this->lastTopic = $lastTopic;

		if ($this->getParent() && ($this->getParent()->getLastTopic() === NULL || $this->getParent()->getLastTopic()
			->getTimestamp() < $lastTopic->getTimestamp())
		)
		{
			$this->getParent()->setLastTopic($lastTopic);
			$this->_modifiedParent = TRUE;
		}
	}



	/**
	 * Sets the last post.
	 *
	 * @param Tx_MmForum_Domain_Model_Forum_Post $lastPost The last post.
	 *
	 * @return void
	 */
	public function setLastPost(Tx_MmForum_Domain_Model_Forum_Post $lastPost)
	{
		$this->lastPost = NULL;
		$this->_memorizePropertyCleanState('lastPost');
		$this->lastPost = $lastPost;

		if ($this->getParent() && ($this->getParent()->getLastPost() === NULL || $this->getParent()->getLastPost()
			->getTimestamp() < $lastPost->getTimestamp())
		)
		{
			$this->getParent()->setLastPost($lastPost);
			$this->_modifiedParent = TRUE;
		}
	}



	/**
	 * Resets the last post. This method iterates over all topics in this
	 * forum and looks for the latest post.
	 * INTERNAL USE ONLY!
	 *
	 * @return void
	 * @access private
	 */
	public function _resetLastPost()
	{
		$lastPost = NULL;
		foreach ($this->topics As $topic)
		{
			if ($lastPost === NULL || $topic->getLastPost()->getTimestamp() > $lastPost->getTimestamp())
				$lastPost = $topic->getLastPost();
		}
		$this->setLastPost($lastPost);
	}



	/**
	 * Resets the last topic. This method iterates over all topics in this
	 * forum and looks for the latest topic.
	 * INTERNAL USE ONLY!
	 *
	 * @access private
	 */
	public function _resetLastTopic()
	{
		$lastTopic = NULL;
		foreach ($this->topics As $topic)
		{
			if ($lastTopic === NULL || $topic->getLastPost()->getTimestamp() > $lastTopic->getTimestamp())
				$lastTopic = $topic;
		}
		$this->setLastTopic($lastTopic);
	}



	/**
	 * Increases (or decreases) the post count of this forum, and of ALL
	 * PARENT FORUMS.
	 * INTERNAL USE ONLY!
	 *
	 * @param  int $amount The amount by which to increase the post count
	 *                     (set a negative amount to decrease).
	 *
	 * @return void
	 * @access private
	 */
	public function _increasePostCount($amount = 1)
	{
		$this->postCount += $amount;
		if ($this->getParent())
		{
			$this->getParent()->_increasePostCount($amount);
			$this->_modifiedParent = TRUE;
		}
	}



	/**
	 * Increases (or decreases) the topic count of this forum, and of ALL
	 * PARENT FORUMS.
	 * INTERNAL USE ONLY!
	 *
	 * @param  int $amount The amount by which to increase the topic count
	 *                     (set a negative amount to decrease).
	 *
	 * @return void
	 * @access private
	 */
	public function _increaseTopicCount($amount = 1)
	{
		$this->topicCount += $amount;
		if ($this->getParent())
		{
			$this->getParent()->_increaseTopicCount($amount);
			$this->_modifiedParent = TRUE;
		}
	}



	/**
	 *
	 * @access private
	 */
	public function _resetCounters()
	{
		$this->_resetTopicCount();
		$this->_resetPostCount();
	}



	/**
	 *
	 * @access private
	 */
	public function _resetPostCount()
	{
		$this->postCount = 0;
		foreach ($this->children as $child)
			$this->postCount += $child->getPostCount();
		foreach ($this->topics as $topic)
			$this->postCount += $topic->getPostCount();
		if ($this->getParent())
			$this->getParent()->_resetPostCount();
	}



	/**
	 *
	 * @access private
	 */
	public function _resetTopicCount()
	{
		$this->topicCount = $this->topics->count();
		foreach ($this->children as $child)
			$this->topicCount += $child->getPostCount();
		if ($this->getParent())
			$this->getParent()->_resetTopicCount();
	}



}
