<?php
namespace Mittwald\Typo3Forum\Domain\Model\Forum;

/* *
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

use DateTime;
use Mittwald\Typo3Forum\Domain\Model\AccessibleInterface;
use Mittwald\Typo3Forum\Domain\Model\NotifiableInterface;
use Mittwald\Typo3Forum\Domain\Model\User\AnonymousFrontendUser;
use Mittwald\Typo3Forum\Domain\Model\User\FrontendUser;
use TYPO3\CMS\Core\Resource\Exception\FileDoesNotExistException;
use TYPO3\CMS\Extbase\Annotation\ORM\Cascade;
use TYPO3\CMS\Extbase\Annotation\ORM\Lazy;
use TYPO3\CMS\Extbase\Annotation\Validate;
use TYPO3\CMS\Extbase\DomainObject\AbstractEntity;
use TYPO3\CMS\Extbase\Persistence\Generic\LazyLoadingProxy;
use TYPO3\CMS\Extbase\Persistence\ObjectStorage;

/**
 * A forum post. Forum posts are submitted to the access control mechanism and can be
 * subscribed by users.
 */
class Post extends AbstractEntity implements AccessibleInterface, NotifiableInterface
{
    /**
     * @Validate("NotEmpty")
     */
    protected string $text = '';
    protected ?FrontendUser $author = null;
    /**
     * The author's username. Necessary for anonymous postings.
     */
    protected string $authorName = '';
    protected ?Topic $topic = null;
    protected DateTime $crdate;

    /**
     * @var ObjectStorage<FrontendUser>
     * @Lazy
     */
    protected ObjectStorage $supporters;

    /**
     * @var ObjectStorage<Attachment>
     * @Lazy
     * @Cascade("remove")
     */
    protected ObjectStorage $attachments;
    protected int $helpfulCount = 0;

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
        if (!isset($this->attachments)) {
            $this->attachments = new ObjectStorage();
        }
        if (!isset($this->supporters)) {
            $this->supporters = new ObjectStorage();
        }
        if (!isset($this->crdate)) {
            $this->crdate = new DateTime();
        }
    }

    /**
     * Gets all users who have subscribed to this forum.
     *
     * @return ObjectStorage<FrontendUser> All subscribers of this forum.
     */
    public function getSupporters(): ObjectStorage
    {
        return $this->supporters;
    }

    /**
     * Gets the helpful count of this post.
     */
    public function getHelpfulCount(): int
    {
        return $this->helpfulCount;
    }

    /**
     * Gets the text.
     */
    public function getText(): string
    {
        return $this->text;
    }

    /**
     * Gets the post name. This is just an alias for the topic->getTitle method.
     */
    public function getName(): string
    {
        return $this->topic->getTitle();
    }

    /**
     * Alias for getText(). Necessary to implement the NotifiableInterface.
     */
    public function getDescription(): string
    {
        return $this->getText();
    }

    /**
     * Gets the post author.
     */
    public function getAuthor(): FrontendUser
    {
        if ($this->author instanceof LazyLoadingProxy) {
            $this->author->_loadRealInstance();
        }
        if ($this->author === null) {
            $this->author = new AnonymousFrontendUser();
            if ($this->authorName) {
                $this->author->setUsername($this->authorName);
            }
        }

        return $this->author;
    }

    /**
     * Gets the post author's name. Diffentiates between posts created by logged in
     * users (in this case this user's username is returned) and posts by anonymous
     * users.
     */
    public function getAuthorName(): string
    {
        if ($this->getAuthor()->isAnonymous()) {
            return $this->authorName;
        }
        return $this->getAuthor()->getUsername();
    }

    /**
     * Gets the topic.
     */
    public function getTopic(): ?Topic
    {
        return $this->topic;
    }

    /**
     * Gets the forum.
     */
    public function getForum(): Forum
    {
        return $this->topic->getForum();
    }

    /**
     * Gets the post's timestamp.
     */
    public function getTimestamp(): DateTime
    {
        return $this->crdate;
    }

    /**
     * Gets the post's crdate.
     */
    public function getCrdate(): DateTime
    {
        return $this->crdate;
    }

    /**
     * Gets the post's attachments.
     * @return ObjectStorage<Attachment>
     */
    public function getAttachments(): ObjectStorage
    {
        return $this->attachments;
    }

    /**
     * Overrides the isPropertyDirty method. See http://forge.typo3.org/issues/8952
     * for further information.
     *
     * @param mixed $previousValue
     * @param mixed $currentValue
     */
    protected function isPropertyDirty($previousValue, $currentValue): bool
    {
        if ($currentValue instanceof Forum || $currentValue instanceof Topic) {
            return false;
        }
        return parent::isPropertyDirty($previousValue, $currentValue);
    }

    /**
     * Performs an access check for this post.
     */
    public function checkAccess(?FrontendUser $user = null, $accessType = Access::TYPE_READ): bool
    {
        switch ($accessType) {
            case Access::TYPE_EDIT_POST:
            case Access::TYPE_DELETE_POST:
                return $this->checkEditOrDeletePostAccess($user, $accessType);
            default:
                return $this->topic === null
                    ? false
                    : $this->topic->checkAccess($user, $accessType)
                ;
        }
    }

    /**
     * Determines if a user may edit this post. This is only possible if EITHER:
     * a1.) The user is the author of this post, AND
     * a2.) This post is the last post in the topic, AND
     * a3.) The topic generally permits posts to be edited (this would not be the
     *      case if the topic would e.g. be closed).
     * OR:
     * b.)  The current user has moderator access to the forum.
     */
    public function checkEditOrDeletePostAccess(?FrontendUser $user = null, string $operation = Access::TYPE_EDIT_POST): bool
    {
        if ($user === null || $user->isAnonymous()) {
            return false;
        }
        if ($user->checkAccess($user, Access::TYPE_MODERATE)) {
            return true;
        }

        if ($this->getForum()->checkModerationAccess($user)) {
            return true;
        }

        $currentUserIsAuthor = ($user === $this->getAuthor());
        $postIsLastPostInTopic = ($this === $this->getTopic()->getLastPost());
        $topicGrantsAccess = $this->getTopic()->checkAccess($user, $operation);

        if ($currentUserIsAuthor && $postIsLastPostInTopic && $topicGrantsAccess) {
            return true;
        }

        return false;
    }

    /**
     * Sets the city value
     */
    public function setHelpfulCount(int $count): self
    {
        $this->helpfulCount = $count;

        return $this;
    }

    /**
     * Sets the post author.
     */
    public function setAuthor(FrontendUser $author): self
    {
        if ($author->isAnonymous()) {
            $this->author = null;
        } else {
            $this->author = $author;
        }

        return $this;
    }

    /**
     * Sets the post author's name. Necessary for anonymous postings.
     */
    public function setAuthorName(string $authorName): self
    {
        $this->authorName = $authorName;

        return $this;
    }

    /**
     * Sets the post text.
     */
    public function setText(string $text): self
    {
        $this->text = $text;

        return $this;
    }

    /**
     * Sets the attachments.
     */
    public function setAttachments(ObjectStorage $attachments): self
    {
        $this->attachments = $attachments;

        return $this;
    }

    /**
     * Adds an or more attachments.
     */
    public function addAttachments(Attachment $attachment): self
    {
        $this->attachments->attach($attachment);

        return $this;
    }

    /**
     * Removes an attachment.
     */
    public function removeAttachment(Attachment $attachment): self
    {
        $fileReference = $attachment->getFileReference();
        if ($fileReference !== null) {
            try {
                $fileReference->getOriginalResource()->getOriginalFile()->delete();
            } catch (FileDoesNotExistException $e) {}
        }

        $this->attachments->detach($attachment);

        return $this;
    }

    /**
     * Determines whether this post has been supported by a user.
     */
    public function hasBeenSupportedByUser(?FrontendUser $user = null): bool
    {
        return $user !== null
            ? $user->getUid() === $this->getAuthor()->getUid() || $this->supporters->contains($user)
            : false
        ;
    }

    public function setTopic(Topic $topic): self
    {
        $this->topic = $topic;

        return $this;
    }

    /**
     * Marks this topic as supported by a certain user.
     */
    public function addSupporter(FrontendUser $supporter): self
    {
        $this->setHelpfulCount($this->getHelpfulCount() + 1);
        $this->supporters->attach($supporter);

        return $this;
    }

    /**
     * Mark this topic as unread for a certain user.
     */
    public function removeSupporter(FrontendUser $supporter): self
    {
        $this->setHelpfulCount($this->getHelpfulCount() - 1);
        $this->supporters->detach($supporter);

        return $this;
    }

    /**
     * Mark this topic as unread for all users.
     */
    public function removeAllSupporters(): self
    {
        $this->supporters = new ObjectStorage();

        return $this;
    }

    public function isFirstPost(): bool
    {
        return $this->getTopic() !== null && $this->getTopic()->getFirstPost()->getUid() === $this->getUid();
    }

    public function isSolution(): bool
    {
        if ($this->getTopic() === null) {
            return false;
        }
        $solution = $this->getTopic()->getSolution();
        return $this->topic->isQuestion() && $solution !== null && $solution->getUid() === $this->getUid();
    }
}
