<?php
namespace Mittwald\MmForum\Controller;


/*                                                                      *
 *  COPYRIGHT NOTICE                                                    *
 *                                                                      *
 *  (c) 2013 Martin Helmich <m.helmich@mittwald.de>                     *
 *           Sebastian Gieselmann <s.gieselmann@mittwald.de>            *
 *           Ruven Fehling <r.fehling@mittwald.de>                      *
 *           Mittwald CM Service GmbH & Co KG                           *
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


/**
 *
 * This class implements a simple dispatcher for a mm_form eID script.
 *
 * @author     Martin Helmich <m.helmich@mittwald.de>
 * @author     Sebastian Gieselmann <s.gieselmann@mittwald.de>
 * @author     Ruven Fehling <r.fehling@mittwald.de>
 * @package    MmForum
 * @subpackage Controller
 * @version    $Id$
 *
 * @copyright  2012 Martin Helmich <m.helmich@mittwald.de>
 *             Mittwald CM Service GmbH & Co. KG
 *             http://www.mittwald.de
 * @license    GNU Public License, version 2
 *             http://opensource.org/licenses/gpl-license.php
 *
 */
class UserController extends AbstractController {



	/*
	 * ATTRIBUTES
	 */



	/**
	 * The userfield repository.
	 * @var \Mittwald\MmForum\Domain\Repository\User\UserfieldRepository
	 */
	protected $userfieldRepository = NULL;


	/**
	 * The topic repository.
	 * @var \Mittwald\MmForum\Domain\Repository\Forum\TopicRepository
	 */
	protected $topicRepository = NULL;


	/**
	 * The forum repository.
	 * @var \Mittwald\MmForum\Domain\Repository\Forum\ForumRepository
	 */
	protected $forumRepository = NULL;



	/**
	 * The forum repository.
	 * @var \Mittwald\MmForum\Domain\Repository\User\PrivateMessagesRepository
	 */
	protected $messageRepository = NULL;


	/**
	 * A message factory.
	 * @var \Mittwald\MmForum\Domain\Factory\User\PrivateMessagesFactory
	 */
	protected $privateMessagesFactory;


	/**
	 * The rank repository.
	 * @var \Mittwald\MmForum\Domain\Repository\User\RankRepository
	 */
	protected $rankRepository = NULL;


	/**
	 * The notification repository.
	 * @var \Mittwald\MmForum\Domain\Repository\User\NotificationRepository
	 */
	protected $notificationRepository = NULL;


	/*
	 * DEPENDENCY INJECTORS
	 */



	/**
	 * Constructor. Used primarily for dependency injection.
	 *
	 * @param \\Mittwald\MmForum\Domain\Repository\Forum\ForumRepository    $forumRepository
	 *                                 An instance of the forum repository.
	 * @param \\Mittwald\MmForum\Domain\Repository\Forum\TopicRepository    $topicRepository
	 *                                 An instance of the topic repository.
	 * @param \\Mittwald\MmForum\Domain\Repository\User\UserfieldRepository $userfieldRepository
	 *                                 An instance of the userfield repository.
	 * @param \Mittwald\MmForum\Domain\Repository\User\PrivateMessagesRepository $messageRepository
	 * 									An instance of the private message repository.
	 * @param \Mittwald\MmForum\Domain\Factory\User\PrivateMessagesFactory $privateMessagesFactory
	 * 									An instance of the private message factory
	 * @param \Mittwald\MmForum\Domain\Repository\User\RankRepository $rankRepository
	 * 									An instance of the rank repository
	 * @param \Mittwald\MmForum\Domain\Repository\User\NotificationRepository
	 *									An instance of the notification repository
	 */
	public function __construct(\Mittwald\MmForum\Domain\Repository\Forum\ForumRepository $forumRepository,
	                            \Mittwald\MmForum\Domain\Repository\Forum\TopicRepository $topicRepository,
	                            \Mittwald\MmForum\Domain\Repository\User\UserfieldRepository $userfieldRepository,
								\Mittwald\MmForum\Domain\Repository\User\PrivateMessagesRepository $messageRepository,
								\Mittwald\MmForum\Domain\Factory\User\PrivateMessagesFactory $privateMessagesFactory,
								\Mittwald\MmForum\Domain\Repository\User\RankRepository $rankRepository,
								\Mittwald\MmForum\Domain\Repository\User\NotificationRepository $notificationRepository) {
		parent::__construct();
		$this->forumRepository			= $forumRepository;
		$this->topicRepository			= $topicRepository;
		$this->userfieldRepository		= $userfieldRepository;
		$this->messageRepository		= $messageRepository;
		$this->privateMessagesFactory	= $privateMessagesFactory;
		$this->rankRepository			= $rankRepository;
		$this->notificationRepository	= $notificationRepository;
	}



	/*
	  * ACTION METHODS
	  */



	/**
	 * Displays a list of all existing users.
	 * @return void
	 */
	public function indexAction() {
		$this->view->assign('users', $this->frontendUserRepository->findForIndex());
	}

	/**
	 *  Listing Action.
	 * @return void
	 */
	public function listAction() {
		$showPaginate = false;
		switch($this->settings['listUsers']){
			case 'activeUserWidget':
				$dataset['users'] = $this->frontendUserRepository->findByFilter(intval($this->settings['widgets']['activeUser']['limit']),
																				array('postCountSeason' => 'DESC', 'username' => 'ASC'));
				$partial = 'User/ActiveBox';
				break;
			case 'helpfulUserWidget':
				$dataset['users'] = $this->frontendUserRepository->findByFilter(intval($this->settings['widgets']['helpfulUser']['limit']),
																				array('helpfulCountSeason' => 'DESC', 'username' => 'ASC'));
				$partial = 'User/HelpfulBox';
				break;
			case 'onlineUserWidget':
				//NO DATA - Ajax Reload
				$dataset['count'] = 0;
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
				$dataset['users'] = $this->frontendUserRepository->findByFilter('', array('username' => 'ASC'));
				$partial = 'User/List';
				break;
		}

		$this->view->assign('showPaginate', $showPaginate);
		$this->view->assign('partial', $partial);
		$this->view->assign('dataset',$dataset);
	}

	/**
	 * Lists all posts of a specific user. If no user is specified, this action lists all
	 * posts of the current user.
	 * @param \Mittwald\MmForum\Domain\Model\User\FrontendUser $user
	 *
	 * @throws \Mittwald\MmForum\Domain\Exception\Authentication\NotLoggedInException
	 * @return void
	 */
	public function listPostsAction(\Mittwald\MmForum\Domain\Model\User\FrontendUser $user = NULL) {
		if ($user === NULL) {
			$user = $this->getCurrentUser();
		}
		if ($user->isAnonymous()) {
			throw new \Mittwald\MmForum\Domain\Exception\Authentication\NotLoggedInException("You need to be logged in to view your own posts.", 1288084981);
		}
		$this->view
			->assign('topics', $this->topicRepository->findByPostAuthor($user))
			->assign('user', $user);
	}

	/**
	 * Lists all topics of a specific user. If no user is specified, this action lists all
	 * topics of the current user.
	 * @param \Mittwald\MmForum\Domain\Model\User\FrontendUser $user
	 *
	 * @throws \Mittwald\MmForum\Domain\Exception\Authentication\NotLoggedInException
	 * @return void
	 */
	public function listFavoritesAction(\Mittwald\MmForum\Domain\Model\User\FrontendUser $user = NULL) {
		if ($user === NULL) {
			$user = $this->getCurrentUser();
		}
		if ($user->isAnonymous()) {
			throw new \Mittwald\MmForum\Domain\Exception\Authentication\NotLoggedInException("You need to be logged in to view your own posts.", 1288084981);
		}
		$this->view
			->assign('topics', $this->topicRepository->findTopicsFavSubscribedByUser($user))
			->assign('user', $user);
	}

	/**
	 * Lists all topics of a specific user. If no user is specified, this action lists all
	 * topics of the current user.
	 * @param \Mittwald\MmForum\Domain\Model\User\FrontendUser $user
	 *
	 * @throws \Mittwald\MmForum\Domain\Exception\Authentication\NotLoggedInException
	 * @return void
	 */
	public function listTopicsAction(\Mittwald\MmForum\Domain\Model\User\FrontendUser $user = NULL) {
		if ($user === NULL) {
			$user = $this->getCurrentUser();
		}
		if ($user->isAnonymous()) {
			throw new \Mittwald\MmForum\Domain\Exception\Authentication\NotLoggedInException("You need to be logged in to view your own posts.", 1288084981);
		}
		$this->view
			->assign('topics', $this->topicRepository->findTopicsCreatedByAuthor($user))
			->assign('user', $user);
	}

	/**
	 * Lists all questions of a specific user. If no user is specified, this action lists all
	 * posts of the current user.
	 * @param \Mittwald\MmForum\Domain\Model\User\FrontendUser $user
	 *
	 * @throws \Mittwald\MmForum\Domain\Exception\Authentication\NotLoggedInException
	 * @return void
	 */
	public function listQuestionsAction(\Mittwald\MmForum\Domain\Model\User\FrontendUser $user = NULL) {
		if ($user === NULL) {
			$user = $this->getCurrentUser();
		}
		if ($user->isAnonymous()) {
			throw new \Mittwald\MmForum\Domain\Exception\Authentication\NotLoggedInException("You need to be logged in to view your own posts.", 1288084981);
		}
		$this->view
			->assign('topics', $this->topicRepository->findQuestions(null, true, $user))
			->assign('user', $user);
	}


	/**
	 * Lists all messages of a specific user. If no user is specified, this action lists all
	 * messages of the current user.
	 * @param \Mittwald\MmForum\Domain\Model\User\FrontendUser $opponent
	 * 												The dialog with which user should be shown. If null get first dialog.
	 *
	 * @throws \Mittwald\MmForum\Domain\Exception\Authentication\NotLoggedInException
	 * @return void
	 */
	public function listMessagesAction(\Mittwald\MmForum\Domain\Model\User\FrontendUser $opponent=NULL) {
		$user = $this->getCurrentUser();
		if ($user->isAnonymous()) {
			throw new \Mittwald\MmForum\Domain\Exception\Authentication\NotLoggedInException("You need to be logged in to view your own posts.", 1288084981);
		}
		/** @var TYPO3\CMS\Extbase\Persistence\Generic\QueryResult $dialog */
		$dialog = null;
		$partner = 'unknown';
		$userList = array();
		$userList = $this->messageRepository->findStartedConversations($user);

		if(!empty($userList)) {
			if($opponent === NULL) {
				$dialog = $this->messageRepository->findMessagesBetweenUser($userList[0]->getFeuser(),$userList[0]->getOpponent());
				$partner = $userList[0]->getOpponent();
			} else {
				$dialog = $this->messageRepository->findMessagesBetweenUser($user,$opponent);
				$partner = $opponent;
			}

			foreach($dialog AS $pm) {
				if($pm->getOpponent() == $user) {
					if($pm->getUserRead() == 1) break; // if user already read this message, the next should be already read
					$pm->setUserRead(1);
					$this->messageRepository->update($pm);
				}
			}
		}
		$this->view
			->assign('userList', $userList)
			->assign('dialog',$dialog)
		    ->assign('currentUser',$user)
			->assign('partner',$partner);
	}


	/**
 * Shows the form for creating a new message
 * @param \Mittwald\MmForum\Domain\Model\User\FrontendUser $recipient
 *
 * @throws \Mittwald\MmForum\Domain\Exception\Authentication\NotLoggedInException
 * @return void
 */
	public function newMessageAction(\Mittwald\MmForum\Domain\Model\User\FrontendUser $recipient=NULL) {
		$user = $this->getCurrentUser();
		if ($user->isAnonymous()) {
			throw new \Mittwald\MmForum\Domain\Exception\Authentication\NotLoggedInException("You need to be logged in.", 1288084981);
		}
		$readonly=0;
		if($recipient !== NULL) {
			$recipient = $recipient->getUsername();
			$readonly=1;
		}
		$this->view->assign('user', $user)->assign('recipient',$recipient)->assign('readonly',$readonly);
	}

	/**
	 * Create a new message
	 * @param string $recipient
	 * @param string $text
	 *
	 * @throws \Mittwald\MmForum\Domain\Exception\Authentication\NotLoggedInException
	 * @validate $recipient \Mittwald\MmForum\Domain\Validator\User\PrivateMessageRecipientValidator
	 * @return void
	 */
	public function createMessageAction($recipient, $text) {
		$user = $this->getCurrentUser();
		$recipient = $this->frontendUserRepository->findByUsername($recipient);
		if ($user->isAnonymous()) {
			throw new \Mittwald\MmForum\Domain\Exception\Authentication\NotLoggedInException("You need to be logged in.", 1288084981);
		}
		$message = $this->objectManager->create('Mittwald\\MmForum\\Domain\\Model\\User\\PrivateMessagesText');
		$message->setMessageText($text);
		$pmFeUser = $this->privateMessagesFactory->createPrivateMessage($user,$recipient,$message,0,1);
		$pmRecipient = $this->privateMessagesFactory->createPrivateMessage($recipient,$user,$message,1,0);
		$this->messageRepository->add($pmFeUser);
		$this->messageRepository->add($pmRecipient);
		$this->redirect('listMessages');
	}

	/**
	 * Lists all messages of a specific user. If no user is specified, this action lists all
	 * messages of the current user.
	 *
	 * @throws \Mittwald\MmForum\Domain\Exception\Authentication\NotLoggedInException
	 * @return void
	 */
	public function listNotificationsAction() {
		$user = $this->authenticationService->getUser();
		if ($user->isAnonymous()) {
			throw new \Mittwald\MmForum\Domain\Exception\Authentication\NotLoggedInException("You need to be logged in.", 1288084981);
		}
		$notifications = $this->notificationRepository->findNotificationsForUser($user);

		foreach($notifications AS $notification) {
			if($notification->getUserRead() == 1) break; // if user already read this notification, the next should be already read
			$notification->setUserRead(1);
			$this->notificationRepository->update($notification);
		}

		$this->view
			->assign('notifications',$notifications)
			->assign('currentUser',$user);

	}

	/**
	 * Disable single user
	 *
	 * @param \Mittwald\MmForum\Domain\Model\User\FrontendUser $user The user whose profile is to be displayed.
	 * @return void
	 */
	public function disableUserAction(\Mittwald\MmForum\Domain\Model\User\FrontendUser $user=NULL) {

		$currentUser = $this->getCurrentUser();
		if ($currentUser->isAnonymous()) {
			throw new \Mittwald\MmForum\Domain\Exception\Authentication\NotLoggedInException("You need to be logged in.", 1288084981);
		}
		$allowed = false;
		foreach($currentUser->getUsergroup() as $group){
			if($group->getUserMod()){
				$allowed = true;
			}
		}
		if(!$allowed){
			throw new \Mittwald\MmForum\Domain\Exception\Authentication\NotLoggedInException("You need to be logged in as Admin.", 1288344981);
		}

		$user->setDisable(true);
		$this->frontendUserRepository->update($user);
		$this->redirect('show', 'User', 'mmforum', array('user' => $user));
	}

	/**
	 * Displays a single user.
	 *
	 * @param \Mittwald\MmForum\Domain\Model\User\FrontendUser $user The user whose profile is to be displayed.
	 * @return void
	 */
	public function showAction(\Mittwald\MmForum\Domain\Model\User\FrontendUser $user=NULL) {
		/** @noinspection PhpUndefinedMethodInspection */
		if ($user === NULL) {
			return $this->redirect('show', NULL, NULL, array('user' => $this->getCurrentUser()));
		}
		$lastFiveTopics = $this->topicRepository
			->findByPostAuthor($user)
			->getQuery()
			->setLimit(5)
			->execute();
		$this->view
			->assign('user', $user)
			->assign('currentUser', $this->getCurrentUser())
			->assign('userfields', $this->userfieldRepository->findAll())
			->assign('topics', $lastFiveTopics)
			->assign('questions', $this->topicRepository->findQuestions(6, false, $user))
			->assign('myTopics',$this->topicRepository->findTopicsCreatedByAuthor($user, 6));
	}


	/**
	 * Subscribes the current user to a forum or a topic.
	 *
	 * @param \Mittwald\MmForum\Domain\Model\Forum\Forum $forum       The forum that is to be subscribed. Either this
	 *                                                         value or the $topic parameter must be != NULL.
	 * @param \Mittwald\MmForum\Domain\Model\Forum\Topic $topic       The topic that is to be subscribed. Either this
	 *                                                         value or the $forum parameter must be != NULL.
	 * @param boolean                             $unsubscribe TRUE to unsubscribe the forum or topic instead.
	 * @return void
	 */
	public function subscribeAction(\Mittwald\MmForum\Domain\Model\Forum\Forum $forum = NULL,
	                                \Mittwald\MmForum\Domain\Model\Forum\Topic $topic = NULL, $unsubscribe = FALSE) {

		// Validate arguments
		if ($forum === NULL && $topic === NULL) {
			throw new \TYPO3\CMS\Extbase\Mvc\Exception\InvalidArgumentValueException("You need to subscribe a Forum or Topic!", 1285059341);
		}
		$user = $this->getCurrentUser();
		if ($user->isAnonymous()) {
			throw new \Mittwald\MmForum\Domain\Exception\Authentication\NotLoggedInException('You need to be logged in to subscribe or unsubscribe an object.', 1335121482);
		}

		# Create subscription
		$object = $forum ? $forum : $topic;

		if ($unsubscribe) {
			$user->removeSubscription($object);
		} else {
			$user->addSubscription($object);
		}

		# Update user and redirect to subscription object.
		$this->frontendUserRepository->update($user);
		$this->controllerContext->getFlashMessageQueue()->addMessage(
			new \TYPO3\CMS\Core\Messaging\FlashMessage(
				$this->getSubscriptionFlashMessage($object, $unsubscribe)
			)
		);
		$this->clearCacheForCurrentPage();
		$this->redirectToSubscriptionObject($object);
	}

	/**
	 * Fav Subscribes the current user to a forum or a topic.
	 *
	 * @param \Mittwald\MmForum\Domain\Model\Forum\Forum $forum       The forum that is to be subscribed. Either this
	 *                                                         value or the $topic parameter must be != NULL.
	 * @param \Mittwald\MmForum\Domain\Model\Forum\Topic $topic       The topic that is to be subscribed. Either this
	 *                                                         value or the $forum parameter must be != NULL.
	 * @param boolean                             $unsubscribe TRUE to unsubscribe the forum or topic instead.
	 * @return void
	 */
	public function favSubscribeAction(\Mittwald\MmForum\Domain\Model\Forum\Forum $forum = NULL,
									\Mittwald\MmForum\Domain\Model\Forum\Topic $topic = NULL, $unsubscribe = FALSE) {

		// Validate arguments
		if ($forum === NULL && $topic === NULL) {
			throw new \TYPO3\CMS\Extbase\Mvc\Exception\InvalidArgumentValueException("You need to subscribe a Forum or Topic!", 1285059341);
		}
		$user = $this->getCurrentUser();
		if ($user->isAnonymous()) {
			throw new \Mittwald\MmForum\Domain\Exception\Authentication\NotLoggedInException('You need to be logged in to subscribe or unsubscribe an object.', 1335121482);
		}

		# Create subscription
		$object = $forum ? $forum : $topic;

		if ($unsubscribe) {
			$user->removeFavSubscription($object);
			$topic->getAuthor()->decreasePoints(intval($this->settings['rankScore']['gotFavorite']));
		} else {
			$user->addFavSubscription($object);
			$topic->getAuthor()->increasePoints(intval($this->settings['rankScore']['gotFavorite']));
		}

		# Update user and redirect to subscription object.
		$this->frontendUserRepository->update($user);
		$this->frontendUserRepository->update($topic->getAuthor());
		$this->controllerContext->getFlashMessageQueue()->addMessage(
			new \TYPO3\CMS\Core\Messaging\FlashMessage(
				$this->getSubscriptionFlashMessage($object, $unsubscribe)
			)
		);
		$this->clearCacheForCurrentPage();
		$this->redirectToSubscriptionObject($object);
	}

	/**
	 * Displays all topics and forums subscribed by the current user.
	 * @return void
	 *
	 * @throws \Mittwald\MmForum\Domain\Exception\Authentication\NotLoggedInException
	 */
	public function listSubscriptionsAction() {
		$user = $this->getCurrentUser();
		if ($user->isAnonymous()) {
			throw new \Mittwald\MmForum\Domain\Exception\Authentication\NotLoggedInException('You need to be logged in to view your own subscriptions!', 1335120249);
		}

		$this->view
			->assign('forums', $this->forumRepository->findBySubscriber($user))
			->assign('topics', $this->topicRepository->findBySubscriber($user))
			->assign('user', $user);
	}


	/**
	 * Displays a dashboard for the current user

	 * @return void
	 */
	public function dashboardAction() {
		$user = $this->frontendUserRepository->findCurrent();
		if (!$user || $user->isAnonymous()) {
			throw new \Mittwald\MmForum\Domain\Exception\Authentication\NotLoggedInException('You need to be logged in to view your dashboard!', 1335120249);
		}
		$this->view->assign('user',$user)
					->assign('myNotifications', $this->notificationRepository->findNotificationsForUser($user, 6))
					->assign('myMessages', $this->messageRepository->findReceivedMessagesForUser($user, 6))
					->assign('myFavorites', $this->topicRepository->findTopicsFavSubscribedByUser($user, 6))
					->assign('myTopics',$this->topicRepository->findTopicsCreatedByAuthor($user, 6));
	}



	/**
	 * @param string $searchValue
	 * @param string $filter
	 * @param int $order
	 * @return void
	 */
	public function searchUserAction($searchValue=NULL,$filter=NULL,$order=NULL) {
		/*
		switch($filter) {
			default:
			case 'username':
				$filterValue = "username";
				break;
			case 'memberSince':
				$filterValue = "crdate";
				break;
			case 'posts':
				$filterValue = "tx_mmforum_post_count";
				break;
			case 'helpful':
				$filterValue = "tx_mmforum_support_posts";
				break;
		}
		if($order == 0 || $order === NULL) {
			$orderValue = "ASC";
		} else {
			$orderValue = "DESC";
		}
		$users = $this->frontendUserRepository->findLikeUsername($searchValue,$filterValue,$orderValue);
		$this->view->assign('dataset',array('users' => $users, 'search' => $searchValue));*/
	}



	/*
	 * HELPER METHODS
	 */



	/**
	 * Redirects the user to the display view of a subscribeable object. This may
	 * either be a forum or a topic, so this method redirects either to the
	 * Forum->show or the Topic->show action.
	 *
	 * @param \Mittwald\MmForum\Domain\Model\SubscribeableInterface $object
	 *                             A subscribeable object, i.e. either a forum or a
	 *                             topic.
	 * @return void
	 */
	protected function redirectToSubscriptionObject(\Mittwald\MmForum\Domain\Model\SubscribeableInterface $object) {
		if ($object instanceof \Mittwald\MmForum\Domain\Model\Forum\Forum) {
			$this->redirect('show', 'Forum', NULL, array('forum' => $object));
		}
		if ($object instanceof \Mittwald\MmForum\Domain\Model\Forum\Topic) {
			$this->redirect('show', 'Topic', NULL, array('topic' => $object));
		}
	}



	/**
	 * Generates a flash message for when a subscription has successfully been
	 * created or removed.
	 *
	 * @param \\Mittwald\MmForum\Domain\Model\SubscribeableInterface $object
	 * @param bool                                            $unsubscribe
	 * @return string A flash message.
	 */
	protected function getSubscriptionFlashMessage(\Mittwald\MmForum\Domain\Model\SubscribeableInterface $object,
	                                               $unsubscribe = FALSE) {
		$type = array_pop(explode('_', get_class($object)));
		$key  = 'User_' . ($unsubscribe ? 'Uns' : 'S') . 'ubscribe_' . $type . '_Success';
		return \TYPO3\CMS\Extbase\Utility\LocalizationUtility::translate($key, 'MmForum', array($object->getTitle()));
	}



}
