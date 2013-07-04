<?php

/*                                                                    - *
 *  COPYRIGHT NOTICE                                                    *
 *                                                                      *
 *  (c) 2012 Martin Helmich <m.helmich@mittwald.de>                     *
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
 * Controller for the User object. Offers user specific funcions, like user profiles, the
 * user list, and (un)subscribe functionalities.
 *
 * @author     Martin Helmich <m.helmich@mittwald.de>
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
class Tx_MmForum_Controller_UserController extends Tx_MmForum_Controller_AbstractController {



	/*
	 * ATTRIBUTES
	 */



	/**
	 * The userfield repository.
	 * @var Tx_MmForum_Domain_Repository_User_UserfieldRepository
	 */
	protected $userfieldRepository = NULL;


	/**
	 * The topic repository.
	 * @var Tx_MmForum_Domain_Repository_Forum_TopicRepository
	 */
	protected $topicRepository = NULL;


	/**
	 * The forum repository.
	 * @var Tx_MmForum_Domain_Repository_Forum_ForumRepository
	 */
	protected $forumRepository = NULL;



	/**
	 * The forum repository.
	 * @var Tx_MmForum_Domain_Repository_User_PrivateMessagesRepository
	 */
	protected $messageRepository = NULL;


	/**
	 * A message factory.
	 * @var Tx_MmForum_Domain_Factory_User_PrivateMessagesFactory
	 */
	protected $privateMessagesFactory;


	/**
	 * The rank repository.
	 * @var Tx_MmForum_Domain_Repository_User_RankRepository
	 */
	protected $rankRepository = NULL;


	/**
	 * The notification repository.
	 * @var Tx_MmForum_Domain_Repository_User_NotificationRepository
	 */
	protected $notificationRepository = NULL;


	/*
	 * DEPENDENCY INJECTORS
	 */



	/**
	 * Constructor. Used primarily for dependency injection.
	 *
	 * @param \Tx_MmForum_Domain_Repository_Forum_ForumRepository    $forumRepository
	 *                                 An instance of the forum repository.
	 * @param \Tx_MmForum_Domain_Repository_Forum_TopicRepository    $topicRepository
	 *                                 An instance of the topic repository.
	 * @param \Tx_MmForum_Domain_Repository_User_UserfieldRepository $userfieldRepository
	 *                                 An instance of the userfield repository.
	 * @param Tx_MmForum_Domain_Repository_User_PrivateMessagesRepository $messageRepository
	 * 									An instance of the private message repository.
	 * @param Tx_MmForum_Domain_Factory_User_PrivateMessagesFactory $privateMessagesFactory
	 * 									An instance of the private message factory
	 * @param Tx_MmForum_Domain_Repository_User_RankRepository $rankRepository
	 * 									An instance of the rank repository
	 * @param Tx_MmForum_Domain_Repository_User_NotificationRepository
	 *									An instance of the notification repository
	 */
	public function __construct(Tx_MmForum_Domain_Repository_Forum_ForumRepository $forumRepository,
	                            Tx_MmForum_Domain_Repository_Forum_TopicRepository $topicRepository,
	                            Tx_MmForum_Domain_Repository_User_UserfieldRepository $userfieldRepository,
								Tx_MmForum_Domain_Repository_User_PrivateMessagesRepository $messageRepository,
								Tx_MmForum_Domain_Factory_User_PrivateMessagesFactory $privateMessagesFactory,
								Tx_MmForum_Domain_Repository_User_RankRepository $rankRepository,
								Tx_MmForum_Domain_Repository_User_NotificationRepository $notificationRepository) {
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
				$dataset['users'] = $this->frontendUserRepository->findByFilter(6, array('postCount' => 'DESC'));
				$partial = 'User/ActiveBox';
				break;
			case 'helpfulUserWidget':
				$dataset['users'] = $this->frontendUserRepository->findByFilter(6, array('helpfulCount' => 'DESC'));
				$partial = 'User/HelpfulBox';
				break;
			case 'onlineUserWidget':
				$dataset['count'] = $this->frontendUserRepository->countByFilter(TRUE);
				$dataset['users'] = $this->frontendUserRepository->findByFilter(4, array('is_online' => 'DESC'), TRUE);
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
				$dataset['users'] = $this->frontendUserRepository->findByFilter(6, array('postCount' => 'DESC'));
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
	 * @param Tx_MmForum_Domain_Model_User_FrontendUser $user
	 *
	 * @throws Tx_MmForum_Domain_Exception_Authentication_NotLoggedInException
	 * @return void
	 */
	public function listPostsAction(Tx_MmForum_Domain_Model_User_FrontendUser $user = NULL) {
		if ($user === NULL) {
			$user = $this->getCurrentUser();
		}
		if ($user->isAnonymous()) {
			throw new Tx_MmForum_Domain_Exception_Authentication_NotLoggedInException("You need to be logged in to view your own posts.", 1288084981);
		}
		$this->view
			->assign('topics', $this->topicRepository->findByPostAuthor($user))
			->assign('user', $user);
	}

	/**
	 * Lists all topics of a specific user. If no user is specified, this action lists all
	 * topics of the current user.
	 * @param Tx_MmForum_Domain_Model_User_FrontendUser $user
	 *
	 * @throws Tx_MmForum_Domain_Exception_Authentication_NotLoggedInException
	 * @return void
	 */
	public function listFavoritesAction(Tx_MmForum_Domain_Model_User_FrontendUser $user = NULL) {
		if ($user === NULL) {
			$user = $this->getCurrentUser();
		}
		if ($user->isAnonymous()) {
			throw new Tx_MmForum_Domain_Exception_Authentication_NotLoggedInException("You need to be logged in to view your own posts.", 1288084981);
		}
		$this->view
			->assign('topics', $this->topicRepository->findTopicsFavSubscribedByUser($user))
			->assign('user', $user);
	}

	/**
	 * Lists all topics of a specific user. If no user is specified, this action lists all
	 * topics of the current user.
	 * @param Tx_MmForum_Domain_Model_User_FrontendUser $user
	 *
	 * @throws Tx_MmForum_Domain_Exception_Authentication_NotLoggedInException
	 * @return void
	 */
	public function listTopicsAction(Tx_MmForum_Domain_Model_User_FrontendUser $user = NULL) {
		if ($user === NULL) {
			$user = $this->getCurrentUser();
		}
		if ($user->isAnonymous()) {
			throw new Tx_MmForum_Domain_Exception_Authentication_NotLoggedInException("You need to be logged in to view your own posts.", 1288084981);
		}
		$this->view
			->assign('topics', $this->topicRepository->findTopicsCreatedByAuthor($user))
			->assign('user', $user);
	}

	/**
	 * Lists all questions of a specific user. If no user is specified, this action lists all
	 * posts of the current user.
	 * @param Tx_MmForum_Domain_Model_User_FrontendUser $user
	 *
	 * @throws Tx_MmForum_Domain_Exception_Authentication_NotLoggedInException
	 * @return void
	 */
	public function listQuestionsAction(Tx_MmForum_Domain_Model_User_FrontendUser $user = NULL) {
		if ($user === NULL) {
			$user = $this->getCurrentUser();
		}
		if ($user->isAnonymous()) {
			throw new Tx_MmForum_Domain_Exception_Authentication_NotLoggedInException("You need to be logged in to view your own posts.", 1288084981);
		}
		$this->view
			->assign('topics', $this->topicRepository->findQuestions(null, true, $user))
			->assign('user', $user);
	}


	/**
	 * Lists all messages of a specific user. If no user is specified, this action lists all
	 * messages of the current user.
	 * @param Tx_MmForum_Domain_Model_User_FrontendUser $opponent
	 * 												The dialog with which user should be shown. If null get first dialog.
	 *
	 * @throws Tx_MmForum_Domain_Exception_Authentication_NotLoggedInException
	 * @return void
	 */
	public function listMessagesAction(Tx_MmForum_Domain_Model_User_FrontendUser $opponent=NULL) {
		$user = $this->getCurrentUser();
		if ($user->isAnonymous()) {
			throw new Tx_MmForum_Domain_Exception_Authentication_NotLoggedInException("You need to be logged in to view your own posts.", 1288084981);
		}
		/** @var TYPO3\CMS\Extbase\Persistence\Generic\QueryResult $dialog */
		$dialog = null;
		$partner = 'unknown';
		$userList = array();
		$userList = $this->messageRepository->findStartedConversations($user);

		if(!empty($userList)) {
			if($opponent === NULL) {
				$dialog = $this->messageRepository->findMessagesBetweenUser($userList[0]->getFeuser(),$userList[0]->getOpponent());
				$partner = $userList[0]->getOpponent()->getUsername();
			} else {
				$dialog = $this->messageRepository->findMessagesBetweenUser($user,$opponent);
				$partner = $opponent->getUsername();
			}

			foreach($dialog AS $pm) {
				if($pm->getOpponent() == $user) {
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
 *
 * @throws Tx_MmForum_Domain_Exception_Authentication_NotLoggedInException
 * @return void
 */
	public function newMessageAction() {
		$user = $this->getCurrentUser();
		if ($user->isAnonymous()) {
			throw new Tx_MmForum_Domain_Exception_Authentication_NotLoggedInException("You need to be logged in.", 1288084981);
		}
		$this->view->assign('user', $user);
	}

	/**
	 * Create a new message
	 * @param string $recipient
	 * @param string $text
	 *
	 * @throws Tx_MmForum_Domain_Exception_Authentication_NotLoggedInException
	 * @validate $recipient Tx_MmForum_Domain_Validator_User_PrivateMessageRecipientValidator
	 * @validate $text notEmpty
	 * @return void
	 */
	public function createMessageAction($recipient, $text) {
		$user = $this->getCurrentUser();
		$recipient = $this->frontendUserRepository->findByUsername($recipient);
		if ($user->isAnonymous()) {
			throw new Tx_MmForum_Domain_Exception_Authentication_NotLoggedInException("You need to be logged in.", 1288084981);
		}
		$message = $this->objectManager->create('Tx_MmForum_Domain_Model_User_PrivateMessagesText');
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
	 * @throws Tx_MmForum_Domain_Exception_Authentication_NotLoggedInException
	 * @return void
	 */
	public function listNotificationsAction() {
		$user = $this->authenticationService->getUser();
		if ($user->isAnonymous()) {
			throw new Tx_MmForum_Domain_Exception_Authentication_NotLoggedInException("You need to be logged in.", 1288084981);
		}
		$this->view
			->assign('notifications',$this->notificationRepository->findNotificationsForUser($user))
			->assign('currentUser',$user);
	}

	/**
	 * Disable single user
	 *
	 * @param Tx_MmForum_Domain_Model_User_FrontendUser $user The user whose profile is to be displayed.
	 * @return void
	 */
	public function disableUserAction(Tx_MmForum_Domain_Model_User_FrontendUser $user=NULL) {

		$currentUser = $this->getCurrentUser();
		if ($currentUser->isAnonymous()) {
			throw new Tx_MmForum_Domain_Exception_Authentication_NotLoggedInException("You need to be logged in.", 1288084981);
		}
		$allowed = false;
		foreach($currentUser->getUsergroup() as $group){
			if($group->getUserMod()){
				$allowed = true;
			}
		}
		if(!$allowed){
			throw new Tx_MmForum_Domain_Exception_Authentication_NotLoggedInException("You need to be logged in as Admin.", 1288344981);
		}

		$user->setDisable(true);
		$this->frontendUserRepository->update($user);
		$this->redirect('show', 'User', 'mmforum', array('user' => $user));
	}

	/**
	 * Displays a single user.
	 *
	 * @param Tx_MmForum_Domain_Model_User_FrontendUser $user The user whose profile is to be displayed.
	 * @return void
	 */
	public function showAction(Tx_MmForum_Domain_Model_User_FrontendUser $user=NULL) {
		/** @noinspection PhpUndefinedMethodInspection */
		if ($user === NULL) {
			$user = $this->getCurrentUser();
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
	 * @param Tx_MmForum_Domain_Model_Forum_Forum $forum       The forum that is to be subscribed. Either this
	 *                                                         value or the $topic parameter must be != NULL.
	 * @param Tx_MmForum_Domain_Model_Forum_Topic $topic       The topic that is to be subscribed. Either this
	 *                                                         value or the $forum parameter must be != NULL.
	 * @param boolean                             $unsubscribe TRUE to unsubscribe the forum or topic instead.
	 * @return void
	 */
	public function subscribeAction(Tx_MmForum_Domain_Model_Forum_Forum $forum = NULL,
	                                Tx_MmForum_Domain_Model_Forum_Topic $topic = NULL, $unsubscribe = FALSE) {

		// Validate arguments
		if ($forum === NULL && $topic === NULL) {
			throw new \TYPO3\CMS\Extbase\Mvc\Exception\InvalidArgumentValueException("You need to subscribe a Forum or Topic!", 1285059341);
		}
		$user = $this->getCurrentUser();
		if ($user->isAnonymous()) {
			throw new Tx_MmForum_Domain_Exception_Authentication_NotLoggedInException('You need to be logged in to subscribe or unsubscribe an object.', 1335121482);
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
	 * @param Tx_MmForum_Domain_Model_Forum_Forum $forum       The forum that is to be subscribed. Either this
	 *                                                         value or the $topic parameter must be != NULL.
	 * @param Tx_MmForum_Domain_Model_Forum_Topic $topic       The topic that is to be subscribed. Either this
	 *                                                         value or the $forum parameter must be != NULL.
	 * @param boolean                             $unsubscribe TRUE to unsubscribe the forum or topic instead.
	 * @return void
	 */
	public function favSubscribeAction(Tx_MmForum_Domain_Model_Forum_Forum $forum = NULL,
									Tx_MmForum_Domain_Model_Forum_Topic $topic = NULL, $unsubscribe = FALSE) {

		// Validate arguments
		if ($forum === NULL && $topic === NULL) {
			throw new \TYPO3\CMS\Extbase\Mvc\Exception\InvalidArgumentValueException("You need to subscribe a Forum or Topic!", 1285059341);
		}
		$user = $this->getCurrentUser();
		if ($user->isAnonymous()) {
			throw new Tx_MmForum_Domain_Exception_Authentication_NotLoggedInException('You need to be logged in to subscribe or unsubscribe an object.', 1335121482);
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
	 * @throws Tx_MmForum_Domain_Exception_Authentication_NotLoggedInException
	 */
	public function listSubscriptionsAction() {
		$user = $this->getCurrentUser();
		if ($user->isAnonymous()) {
			throw new Tx_MmForum_Domain_Exception_Authentication_NotLoggedInException('You need to be logged in to view your own subscriptions!', 1335120249);
		}

		$this->view
			->assign('forums', $this->forumRepository->findBySubscriber($user))
			->assign('topics', $this->topicRepository->findBySubscriber($user))
			->assign('user', $user);
	}


	/**
	 * Displays a dashboard for the current user
	 * @return void
	 *
	 * @throws Tx_MmForum_Domain_Exception_Authentication_NotLoggedInException
	 */
	public function dashboardAction() {
		$user = $this->authenticationService->getUser();
		if ($user->isAnonymous()) {
			throw new Tx_MmForum_Domain_Exception_Authentication_NotLoggedInException('You need to be logged in to view your own subscriptions!', 1335120249);
		}
		$this->view->assign('user',$user)
					->assign('myNotifications', $this->notificationRepository->findNotificationsForUser($user, 6))
					->assign('myMessages', $this->messageRepository->findReceivedMessagesForUser($user, 6))
					->assign('myFavorites', $this->topicRepository->findTopicsFavSubscribedByUser($user, 6))
					->assign('myTopics',$this->topicRepository->findTopicsCreatedByAuthor($user, 6));

	}



	/*
	 * HELPER METHODS
	 */



	/**
	 * Redirects the user to the display view of a subscribeable object. This may
	 * either be a forum or a topic, so this method redirects either to the
	 * Forum->show or the Topic->show action.
	 *
	 * @param Tx_MmForum_Domain_Model_SubscribeableInterface $object
	 *                             A subscribeable object, i.e. either a forum or a
	 *                             topic.
	 * @return void
	 */
	protected function redirectToSubscriptionObject(Tx_MmForum_Domain_Model_SubscribeableInterface $object) {
		if ($object instanceof Tx_MmForum_Domain_Model_Forum_Forum) {
			$this->redirect('show', 'Forum', NULL, array('forum' => $object));
		}
		if ($object instanceof Tx_MmForum_Domain_Model_Forum_Topic) {
			$this->redirect('show', 'Topic', NULL, array('topic' => $object));
		}
	}



	/**
	 * Generates a flash message for when a subscription has successfully been
	 * created or removed.
	 *
	 * @param \Tx_MmForum_Domain_Model_SubscribeableInterface $object
	 * @param bool                                            $unsubscribe
	 * @return string A flash message.
	 */
	protected function getSubscriptionFlashMessage(Tx_MmForum_Domain_Model_SubscribeableInterface $object,
	                                               $unsubscribe = FALSE) {
		$type = array_pop(explode('_', get_class($object)));
		$key  = 'User_' . ($unsubscribe ? 'Uns' : 'S') . 'ubscribe_' . $type . '_Success';
		return \TYPO3\CMS\Extbase\Utility\LocalizationUtility::translate($key, 'MmForum', array($object->getTitle()));
	}



}
