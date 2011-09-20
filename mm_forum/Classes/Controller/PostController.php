<?php

/*                                                                      *
 *  COPYRIGHT NOTICE                                                    *
 *                                                                      *
 *  (c) 2011 Martin Helmich <m.helmich@mittwald.de>                     *
 *           Mittwald CM Service GmbH & Co KG                           *
 *           All rights reserved                                        *
 *                                                                      *
 *  This script is part of the TYPO3 project. The TYPO3 project is      *
 *  free software; you can redistribute it and/or modify                *
 *  it under the terms of the GNU General public License as published   *
 *  by the Free Software Foundation; either version 2 of the License,   *
 *  or (at your option) any later version.                              *
 *                                                                      *
 *  The GNU General public License can be found at                      *
 *  http://www.gnu.org/copyleft/gpl.html.                               *
 *                                                                      *
 *  This script is distributed in the hope that it will be useful,      *
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of      *
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the       *
 *  GNU General public License for more details.                        *
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
	 * @copyright  2011 Martin Helmich <m.helmich@mittwald.de>
	 *             Mittwald CM Service GmbH & Co. KG
	 *             http://www.mittwald.de
	 * @license    GNU public License, version 2
	 *             http://opensource.org/licenses/gpl-license.php
	 */

class Tx_MmForum_Controller_PostController
	extends Tx_MmForum_Controller_AbstractController {





		/*
		 * ATTRIBUTES
		 */





		/**
		 * A forum repository.
		 * @var Tx_MmForum_Domain_Repository_Forum_ForumRepository
		 */
	protected $forumRepository;

		/**
		 * A topic repository.
		 * @var Tx_MmForum_Domain_Repository_Forum_TopicRepository
		 */
	protected $topicRepository;

		/**
		 * A post repository.
		 * @var Tx_MmForum_Domain_Repository_Forum_PostRepository
		 */
	protected $postRepository;

		/**
		 * A post factory.
		 * @var Tx_MmForum_Domain_Factory_Forum_PostFactory
		 */
	protected $postFactory;

		/**
		 * A notification service. This is used when topic subscribers have to be
		 * notified about new posts.
		 * @var Tx_MmForum_Service_Notification_NotificationServiceInterface
		 */
	protected $notificationService;

	
	
	
	
		/*
		 * DEPENDENCY INJECTORS
		 */
	
	
	
	
	
		/**
		 * 
		 * Injects an instance of the forum repository.
		 * @param  Tx_MmForum_Domain_Repository_Forum_ForumRepository $forumRepository
		 *                             An instance of the forum repository
		 * @return void
		 * 
		 */
	
	public function injectForumRepository(Tx_MmForum_Domain_Repository_Forum_ForumRepository $forumRepository) {
		$this->forumRepository = $forumRepository;
	}
	
	
	
		/**
		 * 
		 * Injects an instance of the topic repository.
		 * @param  Tx_MmForum_Domain_Repository_Forum_TopicRepository $topicRepository
		 *                             An instance of the topic repository.
		 * @return void
		 * 
		 */
	
	public function injectTopicRepository(Tx_MmForum_Domain_Repository_Forum_TopicRepository $topicRepository) {
		$this->topicRepository = $topicRepository;
	}
	
	
	
		/**
		 * 
		 * Injects an instance of the post repository.
		 * @param  Tx_MmForum_Domain_Repository_Forum_PostRepository $postRepository
		 *                             An instance of the post repository.
		 * @return void
		 * 
		 */
	
	public function injectPostRepository(Tx_MmForum_Domain_Repository_Forum_PostRepository $postRepository) {
		$this->postRepository = $postRepository;
	}
	
	
	
		/**
		 * 
		 * Injects an instance of the post factory.
		 * @param  Tx_MmForum_Domain_Factory_Forum_PostFactory $postFactory
		 *                             An instance of the post factory.
		 * @return void
		 * 
		 */
	
	public function injectPostFactory(Tx_MmForum_Domain_Factory_Forum_PostFactory $postFactory) {
		$this->postFactory = $postFactory;
	}
	
	
	
		/**
		 * 
		 * Injects an instance of the notification service.
		 * @param  Tx_MmForum_Service_Notification_NotificationServiceInterface $notificationService
		 *                             An instance of the notification service.
		 * @return void
		 * 
		 */
	
	public function injectNotificationService(Tx_MmForum_Service_Notification_NotificationServiceInterface $notificationService) {
		$this->notificationService = $notificationService;
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
	
	public function showAction ( Tx_MmForum_Domain_Model_Forum_Post $post ) {
			# Assert authentication
		$this->authenticationService->assertReadAuthorization($post);

			# Determine the page number of the requested post.
		$postNumber =  0;
		$posts      =& $post->getTopic()->getPosts();
		$postCount  =  count($posts);
		for($postNumber = 0; $postNumber < $postCount; $postNumber ++)
			if($posts[$postNumber] == $post) break;

		$itemsPerPage = (int)$this->settings['topicController']['show']['pagebrowser']['itemsPerPage'];
		$pageNumber   = ceil($postNumber / $itemsPerPage);

			# Redirect to the topic->show action.
		$this->redirect('show', 'Topic', NULL, array('topic' => $post->getTopic(), 'page' => $pageNumber));
	}




		/**
		 *
		 * Displays the form for creating a new post.
		 *
		 * @dontvalidate $post
		 * @param  Tx_MmForum_Domain_Model_Forum_Topic $topic
		 *                             The topic in which the new post is to be created.
		 * @param  Tx_MmForum_Domain_Model_Forum_Post $post
		 *                             The new post.
		 * @param  Tx_MmForum_Domain_Model_Forum_Post $quote
		 *                             An optional post that will be quoted within the
		 *                             bodytext of the new post.
		 * @return void
		 *
		 */

	public function newAction ( Tx_MmForum_Domain_Model_Forum_Topic $topic,
	                            Tx_MmForum_Domain_Model_Forum_Post  $post  = NULL,
								Tx_MmForum_Domain_Model_Forum_Post  $quote = NULL ) {

			# Assert authorization
		$this->authenticationService->assertNewPostAuthorization($topic);

			# If no post is specified, create an optionally pre-filled post (if a quoted
			# post was specified).
		if($post === NULL)
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

	public function createAction ( Tx_MmForum_Domain_Model_Forum_Topic $topic,
	                               Tx_MmForum_Domain_Model_Forum_Post $post ) {

			# Assert authorization
		$this->authenticationService->assertNewPostAuthorization($topic);
		$this->on('beforeCreatePost', array($post));

			# Create new post, add the new post to the topic and persist the topic.
		$this->postFactory->assignUserToPost($post);
		$topic->addPost($post);
		$this->topicRepository->update($topic);

			# Notify topic and forum subscribers about the new post.
		$this->notificationService->notifySubscribers($topic, $post);
		$this->on('createPost', array($post));

			# Display flash message and redirect to topic->show action.
		$this->flashMessages->add(Tx_MmForum_Utility_Localization::translate('Post_Create_Success'));
		$this->redirect('show', 'Topic', NULL, array('topic' => $topic));
	}



		/**
		 *
		 * Displays a form for editing a post.
		 *
		 * @dontvalidate $post
		 * @param Tx_MmForum_Domain_Model_Forum_Post $post
		 *                             The post that is to be edited.
		 * @return void
		 * 
		 */

	public function editAction(Tx_MmForum_Domain_Model_Forum_Post $post) {
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
	
	public function updateAction(Tx_MmForum_Domain_Model_Forum_Post $post) {
		$this->authenticationService->assertEditPostAuthorization($post);
		$this->postRepository->update($post);
		$this->on('updatePost', array($post));
		$this->flashMessages->add(Tx_MmForum_Utility_Localization::translate('Post_Update_Success'));
		$this->redirect('show', 'Topic', NULL, array('topic' => $post->getTopic()));
	}



		/**
		 *
		 * Deletes a post.
		 * @param Tx_MmForum_Domain_Model_Forum_Post $post
		 *                             The post that is to be deleted.
		 * @return void
		 *
		 */
	
	public function deleteAction(Tx_MmForum_Domain_Model_Forum_Post $post) {
		$this->authenticationService->assertDeletePostAuthorization($post);
		
		$postCount = $post->getTopic()->getPostCount();
		$this->postFactory->deletePost($post);
		$this->flashMessages->add(Tx_MmForum_Utility_Localization::translate('Post_Delete_Success'));
		
		$this->on('deletePost', array($post));
		
		if($postCount > 1)
			$this->redirect('show', 'Topic', NULL, array('topic' => $post->getTopic()));
		else $this->redirect('show', 'Forum', NULL, array('forum' => $post->getForum()));
	}
	
	
	
		/**
		 * 
		 * Displays a preview of a rendered post text.
		 * @param string $text The content.
		 * 
		 */
	
	public function previewAction($text) {
		$this->view->assign('text', $text);
	}

}
