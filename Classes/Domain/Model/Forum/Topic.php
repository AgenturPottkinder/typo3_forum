<?php
namespace Mittwald\Typo3Forum\Domain\Model\Forum;
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
 * @author     Martin Helmich <m.helmich@mittwald.de>
 * @package    Typo3Forum
 * @subpackage Domain_Model_Forum
 * @version    $Id$
 * @license    GNU Public License, version 2
 *             http://opensource.org/licenses/gpl-license.php

 */
class Topic extends \TYPO3\CMS\Extbase\DomainObject\AbstractEntity
	implements \Mittwald\Typo3Forum\Domain\Model\AccessibleInterface, \Mittwald\Typo3Forum\Domain\Model\SubscribeableInterface,
	\Mittwald\Typo3Forum\Domain\Model\NotifiableInterface, Tx_Typo3Forum_Domain_Model_ReadableInterface {


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
	 * The posts in this topic.
	 * @var Tx_Extbase_Persistence_ObjectStorage<\Mittwald\Typo3Forum\Domain\Model\Forum\Post>
	 * @lazy
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
	 * The user who created the topic.
	 * @var \Mittwald\Typo3Forum\Domain\Model\User\FrontendUser
	 */
	protected $author;


	/**
	 * All users who have subscribed this topic.
	 *
	 * @var Tx_Extbase_Persistence_ObjectStorage<\Mittwald\Typo3Forum\Domain\Model\User\FrontendUser>
	 * @lazy
	 */
	protected $subscribers;

	/**
	 * All users who have subscribed this topic.
	 *
	 * @var Tx_Extbase_Persistence_ObjectStorage<\Mittwald\Typo3Forum\Domain\Model\User\FrontendUser>
	 * @lazy
	 */
	protected $favSubscribers;


	/**
	 * The as solution marked post
	 *
	 * @var \Mittwald\Typo3Forum\Domain\Model\Forum\Post
	 * @lazy
	 */
	protected $solution;

	/**
	 *
	 * @var int
	 *
	 */
	protected $isSolved;


	/**
	 * A pointer to the last post in this topic.
	 *
	 * @var \Mittwald\Typo3Forum\Domain\Model\Forum\Post
	 *
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
	 * The forum in which this topic is located.
	 * @var \Mittwald\Typo3Forum\Domain\Model\Forum\Forum
	 */
	protected $forum;


	/**
	 * Defines whether this topic is closed.
	 * @var boolean
	 */
	protected $closed;


	/**
	 * Defines whether this topic is sticky.
	 * @var boolean
	 */
	protected $sticky;


	/**
	 * Defines whether this topic is a question.
	 * @var int
	 */
	protected $question;


	/**
	 * The topic date.
	 * @var DateTime
	 */
	protected $crdate;


	/**
	 * All users who have read this topic.
	 *
	 * @var Tx_Extbase_Persistence_ObjectStorage<\Mittwald\Typo3Forum\Domain\Model\User\FrontendUser>
	 * @lazy
	 */
	protected $readers;


	/**
	 * Get all options of a criteria of this topic
	 *
	 * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\Mittwald\Typo3Forum\Domain\Model\Forum\CriteriaOption>
	 */
	protected $criteriaOptions;

	/**
	 * Get all tags of this topic
	 *
	 * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\Mittwald\Typo3Forum\Domain\Model\Forum\Tag>
	 * @lazy
	 */
	protected $tags;

	/**
	 * @var \Mittwald\Typo3Forum\Domain\Repository\Forum\TopicRepository
	 * @lazy
	 */
	protected $topicRepository;


    /**
     * An instance of the typo3_forum authentication service.
     * @var TYPO3\CMS\Extbase\Service\TypoScriptService
     */
    protected $typoScriptService = NULL;

    /**
     * Whole TypoScript typo3_forum settings
     * @var array
     */
    protected $settings;


 	/**
	 * Helper variable to store if the parent object was modified. This is necessary
	 * due to http://forge.typo3.org/issues/8952
	 *
	 * @var boolean
	 */
//	private $_modifiedParent = FALSE;


	/*
	 * CONSTRUCTOR
	 */


	/**
	 * @param \Mittwald\Typo3Forum\Domain\Repository\Forum\TopicRepository $topicRepository
	 */
	public function injectTopicRepository(\Mittwald\Typo3Forum\Domain\Repository\Forum\TopicRepository $topicRepository) {
		$this->topicRepository = $topicRepository;
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


	/**
	 * Constructor. Initializes all Tx_Extbase_Persistence_ObjectStorage instances.
	 * @param string $subject The topic's subject.
	 */
	public function __construct($subject = '') {
		$this->posts = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
		$this->subscribers = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
		$this->readers = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
		$this->criteriaOptions = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
		$this->tags = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
		$this->crdate = new DateTime();
		$this->subject = $subject;
	}


	/*
	 * GETTER METHODS
	 */


	/**
	 * Gets the topic subject.
	 * @return string The subject
	 */
	public function getSubject() {
		return $this->subject;
	}


	/**
	 * Alias for getSubject. Necessary to implement the SubscribeableInterface.
	 * @return string The subject
	 */
	public function getTitle() {
		return $this->getSubject();
	}


	/**
	 * Alias for getSubject. Necessary to implement the NofifiableInterface.
	 * @return string  The subject
	 */
	public function getName() {
		return $this->getSubject();
	}


	/**
	 * Delegate function to call getText() of the first post. Necessary to implement
	 * the NofifiableInterface.
	 *
	 * @return string The description
	 */
	public function getDescription() {
		/** @noinspection PhpUndefinedMethodInspection */
		return $this->posts->current()->getText();
	}


	/**
	 * Gets the topic author.
	 * @return \Mittwald\Typo3Forum\Domain\Model\User\FrontendUser author
	 */
	public function getAuthor() {
		if ($this->author === NULL) {
			if (count($this->posts) > 0) {
				$posts = $this->posts->toArray();
				$this->author = $posts[0]->getAuthor();
			} else {
				$this->author = new \Mittwald\Typo3Forum\Domain\Model\User\AnonymousFrontendUser();
			}
		}
		return $this->author;
	}


	/**
	 * Gets all users who have subscribes to this forum.
	 * @return Tx_Extbase_Persistence_ObjectStorage<\Mittwald\Typo3Forum\Domain\Model\User\FrontendUser>
	 */
	public function getSubscribers() {
		return $this->subscribers;
	}

	/**
	 * Gets all users who have subscribes to this forum.
	 * @return Tx_Extbase_Persistence_ObjectStorage<\Mittwald\Typo3Forum\Domain\Model\User\FrontendUser>
	 */
	public function getFavSubscribers() {
		return $this->favSubscribers;
	}

	public function getIsSolved(){
		if($this->isSolved ==  1 || $this->getSolution() != null){
			return true;
		} 
		return false;
	}
	/**
	 * Get the as solution marked post
	 * @return \Mittwald\Typo3Forum\Domain\Model\Forum\Post
	 */
	public function getSolution() {
		return $this->solution;
	}

	/**
	 * Gets all posts.
	 * @return Tx_Extbase_Persistence_ObjectStorage<\Mittwald\Typo3Forum\Domain\Model\Forum\Post> posts
	 */
	public function getPosts() {
		return $this->posts;
	}


	/**
	 * Gets the post count.
	 * @return integer Post count
	 */
	public function getPostCount() {
		return $this->postCount;
	}


	 /**
	 * Gets the amount of pages of this topic.
	 * @return integer Page count
	 */
	public function getPageCount() {
		return ceil($this->postCount / intval($this->settings['pagebrowser']['topicShow']['itemsPerPage']));
	}


	/**
	 * Gets the reply count.
	 * @return integer Reply count
	 */
	public function getReplyCount() {
		if($this->getPostCount() == 0) {
			return 0;
		} else {
			return $this->getPostCount() - 1;
		}
	}


	/**
	 * Gets whether the topic is closed.
	 * @return boolean
	 */
	public function isClosed() {
		return $this->closed;
	}


	/**
	 * Gets the last post.
	 * @return \Mittwald\Typo3Forum\Domain\Model\Forum\Post lastPost
	 */
	public function getLastPost() {
		return $this->lastPost;
	}


	/**
	 * Gets the forum.
	 * @return \Mittwald\Typo3Forum\Domain\Model\Forum\Forum A forum
	 */
	public function getForum() {
		return $this->forum;
	}


	/**
	 * Gets the creation time of this topic.
	 * @return DateTime
	 */
	public function getTimestamp() {
		return $this->crdate;
	}


	/**
	 * Checks if this topic is sticky.
	 * @return boolean
	 */
	public function isSticky() {
		return $this->sticky;
	}


	/**
	 * Checks if this topic is a question.
	 * @return int
	 */
	public function getQuestion() {
		return (int)$this->question;
	}

	/**
	 * Get all criteria options
	 * @return \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\Mittwald\Typo3Forum\Domain\Model\Forum\CriteriaOption>
	 */
	public function getCriteriaOptions() {
		return $this->criteriaOptions;
	}

	/**
	 * Get all tags of this topic
	 * @return \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\Mittwald\Typo3Forum\Domain\Model\Forum\Tag>
	 */
	public function getTags() {
		return $this->tags;
	}

	/**
	 * Determines whether this topic has been read by a certain user.
	 *
	 * @param  \Mittwald\Typo3Forum\Domain\Model\User\FrontendUser $reader The user who is to be checked.
	 * @return boolean                                           TRUE, if the user did read this topic, otherwise FALSE.
	 */
	public function hasBeenReadByUser(\Mittwald\Typo3Forum\Domain\Model\User\FrontendUser $reader = NULL) {
		if(intval($this->settings['useSqlStatementsOnCriticalFunctions']) == 0) {
			return $reader ? $this->readers->contains($reader) : TRUE;
		} else {
			$res = $this->topicRepository->getTopicReadByUser($this,$reader);
			return !$res;
		}
	}


	/**
	 * Returns all parent forums in hiearchical order as a flat list (optionally
	 * with or without this topic itself).
	 *
	 * @param  boolean $withSelf TRUE to include this forum into the rootline, otherwise FALSE.
	 * @return array<\Mittwald\Typo3Forum\Domain\Model\Forum\Forum>
	 */
	public function getRootline($withSelf = TRUE) {
		$rootline = $this->forum->getRootline(TRUE);

		if ($withSelf === TRUE) {
			$rootline[] = $this;
		}
		return $rootline;
	}

	/**
	 * Get the first post of a topic
	 * @return \Mittwald\Typo3Forum\Domain\Model\Forum\Post
	 */
	public function getFirstPost() {
		$this->getPosts()->rewind();
		return $this->getPosts()->current();
	}

	/**
	 * Get the most supported post of a topic
	 * @return \Mittwald\Typo3Forum\Domain\Model\Forum\Post
	 * @todo refactor (Lazyloading or something else)
	 */
	public function getMostSupportedPost() {
		$oPost = false;
		foreach ($this->getPosts() as $post) {
			if (($oPost == false || $post->getHelpfulCount() > $oPost->getHelpfulCount()) && $this->getSolution() !== $post && $post->getHelpfulCount() > 0 && $this->getAuthor() !== $post->getAuthor()) {
				$oPost = $post;
			}
		}
		return $oPost;
	}


	/**
	 * Checks if a user may perform a certain operation (read, answer...) with this
	 * topic.
	 *
	 * @param  \Mittwald\Typo3Forum\Domain\Model\User\FrontendUser $user       The user.
	 * @param  string $accessType The access type to be checked.
	 * @return boolean
	 */
	public function checkAccess(\Mittwald\Typo3Forum\Domain\Model\User\FrontendUser $user = NULL, $accessType = 'read') {
		switch ($accessType) {
			case 'newPost':
				return $this->checkNewPostAccess($user);
			case 'moderate':
				return $this->checkModerationAccess($user);
			case 'solution':
				return $this->checkSolutionAccess($user);
			default:
				return $this->forum->checkAccess($user, $accessType);
		}
	}


	/**
	 * Checks if a user may reply to this topic.
	 *
	 * @param  \Mittwald\Typo3Forum\Domain\Model\User\FrontendUser $user
	 * @return boolean
	 */
	public function checkNewPostAccess(\Mittwald\Typo3Forum\Domain\Model\User\FrontendUser $user = NULL) {
		if ($user === NULL) {
			return FALSE;
		}
		return $this->getForum()->checkModerationAccess($user) ? TRUE : ($this->isClosed() ? FALSE : $this->getForum()
			->checkNewPostAccess($user));
	}


	/**
	 * Checks if a user has moderative access to this topic.
	 *
	 * @param  \Mittwald\Typo3Forum\Domain\Model\User\FrontendUser $user
	 * @return boolean
	 */
	public function checkModerationAccess(\Mittwald\Typo3Forum\Domain\Model\User\FrontendUser $user = NULL) {
		return ($user === NULL) ? FALSE : $this->getForum()->checkModerationAccess($user);
	}


	/**
	 * Checks if a user has solution access to this topic.
	 *
	 * @param  \Mittwald\Typo3Forum\Domain\Model\User\FrontendUser $user
	 * @return boolean
	 */
	public function checkSolutionAccess(\Mittwald\Typo3Forum\Domain\Model\User\FrontendUser $user = NULL) {
		if($this->getAuthor()->getUid() == $user->getUid() || $this->checkModerationAccess($user)) {
			return true;
		} else {
			return false;
		}
	}


	/**
	 * Workaround to prevent endless-recursive object persisting.
	 *
	 * @param  mixed $previousValue
	 * @param  mixed $currentValue
	 * @return boolean
	 */
//	protected function isPropertyDirty($previousValue, $currentValue) {
//		if ($currentValue instanceof \Mittwald\Typo3Forum\Domain\Model\Forum\Forum) {
//			return $this->_modifiedParent;
//		} else {
//			return parent::isPropertyDirty($previousValue, $currentValue);
//		}
//	}


	/*
	 * SETTER METHODS
	 */


	/**
	 * Adds a Post. By adding a new post, this topic is automatically marked unread
	 * for all users who have read this topic before.
	 *
	 * @param  \Mittwald\Typo3Forum\Domain\Model\Forum\Post $post The Post to be added
	 * @return void
	 */
	public function addPost(\Mittwald\Typo3Forum\Domain\Model\Forum\Post $post) {
		$this->posts->attach($post);
		$post->setTopic($this);
		$this->postCount++;
		$this->removeAllReaders();

		//		if ($this->forum !== NULL) {
		//			$this->_modifiedParent = TRUE;
		//		}

		// If the added posts is the first post or has a newer timestamp than the
		// latest post in this topic, mark then new post at the latest post in this
		// topic.
		if ($this->lastPost === NULL || $this->lastPost->getTimestamp() < $post->getTimestamp()) {
			$this->setLastPost($post);
		}

		// Increase the parent's forum post counter by one and mark the new post as
		// the forums latest post if necessary.
		if ($this->forum !== NULL) {
			$this->forum->_increasePostCount(+1);
			if ($this->forum->getLastPost() === NULL || $this->forum->getLastPost()
					->getTimestamp() < $post->getTimestamp()
			) {
				$this->forum->setLastPost($post);
			}
		}
	}


	/**
	 * Adds a criteria option to the repository.
	 *
	 * @param  \Mittwald\Typo3Forum\Domain\Model\Forum\CriteriaOption $option The Option to be added
	 * @return void
	 */
	public function addCriteriaOption(\Mittwald\Typo3Forum\Domain\Model\Forum\CriteriaOption $option) {
		$this->criteriaOptions->attach($option);
	}


	/**
	 * Removes a Post.
	 *
	 * @param  \Mittwald\Typo3Forum\Domain\Model\Forum\Post $post The Post to be removed
	 * @return void
	 */
	public function removePost(\Mittwald\Typo3Forum\Domain\Model\Forum\Post $post) {
		if ($this->postCount === 1) {
			throw new \Mittwald\Typo3Forum\Domain\Exception\InvalidOperationException('You cannot delete the last post of a topic without deleting the topic itself (use \Mittwald\Typo3Forum\Domain\Factory\Forum\TopicFactory::deleteTopic for that).', 1334603895);
		}

		$this->posts->detach($post);
		$this->postCount--;

		if ($this->lastPost == $post) {
			$postsArray = $this->posts->toArray();
			$this->setLastPost(array_pop($postsArray));
		}

		if ($this->forum !== NULL) {
			$this->forum->_increasePostCount(-1);
			if ($this->forum->getLastPost() === $post) {
				$this->forum->_resetLastPost();
			}
			//		    $this->_modifiedParent = TRUE;
		}

	}


	/**
	 * Sets the topic author.
	 *
	 * @param  \Mittwald\Typo3Forum\Domain\Model\User\FrontendUser $author The topic author.
	 * @return void
	 */
	public function setAuthor(\Mittwald\Typo3Forum\Domain\Model\User\FrontendUser $author) {
		$this->author = $author;
	}


	/**
	 * Sets the last post. This method is not publicy accessible; is is called
	 * automatically when a new post is added to this topic.
	 *
	 * @param  \Mittwald\Typo3Forum\Domain\Model\Forum\Post $lastPost The last post.
	 * @return void
	 */
	protected function setLastPost(\Mittwald\Typo3Forum\Domain\Model\Forum\Post $lastPost) {
		$this->lastPost = $lastPost;
		$this->lastPostCrdate = $lastPost->getTimestamp();
	}


	/**
	 * Sets the subject of this topic.
	 *
	 * @param  string $subject The subject
	 * @return void
	 */
	public function setSubject($subject) {
		$this->subject = $subject;
	}


	/**
	 * Set a post as solution
	 * @param \Mittwald\Typo3Forum\Domain\Model\Forum\Post $solution
	 * @return void
	 */
	public function setSolution(\Mittwald\Typo3Forum\Domain\Model\Forum\Post $solution) {
		$this->solution = $solution;
	}

	/**
	 * Sets the forum.
	 *
	 * @param  \Mittwald\Typo3Forum\Domain\Model\Forum\Forum $forum The forum
	 * @return void
	 */
	public function setForum(\Mittwald\Typo3Forum\Domain\Model\Forum\Forum $forum) {
		$this->forum = $forum;
	}


	/**
	 * Sets this topic to closed.
	 *
	 * @param  boolean $closed TRUE to close this topic, FALSE to re-open it.
	 * @return void
	 */
	public function setClosed($closed) {
		$this->closed = (boolean)$closed;
	}


	/**
	 * Sets this topic to sticky. Sticky topics will always remain at the top of the
	 * forum list, regardless of the timestamp of the last post.
	 *
	 * @param  boolean $sticky TRUE to make this topic sticky, FALSE to reset this.
	 * @return void
	 */
	public function setSticky($sticky) {
		$this->sticky = (boolean)$sticky;
	}


	/**
	 * Sets this topic to a question. Question topics will be shown at the support queries helpbox.
	 *
	 * @param  int $question TRUE to make this topic a question, FALSE to reset this.
	 * @return void
	 */
	public function setQuestion($question) {
		$this->question = (int)$question;
	}


	/**
	 * Set all criteria and options
	 * @param \TYPO3\CMS\Extbase\Persistence\ObjectStorage $criteriaOptions
	 * @return void
	 */
	public function setCriteriaOptions(\TYPO3\CMS\Extbase\Persistence\ObjectStorage $criteriaOptions) {
		$this->criteriaOptions = $criteriaOptions;
	}


	/**
	 * Add a tag to this topic
	 * @param \Mittwald\Typo3Forum\Domain\Model\Forum\Tag $tag
	 * @return void
	 */
	public function addTag(\Mittwald\Typo3Forum\Domain\Model\Forum\Tag $tag) {
		$this->tags->attach($tag);
	}

	/**
	 * Remove a tag of this topic
	 * @param \Mittwald\Typo3Forum\Domain\Model\Forum\Tag $tag
	 * @return void
	 */
	public function removeTag(\Mittwald\Typo3Forum\Domain\Model\Forum\Tag $tag) {
		$this->tags->detach($tag);
	}

	/**
	 * Set a whole ObjectStorage as tag
	 * @param \TYPO3\CMS\Extbase\Persistence\ObjectStorage $tags
	 * @return void
	 */
	public function setTags(\TYPO3\CMS\Extbase\Persistence\ObjectStorage $tags) {
		$this->tags = $tags;
	}

	/**
	 * Marks this topic as read by a certain user.
	 *
	 * @param \Mittwald\Typo3Forum\Domain\Model\User\FrontendUser $reader The user who read this topic.
	 * @return void
	 */
	public function addReader(\Mittwald\Typo3Forum\Domain\Model\User\FrontendUser $reader) {
		$this->readers->attach($reader);
	}


	/**
	 * Mark this topic as unread for a certain user.
	 *
	 * @param \Mittwald\Typo3Forum\Domain\Model\User\FrontendUser $reader The user for whom to mark this topic as unread.
	 * @return void
	 */
	public function removeReader(\Mittwald\Typo3Forum\Domain\Model\User\FrontendUser $reader) {
		$this->readers->detach($reader);
	}


	/**
	 * Mark this topic as unread for all users.
	 * @return void
	 */
	public function removeAllReaders() {
		$this->readers = New \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
	}

	/**
	 * Adds a new subscriber.
	 * @param \Mittwald\Typo3Forum\Domain\Model\User\FrontendUser $user The new subscriber.
	 * @return void
	 */
	public function addFavSubscriber(\Mittwald\Typo3Forum\Domain\Model\User\FrontendUser $user) {
		$this->favSubscribers->attach($user);
	}


	/**
	 * Removes a subscriber.
	 * @param \Mittwald\Typo3Forum\Domain\Model\User\FrontendUser $user The subscriber to be removed.
	 */
	public function removeFavSubscriber(\Mittwald\Typo3Forum\Domain\Model\User\FrontendUser $user) {
		$this->favSubscribers->detach($user);
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


}
