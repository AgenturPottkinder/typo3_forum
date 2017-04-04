<?php
namespace Mittwald\Typo3Forum\Service\Notification;
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
	 * @var array
	 */
	protected $settings;

	public function initializeObject() {
		$this->settings = $this->configurationBuilder->getSettings();
	}

	/**
	 *
	 * Notifies subscribers of a subscribeable objects about a new notifiable object
	 * within the subscribeable object, e.g. of a new post within a subscribed topic.
	 *
	 * @param SubscribeableInterface $subscriptionObject The subscribed object. This may for example be a forum or a topic.
	 * @param NotifiableInterface $notificationObject The object that the subscriber is notified about. This may for example be a new post within an observed topic or forum or a new topic within an observed forum.
	 * @return void
	 *
	 */
	public function notifySubscribers(SubscribeableInterface $subscriptionObject, NotifiableInterface $notificationObject) {
		$topic = $subscriptionObject;
		$post  = $notificationObject;

		$subject = Localization::translate('Mail_Subscribe_NewPost_Subject');
		$messageTemplate = Localization::translate('Mail_Subscribe_NewPost_Body');
		$postAuthor = $post->getAuthor()->getUsername();
		$arguments = [
			'tx_typo3forum_pi1[controller]' => 'Topic',
			'tx_typo3forum_pi1[action]' => 'show',
			'tx_typo3forum_pi1[topic]' => $topic->getUid()
		];
		$pageNumber = $post->getTopic()->getPageCount();
		if ($pageNumber > 1) {
			$arguments['@widget_0']['currentPage'] = $pageNumber;
		}

		$topicLink = $this->uriBuilder->setTargetPageUid($this->settings['pids']['Forum'])->setArguments($arguments)->build();
		$topicLink = '<a href="' . $topicLink . '">' . $topic->getTitle() . '</a>';
		$this->uriBuilder->reset();
		$unSubscribeLink = $this->uriBuilder->setTargetPageUid($this->settings['pids']['Forum'])->setArguments([
			'tx_typo3forum_pi1[topic]' => $topic->getUid(),
			'tx_typo3forum_pi1[controller]' => 'User',
			'tx_typo3forum_pi1[action]' => 'subscribe',
			'tx_typo3forum_pi1[unsubscribe]' => 1,
		])->build();
		$unSubscribeLink = '<a href="' . $unSubscribeLink . '">' . $unSubscribeLink . '</a>';
		foreach ($topic->getSubscribers() as $subscriber) {
			if ($subscriber->getUid() != $post->getAuthor()->getUid() ) {
				$marker = [
					'###RECIPIENT###' => $subscriber->getUsername(),
					'###POST_AUTHOR###' => $postAuthor,
					'###TOPIC_LINK###' => $topicLink,
					'###UNSUBSCRIBE_LINK###' => $unSubscribeLink,
					'###FORUM_NAME###' => $this->settings['mailing']['sender']['name']
				];
				$message = $messageTemplate;
				foreach ($marker As $name => $value) {
					$message = str_replace($name, $value, $message);
				}
				$this->htmlMailingService->sendMail($subscriber, $subject, nl2br($message));
			}
		}
	}

}
