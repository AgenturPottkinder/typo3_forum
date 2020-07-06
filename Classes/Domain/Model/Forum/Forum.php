<?php
namespace Mittwald\Typo3Forum\Domain\Model\Forum;

/*                                                                      *
 *  COPYRIGHT NOTICE                                                    *
 *                                                                      *
 *  (c) 2015 Mittwald CM Service GmbH & Co KG                           *
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

use Mittwald\Typo3Forum\Domain\Model\AccessibleInterface;
use Mittwald\Typo3Forum\Domain\Model\SubscribeableInterface;
use Mittwald\Typo3Forum\Domain\Model\User\FrontendUser;
use TYPO3\CMS\Extbase\DomainObject\AbstractEntity;
use TYPO3\CMS\Extbase\Persistence\ObjectStorage;


/**
 * A forum. Forums can be infinitely nested and contain a number of topics. Forums
 * are submitted to the access control mechanism and can be subscribed by users.
 */
class Forum extends AbstractEntity implements AccessibleInterface, SubscribeableInterface {

	/**
	 * All access rules.
	 * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\Mittwald\Typo3Forum\Domain\Model\Forum\Access>
	 */
	protected $acls;

	/**
	 * The child forums
	 * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\Mittwald\Typo3Forum\Domain\Model\Forum\Forum>
	 * @lazy
	 */
	protected $children;

	/**
	 * The criterias of this forum.
	 * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\Mittwald\Typo3Forum\Domain\Model\Forum\Criteria>
	 * @lazy
	 */
	protected $criteria;

	/**
	 * A description for the forum
	 * @var string
	 */
	protected $description;

	/**
	 * @var int
	 */
	protected $displayedPid;

	/**
	 * The parent forum.
	 * @var \Mittwald\Typo3Forum\Domain\Model\Forum\Forum
	 * @lazy
	 */
	protected $forum;

	/**
	 * @var \Mittwald\Typo3Forum\Domain\Model\Forum\Post
	 */
	protected $lastPost;

	/**
	 * @var \Mittwald\Typo3Forum\Domain\Model\Forum\Topic
	 */
	protected $lastTopic;

	/**
	 * An instance of the Extbase object manager.
	 * @var \TYPO3\CMS\Extbase\Object\ObjectManagerInterface
	 * @inject
	 */
	protected $objectManager;

	/**
	 * The amount of post in this forum.
	 * @var int
	 */
	protected $postCount;

	/**
	 * All users who have read this forum.
	 *
	 * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\Mittwald\Typo3Forum\Domain\Model\User\FrontendUser>
	 * @lazy
	 */
	protected $readers;

	/**
	 * All subscribers of this forum.
	 * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\Mittwald\Typo3Forum\Domain\Model\User\FrontendUser>
	 * @lazy
	 */
	protected $subscribers;

	/**
	 * @var string
	 * @validate NotEmpty
	 */
	protected $title;

	/**
	 * Amount of topics in this forum.
	 * @var int
	 */
	protected $topicCount;

	/**
	 * The topics in this forum.
	 * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\Mittwald\Typo3Forum\Domain\Model\Forum\Topic>
	 * @lazy
	 */
	protected $topics;

	/**
	 * The VISIBLE child forums of this forum, i.e. all forums that the
	 * currently logged in user has read access to.
	 *
	 * @var \ArrayObject<\Mittwald\Typo3Forum\Domain\Model\Forum\Forum>
	 * @lazy
	 */
	protected $visibleChildren;

	/**
	 * An instance of the typo3_forum authentication service.
	 * @var \Mittwald\Typo3Forum\Service\Authentication\AuthenticationServiceInterface
	 * @inject
	 */
	protected $authenticationService;

	/**
	 * The sorting value
	 * @var int
	 */
	protected $sorting;

	/**
	 * Constructor. Initializes all \TYPO3\CMS\Extbase\Persistence\ObjectStorage instances.
	 *
	 * @param string $title The forum title.
	 */
	public function __construct($title = '') {
		$this->children = new ObjectStorage();
		$this->topics = new ObjectStorage();
		$this->criteria = new ObjectStorage();
		$this->acls = new ObjectStorage();
		$this->subscribers = new ObjectStorage();
		$this->readers = new ObjectStorage();

		$this->title = $title;
	}

	/**
	 * @return int
	 */
	public function getDisplayedPid() {
		return $this->displayedPid;
	}

	/**
	 * Gets the forum title.
	 * @return string The title of the forum.
	 */
	public function getTitle() {
		return $this->title;
	}


	/**
	 * Gets the forum description.
	 * @return string A description for the forum.
	 */
	public function getDescription() {
		return $this->description;
	}


	/**
	 * Gets all VISIBLE child forums. This function does NOT simply return
	 * all child forums, but performs an access check on each forum, so
	 * that only forums visible to the current user are returned.
	 *
	 * @return ObjectStorage<\Mittwald\Typo3Forum\Domain\Model\Forum\Forum> All visible child forums
	 */
	public function getChildren() {
		if ($this->visibleChildren === NULL) {
			$this->visibleChildren = new \ArrayObject();

			// Note: Use the authentication service instead of performing the
			// access checks on the domain objects themselves, since the authentication
			// service caches its results (which should be safe in this case).
			foreach ($this->children as $child) {
				if ($this->authenticationService->checkAuthorization($child, Access::TYPE_READ)) {
					$this->visibleChildren->append($child);
				}
			}
		}

		return $this->visibleChildren;
	}


	/**
	 * Gets all topics.
	 * @return ObjectStorage<\Mittwald\Typo3Forum\Domain\Model\Forum\Topic> All topics in this forum
	 */
	public function getTopics() {
		return $this->topics;
	}


	/**
     * Return criteria without look recursive
     *
	 * @return ObjectStorage
	 */
	public function getCurrentCriteria()
	{
		return $this->criteria;
	}

	/**
	 * Get all criterias of this forum.
	 * @return ObjectStorage<\Mittwald\Typo3Forum\Domain\Model\Forum\Criteria>
	 */
	public function getCriteria() {
		$criteriaStorage = new ObjectStorage();
		/* @var \Mittwald\Typo3Forum\Domain\Model\Forum\Criteria $criteria */
		$criteria = $this->getCriteriaRecursive([$this, $criteriaStorage]);
		$obj = $criteria[1];

		return $obj;
	}

	/**
	 * Get all criterias recursive.
	 * Please don't call this function. Use getCriteria()!!!
	 *
	 * @param array $array
	 *
	 * @return array
	 */
	private function getCriteriaRecursive($array) {
		/** @var Forum $forum */
		/** @var ObjectStorage $criteriaStorage */
		list($forum, $criteriaStorage) = $array;
		if ($forum->getCurrentCriteria() !== NULL) {
			$criteriaStorage->addAll($forum->getCurrentCriteria());
		}
		if(($parent = $forum->getParent()) && ($parent->getParent() != NULL)) {
			list(, $criteriaStorage) = $this->getCriteriaRecursive([$parent, $criteriaStorage]);
		}

		return [$forum, $criteriaStorage];
	}

	/**
	 * Gets all access rules.
	 * @return ObjectStorage<\Mittwald\Typo3Forum\Domain\Model\Forum\Access> All access rules for this forum
	 */
	public function getAcls() {
		return $this->acls;
	}

	/**
	 * Gets the last topic.
	 * @return Topic The last topic
	 */
	public function getLastTopic() {
		if (!$this->lastTopic instanceof Topic) {
			return NULL;
		}
		$lastTopic = $this->lastTopic;
		foreach ($this->getChildren() as $child) {
			/** @var $child Forum */
			/** @noinspection PhpUndefinedMethodInspection */
			if ($lastTopic === NULL || ($child->getLastTopic() !== NULL && $child->getLastTopic()->getLastPost()
						->getTimestamp() > $lastTopic->getLastPost()->getTimestamp())
			) {
				$lastTopic = $child->getLastTopic();
			}
		}

		return $lastTopic;
	}

	/**
	 * Gets the last post.
	 * @return Post The last post
	 */
	public function getLastPost() {
		if (!$this->lastPost instanceof Post) {
			return NULL;
		}
		$lastPost = $this->lastPost;
		foreach ($this->getChildren() as $child) {
			/** @var $child Forum */
			if (
				$lastPost === NULL ||
				($child->getLastPost() !== NULL && $child->getLastPost()->getTimestamp() > $lastPost->getTimestamp())
			) {
				$lastPost = $child->getLastPost();
			}
		}

		return $lastPost;
	}

	/**
	 * Gets the parent forum.
	 * @return Forum The parent forum
	 */
	public function getForum() {
		return $this->forum;
	}

	/**
	 * Alias for getForum().
	 * @return Forum The parent forum
	 */
	public function getParent() {
		return $this->getForum();
	}

	/**
	 * Gets the amount of topics in this forum.
	 * @return integer The number of topics in this forum
	 */
	public function getTopicCount() {
		$topicCount = $this->topicCount;
		foreach ($this->getChildren() as $child) {
			/** @var $child Forum */
			$topicCount += $child->getTopicCount();
		}
		return $topicCount;
	}


	/**
	 * Gets the amount of posts in this forum and all subforums.
	 * @return integer The amount of posts in this forum and all subforums.
	 */
	public function getPostCount() {
		$postCount = $this->postCount;
		foreach ($this->getChildren() as $child) {
			/** @var $child Forum */
			$postCount += $child->getPostCount();
		}
		return $postCount;
	}


	/**
	 * Gets all users who have subscribed to this forum.
	 *
	 * @return ObjectStorage<\Mittwald\Typo3Forum\Domain\Model\User\FrontendUser> All subscribers of this forum.
	 */
	public function getSubscribers() {
		return $this->subscribers;
	}


	/**
	 * Gets the sorting value
	 * @return int
	 */
	public function getSorting() {
		return $this->sorting;
	}


	/**
	 * Determines if this forum (i.e. all topics in it) has been read by the
	 * currently logged in user.
	 *
	 * @param FrontendUser $user The user.
	 * @return boolean TRUE, if all topics in this forum have been read, otherwise FALSE.
	 */
	public function hasBeenReadByUser(FrontendUser $user = NULL) {
		if ($user === NULL || $this->readers === NULL) {
			return TRUE;
		}

		return $this->readers->contains($user);
	}


	/**
	 * Returns all parent forums in hiearchical order as a flat list (optionally
	 * with or without this forum itself).
	 *
	 * @param boolean $withSelf TRUE to include this forum into the rootline, otherwise FALSE.
	 * @return array<\Mittwald\Typo3Forum\Domain\Model\Forum\Forum>
	 */
	public function getRootline($withSelf = TRUE) {
		$rootline = $this->forum === NULL ? [] : $this->forum->getRootline(TRUE);

		if ($withSelf === TRUE) {
			$rootline[] = $this;
		}

		return $rootline;
	}


	/**
	 * Performs an access check for this forum.
	 * *INTERAL USE ONLY!*
	 *
	 * @access private
	 * @param FrontendUser $user The user that is to be checked against the access rules of this forum.
	 * @param string $accessType The operation
	 * @return boolean TRUE, if the user has access to the requested operation, otherwise FALSE.
	 */
	public function checkAccess(FrontendUser $user = NULL, $accessType = Access::TYPE_READ) {

		// If there aren't any access rules defined for this forum, delegate
		// the access check to the parent forum. If there is no parent forum
		// either, simply deny access (except for 'read' operations).
		if (count($this->acls) === 0) {

			if(($parent = $this->getParent())) {
				return $this->getParent()->checkAccess($user, $accessType);
            }
		}

		// Iterate over all access rules, until a matching rule is found
		// that explicitly grants or denies access. If no matching rule is
		// found, delegate to the parent object or deny access (grant read
		// access, if no parent is set).
		foreach ($this->acls as $acl) {
			/** @var $acl Access */
			if ($acl->getOperation() !== $accessType) {
				continue;
			}
			if ($acl->matches($user)) {
				return !$acl->isNegated();
			}
		}

		if(($parent = $this->getParent())) {
			return $this->getParent()->checkAccess($user, $accessType);
		}

		if($accessType === Access::TYPE_READ) {
			return true;
		}

		return false;
	}


	/**
	 * Checks if a user has read access to this forum.
	 *
	 * @param FrontendUser $user The user that is to be checked.
	 *
	 * @return boolean TRUE if the user has read access, otherwise FALSE.
	 */
	public function checkReadAccess(FrontendUser $user = NULL) {
		return $this->checkAccess($user, Access::TYPE_READ);
	}


	/**
	 * Checks if a user has access to create new posts in this forum.
	 *
	 * @param FrontendUser $user The user that is to be checked.
	 *
	 * @return boolean TRUE if the user has access, otherwise FALSE.
	 */
	public function checkNewPostAccess(FrontendUser $user = NULL) {
		return $this->checkAccess($user, Access::TYPE_NEW_POST);
	}


	/**
	 * Checks if a user has access to create new topics in this forum.
	 *
	 * @param FrontendUser $user The user that is to be checked.
	 * @return boolean TRUE if the user has access, otherwise FALSE.
	 */
	public function checkNewTopicAccess(FrontendUser $user = NULL) {
		return $this->checkAccess($user, Access::TYPE_NEW_TOPIC);
	}


	/**
	 * Checks if a user has access to moderate in this forum.
	 *
	 * @param FrontendUser $user The user that is to be checked.
	 *
	 * @return boolean TRUE if the user has access, otherwise FALSE.
	 */
	public function checkModerationAccess(FrontendUser $user = NULL) {
		if ($user === NULL) {
			return FALSE;
		}
		return $this->checkAccess($user, Access::TYPE_MODERATE);
	}


	/**
	 * Sets the title.
	 *
	 * @param string $title The title of the forum
	 *
	 * @return void
	 */
	public function setTitle($title) {
		$this->title = $title;
	}

	/**
	 * Set the criteria of this forum..
	 *
	 * @param ObjectStorage $criteria
	 *
	 * @return void
	 */
	public function setCriteria(ObjectStorage $criteria) {
		$this->criteria = $criteria;
	}


	/**
	 * Sets the description.
	 *
	 * @param string $description A description for the forum
	 *
	 * @return void
	 */
	public function setDescription($description) {
		$this->description = $description;
	}

	/**
	 * Sets the parent forum.
	 *
	 * @param Forum $parent The parent forum.
	 *
	 * @return void
	 */
	public function setParent(Forum $parent) {
		$this->forum = $parent;
	}

	/**
	 * Adds a child forum.
	 *
	 * @param Forum $child
	 *
	 * @return void
	 */
	public function addChild(Forum $child) {
		$this->visibleChildren = NULL;
		$this->children->attach($child);

		$child->setParent($this);
		$this->_resetCounters();
		$this->_resetLastPost();
		$this->_resetLastTopic();
	}


	/**
	 * Removes a child forum.
	 *
	 * @param Forum $child The Forum to be removed
	 *
	 * @return void
	 */
	public function removeChild(Forum $child) {
		$this->children->detach($child);
	}


	/**
	 * Adds a topic.
	 *
	 * @param Topic $topic
	 *
	 * @return void
	 */
	public function addTopic(Topic $topic) {

		if ($this->lastTopic === NULL || $this->lastTopic->getTimestamp() <= $topic->getTimestamp()) {
			$this->setLastTopic($topic);
		}
		$topicLastPost = $topic->getLastPost();
		if (($topicLastPost !== NULL) && ($this->lastPost === NULL || $this->lastPost->getTimestamp() <= $topicLastPost->getTimestamp())) {
			$this->setLastPost($topic->getLastPost());
		}
		$this->_increaseTopicCount(+1);
		// topic will increase postCount itself when adding the initial post to it

		$topic->setForum($this);
		$this->topics->attach($topic);
	}


	/**
	 * Removes a topic.
	 *
	 * @param Topic $topic The Topic to be removed
	 *
	 * @return void
	 */
	public function removeTopic(Topic $topic) {
		$this->topics->detach($topic);
		$this->_resetCounters();
		$this->_resetLastPost();
		$this->_resetLastTopic();
	}


	/**
	 * Sets the access rules for this forum.
	 *
	 * @param ObjectStorage $acls
	 *
	 * @return void
	 */
	public function setAcls(ObjectStorage $acls) {
		$this->acls = $acls;
	}


	/**
	 * Adds a new access rule.
	 *
	 * @param Access $acl The access rule to be added
	 *
	 * @return void
	 */
	public function addAcl(Access $acl) {
		$this->acls->attach($acl);
	}


	/**
	 * Removes a access rule.
	 *
	 * @param Access $acl The access rule to be removed
	 *
	 * @return void
	 */
	public function removeAcl(Access $acl) {
		$this->acls->detach($acl);
	}


	/**
	 * Sets the last topic.
	 *
	 * @param Topic $lastTopic The last topic
	 *
	 * @return void
	 */
	public function setLastTopic(Topic $lastTopic = NULL) {
		$this->lastTopic = $lastTopic;
	}


	/**
	 * Sets the last post.
	 *
	 * @param Post $lastPost The last post.
	 *
	 * @return void
	 */
	public function setLastPost(Post $lastPost = NULL) {
		$this->lastPost = $lastPost;
	}


	/**
	 * Adds a new subscriber.
	 *
	 * @param FrontendUser $user The new subscriber.
	 *
	 * @return void
	 */
	public function addSubscriber(FrontendUser $user) {
		$this->subscribers->attach($user);
	}


	/**
	 * Removes a subscriber.
	 *
	 * @param FrontendUser $user The subscriber to be removed.
	 */
	public function removeSubscriber(FrontendUser $user) {
		$this->subscribers->detach($user);
	}


	/**
	 * Marks this forum as read by a certain user.
	 *
	 * @param FrontendUser $reader The user who read this forum.
	 *
	 * @return void
	 */
	public function addReader(FrontendUser $reader) {
		$this->readers->attach($reader);
	}


	/**
	 * Mark this forum as unread for a certain user.
	 *
	 * @param FrontendUser $reader The user for whom to mark this forum as unread.
	 *
	 * @return void
	 */
	public function removeReader(FrontendUser $reader) {
		$this->readers->detach($reader);
	}


	/**
	 * Mark this forum as unread for all users.
	 * @return void
	 */
	public function removeAllReaders() {
		$this->readers = new ObjectStorage();
	}


	/**
	 * Resets the last post. This method iterates over all topics in this
	 * forum and looks for the latest post.
	 * INTERNAL USE ONLY!
	 *
	 * @return void
	 * @access private
	 */
	public function _resetLastPost() {
		/** @var $lastPost Post */
		$lastPost = NULL;
		foreach ($this->topics as $topic) {
			/** @var $topic Topic */
			/** @noinspection PhpUndefinedMethodInspection */
			if ($topic->getLastPost() instanceof Post) {
				$lastTopicPostTimestamp = $topic->getLastPost()->getTimestamp();
				if ($lastPost === NULL || $lastTopicPostTimestamp > $lastPost->getTimestamp()) {
					$lastPost = $topic->getLastPost();
				}
			}
		}

		$this->lastPost = $lastPost;
	}


	/**
	 * Resets the last topic. This method iterates over all topics in this
	 * forum and looks for the latest topic.
	 * INTERNAL USE ONLY!
	 *
	 * @access private
	 */
	public function _resetLastTopic() {
		$lastTopic = NULL;
		foreach ($this->topics as $topic) {
			/** @var $topic Topic */
			/** @var $lastTopic Topic */
			if ($topic->getLastPost() instanceof Post) {
				$lastTopicPostTimestamp = $topic->getLastPost()->getTimestamp();
				if ($lastTopic === NULL || $lastTopicPostTimestamp > $lastTopic->getTimestamp()) {
					$lastTopic = $topic;
				}
			}
		}

		$this->lastTopic = $lastTopic;
	}


	/**
	 * Increases (or decreases) the post count of this forum, and of ALL
	 * PARENT FORUMS.
	 * INTERNAL USE ONLY!
	 *
	 * @param int $amount The amount by which to increase the post count
	 *                     (set a negative amount to decrease).
	 *
	 * @return void
	 * @access private
	 */
	public function _increasePostCount($amount = 1) {
		$this->postCount += $amount;
	}


	/**
	 * Increases (or decreases) the topic count of this forum, and of ALL
	 * PARENT FORUMS.
	 * INTERNAL USE ONLY!
	 *
	 * @param int $amount The amount by which to increase the topic count (set a negative amount to decrease).
	 *
	 * @return void
	 * @access private
	 */
	public function _increaseTopicCount($amount = 1) {
		$this->topicCount += $amount;
	}


	/**
	 * Resets all internal counters (e.g. topic and post counter).
	 * @access private
	 */
	public function _resetCounters() {
		$this->_resetTopicCount();
		$this->_resetPostCount();
	}


	/**
	 * Resets the internal post counter.
	 * @access private
	 */
	public function _resetPostCount() {
		$this->postCount = 0;
		foreach ($this->topics as $topic) {
			/** @var $topic Topic */
			$this->postCount += $topic->getPostCount();
		}
	}


	/**
	 * Resets the internal topic counter.
	 * @access private
	 */
	public function _resetTopicCount() {
		$this->topicCount = count($this->topics);
	}

}
