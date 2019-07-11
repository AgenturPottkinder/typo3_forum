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

use Mittwald\Typo3Forum\Domain\Model\AccessibleInterface;
use Mittwald\Typo3Forum\Domain\Model\ConfigurableEntityTrait;
use Mittwald\Typo3Forum\Domain\Model\ConfigurableInterface;
use Mittwald\Typo3Forum\Domain\Model\Forum\Access;
use Mittwald\Typo3Forum\Domain\Model\Forum\Forum;
use Mittwald\Typo3Forum\Domain\Model\Forum\Topic;
use Mittwald\Typo3Forum\Domain\Model\ReadableInterface;
use Mittwald\Typo3Forum\Domain\Model\SubscribeableInterface;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Persistence\ObjectStorage;


/**
 * A frontend user.
 */
class FrontendUser extends \TYPO3\CMS\Extbase\Domain\Model\FrontendUser implements AccessibleInterface, ConfigurableInterface {

	const GENDER_MALE = 0;
	const GENDER_FEMALE = 1;
	const GENDER_PRIVATE = 99;

	use ConfigurableEntityTrait;

	/**
	 * The rank repository
	 *
	 * @var \Mittwald\Typo3Forum\Domain\Repository\User\RankRepository
	 * @inject
	 */
	protected $rankRepository = NULL;

	/**
	 * Forum post count
	 *
	 * @var integer
	 */
	protected $postCount;

	/**
	 * Forum post count for the current session (Widgets)
	 *
	 * @var integer
	 */
	protected $postCountSession;

	/**
	 * Topic count of a user
	 *
	 * @var integer
	 */
	protected $topicCount;

	/**
	 * Forum helpful count
	 *
	 * @var integer
	 */
	protected $helpfulCount;

	/**
	 * Forum helpful count for the current session (Widgets)
	 *
	 * @var integer
	 */
	protected $helpfulCountSession;

	/**
	 * Question count of a user
	 *
	 * @var integer
	 */
	protected $questionCount;

	/**
	 * The signature. This will be displayed below this user's posts.
	 *
	 * @var string
	 */
	protected $signature;

	/**
	 * @var string
	 */
	protected $facebook;

	/**
	 * @var string
	 */
	protected $twitter;

	/**
	 * @var string
	 */
	protected $google;

	/**
	 * @var string
	 */
	protected $skype;

	/**
	 * @var string
	 */
	protected $job;

	/**
	 * @var string
	 */
	protected $workingEnvironment;

	/**
	 * Fav Subscribed topics.
	 *
	 * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\Mittwald\Typo3Forum\Domain\Model\Forum\Topic>
	 * @lazy
	 */
	protected $topicFavSubscriptions;

	/**
	 * Fav Subscribed forums.
	 *
	 * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\Mittwald\Typo3Forum\Domain\Model\Forum\Forum>
	 * @lazy
	 */
	protected $forumFavSubscriptions;

	/**
	 * Subscribed topics.
	 *
	 * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\Mittwald\Typo3Forum\Domain\Model\Forum\Topic>
	 * @lazy
	 */
	protected $topicSubscriptions;

	/**
	 * Subscribed forums.
	 *
	 * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\Mittwald\Typo3Forum\Domain\Model\Forum\Forum>
	 * @lazy
	 */
	protected $forumSubscriptions;

	/**
	 * The creation date of this user.
	 *
	 * @var \DateTime
	 */
	protected $crdate;

	/**
	 * Userfield values.
	 *
	 * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\Mittwald\Typo3Forum\Domain\Model\User\Userfield\Value>
	 */
	protected $userfieldValues;

	/**
	 * Read topics.
	 *
	 * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\Mittwald\Typo3Forum\Domain\Model\Forum\Topic>
	 * @lazy
	 */
	protected $readTopics;

	/**
	 * Read forum.
	 *
	 * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\Mittwald\Typo3Forum\Domain\Model\Forum\Forum>
	 * @lazy
	 */
	protected $readForum;

	/**
	 * Read topics.
	 *
	 * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\Mittwald\Typo3Forum\Domain\Model\Forum\Post>
	 * @lazy
	 */
	protected $supportPosts;

	/**
	 * The country.
	 *
	 * @var string
	 */
	protected $staticInfoCountry;

	/**
	 * The gender.
	 *
	 * @var integer
	 */
	protected $gender;

	/**
	 * Timestamp of last action of the user
	 *
	 * @var integer
	 */
	protected $isOnline;

	/**
	 * @var integer
	 */
	protected $disable;

	/**
	 * Defines whether to use a "gravatar" if no user image is available.
	 *
	 * @var boolean
	 */
	protected $useGravatar = FALSE;

	/**
	 * The rank of this user
	 *
	 * @var \Mittwald\Typo3Forum\Domain\Model\User\Rank
	 */
	protected $rank;

	/**
	 * The points of this user
	 *
	 * @var int
	 */
	protected $points;

	/**
	 * @var string
	 */
	protected $interests;

	/**
	 * @var int
	 */
	protected $dateOfBirth;

	/**
	 * The private messages of this user.
	 *
	 * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\Mittwald\Typo3Forum\Domain\Model\User\PrivateMessage>
	 * @lazy
	 */
	protected $privateMessages;

	/**
	 * JSON encoded contact addresses and social network profile names. Stored
	 * unstructuredly in order to add more types of addresses without extending
	 * the database for each social network.
	 *
	 * @var string
	 */
	protected $contact = '';

	/**
	 * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\Mittwald\Typo3Forum\Domain\Model\User\FrontendUserGroup>
	 */
	protected $usergroup;

    /**
	 * Constructor.
	 *
	 * @param string $username The user's username.
	 * @param string $password The user's password.
	 */
	public function __construct($username = '', $password = '') {
		parent::__construct($username, $password);
		$this->readTopics = new ObjectStorage();
		$this->readForum = new ObjectStorage();
	}

	/**
	 * Gets the post count of this user.
	 *
	 * @return integer The post count.
	 */
	public function getPostCount() {
		return $this->postCount;
	}

	/**
	 * Gets the post count of this user of the current session (Widgets).
	 *
	 * @return integer The post count.
	 */
	public function getPostCountSession() {
		return $this->postCountSession;
	}

	/**
	 * Gets the topic count of this user.
	 *
	 * @return integer The topic count.
	 */
	public function getTopicCount() {
		return $this->topicCount;
	}

	/**
	 * Gets the social-profile of user
	 *
	 * @return string
	 */
	public function getFacebook() {
		return $this->facebook;
	}

	/**
	 * Gets the social-profile of user
	 *
	 * @return string
	 */
	public function getTwitter() {
		return $this->twitter;
	}

	/**
	 * Gets the social-profile of user
	 *
	 * @return string
	 */
	public function getGoogle() {
		return $this->google;
	}

	/**
	 * Gets the social-profile of user
	 *
	 * @return string
	 */
	public function getSkype() {
		return $this->skype;
	}

	/**
	 * Gets the job of user
	 *
	 * @return string
	 */
	public function getJob() {
		return $this->job;
	}

	/**
	 * Gets the job of user
	 *
	 * @return string
	 */
	public function getWorkingEnvironment() {
		return $this->workingEnvironment;
	}

	/**
	 * Gets the question count of this user.
	 *
	 * @return integer The question count.
	 */
	public function getQuestionCount() {
		return $this->questionCount;
	}

	/**
	 * Gets the gender of the user
	 *
	 * @return integer See GENDER_ constants of this class
	 */
	public function getGender() {
		return $this->gender;
	}

	/**
	 * @return string
	 */
	public function getRegistrationDate() {
		return $this->crdate->format('d.m.Y');
	}

	/**
	 * Gets the private messages of this user.
	 *
	 * @return ObjectStorage<\Mittwald\Typo3Forum\Domain\Model\User\PrivateMessage>
	 */
	public function getPrivateMessages() {
		return $this->privateMessages;
	}

	/**
	 * Gets the subscribed topics.
	 *
	 * @return ObjectStorage<\Mittwald\Typo3Forum\Domain\Model\Forum\Topic> The subscribed topics.
	 */
	public function getTopicSubscriptions() {
		return $this->topicSubscriptions;
	}

	/**
	 * Gets the helpful count of this user of the current session (Widgets).
	 *
	 * @return integer The helpful count.
	 */
	public function getHelpfulCountSession() {
		return $this->helpfulCountSession;
	}

	/**
	 * Gets the subscribed forums.
	 *
	 * @return ObjectStorage<\Mittwald\Typo3Forum\Domain\Model\Forum\Forum>
	 *                             The subscribed forums.
	 */
	public function getForumSubscriptions() {
		return $this->forumSubscriptions;
	}

	/**
	 * Gets the subscribed forums.
	 *
	 * @return ObjectStorage<\Mittwald\Typo3Forum\Domain\Model\Forum\Post>
	 *                             The subscribed forums.
	 */
	public function getSupportPosts() {
		return $this->supportPosts;
	}

	/**
	 * Gets the user's registration date.
	 *
	 * @return \DateTime The registration date
	 */
	public function getTimestamp() {
		return $this->crdate;
	}

	/**
	 * Get the age of a user
	 *
	 * @return int
	 */
	public function getAge() {
		$age = (time() - $this->getDateOfBirth()) / (3600 * 24 * 365);

		return floor($age);
	}

	/**
	 * Get the date_of_birth value from fe_users
	 *
	 * @return int
	 */
	public function getDateOfBirth() {
		return (int)$this->dateOfBirth;
	}

	/**
	 * Performs an access check for this post.
	 *
	 * @access private
	 *
	 * @param FrontendUser $user
	 * @param string $accessType
	 * @return boolean
	 */
	public function checkAccess(FrontendUser $user = NULL, $accessType = Access::TYPE_MODERATE) {
		foreach ($user->getUsergroup() as $group) {
			if ($group->getUserMod()) {
				return TRUE;
			}
		}

		return FALSE;
	}

	/**
	 * Returns the usergroups. Keep in mind that the property is called "usergroup"
	 * although it can hold several usergroups.
	 *
	 * @return ObjectStorage An object storage containing the usergroup
	 * @api
	 */
	public function getUsergroup() {
		return $this->usergroup;
	}

	/**
	 * Determines if this user is member of a specific group.
	 *
	 * @param FrontendUserGroup $checkGroup
	 *
	 * @return boolean
	 */
	public function isInGroup(FrontendUserGroup $checkGroup) {
		foreach ($this->getUsergroup() As $group) {
			if ($group == $checkGroup) {
				return TRUE;
			}
		}

		return FALSE;
	}

	/**
	 * Get the online status of a User
	 *
	 * @return boolean.
	 */
	public function getIsOnline() {
		if (time() - $this->isOnline < 300) {
			return TRUE;
		} else {
			return FALSE;
		}
	}

	/**
	 * Gets the user's signature.
	 *
	 * @return string The signature.
	 */
	public function getSignature() {
		return $this->signature;
	}

	/**
	 * Gets the user's signature.
	 *
	 * @return boolean
	 */
	public function getDisable() {
		return $this->disable;
	}

	/**
	 * @param bool $val
	 */
	public function setDisable($val) {
		$this->disable = (int)$val;
	}

	/**
	 * Gets the userfield values for this user.
	 *
	 * @return ObjectStorage<\Mittwald\Typo3Forum\Domain\Model\User\Userfield\Value>
	 */
	public function getUserfieldValues() {
		return $this->userfieldValues;
	}

	/**
	 * @return ObjectStorage<\Mittwald\Typo3Forum\Domain\Model\User\Userfield\Value>
	 */
	public function getInterests() {
		return $this->interests;
	}

	/**
	 * Returns the absolute path of this user's avatar image (if existent).
	 *
	 * @return string The absolute path of this user's avatar image (if existent).
	 */
	public function getImagePath() {

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
			if (!file_exists(PATH_site . $temporaryFilename)) {
				$image = GeneralUtility::getUrl('https://secure.gravatar.com/avatar/' . $emailHash . '.jpg');
				file_put_contents(PATH_site . $temporaryFilename, $image);
			}

			return $temporaryFilename;
		}

		switch ($this->gender) {
			case self::GENDER_MALE:
				$imageFilename = $this->getSettings()['images']['avatar']['dummyMale'];
				break;
			case self::GENDER_FEMALE:
				$imageFilename = $this->getSettings()['images']['avatar']['dummyFemale'];
				break;
		}

		if (!isset($imageFilename) || !file_exists($imageFilename)) {
			return NULL;
		}

		return $imageFilename;
	}

	/**
	 * Alias for isAnonymous().
	 *
	 * @return bool TRUE when this user is an anonymous user.
	 */
	public function getAnonymous() {
		return $this->isAnonymous();
	}

	/**
	 * Determines whether is user is an anonymous user.
	 *
	 * @return bool TRUE when this user is an anonymous user.
	 */
	public function isAnonymous() {
		return FALSE;
	}

	/**
	 * Subscribes this user to a subscribeable object, like a topic or a forum.
	 *
	 * @param SubscribeableInterface $object The object that is to be subscribed. This may either be a topic or a forum.
	 * @return void
	 */
	public function addFavSubscription(SubscribeableInterface $object) {
		if ($object instanceof Topic) {
			$this->topicFavSubscriptions->attach($object);
		} elseif ($object instanceof Forum) {
			$this->forumFavSubscriptions->attach($object);
		}
	}

	/**
	 * Unsubscribes this user from a subscribeable object.
	 *
	 * @param SubscribeableInterface $object The object that is to be unsubscribed.
	 * @return void
	 */
	public function removeFavSubscription(SubscribeableInterface $object) {
		if ($object instanceof Topic) {
			$this->topicFavSubscriptions->detach($object);
		} elseif ($object instanceof Forum) {
			$this->forumFavSubscriptions->detach($object);
		}
	}

	/**
	 * Subscribes this user to a subscribeable object, like a topic or a forum.
	 *
	 * @param SubscribeableInterface $object The object that is to be subscribed. This may either be a topic or a forum.
	 *
	 * @return void
	 */
	public function addSubscription(SubscribeableInterface $object) {
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
	 *
	 * @return void
	 */
	public function removeSubscription(SubscribeableInterface $object) {
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
	 * @return void
	 */
	public function addReadObject(ReadableInterface $readObject) {
		if ($readObject instanceof Topic) {
			$this->readTopics->attach($readObject);
		}
	}

	/**
	 * Removes a readable object from the list of objects read by this user.
	 *
	 * @param ReadableInterface $readObject The object that is to be marked as unread.
	 *
	 * @return void
	 */
	public function removeReadObject(ReadableInterface $readObject) {
		if ($readObject instanceof Topic) {
			$this->readTopics->detach($readObject);
		}
	}

	/**
	 * Add a private message
	 *
	 * @param $message PrivateMessage
	 */
	public function addPrivateMessage(PrivateMessage $message) {
		$this->privateMessages->attach($message);
	}

	/**
	 * Removes a private messages
	 *
	 * @param $message PrivateMessage
	 */
	public function removePrivateMessage(PrivateMessage $message) {
		$this->privateMessages->detach($message);
	}

	/**
	 * Decrease the user's post count.
	 *
	 * @return void
	 */
	public function decreasePostCount() {
		$this->postCount--;
		$this->decreasePostCountSession(1);
	}

	/**
	 * Decrease the user's post count of the current session (Widgets).
	 *
	 * @param int $by
	 *
	 * @return void
	 */
	public function decreasePostCountSession($by = 1) {
		if ($by < 0) {
			$by = 1;
		}
		$this->postCountSession = $this->postCountSession - $by;
	}

	/**
	 * Increase the user's post count.
	 *
	 * @return void
	 */
	public function increasePostCount() {
		$this->postCount++;
		$this->increasePostCountSession(1);
	}

	/**
	 * Increase the user's post count of the current session (Widgets)
	 *
	 * @param int $by
	 *
	 * @return void
	 */
	public function increasePostCountSession($by = 1) {
		if ($by < 0) {
			$by = 1;
		}
		$this->postCountSession = $this->postCountSession + $by;
	}

	/**
	 * Decrease the user's helpful count of the current session (Widgets)
	 *
	 * @param int $by
	 *
	 * @return void
	 */
	public function decreaseHelpfulCountSession($by = 1) {
		if ($by < 0) {
			$by = 1;
		}
		$this->helpfulCountSession = $this->helpfulCountSession - $by;
	}

	/**
	 * Decrease the user's topic count.
	 *
	 * @return void
	 */
	public function decreaseTopicCount() {
		$this->topicCount--;
	}

	/**
	 * Increase the user's topic count.
	 *
	 * @return void
	 */
	public function increaseTopicCount() {
		$this->topicCount++;
	}

	/**
	 * Decrease the user's question count.
	 *
	 * @return void
	 */
	public function decreaseQuestionCount() {
		$this->questionCount--;
	}

	/**
	 * Increase the user's question count.
	 *
	 * @return void
	 */
	public function increaseQuestionCount() {
		$this->questionCount++;
	}

	/**
	 * Increase the user's points.
	 *
	 * @param int $by The amount of points to be added
	 *
	 * @return void
	 */
	public function increasePoints($by) {
		$currentRank = $this->getRank();

		$this->points = $this->points + $by;

		/**
		 * @var Rank
		 */
		$rank = $this->rankRepository->findOneRankByPoints($this->getPoints());

		if ($rank !== NULL && $rank != $currentRank) {
			$this->setRank($rank);
			$rank->increaseUserCount();
			$currentRank->decreaseUserCount();
			$this->rankRepository->update($currentRank);
			$this->rankRepository->update($rank);
		}
	}

	/**
	 * Get the rank of this user
	 *
	 * @return Rank
	 */
	public function getRank() {
		return $this->rank;
	}

	/**
	 * Set the rank of this user
	 *
	 * @param Rank $rank
	 */
	public function setRank(Rank $rank) {
		$this->rank = $rank;
	}

	/**
	 * Gets the points of this user
	 *
	 * @return integer
	 */
	public function getPoints() {
		return $this->points;
	}

	/**
	 * Decrease the user's points.
	 *
	 * @param int $by The amount of points to be removed
	 *
	 * @return void
	 */
	public function decreasePoints($by) {
		$this->points = $this->points - $by;
		$currentRank = $this->getRank();
		$rank = $this->rankRepository->findOneRankByPoints($this->getPoints());

		if ($rank !== NULL && $rank != $currentRank) {
			$this->setRank($rank);
			$rank->increaseUserCount();
			$currentRank->decreaseUserCount();
			$this->rankRepository->update($currentRank);
			$this->rankRepository->update($rank);
		}
	}

	/**
	 * Resets the whole contact data array of this user. This array will be stored in
	 * a JSON serialized format.
	 *
	 * @param array $values All contact data of this user.
	 *
	 * @return void
	 */
	public function setContactData(array $values) {
		$this->contact = json_encode($values);
	}

	/**
	 * Sets a single contact data record. A contact data record can be unset by setting
	 * it to a empty or FALSE value.
	 *
	 * @param $type  string The contact record key (e.g. "twitter", "facebook", "icq", ...)
	 * @param $value string The new value. Set to a FALSE value to unset.
	 *
	 * @return void
	 */
	public function setContactDataItem($type, $value) {
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
	public function getContactData() {
		$decoded = json_decode($this->contact, TRUE);
		if ($decoded === NULL) {
			return [];
		}

		return $decoded;
	}

	/**
	 * Sets the helpfulCount value +1
	 *
	 * @return void
	 * @api
	 */
	public function setHelpful() {
		$this->setHelpfulCount($this->getHelpfulCount() + 1);
		$this->increaseHelpfulCountSession(1);
	}

	/**
	 * Gets the helpful count of this user.
	 *
	 * @return integer The helpful count.
	 */
	public function getHelpfulCount() {
		return $this->helpfulCount;
	}

	/**
	 * Sets the helpfulCount value
	 *
	 * @param int $count
	 *
	 * @return void
	 * @api
	 */
	public function setHelpfulCount($count) {
		$diff = $count - $this->getHelpfulCount();
		if ($diff >= 0) {
			$this->increaseHelpfulCountSession($diff);
		} else {
			$diff = $diff * -1; //Positive only
			$this->decreaseHelpfulCountSession($diff);
		}
		$this->helpfulCount = $count;
	}

	/**
	 * Increase the user's helpful count of the current session (Widgets)
	 *
	 * @param int $by
	 *
	 * @return void
	 */
	public function increaseHelpfulCountSession($by) {
		if ($by < 0) {
			$by = 1;
		}
		$this->helpfulCountSession = $this->helpfulCountSession + $by;
	}
}
