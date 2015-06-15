<?php
namespace Mittwald\Typo3Forum\Controller;
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
 * @package    Typo3Forum
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
class PostController extends \Mittwald\Typo3Forum\Controller\AbstractController {


	/*
	 * ATTRIBUTES
	 */


	/**
	 * A forum repository.
	 * @var Tx_Typo3Forum_Domain_Repository_Forum_ForumRepository
	 */
	protected $forumRepository;


	/**
	 * A topic repository.
	 * @var Tx_Typo3Forum_Domain_Repository_Forum_TopicRepository
	 */
	protected $topicRepository;


	/**
	 * A post repository.
	 * @var Tx_Typo3Forum_Domain_Repository_Forum_PostRepository
	 */
	protected $postRepository;


	/**
	 * A post factory.
	 * @var \Mittwald\Typo3Forum\Domain\Factory\Forum\PostFactory
	 */
	protected $postFactory;


	/**
	 * A post factory.
	 * @var Tx_Typo3Forum_Domain_Repository_Forum_AttachmentRepository
	 */
	protected $attachmentRepository;

	/**
	 * @var Tx_Typo3Forum_Service_AttachmentService
	 */
	protected $attachmentService = NULL;



	/*
	 * DEPENDENCY INJECTORS
	 */


	/**
	 * Constructor. Used primarily for dependency injection.
	 *
	 * @param Tx_Typo3Forum_Domain_Repository_Forum_ForumRepository $forumRepository
	 * @param Tx_Typo3Forum_Domain_Repository_Forum_TopicRepository $topicRepository
	 * @param Tx_Typo3Forum_Domain_Repository_Forum_PostRepository $postRepository
	 * @param \Mittwald\Typo3Forum\Domain\Factory\Forum\PostFactory $postFactory
	 * @param Tx_Typo3Forum_Domain_Repository_Forum_AttachmentRepository $attachmentRepository
	 * @param Tx_Typo3Forum_Service_SessionHandlingService $sessionHandling
	 * @param Tx_Typo3Forum_Service_AttachmentService $attachmentService
	 */
	public function __construct(Tx_Typo3Forum_Domain_Repository_Forum_ForumRepository $forumRepository,
								Tx_Typo3Forum_Domain_Repository_Forum_TopicRepository $topicRepository,
								Tx_Typo3Forum_Domain_Repository_Forum_PostRepository $postRepository,
								\Mittwald\Typo3Forum\Domain\Factory\Forum\PostFactory $postFactory,
								Tx_Typo3Forum_Domain_Repository_Forum_AttachmentRepository $attachmentRepository,
								Tx_Typo3Forum_Service_SessionHandlingService $sessionHandling,
								Tx_Typo3Forum_Service_AttachmentService $attachmentService) {
		$this->forumRepository		= $forumRepository;
		$this->topicRepository		= $topicRepository;
		$this->postRepository		= $postRepository;
		$this->postFactory			= $postFactory;
		$this->attachmentRepository	= $attachmentRepository;
		$this->sessionHandling		= $sessionHandling;
		$this->attachmentService	= $attachmentService;
	}

	/*
	 * ACTION METHODS
	 */

	/**
	 *  Listing Action.
	 * @return void
	 */
	public function listAction() {

		$showPaginate = FALSE;
		switch($this->settings['listPosts']){
			case '2':
				$dataset = $this->postRepository->findByFilter(intval($this->settings['widgets']['newestPosts']['limit']), array('crdate' => 'DESC'));
				$partial = 'Post/LatestBox';
				break;
			default:
				$dataset = $this->postRepository->findByFilter();
				$partial = 'Post/List';
				$showPaginate = TRUE;
				break;
		}
		$this->view->assign('showPaginate', $showPaginate);
		$this->view->assign('partial', $partial);
		$this->view->assign('posts',$dataset);
	}

	/**
	 * add Supporter Action.
	 *
	 * @param \Mittwald\Typo3Forum\Domain\Model\Forum\Post $post
	 * @return void
	 */
	public function addSupporterAction(\Mittwald\Typo3Forum\Domain\Model\Forum\Post $post) {
		// Assert authentication

		/**
		 * @var \Mittwald\Typo3Forum\Domain\Model\User\FrontendUser
		 */
		$currentUser = $this->authenticationService->getUser();

		// Return if User not logged in or user is post author or user has already supported the post
		if ($currentUser === NULL || $currentUser->isAnonymous() || $currentUser === $post->getAuthor() || $post->hasBeenSupportedByUser($currentUser) || $post->getAuthor()->isAnonymous()) {
			return json_encode(array('error' => TRUE, 'error_msg' => 'not_allowed'));
		}

		// Set helpfulCount for Author
		$post->addSupporter($currentUser);
		$this->postRepository->update($post);

		$post->getAuthor()->setHelpfulCount($post->getAuthor()->getHelpfulCount() + 1);
		$post->getAuthor()->increasePoints(intval($this->settings['rankScore']['gotHelpful']));
		$this->frontendUserRepository->update($post->getAuthor());

		$currentUser->increasePoints(intval($this->settings['rankScore']['markHelpful']));
		$this->frontendUserRepository->update($currentUser);

		// output new Data
		return json_encode(array("error" => FALSE, "add" => 0, "postHelpfulCount" => $post->getHelpfulCount(), "userHelpfulCount" => $post->getAuthor()->getHelpfulCount()));
	}

	/**
	 *  remove Supporter Action.
	 * @param \Mittwald\Typo3Forum\Domain\Model\Forum\Post $post
	 * @return void
	 */
	public function removeSupporterAction(\Mittwald\Typo3Forum\Domain\Model\Forum\Post $post) {
		// Assert authentication
		$currentUser = 	$this->authenticationService->getUser();

		if(!$post->hasBeenSupportedByUser($currentUser)) {
			return json_encode(array("error" => true, "error_msg" => "not_allowed"));
		}

		// Set helpfulCount for Author
		$post->removeSupporter($currentUser);
		$this->postRepository->update($post);

		$post->getAuthor()->setHelpfulCount($post->getAuthor()->getHelpfulCount()-1);
		$post->getAuthor()->decreasePoints(intval($this->settings['rankScore']['gotHelpful']));
		$this->frontendUserRepository->update($post->getAuthor());
		$currentUser->decreasePoints(intval($this->settings['rankScore']['markHelpful']));
		$this->frontendUserRepository->update($currentUser);

		// output new Data
		return json_encode(array("error" => false, "add" => 1, "postHelpfulCount" => $post->getHelpfulCount(), "userHelpfulCount" => $post->getAuthor()->getHelpfulCount()));

	}

	/**
	 * Show action for a single post. The method simply redirects the user to the
	 * topic that contains the requested post.
	 * This function is called by post summaries (last post link)
	 *
	 * @param \Mittwald\Typo3Forum\Domain\Model\Forum\Post $post The post
	 * @param \Mittwald\Typo3Forum\Domain\Model\Forum\Post $quote The Quote
	 * @param int $showForm ShowForm
	 * @return void
	 */
	public function showAction(\Mittwald\Typo3Forum\Domain\Model\Forum\Post $post, \Mittwald\Typo3Forum\Domain\Model\Forum\Post $quote = NULL, $showForm = 0) {
		// Assert authentication
		$this->authenticationService->assertReadAuthorization($post);


		$redirectArguments = array('topic' => $post->getTopic(), 'showForm' => $showForm);

		if(!empty($quote)){
			$redirectArguments['quote'] =  $quote;
		}
		$pageNumber = $post->getTopic()->getPageCount();
		if($pageNumber > 1) {
			$redirectArguments['@widget_0'] = array('currentPage' => $pageNumber);
		}

		// Redirect to the topic->show action.
		$this->redirect('show', 'Topic', NULL, $redirectArguments);
	}


	/**
	 * Displays the form for creating a new post.
	 *
	 * @dontvalidate $post
	 *
	 * @param  \Mittwald\Typo3Forum\Domain\Model\Forum\Topic $topic The topic in which the new post is to be created.
	 * @param  \Mittwald\Typo3Forum\Domain\Model\Forum\Post $post  The new post.
	 * @param  \Mittwald\Typo3Forum\Domain\Model\Forum\Post $quote An optional post that will be quoted within the
	 *                                                    bodytext of the new post.
	 * @return void
	 */
	public function newAction(\Mittwald\Typo3Forum\Domain\Model\Forum\Topic $topic,
							  \Mittwald\Typo3Forum\Domain\Model\Forum\Post $post = NULL,
							  \Mittwald\Typo3Forum\Domain\Model\Forum\Post $quote = NULL) {
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

//	/**
//	 * initializeCreateAction
//	 *
//	 * manipulate attachments
//	 */
//	public function initializeCreateAction() {
//		$this->request->setArgument('attachments', $this->attachmentService->initAttachments($this->request->getArgument('attachments')));
//		$this->mapRequestArgumentsToControllerArguments();
//
//	}

	/**
	 * Creates a new post.
	 *
	 * @param \Mittwald\Typo3Forum\Domain\Model\Forum\Topic $topic The topic in which the new post is to be created.
	 * @param \Mittwald\Typo3Forum\Domain\Model\Forum\Post $post  The new post.
	 * @param array $attachments File attachments for the post.
	 *
	 * @validate $post Tx_Typo3Forum_Domain_Validator_Forum_PostValidator
	 * @validate $attachments Tx_Typo3Forum_Domain_Validator_Forum_AttachmentPlainValidator
	 */

	public function createAction(\Mittwald\Typo3Forum\Domain\Model\Forum\Topic $topic, \Mittwald\Typo3Forum\Domain\Model\Forum\Post $post, array $attachments = array()) {
		// Assert authorization
		$this->authenticationService->assertNewPostAuthorization($topic);

		// Create new post, add the new post to the topic and persist the topic.
		$this->postFactory->assignUserToPost($post);

		if(!empty($attachments)) {
			$attachments = $this->attachmentService->initAttachments($attachments);
			$post->setAttachments($attachments);
		}

		$topic->addPost($post);
		$this->topicRepository->update($topic);

		// All potential listeners (Signal-Slot FTW!)
		$this->signalSlotDispatcher->dispatch('\Mittwald\Typo3Forum\Domain\Model\Forum\Post', 'postCreated',
			array('post' => $post));

		// Display flash message and redirect to topic->show action.
		$this->controllerContext->getFlashMessageQueue()->addMessage(
			new \TYPO3\CMS\Core\Messaging\FlashMessage(
				Tx_Typo3Forum_Utility_Localization::translate('Post_Create_Success')
			)
		);
		$this->clearCacheForCurrentPage();

		$redirectArguments = array('topic' => $topic, 'forum' => $topic->getForum());
		$pageNumber = $topic->getPageCount();
		if($pageNumber > 1) {
			$redirectArguments['@widget_0'] = array('currentPage' => $pageNumber);
		}
		$this->redirect('show', 'Topic', NULL, $redirectArguments);
	}


	/**
	 * Displays a form for editing a post.
	 *
	 * @dontvalidate $post
	 * @param \Mittwald\Typo3Forum\Domain\Model\Forum\Post $post The post that is to be edited.
	 * @return void
	 */
	public function editAction(\Mittwald\Typo3Forum\Domain\Model\Forum\Post $post) {
		if($post->getAuthor() != $this->authenticationService->getUser() or $post->getTopic()->getLastPost()->getAuthor() != $post->getAuthor()){
			// Assert authorization
			$this->authenticationService->assertModerationAuthorization($post->getTopic()->getForum());
		}
		$this->view->assign('post', $post);
	}

	/**
	 * Delete a Attachment.
	 *
	 * @param \Mittwald\Typo3Forum\Domain\Model\Forum\Attachment $attachment The attachment that is to be deleted
	 * @param string $redirect
	 * @return void
	 */
	public function deletePostAttachmentAction(\Mittwald\Typo3Forum\Domain\Model\Forum\Attachment $attachment, $redirect = false) {
		if($attachment->getPost()->getAuthor() != $this->authenticationService->getUser() or
			$attachment->getPost()->getTopic()->getLastPost()->getAuthor() != $attachment->getPost()->getAuthor()){
			// Assert authorization
			$this->authenticationService->assertModerationAuthorization($attachment->getPost()->getTopic()->getForum());
		}
		$attachment->getPost()->removeAttachment($attachment);
		$this->postRepository->update($attachment->getPost());
		if($redirect){
			$this->redirect('show', 'Post', NULL, array('post' => $attachment->getPost()));
		}else{
			$this->redirect('edit', 'Post', NULL, array('post' => $attachment->getPost()));
		}
	}

	/**
	 * Updates a post.
	 *
	 * @param \Mittwald\Typo3Forum\Domain\Model\Forum\Post $post The post that is to be updated.
	 * @param array $attachments File attachments for the post.
	 *
	 * @return void
	 */
	public function updateAction(\Mittwald\Typo3Forum\Domain\Model\Forum\Post $post, array $attachments = array()) {
		if($post->getAuthor() != $this->authenticationService->getUser() or $post->getTopic()->getLastPost()->getAuthor() != $post->getAuthor()){
			// Assert authorization
			$this->authenticationService->assertModerationAuthorization($post->getTopic()->getForum());
		}
		if(!empty($attachments)) {
			$attachments = $this->attachmentService->initAttachments($attachments);
			foreach($attachments as $attachment){
				$post->addAttachments($attachment);
			}
		}
		$this->postRepository->update($post);

		$this->signalSlotDispatcher->dispatch('\Mittwald\Typo3Forum\Domain\Model\Forum\Post', 'postUpdated',
			array('post' => $post));
		$this->controllerContext->getFlashMessageQueue()->addMessage(
			new \TYPO3\CMS\Core\Messaging\FlashMessage(
				Tx_Typo3Forum_Utility_Localization::translate('Post_Update_Success')
			)
		);
		$this->clearCacheForCurrentPage();
		$this->redirect('show', 'Topic', NULL, array('topic' => $post->getTopic()));
	}


	/**
	 * Displays a confirmation screen in which the user is prompted if a post
	 * should really be deleted.
	 *
	 * @param \Mittwald\Typo3Forum\Domain\Model\Forum\Post $post The post that is to be deleted.
	 * @return void
	 */
	public function confirmDeleteAction(\Mittwald\Typo3Forum\Domain\Model\Forum\Post $post) {
		$this->authenticationService->assertDeletePostAuthorization($post);
		$this->view->assign('post', $post);
	}

	/**
	 * Deletes a post.
	 *
	 * @param \Mittwald\Typo3Forum\Domain\Model\Forum\Post $post The post that is to be deleted.
	 * @return void
	 */
	public function deleteAction(\Mittwald\Typo3Forum\Domain\Model\Forum\Post $post) {
		// Assert authorization
		$this->authenticationService->assertDeletePostAuthorization($post);

		// Delete the post.
		$postCount = $post->getTopic()->getPostCount();
		$this->postFactory->deletePost($post);
		$this->controllerContext->getFlashMessageQueue()->addMessage(
			new \TYPO3\CMS\Core\Messaging\FlashMessage(
				Tx_Typo3Forum_Utility_Localization::translate('Post_Delete_Success')
			)
		);

		// Notify observers and clear cache.
		$this->signalSlotDispatcher->dispatch('\Mittwald\Typo3Forum\Domain\Model\Forum\Post', 'postDeleted',
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


	/**
	 * Downloads a attachment and increase the download counter
	 * @param int Uid of Attachment
	 */
	public function downloadAttachmentAction($attachment) {
		$file = $this->attachmentRepository->findByUid(intval($attachment));
		$file->increaseDownloadCount();
		$this->attachmentRepository->update($file);

		header('Content-type: '.$file->getMimeType());
		header("Content-Type: application/download");
		header('Content-Disposition: attachment; filename="'.$file->getFilename().'"');
		readfile($file->getAbsoluteFilename());
	}


}
