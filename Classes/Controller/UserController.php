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
use Mittwald\Typo3Forum\Domain\Model\Forum\Forum;
use Mittwald\Typo3Forum\Domain\Model\Forum\Topic;
use Mittwald\Typo3Forum\Domain\Model\SubscribeableInterface;
use Mittwald\Typo3Forum\Domain\Model\User\FrontendUser;
use Mittwald\Typo3Forum\Domain\Model\User\PrivateMessage;
use Mittwald\Typo3Forum\Domain\Model\User\PrivateMessageText;
use TYPO3\CMS\Core\Messaging\FlashMessage;
use TYPO3\CMS\Extbase\Mvc\Exception\InvalidArgumentValueException;
use TYPO3\CMS\Extbase\Utility\LocalizationUtility;

class UserController extends AbstractController {

	/**
	 * @var \Mittwald\Typo3Forum\Domain\Repository\Forum\ForumRepository
	 * @inject
	 */
	protected $forumRepository = NULL;

	/**
	 * @var \Mittwald\Typo3Forum\Domain\Repository\User\PrivateMessageRepository
	 * @inject
	 */
	protected $privateMessageRepository = NULL;

	/**
	 * @var \Mittwald\Typo3Forum\Domain\Repository\User\NotificationRepository
	 * @inject
	 */
	protected $notificationRepository = NULL;

	/**
	 * @var \Mittwald\Typo3Forum\Domain\Factory\User\PrivateMessageFactory
	 * @inject
	 */
	protected $privateMessageFactory;

	/**
	 * @var \Mittwald\Typo3Forum\Domain\Repository\User\RankRepository
	 * @inject
	 */
	protected $rankRepository = NULL;

	/**
	 * @var \Mittwald\Typo3Forum\Domain\Repository\Forum\TopicRepository
	 * @inject
	 */
	protected $topicRepository = NULL;

	/**
	 * @var \Mittwald\Typo3Forum\Domain\Repository\User\UserfieldRepository
	 * @inject
	 */
	protected $userfieldRepository = NULL;

	/**
	 * Displays a list of all existing users.
	 */
	public function indexAction() {
		$this->view->assign('users', $this->frontendUserRepository->findForIndex());
	}

	/**
	 *  Listing Action.
	 */
	public function listAction() {
		$showPaginate = false;
		switch ($this->settings['listUsers']) {
			case 'activeUserWidget':
				$dataset['users'] = $this->frontendUserRepository->findByFilter(
					(int)$this->settings['widgets']['activeUser']['limit'],
					['postCountSession' => 'DESC', 'username' => 'ASC']
				);
				$partial = 'User/ActiveBox';
				break;
			case 'helpfulUserWidget':
				$dataset['users'] = $this->frontendUserRepository->findByFilter(
					(int)$this->settings['widgets']['helpfulUser']['limit'],
					['helpfulCountSession' => 'DESC', 'username' => 'ASC']
				);
				$partial = 'User/HelpfulBox';
				break;
			case 'onlineUserWidget':
                $dataset['count'] = $this->frontendUserRepository->countByFilter(TRUE);
                $dataset['users'] = $this->frontendUserRepository->findByFilter((int)$this->settings['widgets']['onlinebox']['limit'], [], TRUE);
				$partial = 'User/OnlineBox';
				break;
			case 'rankingList':
				$dataset['ranks'] = $this->rankRepository->findAllForRankingOverview();
				$partial = 'User/ListRanking';
				break;
			case 'topUserList':
				$dataset['users'] = $this->frontendUserRepository->findTopUserByPoints(50);
				$partial = 'User/ListTopUser';
				break;
			default:
				$dataset['users'] = $this->frontendUserRepository->findByFilter(0, ['username' => 'ASC']);
				$partial = 'User/List';
				break;
		}

		$this->view->assign('showPaginate', $showPaginate);
		$this->view->assign('partial', $partial);
		$this->view->assign('dataset', $dataset);
	}

	/**
	 * Lists all posts of a specific user. If no user is specified, this action lists all
	 * posts of the current user.
	 * @param FrontendUser $user
	 *
	 * @throws NotLoggedInException
	 */
	public function listPostsAction(FrontendUser $user = NULL) {
		if ($user === NULL) {
			$user = $this->getCurrentUser();
		}
		if ($user->isAnonymous()) {
			throw new NotLoggedInException("You need to be logged in to view your own posts.", 1288084981);
		}
		$this->view
			->assign('topics', $this->topicRepository->findByPostAuthor($user))
			->assign('user', $user);
	}

	/**
	 * Lists all topics of a specific user. If no user is specified, this action lists all
	 * topics of the current user.
	 * @param FrontendUser $user
	 *
	 * @throws NotLoggedInException
	 */
	public function listFavoritesAction(FrontendUser $user = NULL) {
		if ($user === NULL) {
			$user = $this->getCurrentUser();
		}
		if ($user->isAnonymous()) {
			throw new NotLoggedInException("You need to be logged in to view your own posts.", 1288084981);
		}
		$this->view
			->assign('topics', $this->topicRepository->findTopicsFavSubscribedByUser($user))
			->assign('user', $user);
	}

	/**
	 * Lists all topics of a specific user. If no user is specified, this action lists all
	 * topics of the current user.
	 * @param FrontendUser $user
	 *
	 * @throws NotLoggedInException
	 */
	public function listTopicsAction(FrontendUser $user = NULL) {
		if ($user === NULL) {
			$user = $this->getCurrentUser();
		}
		if ($user->isAnonymous()) {
			throw new NotLoggedInException("You need to be logged in to view your own posts.", 1288084981);
		}
		$this->view
			->assign('topics', $this->topicRepository->findTopicsCreatedByAuthor($user))
			->assign('user', $user);
	}

	/**
	 * Lists all questions of a specific user. If no user is specified, this action lists all
	 * posts of the current user.
	 * @param FrontendUser $user
	 *
	 * @throws NotLoggedInException
	 */
	public function listQuestionsAction(FrontendUser $user = NULL) {
		if ($user === NULL) {
			$user = $this->getCurrentUser();
		}
		if ($user->isAnonymous()) {
			throw new NotLoggedInException("You need to be logged in to view your own posts.", 1288084981);
		}
		$this->view
			->assign('topics', $this->topicRepository->findQuestions(null, true, $user))
			->assign('user', $user);
	}

	/**
	 * Lists all messages of a specific user. If no user is specified, this action lists all
	 * messages of the current user.
	 * @param FrontendUser $opponent The dialog with which user should be shown. If null get first dialog.
	 *
	 * @throws NotLoggedInException
	 */
	public function listMessagesAction(FrontendUser $opponent = NULL) {
		$user = $this->getCurrentUser();
		if (!$user instanceof FrontendUser || $user->isAnonymous()) {
			throw new NotLoggedInException('You need to be logged in to view your own posts.', 1288084981);
		}
		/** @var \TYPO3\CMS\Extbase\Persistence\Generic\QueryResult $dialog */
		$dialog = null;
		$partner = 'unknown';
		$userList = $this->privateMessageRepository->findStartedConversations($user);

		if (!empty($userList)) {
			if ($opponent === NULL) {
				$dialog = $this->privateMessageRepository->findMessagesBetweenUser($userList[0]->getFeuser(), $userList[0]->getOpponent());
				$partner = $userList[0]->getOpponent();
			} else {
				$dialog = $this->privateMessageRepository->findMessagesBetweenUser($user, $opponent);
				$partner = $opponent;
			}

			foreach ($dialog as $pm) {
				if ($pm->getOpponent()->getUid() == $user->getUid()) {
					if ($pm->getUserRead() == 1) break; // if user already read this message, the next should be already read
					$pm->setUserRead(1);
					$this->privateMessageRepository->update($pm);
				}
			}
		}
		$this->view->assignMultiple([
			'userList' => $userList,
			'dialog' => $dialog,
			'currentUser' => $user,
			'partner' => $partner,
		]);
	}

	/**
	 * Shows the form for creating a new message
	 * @param FrontendUser $recipient
	 *
	 * @throws NotLoggedInException
	 * @return void
	 */
	public function newMessageAction(FrontendUser $recipient = NULL) {
		$user = $this->getCurrentUser();
		if ($user->isAnonymous()) {
			throw new NotLoggedInException("You need to be logged in.", 1288084981);
		}
		$readonly = 0;
		if ($recipient !== NULL) {
			$recipient = $recipient->getUsername();
			$readonly = 1;
		}
		$this->view->assign('user', $user)->assign('recipient', $recipient)->assign('readonly', $readonly);
	}

	/**
	 * Create a new message
	 * @param string $recipient
	 * @param string $text
	 *
	 * @throws NotLoggedInException
	 * @validate $recipient \Mittwald\Typo3Forum\Domain\Validator\User\PrivateMessageRecipientValidator
	 */
	public function createMessageAction($recipient, $text) {
		$user = $this->getCurrentUser();
		$recipient = $this->frontendUserRepository->findOneByUsername($recipient);
		if ($user->isAnonymous()) {
			throw new NotLoggedInException("You need to be logged in.", 1288084981);
		}
		/** @var PrivateMessageText $message */
		$message = $this->objectManager->get(PrivateMessageText::class);
		$message->setMessageText($text);
		$pmFeUser = $this->privateMessageFactory->createPrivateMessage($user, $recipient, $message, PrivateMessage::TYPE_SENDER, 1);
		$pmRecipient = $this->privateMessageFactory->createPrivateMessage($recipient, $user, $message, PrivateMessage::TYPE_RECIPIENT, 0);
		$this->privateMessageRepository->add($pmFeUser);
		$this->privateMessageRepository->add($pmRecipient);
		$this->redirect('listMessages');
	}

	/**
	 * Lists all messages of a specific user. If no user is specified, this action lists all
	 * messages of the current user.
	 *
	 * @throws NotLoggedInException
	 */
	public function listNotificationsAction() {
		/** @var FrontendUser $user */
		$user = $this->authenticationService->getUser();
		if ($user->isAnonymous()) {
			throw new NotLoggedInException("You need to be logged in.", 1288084981);
		}
		$notifications = $this->notificationRepository->findNotificationsForUser($user);

		foreach ($notifications as $notification) {
			if ($notification->getUserRead() == 1) break; // if user already read this notification, the next should be already read
			$notification->setUserRead(1);
			$this->notificationRepository->update($notification);
		}

		$this->view->assignMultiple([
			'notifications' => $notifications,
			'currentUser' => $user,
		]);
	}

	/**
	 * disableUserAction
	 *
	 * @param FrontendUser $user
	 *
	 * @return void
	 * @throws NotLoggedInException
	 * @throws \TYPO3\CMS\Extbase\Persistence\Exception\IllegalObjectTypeException
	 */
	public function disableUserAction(FrontendUser $user = NULL) {

		$currentUser = $this->getCurrentUser();
		if ($currentUser->isAnonymous()) {
			throw new NotLoggedInException("You need to be logged in.", 1288084981);
		}
		$allowed = false;
		foreach ($currentUser->getUsergroup() as $group) {
			if ($group->getUserMod()) {
				$allowed = true;
			}
		}
		if (!$allowed) {
			throw new NotLoggedInException("You need to be logged in as Admin.", 1288344981);
		}

		$user->setDisable(true);
		$this->frontendUserRepository->update($user);
		$this->redirect('show', 'User', 'typo3forum', ['user' => $user]);
	}

	/**
	 * Displays a single user.
	 *
	 * @param FrontendUser $user The user whose profile is to be displayed.
	 */
	public function showAction(FrontendUser $user = NULL) {
		if ($user === NULL) {
			$this->redirect('show', NULL, NULL, ['user' => $this->getCurrentUser()]);
		}
		$lastFiveTopics = $this->topicRepository
			->findByPostAuthor($user)
			->getQuery()
			->setLimit(5)
			->execute();
		$this->view->assignMultiple([
			'user' => $user,
			'currentUser' => $this->getCurrentUser(),
			'userfields' => $this->userfieldRepository->findAll(),
			'topics' => $lastFiveTopics,
			'questions' => $this->topicRepository->findQuestions(6, FALSE, $user),
			'myTopics' => $this->topicRepository->findTopicsCreatedByAuthor($user, 6),
		]);
	}

	/**
	 * Subscribes the current user to a forum or a topic.
	 *
	 * @param Forum $forum The forum that is to be subscribed. Either this value or the $topic parameter must be != NULL.
	 * @param Topic $topic The topic that is to be subscribed. Either this value or the $forum parameter must be != NULL.
	 * @param bool $unsubscribe TRUE to unsubscribe the forum or topic instead.
	 *
	 * @return void
	 * @throws \TYPO3\CMS\Extbase\Persistence\Exception\IllegalObjectTypeException
	 * @throws NotLoggedInException
	 * @throws InvalidArgumentValueException
	 */
	public function subscribeAction(Forum $forum = NULL, Topic $topic = NULL, $unsubscribe = FALSE) {

		// Validate arguments
		if ($forum === NULL && $topic === NULL) {
			throw new InvalidArgumentValueException("You need to subscribe a Forum or Topic!", 1285059341);
		}
		$user = $this->getCurrentUser();
		if (!is_object($user) || $user->isAnonymous()) {
			throw new NotLoggedInException('You need to be logged in to subscribe or unsubscribe an object.', 1335121482);
		}

		# Create subscription
		$object = $topic ? $topic : $forum;

		if ($unsubscribe) {
			$user->removeSubscription($object);
		} else {
			$user->addSubscription($object);
		}

		# Update user and redirect to subscription object.
		$this->frontendUserRepository->update($user);
		$this->controllerContext->getFlashMessageQueue()->enqueue(
			new FlashMessage($this->getSubscriptionFlashMessage($object, $unsubscribe))
		);
		$this->clearCacheForCurrentPage();
		$this->redirectToSubscriptionObject($object);
	}

	/**
	 * Fav Subscribes the current user to a forum or a topic.
	 *
	 * @param Forum $forum The forum that is to be subscribed. Either this value or the $topic parameter must be != NULL.
	 * @param Topic $topic The topic that is to be subscribed. Either this value or the $forum parameter must be != NULL.
	 * @param bool $unsubscribe TRUE to unsubscribe the forum or topic instead.
	 * @return void
	 * @throws InvalidArgumentValueException
	 * @throws NotLoggedInException
	 */
	public function favSubscribeAction(Forum $forum = NULL, Topic $topic = NULL, $unsubscribe = FALSE) {

		// Validate arguments
		if ($forum === NULL && $topic === NULL) {
			throw new InvalidArgumentValueException("You need to subscribe a Forum or Topic!", 1285059341);
		}
		$user = $this->getCurrentUser();
		if ($user->isAnonymous()) {
			throw new NotLoggedInException('You need to be logged in to subscribe or unsubscribe an object.', 1335121482);
		}

		# Create subscription
		$object = $forum ? $forum : $topic;

		if ($unsubscribe) {
			$user->removeFavSubscription($object);
			$topic->getAuthor()->decreasePoints((int)$this->settings['rankScore']['gotFavorite']);
		} else {
			$user->addFavSubscription($object);
			$topic->getAuthor()->increasePoints((int)$this->settings['rankScore']['gotFavorite']);
		}

		# Update user and redirect to subscription object.
		$this->frontendUserRepository->update($user);
		$this->frontendUserRepository->update($topic->getAuthor());
		$this->controllerContext->getFlashMessageQueue()->enqueue(
			new FlashMessage($this->getSubscriptionFlashMessage($object, $unsubscribe))
		);
		$this->clearCacheForCurrentPage();
		$this->redirectToSubscriptionObject($object);
	}

	/**
	 * Displays all topics and forums subscribed by the current user.
	 *
	 * @throws NotLoggedInException
	 */
	public function listSubscriptionsAction() {
		$user = $this->getCurrentUser();
		if ($user->isAnonymous()) {
			throw new NotLoggedInException('You need to be logged in to view your own subscriptions!', 1335120249);
		}

		$this->view->assignMultiple([
			'forums' => $this->forumRepository->findBySubscriber($user),
			'topics' => $this->topicRepository->findBySubscriber($user),
			'user' => $user,
		]);
	}

	/**
	 * Displays a dashboard for the current user
	 *
	 * @return void
	 * @throws \Mittwald\Typo3Forum\Domain\Exception\Authentication\NotLoggedInException
	 */
	public function dashboardAction() {
        $user = $this->getCurrentUser();
        if (!is_object($user) || $user->isAnonymous()) {
			throw new NotLoggedInException('You need to be logged in to view your dashboard!', 1335120249);
		}
		$this->view->assignMultiple([
			'user' => $user,
			'myNotifications' => $this->notificationRepository->findNotificationsForUser($user, 6),
			'myMessages' => $this->privateMessageRepository->findReceivedMessagesForUser($user, 6),
			'myFavorites' => $this->topicRepository->findTopicsFavSubscribedByUser($user, 6),
			'myTopics' => $this->topicRepository->findTopicsCreatedByAuthor($user, 6),
		]);
	}

	/**
	 * @param string $searchValue
	 * @param string $filter
	 * @param int $order
	 * @return void
	 */
	public function searchUserAction($searchValue = NULL, $filter = NULL, $order = NULL) {
	}

	/**
	 * Redirects the user to the display view of a subscribeable object. This may
	 * either be a forum or a topic, so this method redirects either to the
	 * Forum->show or the Topic->show action.
	 *
	 * @param SubscribeableInterface $object A subscribeable object, i.e. either a forum or a topic.
	 */
	protected function redirectToSubscriptionObject(SubscribeableInterface $object) {
		if ($object instanceof Forum) {
			$this->redirect('show', 'Forum', NULL, ['forum' => $object]);
		}
		if ($object instanceof Topic) {
			$this->redirect('show', 'Topic', NULL, ['topic' => $object, 'forum' => $object->getForum()]);
		}
	}

	/**
	 * Generates a flash message for when a subscription has successfully been
	 * created or removed.
	 *
	 * @param SubscribeableInterface $object
	 * @param bool $unsubscribe
	 * @return string A flash message.
	 */
	protected function getSubscriptionFlashMessage(SubscribeableInterface $object, $unsubscribe = FALSE) {
		$type = array_pop(explode('\\', get_class($object)));
		$key = 'User_' . ($unsubscribe ? 'Uns' : 'S') . 'ubscribe_' . $type . '_Success';
		return LocalizationUtility::translate($key, 'Typo3Forum', [$object->getTitle()]);
	}

}
