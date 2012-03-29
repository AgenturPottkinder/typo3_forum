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
 * A single topic. Each topic can contain an infinite number of
 * posts. Topic are submitted to the access control mechanism and
 * can be subscribed by users.
 *
 * @author	 Martin Helmich <m.helmich@mittwald.de>
 * @package	MmForum
 * @subpackage Domain_Model_Forum
 * @version	$Id$
 * @license	GNU Public License, version 2
 *			 http://opensource.org/licenses/gpl-license.php

 */
class Tx_MmForum_Domain_Model_Forum_Topic
	extends Tx_Extbase_DomainObject_AbstractEntity
	implements Tx_MmForum_Domain_Model_AccessibleInterface,
	           Tx_MmForum_Domain_Model_SubscribeableInterface,
	           Tx_MmForum_Domain_Model_NotifiableInterface,
	           Tx_MmForum_Domain_Model_ReadableInterface
{



	/*
	 * ATTRIBUTES
	 */



	/**
	 * The subject
	 *
	 * @var string
	 * @validate NotEmpty
	 */
	protected $subject;



	/**
	 * The posts in this topic
	 *
	 * @var Tx_Extbase_Persistence_ObjectStorage<Tx_MmForum_Domain_Model_Forum_Post>
	 */
	protected $posts;



	/**
	 * The amount of posts in this topic (of course, we could simply do
	 * count($this->posts), however this is much more performant).
	 *
	 * @var int
	 */
	protected $postCount;



	/**
	 * The user who created the topic
	 *
	 * @var Tx_MmForum_Domain_Model_User_FrontendUser
	 */
	protected $author;



	/**
	 * All users who have subscribed this topic.
	 *
	 * @var Tx_Extbase_Persistence_ObjectStorage<Tx_MmForum_Domain_Model_User_FrontendUser>
	 * @lazy
	 */
	protected $subscribers;



	/**
	 * A pointer to the last post in this topic.
	 *
	 * @var Tx_MmForum_Domain_Model_Forum_Post
	 * @lazy
	 */
	protected $lastPost;



	/**
	 * The creation timestamp of the last post. Enables sorting topics
	 * without a SQL join on the posts table.
	 *
	 * @var DateTime
	 */
	protected $lastPostCrdate;



	/**
	 * The forum in which this topic is located
	 *
	 * @var Tx_MmForum_Domain_Model_Forum_Forum
	 */
	protected $forum;



	/**
	 * Defines whether this topic is closed
	 *
	 * @var boolean
	 */
	protected $closed;



	/**
	 * Defines whether this topic is sticky
	 *
	 * @var boolean
	 */
	protected $sticky;



	/**
	 * The topic date.
	 *
	 * @var DateTime
	 */
	protected $crdate;



	/**
	 * All users who have read this topic.
	 *
	 * @var Tx_Extbase_Persistence_ObjectStorage<Tx_MmForum_Domain_Model_User_FrontendUser>
	 * @lazy
	 */
	protected $readers;



	/**
	 * Helper variable to store if the parent object was modified. This is necessary
	 * due to http://forge.typo3.org/issues/8952
	 *
	 * @var boolean
	 */
	private $_modifiedParent = FALSE;



	/*
	  * CONSTRUCTOR
	  */



	/**
	 * Constructor. Initializes all Tx_Extbase_Persistence_ObjectStorage instances.

	 */
	public function __construct()
	{
		$this->posts  = new Tx_Extbase_Persistence_ObjectStorage();
		$this->crdate = new DateTime();
	}



	/*
	 * GETTER METHODS
	 */



	/**
	 * Gets the topic subject.
	 *
	 * @return string The subject

	 */
	public function getSubject()
	{
		return $this->subject;
	}



	/**
	 * Alias for getSubject. Necessary to implement the SubscribeableInterface.
	 *
	 * @return string The subject

	 */
	public function getTitle()
	{
		return $this->getSubject();
	}



	/**
	 * Alias for getSubject. Necessary to implement the NofifiableInterface.
	 *
	 * @return string  The subject

	 */
	public function getName()
	{
		return $this->getSubject();
	}



	/**
	 * Delegate function to call getText() of the first post. Necessary to implement
	 * the NofifiableInterface.
	 *
	 * @return string The description
	 */
	public function getDescription()
	{
		/** @noinspection PhpUndefinedMethodInspection */
		return $this->posts[0]->getText();
	}



	/**
	 * Gets the topic author
	 *
	 * @return Tx_MmForum_Domain_Model_User_FrontendUser author
	 */
	public function getAuthor()
	{
		return $this->author;
	}



	/**
	 * Gets all users who have subscribes to this forum.
	 *
	 * @return Tx_Extbase_Persistence_ObjectStorage<Tx_MmForum_Domain_Model_User_FrontendUser>
	 */
	public function getSubscribers()
	{
		return $this->subscribers;
	}



	/**
	 * Gets all posts.
	 *
	 * @return Tx_Extbase_Persistence_ObjectStorage<Tx_MmForum_Domain_Model_Forum_Post> posts
	 */
	public function getPosts()
	{
		return $this->posts;
	}



	/**
	 * Gets the post count
	 *
	 * @return integer Post count
	 */
	public function getPostCount()
	{
		return $this->postCount;
	}



	/**
	 * Gets the reply count.
	 *
	 * @return integer Reply count
	 */
	public function getReplyCount()
	{
		return $this->getPostCount() - 1;
	}



	/**
	 * Gets whether the topic is closed.
	 *
	 * @return boolean
	 */
	public function isClosed()
	{
		return $this->closed;
	}



	/**
	 * Gets the last post.
	 *
	 * @return Tx_MmForum_Domain_Model_Forum_Post lastPost
	 */
	public function getLastPost()
	{
		return $this->lastPost;
	}



	/**
	 * Gets the forum.
	 *
	 * @return Tx_MmForum_Domain_Model_Forum_Forum A forum
	 */
	public function getForum()
	{
		return $this->forum;
	}



	/**
	 * Gets the creation time of this topic.
	 *
	 * @return DateTime
	 */
	public function getTimestamp()
	{
		return $this->crdate;
	}



	/**
	 * Checks if this topic is sticky.
	 *
	 * @return boolean
	 */
	public function isSticky()
	{
		return $this->sticky;
	}



	/**
	 * Determines whether this topic has been read by a certain user.
	 *
	 * @param  Tx_MmForum_Domain_Model_User_FrontendUser $reader
	 *							 The user who is to be checked.
	 *
	 * @return boolean			 TRUE, if the user did read this topic, otherwise
	 *							 FALSE.
	 */
	public function hasBeenReadByUser(Tx_MmForum_Domain_Model_User_FrontendUser $reader)
	{
		return $reader ? $this->readers->contains($reader) : TRUE;
	}



	/**
	 * Returns all parent forums in hiearchical order as a flat list (optionally
	 * with or without this topic itself).
	 *
	 * @param  boolean $withSelf TRUE to include this forum into the rootline,
	 *						   otherwise FALSE.
	 *
	 * @return array<Tx_MmForum_Domain_Model_Forum_Forum>
	 */
	public function getRootline($withSelf = TRUE)
	{
		$rootline = $this->forum->getRootline(TRUE);

		if ($withSelf === TRUE)
		{
			$rootline[] = $this;
		}
		return $rootline;
	}



	/**
	 * Checks if a user may perform a certain operation (read, answer...) with this
	 * topic.
	 *
	 * @param  Tx_MmForum_Domain_Model_User_FrontendUser $user       The user.
	 * @param  string                                    $accessType The access type to be checked.
	 *
	 * @return boolean
	 */
	public function _checkAccess(Tx_MmForum_Domain_Model_User_FrontendUser $user = NULL,
	                             $accessType = 'read')
	{

		switch ($accessType)
		{
			case 'newPost':
				return $this->checkNewPostAccess($user);
			default:
				return $this->forum->_checkAccess($user, $accessType);
		}
	}



	/**
	 * Checks if a user may reply to this topic.
	 *
	 * @param  Tx_MmForum_Domain_Model_User_FrontendUser $user
	 *
	 * @return boolean
	 */
	public function checkNewPostAccess(Tx_MmForum_Domain_Model_User_FrontendUser $user = NULL)
	{

		if ($user === NULL)
			return FALSE;
		return $this->getForum()->checkModerationAccess($user) ? TRUE : ($this->isClosed()
			? FALSE : $this->getForum()->checkNewPostAccess($user));
	}



	/**
	 * Checks if a user has moderative access to this topic.
	 *
	 * @param  Tx_MmForum_Domain_Model_User_FrontendUser $user
	 *
	 * @return boolean
	 */
	public function checkModerationAccess(Tx_MmForum_Domain_Model_User_FrontendUser $user = NULL)
	{
		return ($user === NULL) ? FALSE : $this->getForum()->checkModerationAccess($user);
	}



	/**
	 * Workaround to prevent endless-recursive object persisting.
	 *
	 * @param  mixed $previousValue
	 * @param  mixed $currentValue
	 *
	 * @return boolean
	 */
	protected function isPropertyDirty($previousValue, $currentValue)
	{
		if ($currentValue instanceof Tx_MmForum_Domain_Model_Forum_Forum)
			return $this->_modifiedParent;
		else
			return parent::isPropertyDirty($previousValue, $currentValue);
	}



	/*
	 * SETTER METHODS
	 */



	/**
	 * Adds a Post. By adding a new post, this topic is automatically marked unread
	 * for all users who have read this topic before.
	 *
	 * @param  Tx_MmForum_Domain_Model_Forum_Post $post The Post to be added
	 *
	 * @return void
	 */
	public function addPost(Tx_MmForum_Domain_Model_Forum_Post $post)
	{
		$this->posts->attach($post);
		$post->setTopic($this);
		$this->postCount++;
		$this->removeAllReaders();

		$this->forum->_increasePostCount(+1);
		$this->_modifiedParent = TRUE;

		if ($this->lastPost === NULL || $this->lastPost->getTimestamp() < $post->getTimestamp())
			$this->setLastPost($post);
		if ($this->forum->getLastPost() === NULL || $this->forum->getLastPost()->getTimestamp() < $post->getTimestamp())
			$this->forum->setLastPost($post);
	}



	/**
	 * Removes a Post.
	 *
	 * @param  Tx_MmForum_Domain_Model_Forum_Post $post The Post to be removed
	 *
	 * @return void
	 */
	public function removePost(Tx_MmForum_Domain_Model_Forum_Post $post)
	{
		$this->posts->detach($post);
		$this->postCount--;

		$this->forum->_increasePostCount(-1);
		$this->_modifiedParent = TRUE;

		if ($this->lastPost->getUid() === $post->getUid())
		{
			$postsArray = $this->posts->toArray();
			$this->setLastPost(array_pop($postsArray));
		}

		if ($this->forum->getLastPost() === $post)
		{
			$this->forum->_resetLastPost();
		}
	}



	/**
	 * Sets the topic author.
	 *
	 * @param  Tx_MmForum_Domain_Model_User_FrontendUser $author The topic author.
	 *
	 * @return void
	 */
	public function setAuthor(Tx_MmForum_Domain_Model_User_FrontendUser $author)
	{
		$this->author = $author;
	}



	/**
	 * Sets the last post. This method is not publicy accessible; is is called
	 * automatically when a new post is added to this topic.
	 *
	 * @param  Tx_MmForum_Domain_Model_Forum_Post $lastPost The last post.
	 *
	 * @return void
	 */
	protected function setLastPost(Tx_MmForum_Domain_Model_Forum_Post $lastPost)
	{
		$this->lastPost       = $lastPost;
		$this->lastPostCrdate = $lastPost->getTimestamp();
	}



	/**
	 * Sets the subject of this topic.
	 *
	 * @param  string $subject The subject
	 *
	 * @return void
	 */
	public function setSubject($subject)
	{
		$this->subject = $subject;
	}



	/**
	 * Sets the forum.
	 *
	 * @param  Tx_MmForum_Domain_Model_Forum_Forum $forum The forum
	 *
	 * @return void
	 */
	public function setForum(Tx_MmForum_Domain_Model_Forum_Forum $forum)
	{
		$this->forum = $forum;
	}



	/**
	 * Sets this topic to closed.
	 *
	 * @param  boolean $closed TRUE to close this topic, FALSE to re-open it.
	 *
	 * @return void
	 */
	public function setClosed($closed)
	{
		$this->closed = (boolean)$closed;
	}



	/**
	 * Sets this topic to sticky. Sticky topics will always remain at the top of the
	 * forum list, regardless of the timestamp of the last post.
	 *
	 * @param  boolean $sticky TRUE to make this topic sticky, FALSE to reset this.
	 *
	 * @return void
	 */
	public function setSticky($sticky)
	{
		$this->sticky = (boolean)$sticky;
	}



	/**
	 * Marks this topic as read by a certain user.
	 *
	 * @param Tx_MmForum_Domain_Model_User_FrontendUser $reader
	 *							 The user who read this topic.
	 *
	 * @return void
	 */
	public function addReader(Tx_MmForum_Domain_Model_User_FrontendUser $reader)
	{
		$this->readers->attach($reader);
	}



	/**
	 * Mark this topic as unread for a certain user.
	 *
	 * @param Tx_MmForum_Domain_Model_User_FrontendUser $reader
	 *							 The user for whom to mark this topic as unread.
	 *
	 * @return void
	 */
	public function removeReader(Tx_MmForum_Domain_Model_User_FrontendUser $reader)
	{
		$this->readers->detach($reader);
	}



	/**
	 * Mark this topic as unread for all users.
	 *
	 * @return void
	 */
	public function removeAllReaders()
	{
		$this->readers = New Tx_Extbase_Persistence_ObjectStorage();
	}



}
