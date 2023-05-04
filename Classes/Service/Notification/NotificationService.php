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

use Mittwald\Typo3Forum\Configuration\ConfigurationBuilder;
use Mittwald\Typo3Forum\Domain\Model\Forum\Forum;
use Mittwald\Typo3Forum\Domain\Model\Forum\Post;
use Mittwald\Typo3Forum\Domain\Model\Forum\Topic;
use Mittwald\Typo3Forum\Domain\Model\NotifiableInterface;
use Mittwald\Typo3Forum\Domain\Model\SubscribeableInterface;
use Mittwald\Typo3Forum\Service\AbstractService;
use Mittwald\Typo3Forum\Service\Mailing\HTMLMailingService;
use Mittwald\Typo3Forum\Utility\Localization;
use TYPO3\CMS\Extbase\Mvc\Web\Routing\UriBuilder;
use TYPO3\CMS\Extbase\Persistence\Generic\LazyLoadingProxy;

/**
 * Service class for notifications. This service notifies subscribers of
 * forums and topic about new posts within the subscribed objects.
 */
class NotificationService extends AbstractService implements NotificationServiceInterface
{
    protected HTMLMailingService $htmlMailingService;
    protected UriBuilder $uriBuilder;
    protected ConfigurationBuilder $configurationBuilder;

    protected array $settings;

    public function __construct(
        HTMLMailingService $htmlMailingService,
        UriBuilder $uriBuilder,
        ConfigurationBuilder $configurationBuilder
    ) {
        $this->htmlMailingService = $htmlMailingService;
        $this->uriBuilder = $uriBuilder;
        $this->configurationBuilder = $configurationBuilder;
        $this->settings = $this->configurationBuilder->getSettings();
    }

    public function initializeObject()
    {
        $this->settings = $this->configurationBuilder->getSettings();
    }

    /**
     * Notifies subscribers of a subscribeable objects about a new notifiable object
     * within the subscribeable object, e.g. of a new post within a subscribed topic.
     *
     * @param SubscribeableInterface $subscriptionObject The subscribed object. This may for example be a forum or a topic.
     * @param NotifiableInterface $notificationObject The object that the subscriber is notified about. This may for example be a new post within an observed topic or forum or a new topic within an observed forum.
     */
    public function notifySubscribers(SubscribeableInterface $subscriptionObject, NotifiableInterface $notificationObject)
    {
        if ($subscriptionObject instanceof Forum && $notificationObject instanceof Topic) {
            $forum = $subscriptionObject;
            $topic = $notificationObject;
            $post = $topic->getLastPost();
            $this->notifyForumSubscribers($forum, $topic, $post);
        } elseif ($subscriptionObject instanceof Topic && $notificationObject instanceof Post) {
            $topic = $subscriptionObject;
            $forum = $topic->getForum();
            $post = $notificationObject;
            $this->notifyTopicSubscribers($forum, $topic, $post);
        }
    }

    /**
     * Notifies subscribers of a new post within a subscribed topic.
     */
    protected function notifyTopicSubscribers(Forum $forum, Topic $topic, Post $post): void
    {
        $subject = Localization::translate('Mail_Subscribe_NewPost_Subject');
        $message = $this->getMessage(
            $forum,
            $topic,
            $post,
            Localization::translate('Mail_Subscribe_NewPost_Body'),
            $this->getTopicUnsubscribeLink($topic)
        );
        $postAuthorUid = $post->getAuthor()->getUid();

        foreach ($topic->getSubscribers() as $subscriber) {
            if ($forum->checkReadAccess($subscriber) && $subscriber->getUid() !== $postAuthorUid) {
                $subscriberMessage = nl2br(str_replace('###RECIPIENT###', $subscriber->getUsername(), $message));
                $this->htmlMailingService->sendMail($subscriber, $subject, $subscriberMessage);
            }
        }
    }

    /**
     * Notifies subscribers of a new topic within a subscribed forum.
     */
    protected function notifyForumSubscribers(Forum $forum, Topic $topic, Post $post): void
    {
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
                    $notifiedSubscribers[$subscriber->getUid()] = true;
                }
            }

            $forum = $forum->getParent();
            if ($forum instanceof LazyLoadingProxy) {
                $forum = $forum->_loadRealInstance();
            }
        }
    }

    protected function getMessage(Forum $forum, Topic $topic, Post $post, string $messageTemplate, string $unsubscribeLink): string
    {
        $marker = [
            '###POST_AUTHOR###' => $post->getAuthor()->getUsername(),
            '###FORUM_NAME###' => $forum->getTitle(),
            '###FORUM_LINK###' => $this->getForumLink($topic->getForum()),
            '###TOPIC_NAME###' => $topic->getName(),
            '###TOPIC_LINK###' => $this->getTopicLink($topic),
            '###POST_LINK###' => $this->getPostLink($post),
            '###UNSUBSCRIBE_LINK###' => $unsubscribeLink,
            '###FORUM_TEAM###' => $this->settings['mailing']['sender']['name']
        ];
        $message = $messageTemplate;
        foreach ($marker as $name => $value) {
            $message = str_replace($name, $value, $message);
        }

        return $message;
    }

    protected function getForumLink(Forum $forum): string
    {
        $arguments = [
            'tx_typo3forum_forum[controller]' => 'Forum',
            'tx_typo3forum_forum[action]' => 'show',
            'tx_typo3forum_forum[forum]' => $forum->getUid(),
        ];

        $forumLink = $this->uriBuilder
            ->setTargetPageUid($this->settings['pids']['Forum'])
            ->setArguments($arguments)
            ->setCreateAbsoluteUri(true)
            ->build();
        $this->uriBuilder->reset();

        return '<a href="' . $forumLink . '">"' . $forum->getTitle() . '"</a>';
    }

    protected function getTopicLink(Topic $topic)
    {
        $arguments = [
            'tx_typo3forum_forum[controller]' => 'Topic',
            'tx_typo3forum_forum[action]' => 'show',
            'tx_typo3forum_forum[topic]' => $topic->getUid(),
        ];

        $topicLink = $this->uriBuilder
            ->setTargetPageUid($this->settings['pids']['Forum'])
            ->setArguments($arguments)
            ->setCreateAbsoluteUri(true)
            ->build()
        ;
        $this->uriBuilder->reset();

        return '<a href="' . $topicLink . '">' . $topic->getTitle() . '</a>';
    }

    protected function getPostLink(Post $post): string
    {
        $arguments = [
            'tx_typo3forum_forum[controller]' => 'Post',
            'tx_typo3forum_forum[action]' => 'show',
            'tx_typo3forum_forum[post]' => $post->getUid(),
        ];

        $postLink = $this->uriBuilder
            ->setTargetPageUid($this->settings['pids']['Forum'])
            ->setArguments($arguments)
            ->setCreateAbsoluteUri(true)
            ->build()
        ;
        $this->uriBuilder->reset();

        return '<a href="' . $postLink . '">"' . $post->getTopic()->getSubject() . '"</a>';
    }

    protected function getForumUnsubscribeLink(Forum $forum): string
    {
        $unSubscribeLink = $this->uriBuilder
            ->setTargetPageUid($this->settings['pids']['Forum'])
            ->setArguments([
                'tx_typo3forum_forum[controller]' => 'User',
                'tx_typo3forum_forum[action]' => 'subscribe',
                'tx_typo3forum_forum[forum]' => $forum->getUid(),
                'tx_typo3forum_forum[unsubscribe]' => 1,
            ])
            ->setCreateAbsoluteUri(true)
            ->build()
        ;
        $this->uriBuilder->reset();

        return '<a href="' . $unSubscribeLink . '">' . Localization::translate('Button_Unsubscribe') . '</a>';
    }

    protected function getTopicUnsubscribeLink(Topic $topic): string
    {
        $unSubscribeLink = $this->uriBuilder
            ->setTargetPageUid($this->settings['pids']['Forum'])
            ->setArguments([
                'tx_typo3forum_forum[controller]' => 'User',
                'tx_typo3forum_forum[action]' => 'subscribe',
                'tx_typo3forum_forum[topic]' => $topic->getUid(),
                'tx_typo3forum_forum[unsubscribe]' => 1,
            ])
            ->setCreateAbsoluteUri(true)
            ->build()
        ;
        $this->uriBuilder->reset();

        return '<a href="' . $unSubscribeLink . '">' . Localization::translate('Button_Unsubscribe') . '</a>';
    }
}
