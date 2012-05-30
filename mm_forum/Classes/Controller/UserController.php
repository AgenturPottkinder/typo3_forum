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
	 */
	public function __construct(Tx_MmForum_Domain_Repository_Forum_ForumRepository $forumRepository,
	                            Tx_MmForum_Domain_Repository_Forum_TopicRepository $topicRepository,
	                            Tx_MmForum_Domain_Repository_User_UserfieldRepository $userfieldRepository) {
		parent::__construct();
		$this->forumRepository     = $forumRepository;
		$this->topicRepository     = $topicRepository;
		$this->userfieldRepository = $userfieldRepository;
	}



	/*
	  * ACTION METHODS
	  */



	/**
	 * Displays a list of all existing users.
	 *
	 * @return void
	 */
	public function indexAction() {
		$this->view->assign('users', $this->frontendUserRepository->findForIndex());
	}



	/**
	 * Lists all posts of a specific user. If no user is specified, this action lists all
	 * posts of the current user.
	 * @param Tx_MmForum_Domain_Model_User_FrontendUser $user
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
	 * Displays a single user.
	 *
	 * @param Tx_MmForum_Domain_Model_User_FrontendUser $user The user whose profile is to be displayed.
	 * @return void
	 */
	public function showAction(Tx_MmForum_Domain_Model_User_FrontendUser $user) {
		/** @noinspection PhpUndefinedMethodInspection */
		$lastFiveTopics = $this->topicRepository
			->findByPostAuthor($user)
			->getQuery()
			->setLimit(5)
			->execute();
		$this->view
			->assign('user', $user)
			->assign('userfields', $this->userfieldRepository->findAll())
			->assign('topics', $lastFiveTopics);
	}



	/**
	 *
	 * Subscribes the current user to a forum or a topic.
	 *
	 * @param Tx_MmForum_Domain_Model_Forum_Forum $forum
	 *                                                         The forum that is to be subscribed. Either this
	 *                                                         value or the $topic parameter must be != NULL.
	 * @param Tx_MmForum_Domain_Model_Forum_Topic $topic
	 *                                                         The topic that is to be subscribed. Either this
	 *                                                         value or the $forum parameter must be != NULL.
	 * @param boolean                             $unsubscribe TRUE to unsubscribe the forum or topic instead.
	 *
	 */
	public function subscribeAction(Tx_MmForum_Domain_Model_Forum_Forum $forum = NULL,
	                                Tx_MmForum_Domain_Model_Forum_Topic $topic = NULL, $unsubscribe = FALSE) {

		// Validate arguments
		if ($forum === NULL && $topic === NULL) {
			throw new Tx_Extbase_MVC_Exception_InvalidArgumentValue("You need to subscribe a Forum or Topic!", 1285059341);
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
		$this->flashMessageContainer->add($this->getSubscriptionFlashMessage($object, $unsubscribe));
		$this->clearCacheForCurrentPage();
		$this->redirectToSubscriptionObject($object);
	}



	/**
	 * Displays all topics and forums subscribed by the current user.
	 * @return void
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



	/*
	 * HELPER METHODS
	 */



	/**
	 *
	 * Redirects the user to the display view of a subscribeable object. This may
	 * either be a forum or a topic, so this method redirects either to the
	 * Forum->show or the Topic->show action.
	 *
	 * @param Tx_MmForum_Domain_Model_SubscribeableInterface $object
	 *                             A subscribeable object, i.e. either a forum or a
	 *                             topic.
	 *
	 * @return void
	 *
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
	 *
	 * Generates a flash message for when a subscription has successfully been
	 * created or removed.
	 *
	 * @param \Tx_MmForum_Domain_Model_SubscribeableInterface $object
	 * @param bool                                            $unsubscribe
	 *
	 * @return string A flash message.
	 */
	protected function getSubscriptionFlashMessage(Tx_MmForum_Domain_Model_SubscribeableInterface $object,
	                                               $unsubscribe = FALSE) {
		$type = array_pop(explode('_', get_class($object)));
		$key  = 'User_' . ($unsubscribe ? 'Uns' : 'S') . 'ubscribe_' . $type . '_Success';
		return Tx_Extbase_Utility_Localization::translate($key, 'MmForum', array($object->getTitle()));
	}



}
