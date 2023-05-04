<?php
namespace Mittwald\Typo3Forum\Controller;

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

use Mittwald\Typo3Forum\Domain\Exception\Authentication\NotLoggedInException;
use Mittwald\Typo3Forum\Domain\Model\AccessibleInterface;
use Mittwald\Typo3Forum\Domain\Model\Forum\Forum;
use Mittwald\Typo3Forum\Domain\Model\Forum\Topic;
use Mittwald\Typo3Forum\Domain\Model\SubscribeableInterface;
use Mittwald\Typo3Forum\Domain\Model\User\FrontendUser;
use Mittwald\Typo3Forum\Domain\Repository\Forum\ForumRepository;
use Mittwald\Typo3Forum\Domain\Repository\Forum\PostRepository;
use Mittwald\Typo3Forum\Domain\Repository\Forum\TopicRepository;
use Mittwald\Typo3Forum\Domain\Repository\User\NotificationRepository;
use Mittwald\Typo3Forum\Domain\Repository\User\RankRepository;
use Mittwald\Typo3Forum\Domain\Repository\User\UserfieldRepository;
use TYPO3\CMS\Core\Messaging\FlashMessage;
use TYPO3\CMS\Extbase\Mvc\Exception\InvalidArgumentValueException;
use TYPO3\CMS\Extbase\Persistence\QueryInterface;
use TYPO3\CMS\Extbase\Utility\LocalizationUtility;

class UserController extends AbstractController
{
    protected ForumRepository $forumRepository;
    protected NotificationRepository $notificationRepository;
    protected RankRepository $rankRepository;
    protected TopicRepository $topicRepository;
    protected PostRepository $postRepository;
    protected UserfieldRepository $userfieldRepository;

    public function __construct(
        ForumRepository $forumRepository,
        NotificationRepository $notificationRepository,
        RankRepository $rankRepository,
        TopicRepository $topicRepository,
        PostRepository $postRepository,
        UserfieldRepository $userfieldRepository
    ) {
        $this->forumRepository = $forumRepository;
        $this->notificationRepository = $notificationRepository;
        $this->rankRepository = $rankRepository;
        $this->topicRepository = $topicRepository;
        $this->postRepository = $postRepository;
        $this->userfieldRepository = $userfieldRepository;
    }

    /**
     *  Listing Action.
     */
    public function listAction(?string $nameSearch = null, int $page = 1): void
    {
        $limit = $this->settings['maxUserItems'] ?? null;
        switch ($this->settings['listUsers']) {
            case 'helpfulUsers':
                $users = $this->frontendUserRepository->findMostHelpfulUsers(
                    $limit,
                    $nameSearch
                );
                break;
            case 'onlineUsers':
                $users = $this->frontendUserRepository->findByFilter(
                    $limit,
                    ['username' => QueryInterface::ORDER_ASCENDING],
                    true,
                    null,
                    $nameSearch
                );
                break;
            default:
                $users = $this->frontendUserRepository->findByFilter(
                    null,
                    ['username' => 'ASC'],
                    false,
                    null,
                    $nameSearch
                );
                break;
        }

        $this->view->assign('users', $users);
        $this->view->assign('page', $page);
        $this->view->assign('nameSearch', $nameSearch);
    }

    /**
     * Lists all posts of a specific user. If no user is specified, this action lists all
     * posts of the current user.
     *
     * @throws NotLoggedInException
     */
    public function listPostsAction(FrontendUser $user = null, int $page = 1): void
    {
        if ($user === null) {
            $user = $this->getCurrentUser();
        }
        if ($user->isAnonymous()) {
            throw new NotLoggedInException('You need to be logged in to view your own posts.', 1288084981);
        }
        $this->view
            ->assign('posts', $this->postRepository->findByAuthor($user))
            ->assign('page', $page)
            ->assign('user', $user)
        ;
    }

    /**
     * Lists all topics of a specific user. If no user is specified, this action lists all
     * topics of the current user.
     *
     * @throws NotLoggedInException
     */
    public function listTopicsAction(?FrontendUser $user = null, int $page = 1): void
    {
        $user = $user ?? $this->getCurrentUser();
        if ($user->isAnonymous()) {
            throw new NotLoggedInException('You need to be logged in to view your own posts.', 1288084981);
        }
        $this->view
            ->assign('topics', $this->topicRepository->findTopicsCreatedByAuthor($user, null, false))
            ->assign('page', $page)
            ->assign('user', $user);
    }

    /**
     * Lists all questions of a specific user. If no user is specified, this action lists all
     * posts of the current user.
     *
     * @throws NotLoggedInException
     */
    public function listQuestionsAction(FrontendUser $user = null, int $page = 1): void
    {
        if ($user === null) {
            $user = $this->getCurrentUser();
        }
        if ($user->isAnonymous()) {
            throw new NotLoggedInException('You need to be logged in to view your own posts.', 1288084981);
        }
        $this->view
            ->assign('topics', $this->topicRepository->findQuestions(null, true, $user))
            ->assign('page', $page)
            ->assign('user', $user);
    }

    /**
     * Lists all notifications of a specific user. If no user is specified, this action lists all
     * notifications of the current user.
     *
     * @throws NotLoggedInException
     */
    public function listNotificationsAction(int $page = 1): void
    {
        /** @var FrontendUser $user */
        $user = $this->getCurrentUser();
        if ($user->isAnonymous()) {
            throw new NotLoggedInException('You need to be logged in.', 1288084981);
        }
        $notifications = $this->notificationRepository->findNotificationsForUser($user);

        foreach ($notifications as $notification) {
            if ($notification->getUserRead() == 1) {
                break;
            } // if user already read this notification, the next should be already read
            $notification->setUserRead(1);
            $this->notificationRepository->update($notification);
        }

        $this->view->assignMultiple([
            'notifications' => $notifications,
            'currentUser' => $user,
            'page' => $page,
        ]);
    }

    /**
     * Displays a single user.
     */
    public function showAction(FrontendUser $user = null): void
    {
        if ($user === null) {
            $this->redirect('show', null, null, ['user' => $this->getCurrentUser()]);
        }

        $canReadClosure = function (AccessibleInterface $readableItem): bool {
            return $this->authenticationService->checkReadAuthorization($readableItem);
        };
        $postsByUser = array_filter(
            $this->postRepository->findByAuthor($user)->toArray(),
            $canReadClosure
        );
        $questionsByUser = array_filter(
            $this->topicRepository->findQuestions(6, false, $user)->toArray(),
            $canReadClosure
        );
        $topicsByUser = array_filter(
            $this->topicRepository->findTopicsCreatedByAuthor($user, 6, false)->toArray(),
            $canReadClosure
        );

        $this->view->assignMultiple([
            'user' => $user,
            'userfields' => $this->userfieldRepository->findAll(),
            'posts' => array_slice($postsByUser, 0, 5),
            'openQuestions' => $questionsByUser,
            'topics' => $topicsByUser,
        ]);
    }

    /**
     * Subscribes the current user to a forum or a topic.
     *
     * @throws \TYPO3\CMS\Extbase\Persistence\Exception\IllegalObjectTypeException
     * @throws NotLoggedInException
     * @throws InvalidArgumentValueException
     */
    public function subscribeAction(Forum $forum = null, Topic $topic = null, bool $unsubscribe = false, bool $prioritizeRefererRedirect = false): void
    {
        // Validate arguments
        if ($forum === null && $topic === null) {
            throw new InvalidArgumentValueException('You need to subscribe a Forum or Topic!', 1285059341);
        }
        $user = $this->getCurrentUser();
        if (!is_object($user) || $user->isAnonymous()) {
            throw new NotLoggedInException('You need to be logged in to subscribe or unsubscribe an object.', 1335121482);
        }

        // Create subscription
        $object = $topic ? $topic : $forum;

        if ($unsubscribe) {
            $user->removeSubscription($object);
        } else {
            $user->addSubscription($object);
        }

        // Update user and redirect to subscription object.
        $this->frontendUserRepository->update($user);
        $this->getFlashMessageQueue()->enqueue(
            new FlashMessage($this->getSubscriptionFlashMessage($object, $unsubscribe))
        );
        $this->clearCacheForCurrentPage();

        ($prioritizeRefererRedirect && $this->redirectToReferrer()) || $this->redirectToSubscriptionObject($object);
    }

    /**
     * Displays all topics and forums subscribed by the current user.
     */
    public function listSubscriptionsAction(int $forumPage = 1, int $topicPage = 1): void
    {
        $user = $this->getCurrentUser();
        if ($user->isAnonymous()) {
            throw new NotLoggedInException('You need to be logged in to view your own subscriptions!', 1335120249);
        }

        $this->view->assignMultiple([
            'forums' => $this->forumRepository->findBySubscriber($user),
            'topics' => $this->topicRepository->findBySubscriber($user),
            'user' => $user,
            'forumPage' => $forumPage,
            'topicPage' => $topicPage,
        ]);
    }

    /**
     * Displays a dashboard for the current user
     *
     * @throws \Mittwald\Typo3Forum\Domain\Exception\Authentication\NotLoggedInException
     */
    public function dashboardAction(): void
    {
        $user = $this->getCurrentUser();
        if (!is_object($user) || $user->isAnonymous()) {
            throw new NotLoggedInException('You need to be logged in to view your dashboard!', 1335120249);
        }
        $this->view->assignMultiple([
            'user' => $user,
            // TODO: Uncomment when the notification feature is implemented. Nothing currently creates notifications.
            // 'notifications' => $this->notificationRepository->findNotificationsForUser($user, 6),
            'topics' => $this->topicRepository->findTopicsCreatedByAuthor($user, 6, false),
            'subscribedTopics' => $this->topicRepository->findBySubscriber($user, 6),
            'subscribedForums' => $this->forumRepository->findBySubscriber($user, 6),
        ]);
    }

    /**
     * Redirects the user to the display view of a subscribeable object. This may
     * either be a forum or a topic, so this method redirects either to the
     * Forum->show or the Topic->show action.
     *
     * @param SubscribeableInterface $object A subscribeable object, i.e. either a forum or a topic.
     */
    protected function redirectToSubscriptionObject(SubscribeableInterface $object): void
    {
        if ($object instanceof Forum) {
            $this->redirect('show', 'Forum', null, ['forum' => $object]);
        }
        if ($object instanceof Topic) {
            $this->redirect('show', 'Topic', null, ['topic' => $object]);
        }
    }

    /**
     * Generates a flash message for when a subscription has successfully been
     * created or removed.
     */
    protected function getSubscriptionFlashMessage(SubscribeableInterface $object, bool $unsubscribe = false): string
    {
        $type = array_pop(explode('\\', get_class($object)));
        $key = 'User_' . ($unsubscribe ? 'Uns' : 'S') . 'ubscribe_' . $type . '_Success';
        return LocalizationUtility::translate($key, 'Typo3Forum', [$object->getTitle()]);
    }
}
