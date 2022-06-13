<?php
namespace Mittwald\Typo3Forum\Domain\Model\User;

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

use Mittwald\Typo3Forum\Domain\Model\Forum\Post;
use Mittwald\Typo3Forum\Domain\Model\Forum\Tag;
use Mittwald\Typo3Forum\Domain\Model\Forum\Topic;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\DomainObject\AbstractEntity;
use TYPO3\CMS\Extbase\Persistence\Generic\LazyLoadingProxy;

class Notification extends AbstractEntity {

	/**
	 * The execution date of the cron
	 * @var \DateTime
	 */
	public $crdate;

	/**
	 * User who is related with this notification
	 * @var \Mittwald\Typo3Forum\Domain\Model\User\FrontendUser
	 */
	public $feuser;


	/**
	 * Post which is related with this notification
	 * @var \Mittwald\Typo3Forum\Domain\Model\Forum\Post
	 */
	public $post;

	/**
	 * Tag which is related with this notification
	 * @var \Mittwald\Typo3Forum\Domain\Model\Forum\Tag
	 */
	public $tag;

    /**
     * @var \Mittwald\Typo3Forum\Domain\Model\Forum\Topic
     */
    public $topic;

	/**
	 * The type of notification (Model Name)
	 * @var string
	 */
	public $type;

	/**
	 * Flag if user already read this notification
	 * @var int
	 */
	public $userRead;

	/**
	 * Get the date this message has been sent
	 * @return \DateTime
	 */
	public function getCrdate() {
		return $this->crdate;
	}

	/**
	 * Get the type of this notification (Model name)
	 * @return string
	 */
	public function getType() {
		return $this->type;
	}

	/**
	 * Get the type of this notification (Model Name)
	 *
	 * @param string $type
	 *
	 * @return void
	 */
	public function setType($type) {
		$this->type = $type;
	}

	/**
	 * Get the User who is related with this notification
	 * @return FrontendUser
	 */
	public function getFeuser() {
		if ($this->feuser instanceof LazyLoadingProxy) {
			$this->feuser->_loadRealInstance();
		}
		if ($this->feuser === NULL) {
			$this->feuser = new AnonymousFrontendUser();
		}

		return $this->feuser;
	}

	/**
	 * Sets the user
	 *
	 * @param FrontendUser $feuser
	 *
	 * @return void
	 */
	public function setFeuser(FrontendUser $feuser) {
		$this->feuser = $feuser;
	}

	/**
	 * Get the Post which is related with this notification
	 * @return Post
	 */
	public function getPost() {
		return $this->post;
	}

	/**
	 * Sets the post
	 *
	 * @param Post $post
	 *
	 * @return void
	 */
	public function setPost(Post $post) {
		$this->post = $post;
	}

	/**
	 * Get the tag which is related with this notification
	 * @return Tag
	 */
	public function getTag() {
		return $this->tag;
	}

	/**
	 * Set the tag
	 *
	 * @param Tag $tag
	 *
	 * @return void
	 */
	public function setTag(Tag $tag) {
		$this->tag = $tag;
	}

    /**
     * @return \Mittwald\Typo3Forum\Domain\Model\Forum\Topic
     */
    public function getTopic(): ?\Mittwald\Typo3Forum\Domain\Model\Forum\Topic
    {
        return $this->topic;
    }

    /**
     * @param \Mittwald\Typo3Forum\Domain\Model\Forum\Topic $topic
     *
     * @return Notification
     */
    public function setTopic(\Mittwald\Typo3Forum\Domain\Model\Forum\Topic $topic): Notification
    {
        $this->topic = $topic;
        return $this;
    }

	/**
	 * Get if the user already read this notification
	 * @return int The flag
	 */
	public function getUserRead() {
		return (int)$this->userRead;
	}

	/**
	 * Sets the flag
	 *
	 * @param int $userRead
	 *
	 * @return void
	 */
	public function setUserRead($userRead) {
		$this->userRead = $userRead;
	}

    /**
     * Return if the notification or not
     *
     * @return bool
     */
    public function isTopicNotification(): bool
    {
        return $this->getType() === Topic::class;
    }

    /**
     * Wrapper for extbase
     *
     * @return bool
     */
    public function getIsTopicNotification(): bool
    {
        return $this->isTopicNotification();
    }

    /**
     * Returns the author of the post or topic related to the notification
     *
     * @return FrontendUser
     */
    public function getAutor(): FrontendUser
    {
        try {
            if ($this->isTopicNotification()) {
                return $this->getTopic()->getAuthor();
            }
            return $this->getPost()->getAuthor();
        } catch (\Throwable $exception) {
            return GeneralUtility::makeInstance(AnonymousFrontendUser::class);
        }
    }

    /**
     * Returns the subject of the topic related to the notification
     *
     * @return string
     */
    public function getSubject(): string
    {
        if ($this->isTopicNotification()) {
            return $this->getTopic()->getSubject();
        }

        return $this->getPost()->getTopic()->getSubject();
    }

    /**
     * Returns the timestamp of the post or topic related to this notification
     *
     * @return \DateTime
     */
    public function getTimestamp(): \DateTime
    {
        if ($this->isTopicNotification()) {
            return $this->getTopic()->getTimestamp();
        }

        return $this->getPost()->getTimestamp();
    }

    /**
     * Returns the type of the notification
     *
     * @return string
     */
    public function getNotificationType(): string
    {
        $parts =  explode('\\', $this->getType());

        if (empty($parts) || false === is_array($parts)) {
            return "None";
        }

        return end($parts);
    }

    public function getHideNotification(): bool
    {
        try {
            return $this->isTopicNotification() ?
                null === $this->getTopic() :
                null === $this->getPost()->getTopic();
        } catch (\Throwable $exception) {
            return true;
        }
    }
}
