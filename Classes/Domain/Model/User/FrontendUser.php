<?php
namespace Mittwald\Typo3Forum\Domain\Model\User;

/*                                                                      *
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
use Mittwald\Typo3Forum\Domain\Model\AccessibleInterface;
use Mittwald\Typo3Forum\Domain\Model\ConfigurableEntityTrait;
use Mittwald\Typo3Forum\Domain\Model\ConfigurableInterface;
use Mittwald\Typo3Forum\Domain\Model\Forum\Access;
use Mittwald\Typo3Forum\Domain\Model\Forum\Forum;
use Mittwald\Typo3Forum\Domain\Model\Forum\Topic;
use Mittwald\Typo3Forum\Domain\Model\ReadableInterface;
use Mittwald\Typo3Forum\Domain\Model\SubscribeableInterface;
use Mittwald\Typo3Forum\Domain\Repository\User\RankRepository;
use TYPO3\CMS\Core\Core\Environment;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Annotation\ORM\Lazy;
use TYPO3\CMS\Extbase\Persistence\ObjectStorage;

/**
 * A frontend user.
 */
class FrontendUser extends \TYPO3\CMS\Extbase\Domain\Model\FrontendUser implements AccessibleInterface, ConfigurableInterface
{
    const GENDER_MALE = 0;
    const GENDER_FEMALE = 1;
    const GENDER_OTHER = 2;
    const GENDER_PRIVATE = 99;

    use ConfigurableEntityTrait;

    protected RankRepository $rankRepository;

    protected int $postCount = 0;
    protected int $topicCount = 0;
    protected int $helpfulCount = 0;
    protected int $questionCount = 0;
    protected string $signature = '';
    protected string $facebook = '';
    protected string $twitter = '';
    protected string $google = '';
    protected string $skype = '';
    protected string $job = '';
    protected string $workingEnvironment = '';

    /**
     * Subscribed topics.
     *
     * @var ObjectStorage<\Mittwald\Typo3Forum\Domain\Model\Forum\Topic>
     * @Lazy
     */
    protected ObjectStorage $topicSubscriptions;

    /**
     * Subscribed forums.
     *
     * @var ObjectStorage<\Mittwald\Typo3Forum\Domain\Model\Forum\Forum>
     * @Lazy
     */
    protected ObjectStorage $forumSubscriptions;

    /**
     * The creation date of this user.
     */
    protected DateTime $crdate;

    /**
     * Userfield values.
     *
     * @var ObjectStorage<\Mittwald\Typo3Forum\Domain\Model\User\Userfield\Value>
     */
    protected ObjectStorage $userfieldValues;

    /**
     * Read topics.
     *
     * @var ObjectStorage<\Mittwald\Typo3Forum\Domain\Model\Forum\Topic>
     * @Lazy
     */
    protected ObjectStorage $readTopics;

    /**
     * Read forum.
     *
     * @var ObjectStorage<\Mittwald\Typo3Forum\Domain\Model\Forum\Forum>
     * @Lazy
     */
    protected ObjectStorage $readForum;

    /**
     * Read topics.
     *
     * @var ObjectStorage<\Mittwald\Typo3Forum\Domain\Model\Forum\Post>
     * @Lazy
     */
    protected ObjectStorage $supportPosts;
    protected int $gender = 2;

    /**
     * Timestamp of last action of the user
     */
    protected int $isOnline = 0;
    protected bool $disable = false;

    /**
     * Defines whether to use a "gravatar" if no user image is available.
     */
    protected bool $useGravatar = false;
    protected ?Rank $rank = null;
    protected int $points = 0;
    protected string $interests = '';
    protected int $dateOfBirth = 0;

    /**
     * JSON encoded contact addresses and social network profile names. Stored
     * unstructuredly in order to add more types of addresses without extending
     * the database for each social network.
     */
    protected string $contact = '';

    /**
     * @var ObjectStorage<\Mittwald\Typo3Forum\Domain\Model\User\FrontendUserGroup>
     */
    protected $usergroup;

    public function injectRankRepository(RankRepository $rankRepository): void
    {
        $this->rankRepository = $rankRepository;
    }

    /**
     * Constructor.
     *
     * @param string $username The user's username.
     * @param string $password The user's password.
     */
    public function __construct($username = '', $password = '')
    {
        parent::__construct($username, $password);
        $this->initializeObject();
    }

    public function initializeObject(): void
    {
        $this->ensureObjectStorages();
    }

    public function ensureObjectStorages(): void
    {
        if (!isset($this->usergroup)) {
            $this->usergroup = new ObjectStorage();
        }
        if (!isset($this->supportPosts)) {
            $this->supportPosts = new ObjectStorage();
        }
        if (!isset($this->userfieldValues)) {
            $this->userfieldValues = new ObjectStorage();
        }
        if (!isset($this->readTopics)) {
            $this->readTopics = new ObjectStorage();
        }
        if (!isset($this->readForum)) {
            $this->readForum = new ObjectStorage();
        }
        if (!isset($this->forumSubscriptions)) {
            $this->forumSubscriptions = new ObjectStorage();
        }
        if (!isset($this->topicSubscriptions)) {
            $this->topicSubscriptions = new ObjectStorage();
        }
    }

    /**
     * Gets the post count of this user.
     *
     * @return int The post count.
     */
    public function getPostCount()
    {
        return $this->postCount;
    }

    /**
     * Gets the topic count of this user.
     *
     * @return int The topic count.
     */
    public function getTopicCount()
    {
        return $this->topicCount;
    }

    /**
     * Gets the social-profile of user
     *
     * @return string
     */
    public function getFacebook()
    {
        return $this->facebook;
    }

    /**
     * Gets the social-profile of user
     *
     * @return string
     */
    public function getTwitter()
    {
        return $this->twitter;
    }

    /**
     * Gets the social-profile of user
     *
     * @return string
     */
    public function getGoogle()
    {
        return $this->google;
    }

    /**
     * Gets the social-profile of user
     *
     * @return string
     */
    public function getSkype()
    {
        return $this->skype;
    }

    /**
     * Gets the job of user
     *
     * @return string
     */
    public function getJob()
    {
        return $this->job;
    }

    /**
     * Gets the job of user
     *
     * @return string
     */
    public function getWorkingEnvironment()
    {
        return $this->workingEnvironment;
    }

    /**
     * Gets the question count of this user.
     *
     * @return int The question count.
     */
    public function getQuestionCount()
    {
        return $this->questionCount;
    }

    /**
     * Gets the gender of the user
     *
     * @return int See GENDER_ constants of this class
     */
    public function getGender()
    {
        return $this->gender;
    }

    /**
     * @return string
     */
    public function getRegistrationDate()
    {
        return $this->crdate->format('d.m.Y');
    }

    /**
     * Gets the subscribed topics.
     *
     * @return ObjectStorage<\Mittwald\Typo3Forum\Domain\Model\Forum\Topic> The subscribed topics.
     */
    public function getTopicSubscriptions()
    {
        return $this->topicSubscriptions;
    }

    /**
     * Gets the subscribed forums.
     *
     * @return ObjectStorage<\Mittwald\Typo3Forum\Domain\Model\Forum\Forum>
     *                             The subscribed forums.
     */
    public function getForumSubscriptions()
    {
        return $this->forumSubscriptions;
    }

    /**
     * Gets the subscribed forums.
     *
     * @return ObjectStorage<\Mittwald\Typo3Forum\Domain\Model\Forum\Post>
     *                             The subscribed forums.
     */
    public function getSupportPosts()
    {
        return $this->supportPosts;
    }

    /**
     * Gets the user's registration date.
     *
     * @return \DateTime The registration date
     */
    public function getTimestamp()
    {
        return $this->crdate;
    }

    /**
     * Get the age of a user
     *
     * @return int
     */
    public function getAge()
    {
        $age = (time() - $this->getDateOfBirth()) / (3600 * 24 * 365);

        return floor($age);
    }

    /**
     * Get the date_of_birth value from fe_users
     *
     * @return int
     */
    public function getDateOfBirth()
    {
        return (int)$this->dateOfBirth;
    }

    /**
     * Performs an access check for this post.
     *
     *
     * @param FrontendUser $user
     * @param string $accessType
     * @return bool
     */
    public function checkAccess(FrontendUser $user = null, $accessType = Access::TYPE_MODERATE)
    {
        foreach ($user->getUsergroup() as $group) {
            if ($group->getUserMod()) {
                return true;
                break;
            }
        }

        return false;
    }

    /**
     * Returns the usergroups. Keep in mind that the property is called "usergroup"
     * although it can hold several usergroups.
     *
     * @return ObjectStorage<FrontendUserGroup> An object storage containing the usergroup
     * @api
     */
    public function getUsergroup()
    {
        return $this->usergroup;
    }

    /**
     * Determines if this user is member of a specific group.
     *
     * @param FrontendUserGroup $checkGroup
     *
     * @return bool
     */
    public function isInGroup(FrontendUserGroup $checkGroup)
    {
        foreach ($this->getUsergroup() as $group) {
            if ($group == $checkGroup) {
                return true;
            }
        }

        return false;
    }

    /**
     * Get the online status of a User
     *
     * @return boolean.
     */
    public function getIsOnline()
    {
        if (time() - $this->isOnline < 300) {
            return true;
        }
        return false;
    }

    /**
     * Gets the user's signature.
     *
     * @return string The signature.
     */
    public function getSignature()
    {
        return $this->signature;
    }

    /**
     * Gets the user's signature.
     *
     * @return bool
     */
    public function getDisable()
    {
        return $this->disable;
    }

    /**
     * @param bool $val
     */
    public function setDisable($val)
    {
        $this->disable = (int)$val;
    }

    /**
     * Gets the userfield values for this user.
     *
     * @return ObjectStorage<\Mittwald\Typo3Forum\Domain\Model\User\Userfield\Value>
     */
    public function getUserfieldValues()
    {
        return $this->userfieldValues;
    }

    /**
     * @return ObjectStorage<\Mittwald\Typo3Forum\Domain\Model\User\Userfield\Value>
     */
    public function getInterests()
    {
        return $this->interests;
    }

    /**
     * Returns the absolute path of this user's avatar image (if existent).
     *
     * @return string The absolute path of this user's avatar image (if existent).
     */
    public function getImagePath(): ?string
    {
        if ($this->image) {
            foreach ($this->image as $image) {
                /* @var \TYPO3\CMS\Extbase\Domain\Model\FileReference $image */
                $singleImage = $image->getOriginalResource();
                return $singleImage->getPublicUrl();
            }
        }

        // If the user enabled the use of "Gravatars", then load this user's
        // gravatar using the official API (it's quite simple, actually: Just
        // use the MD5 checksum of the user's email address in the gravatar URL
        // and you're fine (http://de.gravatar.com/site/implement/images/).
        if ($this->useGravatar) {
            $emailHash = md5(strtolower($this->email));
            $temporaryFilename = 'typo3temp/typo3_forum/gravatar/' . $emailHash . '.jpg';
            if (!file_exists(Environment::getPublicPath() . $temporaryFilename)) {
                $image = GeneralUtility::getUrl('https://secure.gravatar.com/avatar/' . $emailHash . '.jpg');
                file_put_contents(Environment::getPublicPath() . $temporaryFilename, $image);
            }

            return $temporaryFilename;
        }

        switch ($this->gender) {
            case self::GENDER_MALE:
                $imageFilename = $this->getSettings()['images']['avatar']['dummyMale'] ?? null;
                break;
            case self::GENDER_FEMALE:
                $imageFilename = $this->getSettings()['images']['avatar']['dummyFemale'] ?? null;
                break;
            case self::GENDER_OTHER:
                $imageFilename = $this->getSettings()['images']['avatar']['dummyOther'] ?? null;
                break;
        }

        if ($imageFilename === null || !file_exists($imageFilename)) {
            return null;
        }

        return $imageFilename;
    }

    /**
     * Alias for isAnonymous().
     *
     * @return bool TRUE when this user is an anonymous user.
     */
    public function getAnonymous()
    {
        return $this->isAnonymous();
    }

    /**
     * Determines whether is user is an anonymous user.
     *
     * @return bool TRUE when this user is an anonymous user.
     */
    public function isAnonymous()
    {
        return false;
    }

    /**
     * Subscribes this user to a subscribeable object, like a topic or a forum.
     *
     * @param SubscribeableInterface $object The object that is to be subscribed. This may either be a topic or a forum.
     */
    public function addSubscription(SubscribeableInterface $object)
    {
        $this->ensureObjectStorages();
        if ($object instanceof Topic) {
            $this->topicSubscriptions->attach($object);
        } elseif ($object instanceof Forum) {
            $this->forumSubscriptions->attach($object);
        }
    }

    /**
     * Unsubscribes this user from a subscribeable object.
     *
     * @param SubscribeableInterface $object The object that is to be unsubscribed.
     */
    public function removeSubscription(SubscribeableInterface $object)
    {
        $this->ensureObjectStorages();
        if ($object instanceof Topic) {
            $this->topicSubscriptions->detach($object);
        } elseif ($object instanceof Forum) {
            $this->forumSubscriptions->detach($object);
        }
    }

    /**
     * Adds a readable object to the list of objects read by this user.
     *
     * @param ReadableInterface $readObject The object that is to be marked as read.
     */
    public function addReadObject(ReadableInterface $readObject)
    {
        $this->ensureObjectStorages();
        if ($readObject instanceof Topic) {
            $this->readTopics->attach($readObject);
        }
    }

    /**
     * Removes a readable object from the list of objects read by this user.
     *
     * @param ReadableInterface $readObject The object that is to be marked as unread.
     */
    public function removeReadObject(ReadableInterface $readObject)
    {
        $this->ensureObjectStorages();
        if ($readObject instanceof Topic) {
            $this->readTopics->detach($readObject);
        }
    }

    /**
     * Decrease the user's post count.
     */
    public function decreasePostCount()
    {
        $this->postCount--;
    }

    /**
     * Increase the user's post count.
     */
    public function increasePostCount()
    {
        $this->postCount++;
    }

    /**
     * Decrease the user's topic count.
     */
    public function decreaseTopicCount()
    {
        $this->topicCount--;
    }

    /**
     * Increase the user's topic count.
     */
    public function increaseTopicCount()
    {
        $this->topicCount++;
    }

    /**
     * Decrease the user's question count.
     */
    public function decreaseQuestionCount()
    {
        $this->questionCount--;
    }

    /**
     * Increase the user's question count.
     */
    public function increaseQuestionCount()
    {
        $this->questionCount++;
    }

    /**
     * Increase the user's points.
     */
    public function increasePoints(int $by): self
    {
        $currentRank = $this->getRank();

        $this->points = $this->points + $by;

        $rank = $this->rankRepository->findOneByUser($this);

        if ($rank !== null && ($currentRank === null || $rank !== $currentRank)) {
            $this->setRank($rank);
            $rank->increaseUserCount();
            $this->rankRepository->update($rank);

            if ($currentRank !== null) {
                $currentRank->decreaseUserCount();
                $this->rankRepository->update($currentRank);
            }
        }

        return $this;
    }

    /**
     * Get the rank of this user
     *
     * @return ?Rank
     */
    public function getRank(): ?Rank
    {
        return $this->rank;
    }

    /**
     * Set the rank of this user
     *
     * @param ?Rank $rank
     */
    public function setRank($rank)
    {
        $this->rank = $rank;
    }

    /**
     * Gets the points of this user
     *
     * @return int
     */
    public function getPoints()
    {
        return $this->points;
    }

    /**
     * Decrease the user's points.
     *
     * @param int $by The amount of points to be removed
     */
    public function decreasePoints(int $by): self
    {
        return $this->increasePoints(-$by);
    }

    /**
     * Resets the whole contact data array of this user. This array will be stored in
     * a JSON serialized format.
     *
     * @param array $values All contact data of this user.
     */
    public function setContactData(array $values)
    {
        $this->contact = json_encode($values);
    }

    /**
     * Sets a single contact data record. A contact data record can be unset by setting
     * it to a empty or FALSE value.
     *
     * @param $type  string The contact record key (e.g. "twitter", "facebook", "icq", ...)
     * @param $value string The new value. Set to a FALSE value to unset.
     */
    public function setContactDataItem($type, $value)
    {
        $contactData = $this->getContactData();
        if (!$value) {
            if (array_key_exists($type, $contactData)) {
                unset($contactData[$type]);
            }
        } else {
            $contactData[$type] = $value;
        }

        $this->contact = json_encode($contactData);
    }

    /**
     * Returns all this user's contact information. In order to keep this extensible and
     * not to add too many columns to the already overloaded fe_users table, these data is
     * stored in JSON serialized format in a single column.
     *
     * @return array All contact information for this user.
     */
    public function getContactData()
    {
        $decoded = json_decode($this->contact, true);
        if ($decoded === null) {
            return [];
        }

        return $decoded;
    }

    /**
     * Sets the helpfulCount value +1
     *
     * @api
     */
    public function setHelpful()
    {
        $this->setHelpfulCount($this->getHelpfulCount() + 1);
    }

    /**
     * Gets the helpful count of this user.
     *
     * @return int The helpful count.
     */
    public function getHelpfulCount()
    {
        return $this->helpfulCount;
    }

    /**
     * Sets the helpfulCount value
     *
     * @param int $count
     *
     * @api
     */
    public function setHelpfulCount(int $count)
    {
        $this->helpfulCount = $count;
    }

    /**
     * @return ObjectStorage
     */
    public function getReadTopics()
    {
        return $this->readTopics;
    }

    /**
     * @param ObjectStorage $readTopics
     */
    public function setReadTopics($readTopics)
    {
        $this->readTopics = $readTopics;
    }

    /**
     * @return ObjectStorage
     */
    public function getReadForum()
    {
        return $this->readForum;
    }

    /**
     * @param ObjectStorage $readForum
     */
    public function setReadForum($readForum)
    {
        $this->readForum = $readForum;
    }

    public function isInModerationGroup(): bool
    {
        foreach ($this->getUsergroup() as $group) {
            if ($group->getUserMod()) {
                return true;
            }
        }
        return false;
    }

    public function canCreateTags(): bool
    {
        return
            $this->isInModerationGroup()
            || $this->getSettings()['forum']['tag']['usersCanCreate'] === '1'
        ;
    }
}
