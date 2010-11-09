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
	 *
	 * Controller for the post object. This class implements all post-related functions,
	 * like displaying, creating or editing posts.
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

Class Tx_MmForum_Controller_PostController Extends Tx_MmForum_Controller_AbstractController {





		/*
		 * ATTRIBUTES
		 */





		/**
		 * A forum repository.
		 * @var Tx_MmForum_Domain_Repository_Forum_ForumRepository
		 */
	Protected $forumRepository;

		/**
		 * A topic repository.
		 * @var Tx_MmForum_Domain_Repository_Forum_TopicRepository
		 */
	Protected $topicRepository;

		/**
		 * A post repository.
		 * @var Tx_MmForum_Domain_Repository_Forum_PostRepository
		 */
	Protected $postRepository;

		/**
		 * A post factory.
		 * @var Tx_MmForum_Domain_Factory_Forum_PostFactory
		 */
	Protected $postFactory;

		/**
		 * A notification service. This is used when topic subscribers have to be
		 * notified about new posts.
		 * @var Tx_MmForum_Service_NotificationService
		 */
	Protected $notificationService;





		/*
		 * INITIALIZATION METHODS
		 */





		/**
		 *
		 * Initializes the current action.
		 * @return void
		 *
		 */

	Protected Function initializeAction() {
		parent::initializeAction();
		$this->forumRepository =&
			t3lib_div::makeInstance('Tx_MmForum_Domain_Repository_Forum_ForumRepository');
		$this->postFactory =&
			t3lib_div::makeInstance('Tx_MmForum_Domain_Factory_Forum_PostFactory');
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
		$this->notificationService =&
			t3lib_div::makeInstance('Tx_MmForum_Service_NotificationService');
		$this->notificationService->injectControllerContext($this->buildControllerContext());
		$this->notificationService->injectMailingService($this->buildMailingService());
	}



		/**
		 *
		 * Initializes the update action.
		 * @return void
		 *
		 */

	Protected Function initializeUpdateAction() {
		$this->postRepository =&
			t3lib_div::makeInstance('Tx_MmForum_Domain_Repository_Forum_PostRepository');
	}





		/*
		 * ACTION METHODS
		 */


		/**
		 *
		 * Show action for a single post. The method simply redirects the user to the
		 * topic that contains the requested post.
		 *
		 * @param Tx_MmForum_Domain_Model_Forum_Post $post The post
		 * @return void
		 *
		 */
	Public Function showAction ( Tx_MmForum_Domain_Model_Forum_Post $post ) {
			# Assert authentication
		$this->authenticationService->assertReadAuthorization($post);

			# Determine the page number of the requested post.
		$postNumber =  0;
		$posts      =& $post->getTopic()->getPosts();
		$postCount  =  count($posts);
		For($postNumber = 0; $postNumber < $postCount; $postNumber ++)
			If($posts[$postNumber] == $post) Break;

		$itemsPerPage = (int)$this->settings['topicController']['show']['pagebrowser']['itemsPerPage'];
		$pageNumber   = ceil($postNumber / $itemsPerPage);

			# Redirect to the topic->show action.
		$this->redirect('show', 'Topic', NULL, Array('topic' => $post->getTopic(), 'page' => $pageNumber));
	}




		/**
		 *
		 * Displays the form for creating a new post.
		 *
		 * @param Tx_MmForum_Domain_Model_Forum_Topic $topic
		 *                             The topic in which the new post is to be created.
		 * @param Tx_MmForum_Domain_Model_Forum_Post $post
		 *                             The new post.
		 * @param Tx_MmForum_Domain_Model_Forum_Post $quote
		 *                             An optional post that will be quoted within the
		 *                             bodytext of the new post.
		 * @dontvalidate $post
		 *
		 */

	Public Function newAction ( Tx_MmForum_Domain_Model_Forum_Topic $topic,
	                            Tx_MmForum_Domain_Model_Forum_Post  $post  = NULL,
								Tx_MmForum_Domain_Model_Forum_Post  $quote = NULL ) {

			# Assert authorization
		$this->authenticationService->assertNewPostAuthorization($topic);

			# If no post is specified, create an optionally pre-filled post (if a quoted
			# post was specified).
		If($post === NULL)
			$post = ($quote !== NULL) ? $this->postFactory->createPostWithQuote($quote) : $this->postFactory->createEmptyPost();

			# Display view
		$this->view->assign('topic', $topic)
		           ->assign('post',  $post);
	}



		/**
		 *
		 * Creates a new post.
		 *
		 * TODO: Needs to be able to handle attachments. But we will not implement this
		 *       until Extbase itself has a decent file upload handling!
		 *
		 * @param Tx_MmForum_Domain_Model_Forum_Topic $topic
		 *                             The topic in which the new post is to be created.
		 * @param Tx_MmForum_Domain_Model_Forum_Post $post
		 *                             The new post.
		 * @return void
		 *
		 */

	Public Function createAction ( Tx_MmForum_Domain_Model_Forum_Topic $topic,
	                               Tx_MmForum_Domain_Model_Forum_Post $post ) {

			# Assert authorization
		$this->authenticationService->assertNewPostAuthorization($topic);

			# Create new post, add the new post to the topic and persist the topic.
		$this->postFactory->assignUserToPost($post);
		$topic->addPost($post);
		$this->topicRepository->update($topic);

			# Notify topic and forum subscribers about the new post.
		$this->notificationService->notifySubscribers($topic, $post);

			# Display flash message and redirect to topic->show action.
		$this->flashMessages->add(Tx_MmForum_Utility_Localization::translate('Post_Create_Success'));
		$this->redirect('show', 'Topic', NULL, Array('topic' => $topic));
	}



		/**
		 *
		 * Displays a form for editing a post.
		 *
		 * @param Tx_MmForum_Domain_Model_Forum_Post $post
		 *                             The post that is to be edited.
		 * @return void
		 * @dontvalidate $post
		 * 
		 */

	Public Function editAction(Tx_MmForum_Domain_Model_Forum_Post $post) {
		$this->authenticationService->assertEditPostAuthorization($post);
		$this->view->assign('post', $post);
	}



		/**
		 *
		 * Updates a post.
		 *
		 * @param Tx_MmForum_Domain_Model_Forum_Post $post
		 *                             The post that is to be updated.
		 * @return void
		 * 
		 */
	
	Public Function updateAction(Tx_MmForum_Domain_Model_Forum_Post $post) {
		$this->authenticationService->assertEditPostAuthorization($post);
		$this->postRepository->update($post);
		$this->flashMessages->add(Tx_MmForum_Utility_Localization::translate('Post_Update_Success'));
		$this->redirect('show', 'Topic', NULL, array('topic' => $post->getTopic()));
	}



		/**
		 *
		 * Delets a post.
		 * @param Tx_MmForum_Domain_Model_Forum_Post $post
		 *                             The post that is to be deleted.
		 *
		 */
	
	Public Function deleteAction(Tx_MmForum_Domain_Model_Forum_Post $post) {
		$this->authenticationService->assertDeletePostAuthorization($post);
		$this->postFactory->deletePost($post);
		$this->flashMessages->add(Tx_MmForum_Utility_Localization::translate('Post_Delete_Success'));
		$this->redirect('show', 'Topic', NULL, array('topic' => $post->getTopic()));
	}

}
?>