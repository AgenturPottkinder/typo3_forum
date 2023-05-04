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
use Mittwald\Typo3Forum\Domain\Model\ReadableInterface;
use Mittwald\Typo3Forum\Domain\Model\SubscribeableInterface;
use Mittwald\Typo3Forum\Domain\Model\User\FrontendUser;
use Mittwald\Typo3Forum\Service\Authentication\AuthenticationServiceInterface;
use TYPO3\CMS\Extbase\Annotation\ORM\Cascade;
use TYPO3\CMS\Extbase\Annotation\ORM\Lazy;
use TYPO3\CMS\Extbase\Annotation\Validate;
use TYPO3\CMS\Extbase\DomainObject\AbstractEntity;
use TYPO3\CMS\Extbase\Persistence\ObjectStorage;

/**
 * A forum. Forums can be infinitely nested and contain a number of topics. Forums
 * are submitted to the access control mechanism and can be subscribed by users.
 */
class Forum // NOSONAR we are not going reduce the amount of functions
    extends AbstractEntity implements AccessibleInterface, SubscribeableInterface, ReadableInterface
{
    /**
     * @var ObjectStorage<Access>
     */
    protected ObjectStorage $acls;
    /**
     * The child forums
     * @var ObjectStorage<Forum>
     * @Lazy
     */
    protected ObjectStorage $children;
    protected string $description = '';
    protected int $displayedPid = 0;
    /**
     * The parent forum. Ugly type annotations because of @Lazy limitation.
     * @Lazy
     * @var Forum|null
     * @phpstan-var Forum|LazyLoadingProxy|null
     */
    protected ?object $forum = null;
    protected ?Post $lastPost = null;
    protected ?Topic $lastTopic = null;

    /**
     * The amount of post in this forum.
     */
    protected int $postCount = 0;

    /**
     * All users who have read this forum.
     *
     * @var ObjectStorage<FrontendUser>
     * @Lazy
     */
    protected ObjectStorage $readers;

    /**
     * All subscribers of this forum.
     * @var ObjectStorage<FrontendUser>
     * @Lazy
     */
    protected ObjectStorage $subscribers;
    /**
     * @Validate("NotEmpty")
     */
    protected string $title = '';
    protected string $slug = '';
    protected int $topicCount = 0;

    /**
     * The topics in this forum.
     * @var ObjectStorage<Topic>
     * @Lazy
     * @Cascade("remove")
     */
    protected ObjectStorage $topics;

    /**
     * The VISIBLE child forums of this forum, i.e. all forums that the
     * currently logged in user has read access to.
     *
     * @var ObjectStorage<Forum>
     * @Lazy
     */
    protected ObjectStorage $visibleChildren;
    protected AuthenticationServiceInterface $authenticationService;
    protected int $sorting = 0;

    public function __construct()
    {
        $this->initializeObject();
    }

    public function injectAuthenticationService(
        AuthenticationServiceInterface $authenticationService
    ): void {
        $this->authenticationService = $authenticationService;
    }

    public function initializeObject(): void
    {
        $this->ensureObjectStorages();
    }

    public function ensureObjectStorages(): void
    {
        if (!isset($this->children)) {
            $this->children = new ObjectStorage();
        }
        if (!isset($this->topics)) {
            $this->topics = new ObjectStorage();
        }
        if (!isset($this->acls)) {
            $this->acls = new ObjectStorage();
        }
        if (!isset($this->subscribers)) {
            $this->subscribers = new ObjectStorage();
        }
        if (!isset($this->readers)) {
            $this->readers = new ObjectStorage();
        }
        if (!isset($this->visibleChildren)) {
            $this->visibleChildren = new ObjectStorage();
        }
    }

    public function getDisplayedPid(): int
    {
        return $this->displayedPid;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getSlug(): string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): self
    {
        $this->slug = $slug;

        return $this;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * Gets all VISIBLE child forums. This function does NOT simply return
     * all child forums, but performs an access check on each forum, so
     * that only forums visible to the current user are returned.
     *
     * @return ObjectStorage<Forum>
     */
    public function getChildren(): ObjectStorage
    {
        if ($this->visibleChildren->count() === 0) {
            foreach ($this->children as $child) {
                if ($this->authenticationService->checkAuthorization($child, Access::TYPE_READ)) {
                    $this->visibleChildren->attach($child);
                }
            }
        }

        return $this->visibleChildren;
    }

    /**
     * @return ObjectStorage<Forum>
     */
    public function getVisibleChildren(): ObjectStorage
    {
        return $this->visibleChildren;
    }

    /**
     * Gets all topics.
     * @return ObjectStorage<\Mittwald\Typo3Forum\Domain\Model\Forum\Topic> All topics in this forum
     */
    public function getTopics()
    {
        return $this->topics;
    }

    /**
     * Gets all access rules.
     * @return ObjectStorage<\Mittwald\Typo3Forum\Domain\Model\Forum\Access> All access rules for this forum
     */
    public function getAcls()
    {
        return $this->acls;
    }

    /**
     * Gets the last topic.
     */
    public function getLastTopic(): ?Topic
    {
        if ($this->lastTopic === null) {
            $this->_resetLastTopic();
        }
        if (!$this->lastTopic instanceof Topic) {
            return null;
        }
        $lastTopic = $this->lastTopic;
        foreach ($this->getChildren() as $child) {
            /** @var $child Forum */
            /** @noinspection PhpUndefinedMethodInspection */
            if ($lastTopic !== null && $lastTopic->getLastPost() !== null && $child->getLastTopic() !== null && $child->getLastTopic()->getLastPost() !== null) {
                if ($lastTopic === null || ($child->getLastTopic() !== null &&
                        $child->getLastTopic()->getLastPost()->getTimestamp() >
                        $lastTopic->getLastPost()->getTimestamp())
                ) {
                    $lastTopic = $child->getLastTopic();
                }
            }
        }

        return $lastTopic;
    }

    /**
     * Gets the last post.
     */
    public function getLastPost(): ?Post
    {
        if ($this->lastPost === null) {
            $this->_resetLastPost();
        }
        if (!$this->lastPost instanceof Post) {
            return null;
        }
        $lastPost = $this->lastPost;
        foreach ($this->getChildren() as $child) {
            /** @var $child Forum */
            if (
                $lastPost === null ||
                ($child->getLastPost() !== null && $child->getLastPost()->getTimestamp() > $lastPost->getTimestamp())
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
    public function getForum()
    {
        return $this->forum;
    }

    /**
     * Alias for getForum().
     * @return Forum The parent forum
     */
    public function getParent()
    {
        return $this->getForum();
    }

    /**
     * Gets the amount of topics in this forum.
     * @return int The number of topics in this forum
     */
    public function getTopicCount()
    {
        $topicCount = $this->topicCount;
        foreach ($this->getChildren() as $child) {
            /** @var $child Forum */
            $topicCount += $child->getTopicCount();
        }
        return $topicCount;
    }

    /**
     * Gets the amount of posts in this forum and all subforums.
     * @return int The amount of posts in this forum and all subforums.
     */
    public function getPostCount()
    {
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
    public function getSubscribers()
    {
        return $this->subscribers;
    }

    /**
     * Gets the sorting value
     * @return int
     */
    public function getSorting()
    {
        return $this->sorting;
    }

    /**
     * Determines if this forum (i.e. all topics in it) has been read by the given user.
     * @return bool TRUE, if all topics in this forum have been read, otherwise FALSE.
     */
    public function hasBeenReadByUser(?FrontendUser $user = null): bool
    {
        if ($user === null || $this->readers === null) {
            return true;
        }

        return $this->readers->contains($user);
    }

    public function hasBeenReadByCurrentUser(): bool
    {
        return $this->hasBeenReadByUser($this->authenticationService->getUser());
    }

    /**
     * Returns all parent forums in hiearchical order as a flat list (optionally
     * with or without this forum itself).
     *
     * @param bool $withSelf TRUE to include this forum into the rootline, otherwise FALSE.
     * @return array<\Mittwald\Typo3Forum\Domain\Model\Forum\Forum>
     */
    public function getRootline($withSelf = true)
    {
        $rootline = $this->forum === null ? [] : $this->forum->getRootline(true);

        if ($withSelf === true) {
            $rootline[] = $this;
        }

        return $rootline;
    }

    /**
     * Performs an access check for this forum.
     * *INTERAL USE ONLY!*
     *
     * @param FrontendUser $user The user that is to be checked against the access rules of this forum.
     * @param string $accessType The operation
     * @return bool TRUE, if the user has access to the requested operation, otherwise FALSE.
     */
    public function checkAccess(FrontendUser $user = null, $accessType = Access::TYPE_READ)
    {
        if ($accessType === Access::TYPE_MODERATE && $user->isInModerationGroup()) {
            return true;
        }

        // If there aren't any access rules defined for this forum, delegate
        // the access check to the parent forum. If there is no parent forum
        // either, simply deny access (except for 'read' operations).
        if (count($this->acls) === 0) {
            if ($this->getParent() !== null) {
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

        if ($this->getParent()) {
            return $this->getParent()->checkAccess($user, $accessType);
        }

        if ($accessType === Access::TYPE_READ) {
            return true;
        }

        return false;
    }

    /**
     * Checks if a user has read access to this forum.
     *
     * @param FrontendUser $user The user that is to be checked.
     *
     * @return bool TRUE if the user has read access, otherwise FALSE.
     */
    public function checkReadAccess(FrontendUser $user = null)
    {
        return $this->checkAccess($user, Access::TYPE_READ);
    }

    /**
     * Checks if a user has access to create new posts in this forum.
     *
     * @param FrontendUser $user The user that is to be checked.
     *
     * @return bool TRUE if the user has access, otherwise FALSE.
     */
    public function checkNewPostAccess(FrontendUser $user = null)
    {
        return $this->checkAccess($user, Access::TYPE_NEW_POST);
    }

    /**
     * Checks if a user has access to create new topics in this forum.
     *
     * @param FrontendUser $user The user that is to be checked.
     * @return bool TRUE if the user has access, otherwise FALSE.
     */
    public function checkNewTopicAccess(FrontendUser $user = null)
    {
        return $this->checkAccess($user, Access::TYPE_NEW_TOPIC);
    }

    /**
     * Checks if a user has access to moderate in this forum.
     *
     * @param FrontendUser $user The user that is to be checked.
     *
     * @return bool TRUE if the user has access, otherwise FALSE.
     */
    public function checkModerationAccess(FrontendUser $user = null)
    {
        if ($user === null) {
            return false;
        }
        return $this->checkAccess($user, Access::TYPE_MODERATE);
    }

    /**
     * Checks if a user has access to delete topic in this forum.
     *
     * @param FrontendUser $user The user that is to be checked.
     *
     * @return bool TRUE if the user has access, otherwise FALSE.
     */
    public function checkDeleteTopicAccess(FrontendUser $user = null)
    {
        if ($user === null) {
            return false;
        }
        return $this->checkAccess($user, Access::TYPE_DELETE_TOPIC);
    }

    /**
     * Sets the title.
     *
     * @param string $title The title of the forum
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }

    /**
     * Sets the description.
     *
     * @param string $description A description for the forum
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }

    /**
     * Sets the parent forum.
     *
     * @param Forum $parent The parent forum.
     */
    public function setParent(Forum $parent)
    {
        $this->forum = $parent;
    }

    /**
     * Adds a child forum.
     */
    public function addChild(Forum $child): self
    {
        $this->visibleChildren->removeAll($this->visibleChildren);
        $this->children->attach($child);

        $child->setParent($this);
        $this->_resetCounters();
        $this->_resetLastPost();
        $this->_resetLastTopic();

        return $this;
    }

    /**
     * Removes a child forum.
     *
     * @param Forum $child The Forum to be removed
     */
    public function removeChild(Forum $child)
    {
        $this->children->detach($child);
    }

    /**
     * Adds a topic.
     *
     * @param Topic $topic
     */
    public function addTopic(Topic $topic)
    {
        if ($this->lastTopic === null || $this->lastTopic->getTimestamp() <= $topic->getTimestamp()) {
            $this->setLastTopic($topic);
        }
        $topicLastPost = $topic->getLastPost();
        if (($topicLastPost !== null) && ($this->lastPost === null || $this->lastPost->getTimestamp() <= $topicLastPost->getTimestamp())) {
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
     */
    public function removeTopic(Topic $topic)
    {
        $this->topics->detach($topic);
        $this->_resetCounters();
        $this->_resetLastPost();
        $this->_resetLastTopic();
    }

    /**
     * Sets the access rules for this forum.
     *
     * @param ObjectStorage $acls
     */
    public function setAcls(ObjectStorage $acls)
    {
        $this->acls = $acls;
    }

    /**
     * Adds a new access rule.
     *
     * @param Access $acl The access rule to be added
     */
    public function addAcl(Access $acl)
    {
        $this->acls->attach($acl);
    }

    /**
     * Removes a access rule.
     *
     * @param Access $acl The access rule to be removed
     */
    public function removeAcl(Access $acl)
    {
        $this->acls->detach($acl);
    }

    /**
     * Sets the last topic.
     *
     * @param Topic $lastTopic The last topic
     */
    public function setLastTopic(Topic $lastTopic = null)
    {
        $this->lastTopic = $lastTopic;
    }

    /**
     * Sets the last post.
     *
     * @param Post $lastPost The last post.
     */
    public function setLastPost(Post $lastPost = null)
    {
        $this->lastPost = $lastPost;
    }

    /**
     * Adds a new subscriber.
     *
     * @param FrontendUser $user The new subscriber.
     */
    public function addSubscriber(FrontendUser $user)
    {
        $this->subscribers->attach($user);
    }

    /**
     * Removes a subscriber.
     *
     * @param FrontendUser $user The subscriber to be removed.
     */
    public function removeSubscriber(FrontendUser $user)
    {
        $this->subscribers->detach($user);
    }

    /**
     * Get the Readers
     *
     * @@return \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\Mittwald\Typo3Forum\Domain\Model\User\FrontendUser>
     */
    public function getReaders()
    {
        return $this->readers;
    }

    /**
     * Marks this forum as read by a certain user.
     *
     * @param FrontendUser $reader The user who read this forum.
     */
    public function addReader(FrontendUser $reader)
    {
        $this->readers->attach($reader);
    }

    /**
     * Mark this forum as unread for a certain user.
     *
     * @param FrontendUser $reader The user for whom to mark this forum as unread.
     */
    public function removeReader(FrontendUser $reader)
    {
        $this->readers->detach($reader);
    }

    /**
     * Mark this forum as unread for all users.
     */
    public function removeAllReaders()
    {
        $this->readers = new ObjectStorage();
    }

    /**
     * Resets the last post. This method iterates over all topics in this
     * forum and looks for the latest post.
     * INTERNAL USE ONLY!
     */
    public function _resetLastPost()
    {
        /** @var $lastPost Post */
        $lastPost = null;
        foreach ($this->topics as $topic) {
            /** @var $topic Topic */
            /** @noinspection PhpUndefinedMethodInspection */
            if ($topic->getLastPost() instanceof Post) {
                $lastTopicPostTimestamp = $topic->getLastPost()->getTimestamp();
                if ($lastPost === null || $lastTopicPostTimestamp > $lastPost->getTimestamp()) {
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
     */
    public function _resetLastTopic()
    {
        $lastTopic = null;
        foreach ($this->topics as $topic) {
            /** @var $topic Topic */
            /** @var $lastTopic Topic */
            if ($topic->getLastPost() instanceof Post) {
                $lastTopicPostTimestamp = $topic->getLastPost()->getTimestamp();
                if ($lastTopic === null || $lastTopicPostTimestamp > $lastTopic->getTimestamp()) {
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
     */
    public function _increasePostCount($amount = 1)
    {
        $this->postCount += $amount;
    }

    /**
     * Increases (or decreases) the topic count of this forum, and of ALL
     * PARENT FORUMS.
     * INTERNAL USE ONLY!
     *
     * @param int $amount The amount by which to increase the topic count (set a negative amount to decrease).
     */
    public function _increaseTopicCount($amount = 1)
    {
        $this->topicCount += $amount;
    }

    /**
     * Resets all internal counters (e.g. topic and post counter).
     */
    public function _resetCounters()
    {
        $this->_resetTopicCount();
        $this->_resetPostCount();
    }

    /**
     * Resets the internal post counter.
     */
    public function _resetPostCount()
    {
        $this->postCount = 0;
        foreach ($this->topics as $topic) {
            /** @var $topic Topic */
            $this->postCount += $topic->getPostCount();
        }
    }

    /**
     * Resets the internal topic counter.
     */
    public function _resetTopicCount()
    {
        $this->topicCount = count($this->topics);
    }
}
