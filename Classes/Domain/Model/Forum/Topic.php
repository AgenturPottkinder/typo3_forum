<?php
namespace Mittwald\Typo3Forum\Domain\Model\Forum;

/*                                                                    - *
 *  COPYRIGHT NOTICE                                                    *
 *                                                                      *
 *  (c) 2015 Mittwald CM Service GmbH & Co KG                           *
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

use DateTime;
use Mittwald\Typo3Forum\Domain\Exception\TextParser\Exception;
use Mittwald\Typo3Forum\Domain\Model\AccessibleInterface;
use Mittwald\Typo3Forum\Domain\Model\ConfigurableEntityTrait;
use Mittwald\Typo3Forum\Domain\Model\ConfigurableInterface;
use Mittwald\Typo3Forum\Domain\Model\NotifiableInterface;
use Mittwald\Typo3Forum\Domain\Model\ReadableInterface;
use Mittwald\Typo3Forum\Domain\Model\SubscribeableInterface;
use Mittwald\Typo3Forum\Domain\Model\User\AnonymousFrontendUser;
use Mittwald\Typo3Forum\Domain\Model\User\FrontendUser;
use Mittwald\Typo3Forum\Domain\Repository\Forum\TopicRepository;
use Mittwald\Typo3Forum\Service\Authentication\AuthenticationServiceInterface;
use Mittwald\Typo3Forum\Utility\Slug;
use TYPO3\CMS\Extbase\Annotation\ORM\Cascade;
use TYPO3\CMS\Extbase\Annotation\ORM\Lazy;
use TYPO3\CMS\Extbase\Annotation\Validate;
use TYPO3\CMS\Extbase\DomainObject\AbstractEntity;
use TYPO3\CMS\Extbase\Persistence\ObjectStorage;

/**
 * A single topic. Each topic can contain an infinite number of
 * posts. Topic are submitted to the access control mechanism and
 * can be subscribed by users.
 */
class Topic extends AbstractEntity implements AccessibleInterface, SubscribeableInterface, NotifiableInterface, ReadableInterface, ConfigurableInterface
{
    use ConfigurableEntityTrait;

    /**
     * @Validate("NotEmpty")
     */
    protected string $subject = '';
    /**
     * @Validate("NotEmpty")
     */
    protected string $slug = '';

    /**
     * The posts in this topic.
     * @var ObjectStorage<\Mittwald\Typo3Forum\Domain\Model\Forum\Post>
     * @Lazy
     * @Cascade("remove")
     */
    protected ObjectStorage $posts;

    /**
     * The amount of posts in this topic (of course, we could simply do
     * count($this->posts), however this is much more performant).
     */
    protected int $postCount = 0;
    protected ?FrontendUser $author = null;

    /**
     * All users who have subscribed this topic.
     *
     * @var ObjectStorage<\Mittwald\Typo3Forum\Domain\Model\User\FrontendUser>
     * @Lazy
     */
    protected ObjectStorage $subscribers;

    /**
     * The as solution marked post
     *
     * @var \Mittwald\Typo3Forum\Domain\Model\Forum\Post
     */
    protected ?Post $solution = null;
    protected bool $solved = false;

    /**
     * A pointer to the last post in this topic.
     */
    protected ?Post $lastPost = null;

    /**
     * The creation timestamp of the last post. Enables sorting topics
     * without a SQL join on the posts table.
     */
    protected DateTime $lastPostCrdate;

    /**
     * The forum in which this topic is located.
     */
    protected Forum $forum;

    /**
     * Defines whether this topic is closed.
     */
    protected bool $closed = false;

    /**
     * Defines whether this topic is sticky.
     */
    protected bool $sticky = false;

    /**
     * Defines whether this topic is a question.
     */
    protected bool $question = false;

    /**
     * The topic date.
     */
    protected DateTime $crdate;

    /**
     * All users who have read this topic.
     *
     * @var ObjectStorage<\Mittwald\Typo3Forum\Domain\Model\User\FrontendUser>
     * @Lazy
     */
    protected ObjectStorage $readers;

    /**
     * Get all tags of this topic
     *
     * @var ObjectStorage<\Mittwald\Typo3Forum\Domain\Model\Forum\Tag>
     * @Lazy
     */
    protected ObjectStorage $tags;

    protected AuthenticationServiceInterface $authenticationService;
    protected TopicRepository $topicRepository;

    public function injectTopicRepository(
        TopicRepository $topicRepository
    ): void {
        $this->topicRepository = $topicRepository;
    }

    public function injectAuthenticationService(
        AuthenticationServiceInterface $authenticationService
    ): void {
        $this->authenticationService = $authenticationService;
    }

    public function __construct()
    {
        $this->initializeObject();
    }

    public function initializeObject(): void
    {
        $this->ensureObjectStorages();
    }

    public function ensureObjectStorages(): void
    {
        if (!isset($this->posts)) {
            $this->posts = new ObjectStorage();
        }
        if (!isset($this->subscribers)) {
            $this->subscribers = new ObjectStorage();
        }
        if (!isset($this->readers)) {
            $this->readers = new ObjectStorage();
        }
        if (!isset($this->tags)) {
            $this->tags = new ObjectStorage();
        }
        if (!isset($this->crdate)) {
            $this->crdate = new \DateTime();
        }
    }

    /**
     * Gets the topic subject.
     * @return string The subject
     */
    public function getSubject(): string
    {
        return $this->subject;
    }

    /**
     * @return string
     */
    public function getSlug(): string
    {
        return $this->slug;
    }

    /**
     * @param string $slug
     */
    public function setSlug($slug): self
    {
        $this->slug = $slug;

        return $this;
    }

    /**
     * Alias for getSubject. Necessary to implement the SubscribeableInterface.
     * @return string The subject
     */
    public function getTitle(): string
    {
        return $this->getSubject();
    }

    /**
     * Alias for getSubject. Necessary to implement the NofifiableInterface.
     * @return string  The subject
     */
    public function getName(): string
    {
        return $this->getSubject();
    }

    /**
     * Delegate function to call getText() of the first post. Necessary to implement
     * the NofifiableInterface.
     */
    public function getDescription(): string
    {
        /** @noinspection PhpUndefinedMethodInspection */
        return $this->posts->current()->getText();
    }

    /**
     * Gets the topic author.
     * @return FrontendUser author
     */
    public function getAuthor(): FrontendUser
    {
        if ($this->author === null) {
            if (count($this->posts) > 0) {
                $posts = $this->posts->toArray();
                $this->author = $posts[0]->getAuthor();
            } else {
                $this->author = new AnonymousFrontendUser();
            }
        }

        return $this->author;
    }

    /**
     * Gets all users who have subscribes to this forum.
     * @return ObjectStorage<FrontendUser>
     */
    public function getSubscribers(): ObjectStorage
    {
        return $this->subscribers;
    }

    public function isSolved(): bool
    {
        if ($this->solved || $this->solution !== null) {
            return true;
        }

        return false;
    }

    /**
     * Get the as solution marked post
     */
    public function getSolution(): ?Post
    {
        return $this->isQuestion() ? $this->solution : null;
    }

    /**
     * Gets all posts.
     * @return ObjectStorage<Post>
     */
    public function getPosts(): ObjectStorage
    {
        return $this->posts;
    }

    /**
     * Gets the post count.
     */
    public function getPostCount(): int
    {
        return $this->postCount;
    }

    /**
     * Gets the amount of pages of this topic.
     * @return int Page count
     */
    public function getPageCount(): int
    {
        return ceil($this->postCount / (int)$this->getSettings()['pagebrowser']['topicShow']['itemsPerPage']);
    }

    /**
     * Gets the reply count.
     */
    public function getReplyCount(): int
    {
        if ($this->getPostCount() == 0) {
            return 0;
        }
        return $this->getPostCount() - 1;
    }

    /**
     * Gets whether the topic is closed.
     */
    public function isClosed(): bool
    {
        return $this->closed;
    }

    /**
     * Gets the last post.
     */
    public function getLastPost(): ?Post
    {
        return $this->lastPost;
    }

    /**
     * Gets the forum.
     */
    public function getForum(): Forum
    {
        return $this->forum;
    }

    /**
     * Gets the creation time of this topic.
     * @return \DateTime
     */
    public function getTimestamp(): DateTime
    {
        return $this->crdate;
    }

    /**
     * Checks if this topic is sticky.
     */
    public function isSticky(): bool
    {
        return $this->sticky;
    }

    /**
     * Checks if this topic is a question.
     */
    public function getQuestion(): bool
    {
        return $this->question;
    }
    public function isQuestion(): bool
    {
        return $this->getQuestion();
    }

    /**
     * Get all tags of this topic
     * @return ObjectStorage<\Mittwald\Typo3Forum\Domain\Model\Forum\Tag>
     */
    public function getTags(): ObjectStorage
    {
        return $this->tags;
    }

    /**
     * Determines whether this topic has been read by a certain user.
     */
    public function hasBeenReadByUser(?FrontendUser $reader = null): bool
    {
        return $reader ? $this->readers->contains($reader) : true;
    }

    public function hasBeenReadByCurrentUser(): bool
    {
        return $this->hasBeenReadByUser($this->authenticationService->getUser());
    }

    /**
     * Returns all parent forums in hiearchical order as a flat list (optionally
     * with or without this topic itself).
     *
     * @param bool $withSelf TRUE to include this forum into the rootline, otherwise FALSE.
     *
     * @return array<Forum>
     */
    public function getRootline($withSelf = true): array
    {
        $rootline = $this->forum->getRootline(true);

        if ($withSelf === true) {
            $rootline[] = $this;
        }

        return $rootline;
    }

    /**
     * Get the first post of a topic.
     */
    public function getFirstPost(): ?Post
    {
        $this->getPosts()->rewind();

        return $this->getPosts()->current();
    }

    /**
     * Get the most supported post of a topic
     */
    public function getMostSupportedPost(): ?Post
    {
        $oPost = null;
        foreach ($this->getPosts() as $post) {
            if (($oPost === null || $post->getHelpfulCount() > $oPost->getHelpfulCount()) && $this->getSolution() !== $post && $post->getHelpfulCount() > 0 && $this->getAuthor() !== $post->getAuthor()) {
                $oPost = $post;
            }
        }

        return $oPost;
    }

    /**
     * Checks if a user may perform a certain operation (read, answer...) with this
     * topic.
     */
    public function checkAccess(?FrontendUser $user = null, $accessType = Access::TYPE_READ): bool
    {
        switch ($accessType) {
            case Access::TYPE_NEW_POST:
                return $this->checkNewPostAccess($user);
            case Access::TYPE_MODERATE:
                return $this->checkModerationAccess($user);
            case Access::TYPE_SOLUTION:
                return $this->checkSolutionAccess($user);
            case Access::TYPE_DELETE_TOPIC:
                return $this->checkDeleteTopicAccess($user);
            default:
                return $this->forum->checkAccess($user, $accessType);
        }
    }

    /**
     * Checks if a user may reply to this topic.
     */
    public function checkNewPostAccess(?FrontendUser $user = null): bool
    {
        if ($user === null) {
            return false;
        }

        return $this->getForum()->checkModerationAccess($user) ? true : ($this->isClosed() ? false : $this->getForum()
            ->checkNewPostAccess($user));
    }

    /**
     * Checks if a user has moderative access to this topic.
     */
    public function checkModerationAccess(FrontendUser $user = null): bool
    {
        return ($user === null) ? false : $this->getForum()->checkModerationAccess($user);
    }

    /**
     * Checks if a user has delete topic access to this topic.
     */
    public function checkDeleteTopicAccess(?FrontendUser $user = null): bool
    {
        return ($user === null) ? false : $this->getForum()->checkDeleteTopicAccess($user);
    }

    /**
     * Checks if a user has solution access to this topic.
     */
    public function checkSolutionAccess(?FrontendUser $user): bool
    {
        return
            !$this->isClosed()
            && $user !== null
            && (
                $this->getAuthor()->getUid() === $user->getUid()
                || $this->checkModerationAccess($user)
            )
        ;
    }

    /**
     * Adds a Post. By adding a new post, this topic is automatically marked unread
     * for all users who have read this topic before.
     *
     * @param Post $post The Post to be added
     */
    public function addPost(Post $post): self
    {
        $this->posts->attach($post);
        $post->setTopic($this);
        $this->postCount++;
        $this->removeAllReaders();

        // If the added posts is the first post or has a newer timestamp than the
        // latest post in this topic, mark then new post at the latest post in this
        // topic.
        if ($this->lastPost === null || $this->lastPost->getTimestamp() < $post->getTimestamp()) {
            $this->setLastPost($post);
        }

        // Increase the parent's forum post counter by one and mark the new post as
        // the forums latest post if necessary.
        if ($this->forum !== null) {
            $this->forum->_increasePostCount(+1);
            if ($this->forum->getLastPost() === null || $this->forum->getLastPost()
                    ->getTimestamp() < $post->getTimestamp()
            ) {
                $this->forum->setLastPost($post);
            }
        }

        return $this;
    }

    /**
     * Removes a Post.
     *
     * @param Post $post The Post to be removed
     *
     * @throws \Mittwald\Typo3Forum\Domain\Exception\InvalidOperationException
     */
    public function removePost(Post $post): self
    {
        if ($this->postCount === 1) {
            throw new \Mittwald\Typo3Forum\Domain\Exception\InvalidOperationException('You cannot delete the last post of a topic without deleting the topic itself (use \Mittwald\Typo3Forum\Domain\Factory\Forum\TopicFactory::deleteTopic for that).', 1334603895);
        }

        $this->posts->detach($post);
        $this->postCount--;

        if ($this->lastPost == $post) {
            $postsArray = $this->posts->toArray();
            $this->setLastPost(array_pop($postsArray));
        }

        if ($this->forum !== null) {
            $this->forum->_increasePostCount(-1);
            if ($this->forum->getLastPost() === $post) {
                $this->forum->_resetLastPost();
            }
        }

        return $this;
    }

    /**
     * Sets the topic author.
     *
     * @param ?FrontendUser $author The topic author.
     */
    public function setAuthor(?FrontendUser $author): self
    {
        $this->author = $author;

        return $this;
    }

    /**
     * Sets the last post. This method is not publicy accessible; is is called
     * automatically when a new post is added to this topic.
     *
     * @param Post $lastPost The last post.
     */
    protected function setLastPost(Post $lastPost): self
    {
        $this->lastPost = $lastPost;
        $this->lastPostCrdate = $lastPost->getTimestamp();

        return $this;
    }

    /**
     * Sets the subject of this topic.
     *
     * @param string $subject The subject
     */
    public function setSubject(string $subject): self
    {
        $this->subject = $subject;

        return $this;
    }

    /**
     * Set a post as solution
     */
    public function setSolution(?Post $solution): self
    {
        $this->solution = $solution;
        $this->solved = $solution !== null;

        return $this;
    }

    /**
     * Sets the forum.
     */
    public function setForum(Forum $forum): self
    {
        $this->forum = $forum;

        return $this;
    }

    /**
     * Sets this topic to closed.
     *
     * @param bool $closed TRUE to close this topic, FALSE to re-open it.
     */
    public function setClosed(bool $closed): self
    {
        $this->closed = $closed;

        return $this;
    }

    /**
     * Sets this topic to sticky. Sticky topics will always remain at the top of the
     * forum list, regardless of the timestamp of the last post.
     *
     * @param bool $sticky TRUE to make this topic sticky, FALSE to reset this.
     */
    public function setSticky(bool $sticky): self
    {
        $this->sticky = $sticky;

        return $this;
    }

    /**
     * Sets this topic to a question. Question topics will be shown at the support queries helpbox.
     *
     * @param int $question TRUE to make this topic a question, FALSE to reset this.
     */
    public function setQuestion(bool $question): self
    {
        $this->question = $question;

        return $this;
    }

    /**
     * Add a tag to this topic
     */
    public function addTag(Tag $tag): self
    {
        $this->tags->attach($tag);

        return $this;
    }

    /**
     * Remove a tag of this topic
     */
    public function removeTag(Tag $tag): self
    {
        $this->tags->detach($tag);

        return $this;
    }

    public function hasTag(Tag $tag): bool
    {
        return $this->tags->contains($tag);
    }

    /**
     * Set a whole ObjectStorage as tag
     *
     * @param ObjectStorage $tags
     */
    public function setTags(ObjectStorage $tags): self
    {
        $this->tags = $tags;

        return $this;
    }

    /**
     * Marks this topic as read by a certain user.
     *
     * @param FrontendUser $reader The user who read this topic.
     */
    public function addReader(FrontendUser $reader): self
    {
        $this->readers->attach($reader);

        return $this;
    }

    /**
     * Mark this topic as unread for a certain user.
     *
     * @param FrontendUser $reader The user for whom to mark this topic as unread.
     */
    public function removeReader(FrontendUser $reader): self
    {
        $this->readers->detach($reader);

        return $this;
    }

    /**
     * Mark this topic as unread for all users.
     */
    public function removeAllReaders(): self
    {
        $this->readers = new ObjectStorage();

        return $this;
    }

    /**
     * Adds a new subscriber.
     *
     * @param FrontendUser $user The new subscriber.
     */
    public function addSubscriber(FrontendUser $user): self
    {
        $this->subscribers->attach($user);

        return $this;
    }

    /**
     * Removes a subscriber.
     *
     * @param FrontendUser $user The subscriber to be removed.
     */
    public function removeSubscriber(FrontendUser $user): self
    {
        $this->subscribers->detach($user);

        return $this;
    }

    /**
     * @return ObjectStorage<FrontendUser>
     */
    public function getReaders(): ObjectStorage
    {
        return $this->readers;
    }

    public function setReaders(ObjectStorage $readers): self
    {
        $this->readers = $readers;

        return $this;
    }

    public function generateSlugIfEmpty(): self
    {
        if ($this->slug === '') {
            $this->generateSlug();
        }

        return $this;
    }

    public function generateSlug(): self
    {
        if ($this->getUid() === null) {
            throw new Exception('Can\'t generate slug for a topic that has not been persisted yet.');
        }
        $this->slug = Slug::generateUniqueSlug(
            $this->getUid(),
            'tx_typo3forum_domain_model_forum_topic',
            'slug'
        );

        return $this;
    }
}
