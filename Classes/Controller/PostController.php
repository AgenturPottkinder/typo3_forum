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
 * @copyright  2012 Martin Helmich <m.helmich@mittwald.de>
 *             Mittwald CM Service GmbH & Co. KG
 *             http://www.mittwald.de
 * @license    GNU public License, version 2
 *             http://opensource.org/licenses/gpl-license.php
 *
 */
class Tx_MmForum_Controller_PostController extends Tx_MmForum_Controller_AbstractController {


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


	/*
	 * DEPENDENCY INJECTORS
	 */


	/**
	 * Constructor. Used primarily for dependency injection.
	 *
	 * @param Tx_MmForum_Domain_Repository_Forum_ForumRepository $forumRepository
	 * @param Tx_MmForum_Domain_Repository_Forum_TopicRepository $topicRepository
	 * @param Tx_MmForum_Domain_Repository_Forum_PostRepository $postRepository
	 * @param Tx_MmForum_Domain_Factory_Forum_PostFactory $postFactory
	 */
	public function __construct(Tx_MmForum_Domain_Repository_Forum_ForumRepository $forumRepository,
								Tx_MmForum_Domain_Repository_Forum_TopicRepository $topicRepository,
								Tx_MmForum_Domain_Repository_Forum_PostRepository $postRepository,
								Tx_MmForum_Domain_Factory_Forum_PostFactory $postFactory) {
		$this->forumRepository = $forumRepository;
		$this->topicRepository = $topicRepository;
		$this->postRepository = $postRepository;
		$this->postFactory = $postFactory;
	}


	/*
	 * ACTION METHODS
	 */


	/**
	 * Show action for a single post. The method simply redirects the user to the
	 * topic that contains the requested post.
	 *
	 * @param Tx_MmForum_Domain_Model_Forum_Post $post The post
	 * @return void
	 */
	public function showAction(Tx_MmForum_Domain_Model_Forum_Post $post) {
		// Assert authentication
		$this->authenticationService->assertReadAuthorization($post);

		// Determine the page number of the requested post.
		$posts = $post->getTopic()->getPosts();
		$postCount = count($posts);
		for ($postNumber = 0; $postNumber < $postCount; $postNumber++) {
			if ($posts[$postNumber] == $post) {
				break;
			}
		}

		$itemsPerPage = (int)$this->settings['topicController']['show']['pagebrowser']['itemsPerPage'];
		$pageNumber = ceil($postNumber / $itemsPerPage);

		// Redirect to the topic->show action.
		$this->redirect('show', 'Topic', NULL, array('topic' => $post->getTopic(),
			'page' => $pageNumber));
	}


	/**
	 * Displays the form for creating a new post.
	 *
	 * @dontvalidate $post
	 *
	 * @param  Tx_MmForum_Domain_Model_Forum_Topic $topic The topic in which the new post is to be created.
	 * @param  Tx_MmForum_Domain_Model_Forum_Post $post  The new post.
	 * @param  Tx_MmForum_Domain_Model_Forum_Post $quote An optional post that will be quoted within the
	 *                                                    bodytext of the new post.
	 * @return void
	 */
	public function newAction(Tx_MmForum_Domain_Model_Forum_Topic $topic,
							  Tx_MmForum_Domain_Model_Forum_Post $post = NULL,
							  Tx_MmForum_Domain_Model_Forum_Post $quote = NULL) {
		// Assert authorization
		$this->authenticationService->assertNewPostAuthorization($topic);

		// If no post is specified, create an optionally pre-filled post (if a
		// quoted post was specified).
		if ($post === NULL) {
			$post = ($quote !== NULL) ? $this->postFactory->createPostWithQuote($quote) : $this->postFactory->createEmptyPost();
		}

		// Display view
		$this->view->assign('topic', $topic)->assign('post', $post)
			->assign('currentUser', $this->frontendUserRepository->findCurrent());
	}


	/**
	 * Creates a new post.
	 * TODO: Needs to be able to handle attachments. But we will not implement this until Extbase itself has a decent file upload handling!
	 *
	 * @param Tx_MmForum_Domain_Model_Forum_Topic $topic The topic in which the new post is to be created.
	 * @param Tx_MmForum_Domain_Model_Forum_Post $post  The new post.
	 * @return void
	 */
	public function createAction(Tx_MmForum_Domain_Model_Forum_Topic $topic, Tx_MmForum_Domain_Model_Forum_Post $post) {
		// Assert authorization
		$this->authenticationService->assertNewPostAuthorization($topic);

		// Create new post, add the new post to the topic and persist the topic.
		$this->postFactory->assignUserToPost($post);
		$topic->addPost($post);
		$this->topicRepository->update($topic);

		// All potential listeners (Signal-Slot FTW!)
		$this->signalSlotDispatcher->dispatch('Tx_MmForum_Domain_Model_Forum_Post', 'postCreated',
			array('post' => $post));

		// Display flash message and redirect to topic->show action.
		$this->controllerContext->getFlashMessageQueue()->addMessage(
			new \TYPO3\CMS\Core\Messaging\FlashMessage(
				Tx_MmForum_Utility_Localization::translate('Post_Create_Success')
			)
		);
		$this->clearCacheForCurrentPage();
		$this->redirect('show', 'Topic', NULL, array('topic' => $topic));
	}


	/**
	 * Displays a form for editing a post.
	 *
	 * @dontvalidate $post
	 * @param Tx_MmForum_Domain_Model_Forum_Post $post The post that is to be edited.
	 * @return void
	 */
	public function editAction(Tx_MmForum_Domain_Model_Forum_Post $post) {
		$this->authenticationService->assertEditPostAuthorization($post);
		$this->view->assign('post', $post);
	}


	/**
	 * Updates a post.
	 *
	 * @param Tx_MmForum_Domain_Model_Forum_Post $post The post that is to be updated.
	 * @return void
	 */
	public function updateAction(Tx_MmForum_Domain_Model_Forum_Post $post) {
		$this->authenticationService->assertEditPostAuthorization($post);
		$this->postRepository->update($post);

		$this->signalSlotDispatcher->dispatch('Tx_MmForum_Domain_Model_Forum_Post', 'postUpdated',
			array('post' => $post));
		$this->controllerContext->getFlashMessageQueue()->addMessage(
			new \TYPO3\CMS\Core\Messaging\FlashMessage(
				Tx_MmForum_Utility_Localization::translate('Post_Update_Success')
			)
		);
		$this->clearCacheForCurrentPage();
		$this->redirect('show', 'Topic', NULL, array('topic' => $post->getTopic()));
	}


	/**
	 * Displays a confirmation screen in which the user is prompted if a post
	 * should really be deleted.
	 *
	 * @param Tx_MmForum_Domain_Model_Forum_Post $post The post that is to be deleted.
	 * @return void
	 */
	public function confirmDeleteAction(Tx_MmForum_Domain_Model_Forum_Post $post) {
		$this->authenticationService->assertDeletePostAuthorization($post);
		$this->view->assign('post', $post);
	}


	/**
	 * Deletes a post.
	 *
	 * @param Tx_MmForum_Domain_Model_Forum_Post $post The post that is to be deleted.
	 * @return void
	 */
	public function deleteAction(Tx_MmForum_Domain_Model_Forum_Post $post) {
		// Assert authorization
		$this->authenticationService->assertDeletePostAuthorization($post);

		// Delete the post.
		$postCount = $post->getTopic()->getPostCount();
		$this->postFactory->deletePost($post);
		$this->controllerContext->getFlashMessageQueue()->addMessage(
			new \TYPO3\CMS\Core\Messaging\FlashMessage(
				Tx_MmForum_Utility_Localization::translate('Post_Delete_Success')
			)
		);

		// Notify observers and clear cache.
		$this->signalSlotDispatcher->dispatch('Tx_MmForum_Domain_Model_Forum_Post', 'postDeleted',
			array('post' => $post));
		$this->clearCacheForCurrentPage();

		// If there is still on post left in the topic, redirect to the topic
		// view. If we have deleted the last post of a topic (i.e. the topic
		// itself), redirect to the forum view instead.
		if ($postCount > 1) {
			$this->redirect('show', 'Topic', NULL, array('topic' => $post->getTopic()));
		} else {
			$this->redirect('show', 'Forum', NULL, array('forum' => $post->getForum()));
		}
	}


	/**
	 * Displays a preview of a rendered post text.
	 * @param string $text The content.
	 */
	public function previewAction($text) {
		$this->view->assign('text', $text);
	}


}
