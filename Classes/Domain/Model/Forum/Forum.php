<?php
namespace Mittwald\Typo3Forum\Domain\Model\Forum;
/*                                                                      *
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
use Mittwald\Typo3Forum\Domain\Model\AccessibleInterface;
use Mittwald\Typo3Forum\Domain\Model\SubscribeableInterface;
use TYPO3\CMS\Extbase\DomainObject\AbstractEntity;


/**
 * A forum. Forums can be infinitely nested and contain a number of topics. Forums
 * are submitted to the access control mechanism and can be subscribed by users.
 *
 * @author     Martin Helmich <m.helmich@mittwald.de>
 * @package    Typo3Forum
 * @subpackage Domain_Model_Forum
 * @version    $Id$
 * @license    GNU public License, version 2
 *             http://opensource.org/licenses/gpl-license.php

 */
class Forum extends AbstractEntity implements AccessibleInterface, SubscribeableInterface {



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
	 * @var string
	 */
	protected $description;


	/**
	 * The child forums
	 * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\Mittwald\Typo3Forum\Domain\Model\Forum\Forum>
	 * @lazy
	 */
	protected $children;


	/**
	 * The VISIBLE child forums of this forum, i.e. all forums that the
	 * currently logged in user has read access to.
	 *
	 * @var \ArrayObject<\Mittwald\Typo3Forum\Domain\Model\Forum\Forum>
	 * @lazy
	 */
	protected $visibleChildren = NULL;


	/**
	 * The topics in this forum.
	 * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\Mittwald\Typo3Forum\Domain\Model\Forum\Topic>
	 * @lazy
	 */
	protected $topics;


	/**
	 * The criterias of this forum.
	 * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\Mittwald\Typo3Forum\Domain\Model\Forum\Criteria>
	 * @lazy
	 */
	protected $criteria;


	/**
	 * Amount of topics in this forum.
	 * @var int
	 */
	protected $topicCount;


	/**
	 * The amount of post in this forum.
	 * @var int
	 */
	protected $postCount;


	/**
	 * All access rules.
	 * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\Mittwald\Typo3Forum\Domain\Model\Forum\Access>
	 */
	protected $acls;


	/**
	 * The last topic.
	 * @var \Mittwald\Typo3Forum\Domain\Model\Forum\Topic
	 */
	protected $lastTopic;


	/**
	 * The last post.
	 * @var \Mittwald\Typo3Forum\Domain\Model\Forum\Post
	 */
	protected $lastPost;


	/**
	 * The parent forum.
	 * @var \Mittwald\Typo3Forum\Domain\Model\Forum\Forum
	 * @lazy
	 */

	protected $forum;


	/**
	 * All subscribers of this forum.
	 * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\Mittwald\Typo3Forum\Domain\Model\User\FrontendUser>
	 * @lazy
	 */
	protected $subscribers;


	/**
	 * All users who have read this forum.
	 *
	 * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\Mittwald\Typo3Forum\Domain\Model\User\FrontendUser>
	 * @lazy
	 */
	protected $readers;


	/**
	 * @var int
	 */
	protected $displayedPid;


	/**
	 * An instance of the Extbase object manager.
	 * @var \TYPO3\CMS\Extbase\Object\ObjectManagerInterface
	 */
	protected $objectManager = NULL;


	/**
	 * An instance of the typo3_forum authentication service.
	 * @var \Mittwald\Typo3Forum\Service\Authentication\AuthenticationServiceInterface
	 */
	protected $authenticationService = NULL;


	/**
	 * The sorting value
	 * @var int
	 */
	protected $sorting;


	/**
	 * An instance of the forum repository.
	 * @var \Mittwald\Typo3Forum\Domain\Repository\Forum\ForumRepository
	 */
	protected $forumRepository;


	/**
	 * An instance of the typo3_forum authentication service.
	 * @var \TYPO3\CMS\Extbase\Service\TypoScriptService
	 */
	protected $typoScriptService = NULL;

	/**
	 * Whole TypoScript typo3_forum settings
	 * @var array
	 */
	protected $settings;



	/*
	 * CONSTRUCTOR
	 */



	/**
	 * Constructor. Initializes all \TYPO3\CMS\Extbase\Persistence\ObjectStorage instances.
	 * @param string                               $title  The forum title.
	 */
	public function __construct($title = '') {
		$this->children    = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
		$this->topics      = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
		$this->criteria    = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
		$this->acls        = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
		$this->subscribers = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
		$this->readers     = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();

		$this->title = $title;
	}



	/**
	 * Injects an instance of the extbase object manager.
	 * @param \TYPO3\CMS\Extbase\Object\ObjectManagerInterface $objectManager
	 */
	public function injectObjectManager(\TYPO3\CMS\Extbase\Object\ObjectManagerInterface $objectManager) {
		$this->objectManager = $objectManager;
	}



	/**
	 * Injects an instance of the typo3_forum authentication service.
	 * @param \Mittwald\Typo3Forum\Service\Authentication\AuthenticationServiceInterface $authenticationService
	 */
	public function injectAuthenticationService(\Mittwald\Typo3Forum\Service\Authentication\AuthenticationServiceInterface $authenticationService) {
		$this->authenticationService = $authenticationService;
	}


	/**
	 * Injects an instance of the forum repository
	 * @param \Mittwald\Typo3Forum\Domain\Repository\Forum\ForumRepository $forumRepository
	 */
	public function injectForumRepository(\Mittwald\Typo3Forum\Domain\Repository\Forum\ForumRepository $forumRepository) {
		$this->forumRepository = $forumRepository;
	}


	/**
	 * Injects an instance of the \TYPO3\CMS\Extbase\Service\TypoScriptService.
	 * @param \TYPO3\CMS\Extbase\Service\TypoScriptService $typoScriptService
	 */
	public function injectTyposcriptService(\TYPO3\CMS\Extbase\Service\TypoScriptService $typoScriptService) {
		$this->typoScriptService = $typoScriptService;
		$ts = $this->typoScriptService->convertTypoScriptArrayToPlainArray(\TYPO3\CMS\Extbase\Configuration\FrontendConfigurationManager::getTypoScriptSetup());
		$this->settings = $ts['plugin']['tx_typo3forum']['settings'];
	}



	/*
	 * GETTERS
	 */


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
	 * @return \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\Mittwald\Typo3Forum\Domain\Model\Forum\Forum>
	 *                             All visible child forums
	 */
	public function getChildren() {
		if ($this->visibleChildren === NULL) {
			$this->visibleChildren = new \ArrayObject();

			// Note: Use the authentication service instead of performing the
			// access checks on the domain objects themselves, since the authentication
			// service caches its results (which should be safe in this case).
			foreach ($this->children as $child) {
				if ($this->authenticationService->checkAuthorization($child, 'read')) {
					$this->visibleChildren->append($child);
				}
			}
		}

		return $this->visibleChildren;
	}



	/**
	 * Gets all topics.
	 * @return \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\Mittwald\Typo3Forum\Domain\Model\Forum\Topic>
	 *                             All topics in this forum
	 */
	public function getTopics() {
		return $this->topics;
	}


	/**
	 * Get all criterias of this forum.
	 * @return \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\Mittwald\Typo3Forum\Domain\Model\Forum\Criteria>
	 */
	public function getCriteria() {
		/* @var \Mittwald\Typo3Forum\Domain\Model\Forum\Criteria */
		$obj =  new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();

		$criteria = $this->getCriteriaRecursive(array($this,$obj));
		$obj = $criteria[1];
		return $obj;
	}

	/**
	 * Get all criterias recursive.
	 * Please don't call this function. Use getCriteria()!!!
	 * @param array $array
	 * @return array
	 */
	private function getCriteriaRecursive($array) {
		//$array[0] current object. Will be repleaced with the parent object in the next call
		//$array[1] object storage with the desired criteria data. Will be filled in every call.
		if ($array[0]->criteria !== NULL) {
			$array[1]->addAll($array[0]->criteria);
		}
		if($array[0]->getParent()->getUid() > 0) {
			$tmp = $this->getCriteriaRecursive(array($array[0]->getParent(),$array[1]));
			$array[1] = $tmp[1];
		}
		return $array;
	}

	/**
	 * Gets all access rules.
	 * @return \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\Mittwald\Typo3Forum\Domain\Model\Forum\Access>
	 *                             All access rules for this forum.
	 */
	public function getAcls() {
		return $this->acls;
	}



	/**
	 * Gets the last topic.
	 * @return \Mittwald\Typo3Forum\Domain\Model\Forum\Topic The last topic
	 */
	public function getLastTopic() {
		if(!$this->lastTopic instanceof \Mittwald\Typo3Forum\Domain\Model\Forum\Topic){
			return NULL;
		}
		$lastTopic = $this->lastTopic;
		foreach ($this->getChildren() as $child) {
			/** @var $child \Mittwald\Typo3Forum\Domain\Model\Forum\Forum */
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
	 * @return \Mittwald\Typo3Forum\Domain\Model\Forum\Post The last post
	 */
	public function getLastPost() {
		if(!$this->lastPost instanceof \Mittwald\Typo3Forum\Domain\Model\Forum\Post){
			return NULL;
		}
		$lastPost = $this->lastPost;
		foreach ($this->getChildren() as $child) {
			/** @var $child \Mittwald\Typo3Forum\Domain\Model\Forum\Forum */
			if ($lastPost === NULL || ($child->getLastPost() !== NULL && $child->getLastPost()
				->getTimestamp() > $lastPost->getTimestamp())
			) {
				$lastPost = $child->getLastPost();
			}
		}
		return $lastPost;
	}



	/**
	 * Gets the parent forum.
	 * @return \Mittwald\Typo3Forum\Domain\Model\Forum\Forum The parent forum
	 */
	public function getForum() {
		if ($this->forum == NULL) {
			return $this->objectManager->get('Mittwald\\Typo3Forum\\Domain\\Model\\Forum\\RootForum');
		}
		return $this->forum;
	}



	/**
	 * Alias for getForum().
	 * @return \Mittwald\Typo3Forum\Domain\Model\Forum\Forum The parent forum
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
			/** @var $child \Mittwald\Typo3Forum\Domain\Model\Forum\Forum */
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
			/** @var $child \Mittwald\Typo3Forum\Domain\Model\Forum\Forum */
			$postCount += $child->getPostCount();
		}
		return $postCount;
	}



	/**
	 * Gets all users who have subscribed to this forum.
	 *
	 * @return \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\Mittwald\Typo3Forum\Domain\Model\User\FrontendUser>
	 *                             All subscribers of this forum.
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
	 * @param \Mittwald\Typo3Forum\Domain\Model\User\FrontendUser $user
	 *                             The user.
	 * @return boolean             TRUE, if all topics in this forum have been read,
	 *                             otherwise FALSE.
	 */
	public function hasBeenReadByUser(\Mittwald\Typo3Forum\Domain\Model\User\FrontendUser $user = NULL) {
		if ($user === NULL || $this->readers === NULL) {
			return TRUE;
		}

		if(intval($this->settings['useSqlStatementsOnCriticalFunctions']) == 0) {
			if($this->readers->contains($user)) {
				return TRUE;
			} else {
				return FALSE;
			}
		} else {
			return $this->forumRepository->getForumReadByUser($this,$user);
		}
	}



	/**
	 * Returns all parent forums in hiearchical order as a flat list (optionally
	 * with or without this forum itself).
	 *
	 * @param  boolean $withSelf TRUE to include this forum into the rootline,
	 *                           otherwise FALSE.
	 *
	 * @return array<\Mittwald\Typo3Forum\Domain\Model\Forum\Forum>
	 */
	public function getRootline($withSelf = TRUE) {
		$rootline = $this->forum === NULL ? array() : $this->forum->getRootline(TRUE);

		if ($withSelf === TRUE) {
			$rootline[] = $this;
		}

		return $rootline;
	}



	/**
	 * Performs an access check for this forum.
	 * *INTERAL USE ONLY!*
	 *
	 * @access                     private
	 * @param  \Mittwald\Typo3Forum\Domain\Model\User\FrontendUser $user        The user that is to be checked against the access
	 *                                                                rules of this forum.
	 * @param  string                                    $accessType  The operation
	 * @return boolean             TRUE, if the user has access to the requested
	 *                             operation, otherwise FALSE.
	 */
	public function checkAccess(\Mittwald\Typo3Forum\Domain\Model\User\FrontendUser $user = NULL, $accessType = 'read') {

		// If there aren't any access rules defined for this forum, delegate
		// the access check to the parent forum. If there is no parent forum
		// either, simply deny access (except for 'read' operations).
		if (count($this->acls) === 0) {
			return $this->getParent()->checkAccess($user, $accessType);
		}

		// Iterate over all access rules, until a matching rule is found
		// that explicitly grants or denies access. If no matching rule is
		// found, delegate to the parent object or deny access (grant read
		// access, if no parent is set).
		$found = FALSE;
		foreach ($this->acls as $acl) {
			/** @var $acl \Mittwald\Typo3Forum\Domain\Model\Forum\Access */
			if ($acl->getOperation() !== $accessType) {
				continue;
			}

			if ($acl->matches($user)) {
				return !$acl->isNegated();
			}

		}
		return $this->getParent()->checkAccess($user, $accessType);
	}



	/**
	 * Checks if a user has read access to this forum.
	 *
	 * @param  \Mittwald\Typo3Forum\Domain\Model\User\FrontendUser $user
	 *                             The user that is to be checked.
	 * @return boolean             TRUE if the user has read access, otherwise FALSE.
	 */
	public function checkReadAccess(\Mittwald\Typo3Forum\Domain\Model\User\FrontendUser $user = NULL) {
		return $this->checkAccess($user, 'read');
	}



	/**
	 * Checks if a user has access to create new posts in this forum.
	 *
	 * @param  \Mittwald\Typo3Forum\Domain\Model\User\FrontendUser $user
	 *                             The user that is to be checked.
	 * @return boolean             TRUE if the user has access, otherwise FALSE.
	 */
	public function checkNewPostAccess(\Mittwald\Typo3Forum\Domain\Model\User\FrontendUser $user = NULL) {
		return $this->checkAccess($user, 'newPost');
	}



	/**
	 * Checks if a user has access to create new topics in this forum.
	 *
	 * @param  \Mittwald\Typo3Forum\Domain\Model\User\FrontendUser $user
	 *                             The user that is to be checked.
	 * @return boolean             TRUE if the user has access, otherwise FALSE.
	 */
	public function checkNewTopicAccess(\Mittwald\Typo3Forum\Domain\Model\User\FrontendUser $user = NULL) {
		return $this->checkAccess($user, 'newTopic');
	}



	/**
	 * Checks if a user has access to moderate in this forum.
	 *
	 * @param  \Mittwald\Typo3Forum\Domain\Model\User\FrontendUser $user
	 *                             The user that is to be checked.
	 * @return boolean             TRUE if the user has access, otherwise FALSE.
	 */
	public function checkModerationAccess(\Mittwald\Typo3Forum\Domain\Model\User\FrontendUser $user = NULL) {
		if ($user === NULL) {
			return FALSE;
		}
		return $this->checkAccess($user, 'moderate');
	}



	/*
	 * SETTERS
	 */



	/**
	 * Sets the title.
	 *
	 * @param string $title The title of the forum
	 * @return void
	 */
	public function setTitle($title) {
		$this->title = $title;
	}

	/**
	 * Set the criteria of this forum..
	 * @param \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\Mittwald\Typo3Forum\Domain\Model\Forum\Criteria> $criteria
	 * @return void
	 */
	public function setCriteria(\TYPO3\CMS\Extbase\Persistence\ObjectStorage $criteria) {
		$this->criteria = $criteria;
	}


	/**
	 * Sets the description.
	 *
	 * @param string $description A description for the forum
	 * @return void
	 */
	public function setDescription($description) {
		$this->description = $description;
	}



	/**
	 * Sets the parent forum.
	 *
	 * @param \Mittwald\Typo3Forum\Domain\Model\Forum\Forum $parent The parent forum.
	 * @return void
	 */
	public function setParent(\Mittwald\Typo3Forum\Domain\Model\Forum\Forum $parent) {
		$this->forum = $parent;
	}



	/**
	 * Adds a child forum.
	 *
	 * @param \Mittwald\Typo3Forum\Domain\Model\Forum\Forum The Forum to be added
	 * @return void
	 */
	public function addChild(\Mittwald\Typo3Forum\Domain\Model\Forum\Forum $child) {
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
	 * @param \Mittwald\Typo3Forum\Domain\Model\Forum\Forum The Forum to be removed
	 * @return void
	 */
	public function removeChild(\Mittwald\Typo3Forum\Domain\Model\Forum\Forum $child) {
		$this->children->detach($child);
	}



	/**
	 * Adds a topic.
	 *
	 * @param \Mittwald\Typo3Forum\Domain\Model\Forum\Topic The Topic to be added
	 * @return void
	 */
	public function addTopic(\Mittwald\Typo3Forum\Domain\Model\Forum\Topic $topic) {

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
	 * @param \Mittwald\Typo3Forum\Domain\Model\Forum\Topic The Topic to be removed
	 * @return void
	 */
	public function removeTopic(\Mittwald\Typo3Forum\Domain\Model\Forum\Topic $topic) {
		$this->topics->detach($topic);
		$this->_resetCounters();
		$this->_resetLastPost();
		$this->_resetLastTopic();
	}



	/**
	 * Sets the access rules for this forum.
	 *
	 * @param \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\Mittwald\Typo3Forum\Domain\Model\Forum\Access> $acls acls
	 * @return void
	 */
	public function setAcls(\TYPO3\CMS\Extbase\Persistence\ObjectStorage $acls) {
		$this->acls = $acls;
	}



	/**
	 * Adds a new access rule.
	 *
	 * @param \Mittwald\Typo3Forum\Domain\Model\Forum\Access The access rule to be added
	 * @return void
	 */
	public function addAcl(\Mittwald\Typo3Forum\Domain\Model\Forum\Access $acl) {
		$this->acls->attach($acl);
	}



	/**
	 * Removes a access rule.
	 *
	 * @param \Mittwald\Typo3Forum\Domain\Model\Forum\Access The access rule to be removed
	 * @return void
	 */
	public function removeAcl(\Mittwald\Typo3Forum\Domain\Model\Forum\Access $acl) {
		$this->acls->detach($acl);
	}



	/**
	 * Sets the last topic.
	 *
	 * @param \Mittwald\Typo3Forum\Domain\Model\Forum\Topic $lastTopic The last topic
	 * @return void
	 */
	public function setLastTopic(\Mittwald\Typo3Forum\Domain\Model\Forum\Topic $lastTopic=NULL) {
		$this->lastTopic = $lastTopic;
	}



	/**
	 * Sets the last post.
	 *
	 * @param \Mittwald\Typo3Forum\Domain\Model\Forum\Post $lastPost The last post.
	 * @return void
	 */
	public function setLastPost(\Mittwald\Typo3Forum\Domain\Model\Forum\Post $lastPost=NULL) {
		$this->lastPost = $lastPost;
	}



	/**
	 * Adds a new subscriber.
	 * @param \Mittwald\Typo3Forum\Domain\Model\User\FrontendUser $user The new subscriber.
	 * @return void
	 */
	public function addSubscriber(\Mittwald\Typo3Forum\Domain\Model\User\FrontendUser $user) {
		$this->subscribers->attach($user);
	}



	/**
	 * Removes a subscriber.
	 * @param \Mittwald\Typo3Forum\Domain\Model\User\FrontendUser $user The subscriber to be removed.
	 */
	public function removeSubscriber(\Mittwald\Typo3Forum\Domain\Model\User\FrontendUser $user) {
		$this->subscribers->detach($user);
	}




	/**
	 * Marks this forum as read by a certain user.
	 *
	 * @param \Mittwald\Typo3Forum\Domain\Model\User\FrontendUser $reader The user who read this forum.
	 * @return void
	 */
	public function addReader(\Mittwald\Typo3Forum\Domain\Model\User\FrontendUser $reader) {
		$this->readers->attach($reader);
	}


	/**
	 * Mark this forum as unread for a certain user.
	 *
	 * @param \Mittwald\Typo3Forum\Domain\Model\User\FrontendUser $reader The user for whom to mark this forum as unread.
	 * @return void
	 */
	public function removeReader(\Mittwald\Typo3Forum\Domain\Model\User\FrontendUser $reader) {
		$this->readers->detach($reader);
	}


	/**
	 * Mark this forum as unread for all users.
	 * @return void
	 */
	public function removeAllReaders() {
		$this->readers = New \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
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
		/** @var $lastPost \Mittwald\Typo3Forum\Domain\Model\Forum\Post */
		$lastPost = NULL;
		foreach ($this->topics as $topic) {
			/** @var $topic \Mittwald\Typo3Forum\Domain\Model\Forum\Topic */
			/** @noinspection PhpUndefinedMethodInspection */
			$lastTopicPostTimestamp = $topic->getLastPost()->getTimestamp();
			if ($lastPost === NULL || $lastTopicPostTimestamp > $lastPost->getTimestamp()) {
				$lastPost = $topic->getLastPost();
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
			/** @var $topic \Mittwald\Typo3Forum\Domain\Model\Forum\Topic */
			/** @noinspection PhpUndefinedMethodInspection */
			$lastTopicPostTimestamp = $topic->getLastPost()->getTimestamp();
			if ($lastTopic === NULL || $lastTopicPostTimestamp > $lastTopic->getTimestamp()) {
				$lastTopic = $topic;
			}
		}

		$this->lastTopic = $lastTopic;
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
	public function _increasePostCount($amount = 1) {
		$this->postCount += $amount;
	}



	/**
	 * Increases (or decreases) the topic count of this forum, and of ALL
	 * PARENT FORUMS.
	 * INTERNAL USE ONLY!
	 *
	 * @param  int $amount The amount by which to increase the topic count
	 *                     (set a negative amount to decrease).
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
			/** @var $topic \Mittwald\Typo3Forum\Domain\Model\Forum\Topic */
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

