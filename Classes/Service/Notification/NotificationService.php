<?php
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

namespace Mittwald\Typo3Forum\Service\Notification;

use Mittwald\Typo3Forum\Domain\Model\Forum\Forum;
use Mittwald\Typo3Forum\Domain\Model\Forum\Post;
use Mittwald\Typo3Forum\Domain\Model\Forum\Topic;
use Mittwald\Typo3Forum\Domain\Model\NotifiableInterface;
use Mittwald\Typo3Forum\Domain\Model\SubscribeableInterface;
use Mittwald\Typo3Forum\Service\AbstractService;
use Mittwald\Typo3Forum\Utility\Localization;

/**
 * Service class for notifications. This service notifies subscribers of
 * forums and topic about new posts within the subscribed objects.
 */
class NotificationService extends AbstractService implements NotificationServiceInterface {

	/**
	 * @var \Mittwald\Typo3Forum\Service\Mailing\HTMLMailingService
	 * @inject
	 */
	protected $htmlMailingService;

	/**
	 * @var \TYPO3\CMS\Extbase\Mvc\Web\Routing\UriBuilder
	 * @inject
	 */
	protected $uriBuilder;

	/**
	 * @var \Mittwald\Typo3Forum\Configuration\ConfigurationBuilder
	 * @inject
	 */
	protected $configurationBuilder;

	/**
	 * Whole TypoScript typo3_forum settings
	 *
	 * @var array
	 */
	protected $settings;

	public function initializeObject() {
		$this->settings = $this->configurationBuilder->getSettings();
	}

	/**
	 * Notifies subscribers of a subscribeable objects about a new notifiable object
	 * within the subscribeable object, e.g. of a new post within a subscribed topic.
	 *
	 * @param SubscribeableInterface $subscriptionObject The subscribed object. This may for example be a forum or a topic.
	 * @param NotifiableInterface $notificationObject The object that the subscriber is notified about. This may for example be a new post within an observed topic or forum or a new topic within an observed forum.
	 * @return void
	 */
	public function notifySubscribers(SubscribeableInterface $subscriptionObject, NotifiableInterface $notificationObject) {
		/** @var Forum $forum */
		/** @var Topic $topic */
		/** @var Post $post */

		if ($subscriptionObject instanceof Forum) {
			$forum = $subscriptionObject;
			$topic = $notificationObject;
			$post = $topic->getLastPost();
			$this->notifyForumSubscribers($forum, $topic, $post);
		} elseif ($subscriptionObject instanceof Topic) {
			$topic = $subscriptionObject;
			$forum = $topic->getForum();
			$post = $notificationObject;
			$this->notifyTopicSubscribers($forum, $topic, $post);
		}
	}

	/**
	 * Notifies subscribers of a new post within a subscribed topic.
	 *
	 * @param Forum $forum
	 * @param Topic $topic
	 * @param Post $post
	 * @return void
	 */
	protected function notifyTopicSubscribers(Forum $forum, Topic $topic, Post $post) {

		$subject = Localization::translate('Mail_Subscribe_NewPost_Subject');
		$message = $this->getMessage(
			$forum,
			$topic,
			$post,
			Localization::translate('Mail_Subscribe_NewPost_Body'),
			$this->getTopicUnsubscribeLink($forum, $topic)
		);
		$postAuthorUid = $post->getAuthor()->getUid();

		foreach ($topic->getSubscribers() as $subscriber) {
			if ($forum->checkReadAccess($subscriber) && $subscriber->getUid() !== $postAuthorUid) {
				$subscriberMessage = nl2br(str_replace('###RECIPIENT###', $subscriber->getUsername(), $message));
				$this->htmlMailingService->sendMail($subscriber, $subject, $subscriberMessage);
			}
		}
	}

	protected function notifyForumSubscribers(Forum $forum, Topic $topic, Post $post) {

		$subject = Localization::translate('Mail_Subscribe_NewTopic_Subject');
		$messageTemplate = Localization::translate('Mail_Subscribe_NewTopic_Body');
		$postAuthorUid = $post->getAuthor()->getUid();

		$notifiedSubscribers = [];

		while ($forum) {
			$message = $this->getMessage($forum, $topic, $post, $messageTemplate, $this->getForumUnsubscribeLink($forum));

			foreach ($forum->getSubscribers() as $subscriber) {
				if (!$notifiedSubscribers[$subscriber->getUid()] && $forum->checkReadAccess($subscriber) && $subscriber->getUid() !== $postAuthorUid) {
					$subscriberMessage = nl2br(str_replace('###RECIPIENT###', $subscriber->getUsername(), $message));
					$this->htmlMailingService->sendMail($subscriber, $subject, $subscriberMessage);
					$notifiedSubscribers[$subscriber->getUid()] = TRUE;
				}
			}

			$forum = $forum->getParent();
		}
	}

	protected function getMessage(Forum $forum, Topic $topic, Post $post, $messageTemplate, $unsubscribeLink) {
		$marker = [
			'###POST_AUTHOR###' => $post->getAuthor()->getUsername(),
			'###FORUM_NAME###' => $forum->getTitle(),
			'###FORUM_LINK###' => $this->getForumLink($topic->getForum()),
			'###TOPIC_NAME###' => $topic->getName(),
			'###TOPIC_LINK###' => $this->getTopicLink($forum, $topic),
			'###UNSUBSCRIBE_LINK###' => $unsubscribeLink,
			'###FORUM_TEAM###' => $this->settings['mailing']['sender']['name']
		];
		$message = $messageTemplate;
		foreach ($marker as $name => $value) {
			$message = str_replace($name, $value, $message);
		}

		return $message;
	}

	protected function getForumLink(Forum $forum) {
		$arguments = [
			'tx_typo3forum_pi1[controller]' => 'Forum',
			'tx_typo3forum_pi1[action]' => 'show',
			'tx_typo3forum_pi1[forum]' => $forum->getUid(),
		];

		$forumLink = $this->uriBuilder
			->setTargetPageUid($this->settings['pids']['Forum'])
			->setArguments($arguments)
			->setCreateAbsoluteUri(TRUE)
			->build();
		$this->uriBuilder->reset();

		return '<a href="' . $forumLink . '">"' . $forum->getTitle() . '"</a>';
	}

	protected function getTopicLink(Forum $forum, Topic $topic) {
		$arguments = [
			'tx_typo3forum_pi1[controller]' => 'Topic',
			'tx_typo3forum_pi1[action]' => 'show',
			'tx_typo3forum_pi1[topic]' => $topic->getUid(),
			'tx_typo3forum_pi1[forum]' => $forum->getUid(),
		];

		$pageNumber = $topic->getPageCount();
		if ($pageNumber > 1) {
			$arguments['@widget_0']['currentPage'] = $pageNumber;
		}

		$topicLink = $this->uriBuilder
			->setTargetPageUid($this->settings['pids']['Forum'])
			->setArguments($arguments)
			->setCreateAbsoluteUri(TRUE)
			->build();
		$this->uriBuilder->reset();

		return '<a href="' . $topicLink . '">"' . $topic->getTitle() . '"</a>';
	}

	protected function getForumUnsubscribeLink(Forum $forum) {
		$unSubscribeLink = $this->uriBuilder
			->setTargetPageUid($this->settings['pids']['Forum'])
			->setArguments([
				'tx_typo3forum_pi1[controller]' => 'User',
				'tx_typo3forum_pi1[action]' => 'subscribe',
				'tx_typo3forum_pi1[forum]' => $forum->getUid(),
				'tx_typo3forum_pi1[unsubscribe]' => 1,
			])
			->setCreateAbsoluteUri(TRUE)
			->build();
		$this->uriBuilder->reset();

		return '<a href="' . $unSubscribeLink . '">' . Localization::translate('Button_Unsubscribe') . '</a>';
	}

	protected function getTopicUnsubscribeLink(Forum $forum, Topic $topic) {
		$unSubscribeLink = $this->uriBuilder
			->setTargetPageUid($this->settings['pids']['Forum'])
			->setArguments([
				'tx_typo3forum_pi1[controller]' => 'User',
				'tx_typo3forum_pi1[action]' => 'subscribe',
				'tx_typo3forum_pi1[forum]' => $forum->getUid(),
				'tx_typo3forum_pi1[topic]' => $topic->getUid(),
				'tx_typo3forum_pi1[unsubscribe]' => 1,
			])
			->setCreateAbsoluteUri(TRUE)
			->build();
		$this->uriBuilder->reset();

		return '<a href="' . $unSubscribeLink . '">' . Localization::translate('Button_Unsubscribe') . '</a>';
	}

}
