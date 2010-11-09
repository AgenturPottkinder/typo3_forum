<?php

/*                                                                      *
 *  COPYRIGHT NOTICE                                                    *
 *                                                                      *
 *  (c) 2010 Martin Helmich <m.helmich@mittwald.de>                     *
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
	 * Controller for the Topic object. This controller implements topic-related
	 * functions like displaying, creating or editing topics.
	 *
	 * @author     Martin Helmich <m.helmich@mittwald.de>
	 * @package    MmForum
	 * @subpackage Controller
	 * @version    $Id$
	 *
	 * @copyright  2010 Martin Helmich <m.helmich@mittwald.de>
	 *             Mittwald CM Service GmbH & Co. KG
	 *             http://www.mittwald.de
	 * @license    GNU Public License, version 2
	 *             http://opensource.org/licenses/gpl-license.php
	 */

Class Tx_MmForum_Controller_TopicController Extends Tx_MmForum_Controller_AbstractController {





		/*
		 * ATTRIBUTES
		 */





		/**
		 * The topic repository.
		 * @var Tx_MmForum_Domain_Repository_Forum_TopicRepository
		 */
	Protected $topicRepository;

		/**
		 * The forum repository.
		 * @var Tx_MmForum_Domain_Repository_Forum_ForumRepository
		 */
	Protected $forumRepository;

		/**
		 * The post repository.
		 * @var Tx_MmForum_Domain_Repository_Forum_PostRepository
		 */
	Protected $postRepository;

		/**
		 * A factory class for creating topics.
		 * @var Tx_MmForum_Domain_Factory_Forum_TopicFactory
		 */
	Protected $topicFactory;

		/**
		 * A factory class for creating posts.
		 * @var Tx_MmForum_Domain_Factory_Forum_PostFactory
		 */
	Protected $postFactory;

		/**
		 * A service class for notifying forum subscribers about new topics.
		 * @var Tx_MmForum_Service_NotificationService
		 */
	Protected $notificationService;





		/*
		 * INITIALIZATION METHODS
		 */





		/**
		 *
		 * Initializes the show action.
		 * @return void
		 *
		 */

	Protected Function initializeShowAction() {
		$this->postRepository =&
			t3lib_div::makeInstance('Tx_MmForum_Domain_Repository_Forum_PostRepository');
	}

		/**
		 *
		 * Initializes the create action.
		 * @return void
		 *
		 */

	Protected Function initializeCreateAction() {
		$this->topicRepository =&
			t3lib_div::makeInstance('Tx_MmForum_Domain_Repository_Forum_TopicRepository');
		$this->topicFactory =&
			t3lib_div::makeInstance('Tx_MmForum_Domain_Factory_Forum_TopicFactory');
		$this->postFactory =&
			t3lib_div::makeInstance('Tx_MmForum_Domain_Factory_Forum_PostFactory');
		$this->notificationService =&
			t3lib_div::makeInstance('Tx_MmForum_Service_NotificationService');
		$this->notificationService->injectControllerContext($this->buildControllerContext());
		$this->notificationService->injectMailingService($this->buildMailingService());
	}





		/*
		 * ACTION METHODS
		 */





		/**
		 *
		 * Show action. Displays a single topic and all posts contained in this topic.
		 *
		 * @param Tx_MmForum_Domain_Model_Forum_Topic $topic
		 *                             The topic that is to be displayed.
		 * @param integer $page        The current page.
		 * @return void
		 *
		 */

	Public Function showAction ( Tx_MmForum_Domain_Model_Forum_Topic $topic,
	                             $page = 1 ) {
		$this->authenticationService->assertReadAuthorization($topic);
		$this->markTopicRead($topic);
		$this->view
			->assign('topic', $topic)
			->assign('posts', $this->postRepository->findForTopic($topic, $page, (int)$this->localSettings['show']['pagebrowser']['itemsPerPage']))
			->assign('postCount', $this->postRepository->countByTopic($topic))
			->assign('page', $page);
	}



		/**
		 *
		 * New action. Displays a form for creating a new topic.
		 *
		 * @param Tx_MmForum_Domain_Model_Forum_Forum $forum
		 *                             The forum in which the new topic is to be created.
		 * @param Tx_MmForum_Domain_Model_Forum_Post $post
		 *                             The first post of the new topic.
		 * @param string $subject      The subject of the new topic
		 * @dontvalidate $topic
		 *
		 */

	Public Function newAction ( Tx_MmForum_Domain_Model_Forum_Forum $forum,
	                            Tx_MmForum_Domain_Model_Forum_Post $post = NULL,
	                            $subject = NULL ) {
		$this->authenticationService->assertNewTopicAuthorization($forum);
		$this->view->assign('forum', $forum)
			->assign('post', $post)
			->assign('subject', $subject);
	}



		/**
		 *
		 * Creates a new topic.
		 *
		 * @param Tx_MmForum_Domain_Model_Forum_Forum $forum
		 *                             The forum in which the new topic is to be created.
		 * @param Tx_MmForum_Domain_Model_Forum_Post $post
		 *                             The first post of the new topic.
		 * @param string $subject      The subject of the new topic
		 * @validate $subject NotEmpty
		 *
		 */

	Public Function createAction ( Tx_MmForum_Domain_Model_Forum_Forum $forum,
	                               Tx_MmForum_Domain_Model_Forum_Post $post,
	                               $subject ) {

			# Assert authorization
		$this->authenticationService->assertNewTopicAuthorization($forum);

			# Create the new post; add the new post to a new topic and add the new topic
			# to the forum. Then persist the forum object.
		$this->postFactory->assignUserToPost($post);
		$this->topicFactory->createTopic($forum, $post, $subject);

			# Notify forum subscribers.
		$this->notificationService->notifySubscribers($forum, $topic);

			# Redirect to single forum display view
		$this->flashMessages->add(Tx_MmForum_Utility_Localization::translate('Topic_Create_Success'));
		$this->redirect('show', 'Forum', NULL, array('forum' => $forum));
		
	}





		/*
		 * HELPER METHODS
		 */





		/**
		 *
		 * Marks a topic as read by the current user.
		 * @param  Tx_MmForum_Domain_Model_Forum_Topic $topic
		 *                             The topic that is to be marked as read.
		 * @return void
		 *
		 */
	
	Protected Function markTopicRead ( Tx_MmForum_Domain_Model_Forum_Topic $topic ) {
		$currentUser =& $this->getCurrentUser();
		If($currentUser === NULL) Return;
		Else {	$currentUser->addReadObject($topic);
				$this->frontendUserRepository->update($currentUser); }
	}

}
?>