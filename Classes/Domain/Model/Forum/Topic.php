<?php
namespace Mittwald\Typo3Forum\Domain\Model\Forum;

use Mittwald\Typo3Forum\Domain\Model\AccessibleInterface;

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

use Mittwald\Typo3Forum\Domain\Model\ConfigurableEntityTrait;
use Mittwald\Typo3Forum\Domain\Model\ConfigurableInterface;
use Mittwald\Typo3Forum\Domain\Model\NotifiableInterface;
use Mittwald\Typo3Forum\Domain\Model\ReadableInterface;
use Mittwald\Typo3Forum\Domain\Model\SubscribeableInterface;
use Mittwald\Typo3Forum\Domain\Model\User\FrontendUser;
use TYPO3\CMS\Extbase\Annotation\Inject;
use TYPO3\CMS\Extbase\Annotation\ORM\Lazy;
use TYPO3\CMS\Extbase\Annotation\Validate;
use TYPO3\CMS\Extbase\DomainObject\AbstractEntity;

/**
 * A single topic. Each topic can contain an infinite number of
 * posts. Topic are submitted to the access control mechanism and
 * can be subscribed by users.
 */
class Topic extends AbstractEntity implements AccessibleInterface, SubscribeableInterface, NotifiableInterface, ReadableInterface, ConfigurableInterface
{
    use ConfigurableEntityTrait;

    /**
     * The subject
     *
     * @var string
     * @Validate("NotEmpty")
     */
    protected $subject;

    /**
     * The posts in this topic.
     * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\Mittwald\Typo3Forum\Domain\Model\Forum\Post>
     * @Lazy
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
     * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\Mittwald\Typo3Forum\Domain\Model\User\FrontendUser>
     * @Lazy
     */
    protected $subscribers;

    /**
     * All users who have subscribed this topic.
     *
     * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\Mittwald\Typo3Forum\Domain\Model\User\FrontendUser>
     * @Lazy
     */
    protected $favSubscribers;

    /**
     * The as solution marked post
     *
     * @var \Mittwald\Typo3Forum\Domain\Model\Forum\Post
     * @Lazy
     */
    protected $solution;

    /**
     * @var int
     */
    protected $isSolved;

    /**
     * A pointer to the last post in this topic.
     *
     * @var \Mittwald\Typo3Forum\Domain\Model\Forum\Post
     */
    protected $lastPost;

    /**
     * The creation timestamp of the last post. Enables sorting topics
     * without a SQL join on the posts table.
     *
     * @var \DateTime
     */
    protected $lastPostCrdate;

    /**
     * The forum in which this topic is located.
     * @var \Mittwald\Typo3Forum\Domain\Model\Forum\Forum
     */
    protected $forum;

    /**
     * Defines whether this topic is closed.
     * @var bool
     */
    protected $closed;

    /**
     * Defines whether this topic is sticky.
     * @var bool
     */
    protected $sticky;

    /**
     * Defines whether this topic is a question.
     * @var int
     */
    protected $question;

    /**
     * The topic date.
     * @var \DateTime
     */
    protected $crdate;

    /**
     * All users who have read this topic.
     *
     * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\Mittwald\Typo3Forum\Domain\Model\User\FrontendUser>
     * @Lazy
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
     * @Lazy
     */
    protected $tags;

    /**
     * @var \Mittwald\Typo3Forum\Domain\Repository\Forum\TopicRepository
     * @Inject
     */
    protected $topicRepository;

    /**
     * Constructor. Initializes all \TYPO3\CMS\Extbase\Persistence\ObjectStorage instances.
     *
     * @param string $subject The topic's subject.
     */
    public function __construct($subject = '')
    {
        $this->posts = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
        $this->subscribers = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
        $this->readers = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
        $this->criteriaOptions = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
        $this->tags = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
        $this->crdate = new \DateTime();
        $this->subject = $subject;
    }

    /**
     * Gets the topic subject.
     * @return string The subject
     */
    public function getSubject()
    {
        return $this->subject;
    }

    /**
     * Alias for getSubject. Necessary to implement the SubscribeableInterface.
     * @return string The subject
     */
    public function getTitle()
    {
        return $this->getSubject();
    }

    /**
     * Alias for getSubject. Necessary to implement the NofifiableInterface.
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
        return $this->posts->current()->getText();
    }

    /**
     * Gets the topic author.
     * @return FrontendUser author
     */
    public function getAuthor()
    {
        if ($this->author === null) {
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
     * @return \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\Mittwald\Typo3Forum\Domain\Model\User\FrontendUser>
     */
    public function getSubscribers()
    {
        return $this->subscribers;
    }

    public function getIsSolved()
    {
        if ($this->isSolved == 1 || $this->getSolution() != null) {
            return true;
        }

        return false;
    }

    /**
     * Get the as solution marked post
     * @return Post
     */
    public function getSolution()
    {
        return $this->solution;
    }

    /**
     * Gets all posts.
     * @return \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\Mittwald\Typo3Forum\Domain\Model\Forum\Post> posts
     */
    public function getPosts()
    {
        return $this->posts;
    }

    /**
     * Gets the post count.
     * @return int Post count
     */
    public function getPostCount()
    {
        return $this->postCount;
    }

    /**
     * Gets the amount of pages of this topic.
     * @return int Page count
     */
    public function getPageCount()
    {
        return ceil($this->postCount / (int)$this->getSettings()['pagebrowser']['topicShow']['itemsPerPage']);
    }

    /**
     * Gets the reply count.
     * @return int Reply count
     */
    public function getReplyCount()
    {
        if ($this->getPostCount() == 0) {
            return 0;
        }
        return $this->getPostCount() - 1;
    }

    /**
     * Gets whether the topic is closed.
     * @return bool
     */
    public function isClosed()
    {
        return $this->closed;
    }

    /**
     * Gets the last post.
     * @return Post lastPost
     */
    public function getLastPost()
    {
        return $this->lastPost;
    }

    /**
     * Gets the forum.
     * @return \Mittwald\Typo3Forum\Domain\Model\Forum\Forum A forum
     */
    public function getForum()
    {
        return $this->forum;
    }

    /**
     * Gets the creation time of this topic.
     * @return \DateTime
     */
    public function getTimestamp()
    {
        return $this->crdate;
    }

    /**
     * Checks if this topic is sticky.
     * @return bool
     */
    public function isSticky()
    {
        return $this->sticky;
    }

    /**
     * Checks if this topic is a question.
     * @return int
     */
    public function getQuestion()
    {
        return (int)$this->question;
    }

    /**
     * Get all criteria options
     * @return \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\Mittwald\Typo3Forum\Domain\Model\Forum\CriteriaOption>
     */
    public function getCriteriaOptions()
    {
        return $this->criteriaOptions;
    }

    /**
     * Get all tags of this topic
     * @return \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\Mittwald\Typo3Forum\Domain\Model\Forum\Tag>
     */
    public function getTags()
    {
        return $this->tags;
    }

    /**
     * Determines whether this topic has been read by a certain user.
     *
     * @param FrontendUser $reader The user who is to be checked.
     *
     * @return bool                                           TRUE, if the user did read this topic, otherwise FALSE.
     */
    public function hasBeenReadByUser(FrontendUser $reader = null)
    {
        return $reader ? $this->readers->contains($reader) : true;
    }

    /**
     * Returns all parent forums in hiearchical order as a flat list (optionally
     * with or without this topic itself).
     *
     * @param bool $withSelf TRUE to include this forum into the rootline, otherwise FALSE.
     *
     * @return array<\Mittwald\Typo3Forum\Domain\Model\Forum\Forum>
     */
    public function getRootline($withSelf = true)
    {
        $rootline = $this->forum->getRootline(true);

        if ($withSelf === true) {
            $rootline[] = $this;
        }

        return $rootline;
    }

    /**
     * Get the first post of a topic
     * @return Post
     */
    public function getFirstPost()
    {
        $this->getPosts()->rewind();

        return $this->getPosts()->current();
    }

    /**
     * Get the most supported post of a topic
     * @return Post
     * @todo refactor (Lazyloading or something else)
     */
    public function getMostSupportedPost()
    {
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
     * @param FrontendUser $user The user.
     * @param string $accessType The access type to be checked.
     *
     * @return bool
     */
    public function checkAccess(FrontendUser $user = null, $accessType = Access::TYPE_READ)
    {
        switch ($accessType) {
            case Access::TYPE_NEW_POST:
                return $this->checkNewPostAccess($user);
            case Access::TYPE_MODERATE:
                return $this->checkModerationAccess($user);
            case Access::TYPE_SOLUTION:
                return $this->checkSolutionAccess($user);
            default:
                return $this->forum->checkAccess($user, $accessType);
        }
    }

    /**
     * Checks if a user may reply to this topic.
     *
     * @param FrontendUser $user
     *
     * @return bool
     */
    public function checkNewPostAccess(FrontendUser $user = null)
    {
        if ($user === null) {
            return false;
        }

        return $this->getForum()->checkModerationAccess($user) ? true : ($this->isClosed() ? false : $this->getForum()
            ->checkNewPostAccess($user));
    }

    /**
     * Checks if a user has moderative access to this topic.
     *
     * @param FrontendUser $user
     *
     * @return bool
     */
    public function checkModerationAccess(FrontendUser $user = null)
    {
        return ($user === null) ? false : $this->getForum()->checkModerationAccess($user);
    }

    /**
     * Checks if a user has solution access to this topic.
     *
     * @param FrontendUser $user
     * @return bool
     */
    public function checkSolutionAccess(FrontendUser $user = null)
    {
        if ($this->getAuthor()->getUid() == $user->getUid() || $this->checkModerationAccess($user)) {
            return true;
        }
        return false;
    }

    /**
     * Adds a Post. By adding a new post, this topic is automatically marked unread
     * for all users who have read this topic before.
     *
     * @param Post $post The Post to be added
     */
    public function addPost(Post $post)
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
    }

    /**
     * Adds a criteria option to the repository.
     *
     * @param \Mittwald\Typo3Forum\Domain\Model\Forum\CriteriaOption $option The Option to be added
     */
    public function addCriteriaOption(\Mittwald\Typo3Forum\Domain\Model\Forum\CriteriaOption $option)
    {
        $this->criteriaOptions->attach($option);
    }

    /**
     * Removes a Post.
     *
     * @param Post $post The Post to be removed
     *
     * @throws \Mittwald\Typo3Forum\Domain\Exception\InvalidOperationException
     */
    public function removePost(Post $post)
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
    }

    /**
     * Sets the topic author.
     *
     * @param FrontendUser $author The topic author.
     */
    public function setAuthor(FrontendUser $author)
    {
        $this->author = $author;
    }

    /**
     * Sets the last post. This method is not publicy accessible; is is called
     * automatically when a new post is added to this topic.
     *
     * @param Post $lastPost The last post.
     */
    protected function setLastPost(Post $lastPost)
    {
        $this->lastPost = $lastPost;
        $this->lastPostCrdate = $lastPost->getTimestamp();
    }

    /**
     * Sets the subject of this topic.
     *
     * @param string $subject The subject
     */
    public function setSubject($subject)
    {
        $this->subject = $subject;
    }

    /**
     * Set a post as solution
     *
     * @param Post $solution
     */
    public function setSolution(Post $solution)
    {
        $this->solution = $solution;
    }

    /**
     * Sets the forum.
     *
     * @param \Mittwald\Typo3Forum\Domain\Model\Forum\Forum $forum The forum
     */
    public function setForum(\Mittwald\Typo3Forum\Domain\Model\Forum\Forum $forum)
    {
        $this->forum = $forum;
    }

    /**
     * Sets this topic to closed.
     *
     * @param bool $closed TRUE to close this topic, FALSE to re-open it.
     */
    public function setClosed($closed)
    {
        $this->closed = (boolean)$closed;
    }

    /**
     * Sets this topic to sticky. Sticky topics will always remain at the top of the
     * forum list, regardless of the timestamp of the last post.
     *
     * @param bool $sticky TRUE to make this topic sticky, FALSE to reset this.
     */
    public function setSticky($sticky)
    {
        $this->sticky = (boolean)$sticky;
    }

    /**
     * Sets this topic to a question. Question topics will be shown at the support queries helpbox.
     *
     * @param int $question TRUE to make this topic a question, FALSE to reset this.
     */
    public function setQuestion($question)
    {
        $this->question = (int)$question;
    }

    /**
     * Set all criteria and options
     *
     * @param \TYPO3\CMS\Extbase\Persistence\ObjectStorage $criteriaOptions
     */
    public function setCriteriaOptions(\TYPO3\CMS\Extbase\Persistence\ObjectStorage $criteriaOptions)
    {
        $this->criteriaOptions = $criteriaOptions;
    }

    /**
     * Add a tag to this topic
     *
     * @param \Mittwald\Typo3Forum\Domain\Model\Forum\Tag $tag
     */
    public function addTag(\Mittwald\Typo3Forum\Domain\Model\Forum\Tag $tag)
    {
        $this->tags->attach($tag);
    }

    /**
     * Remove a tag of this topic
     *
     * @param \Mittwald\Typo3Forum\Domain\Model\Forum\Tag $tag
     */
    public function removeTag(\Mittwald\Typo3Forum\Domain\Model\Forum\Tag $tag)
    {
        $this->tags->detach($tag);
    }

    /**
     * Set a whole ObjectStorage as tag
     *
     * @param \TYPO3\CMS\Extbase\Persistence\ObjectStorage $tags
     */
    public function setTags(\TYPO3\CMS\Extbase\Persistence\ObjectStorage $tags)
    {
        $this->tags = $tags;
    }

    /**
     * Marks this topic as read by a certain user.
     *
     * @param FrontendUser $reader The user who read this topic.
     */
    public function addReader(FrontendUser $reader)
    {
        $this->readers->attach($reader);
    }

    /**
     * Mark this topic as unread for a certain user.
     *
     * @param FrontendUser $reader The user for whom to mark this topic as unread.
     */
    public function removeReader(FrontendUser $reader)
    {
        $this->readers->detach($reader);
    }

    /**
     * Mark this topic as unread for all users.
     */
    public function removeAllReaders()
    {
        $this->readers = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
    }

    /**
     * Adds a new subscriber.
     *
     * @param FrontendUser $user The new subscriber.
     */
    public function addFavSubscriber(FrontendUser $user)
    {
        $this->favSubscribers->attach($user);
    }

    /**
     * Removes a subscriber.
     *
     * @param FrontendUser $user The subscriber to be removed.
     */
    public function removeFavSubscriber(FrontendUser $user)
    {
        $this->favSubscribers->detach($user);
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
}
