<?php
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

class Tx_MmForum_Controller_ModerationController extends Tx_MmForum_Controller_AbstractController {


	/**
	 * The topic repository.
	 *
	 * @var Tx_MmForum_Domain_Repository_Forum_ForumRepository
	 */
	protected $forumRepository;

	/**
	 * The topic repository.
	 *
	 * @var Tx_MmForum_Domain_Repository_Forum_TopicRepository
	 */
	protected $topicRepository;

	/**
	 * The topic repository.
	 *
	 * @var Tx_MmForum_Domain_Repository_Forum_PostRepository
	 */
	protected $postRepository;

	/**
	 * @var Tx_MmForum_Domain_Repository_Moderation_UserReportRepository
	 */
	protected $userReportRepository = NULL;

	/**
	 * @var Tx_MmForum_Domain_Repository_Moderation_PostReportRepository
	 */
	protected $postReportRepository = NULL;

	/**
	 * @var Tx_MmForum_Domain_Repository_Moderation_ReportRepository
	 */
	protected $reportRepository = NULL;

	/**
	 * @var \TYPO3\CMS\Extbase\Persistence\PersistenceManagerInterface
	 */
	protected $persistenceManager;


	/**
	 * @var Tx_MmForum_Domain_Factory_Forum_TopicFactory
	 */
	protected $topicFactory;


	/**
	 * @param \TYPO3\CMS\Extbase\Persistence\PersistenceManagerInterface $persistenceManager
	 * @return void
	 */
	public function injectPersistenceManager(\TYPO3\CMS\Extbase\Persistence\PersistenceManagerInterface $persistenceManager) {
		$this->persistenceManager = $persistenceManager;
	}

	/**
	 * Injects an instance of the report repository.
	 *
	 * @param  Tx_MmForum_Domain_Repository_Moderation_UserReportRepository $userReportRepository
	 *
	 * @return void
	 */
	public function injectUserReportRepository(
		Tx_MmForum_Domain_Repository_Moderation_UserReportRepository $userReportRepository) {
		$this->userReportRepository = $userReportRepository;
	}


	/**
	 * Injects an instance of the topic repository.
	 *
	 * @param  Tx_MmForum_Domain_Repository_Forum_ForumRepository $forumRepository
	 *
	 * @return void
	 */
	public function injectForumRepository(Tx_MmForum_Domain_Repository_Forum_ForumRepository $forumRepository) {
		$this->forumRepository = $forumRepository;
	}


	/**
	 * Injects an instance of the topic repository.
	 *
	 * @param  Tx_MmForum_Domain_Repository_Forum_TopicRepository $topicRepository
	 *
	 * @return void
	 */
	public function injectTopicRepository(Tx_MmForum_Domain_Repository_Forum_TopicRepository $topicRepository) {
		$this->topicRepository = $topicRepository;
	}

	/**
	 * Injects an instance of the topic repository.
	 *
	 * @param  Tx_MmForum_Domain_Repository_Forum_PostRepository $postRepository
	 *
	 * @return void
	 */
	public function injectPostRepository(Tx_MmForum_Domain_Repository_Forum_PostRepository $postRepository) {
		$this->postRepository = $postRepository;
	}

	/**
	 * Injects an instance of the report repository.
	 *
	 * @param  Tx_MmForum_Domain_Repository_Moderation_ReportRepository $reportRepository
	 *
	 * @return void
	 */
	public function injectReportRepository(Tx_MmForum_Domain_Repository_Moderation_ReportRepository $reportRepository) {
		$this->reportRepository = $reportRepository;
	}

	/**
	 * Injects an instance of the report repository.
	 *
	 * @param  Tx_MmForum_Domain_Repository_Moderation_PostReportRepository $postReportRepository
	 *
	 * @return void
	 */
	public function injectPostReportRepository(
		Tx_MmForum_Domain_Repository_Moderation_PostReportRepository $postReportRepository) {
		$this->postReportRepository = $postReportRepository;
	}


	/**
	 * Injects an instance of the topic factory.
	 *
	 * @param Tx_MmForum_Domain_Factory_Forum_TopicFactory $topicFactory
	 *
	 * @return void
	 */
	public function injectTopicFactory(Tx_MmForum_Domain_Factory_Forum_TopicFactory $topicFactory) {
		$this->topicFactory = $topicFactory;
	}


	/**
	 * @return void
	 */
	public function indexReportAction() {
		$this->view->assign('postReports', $this->postReportRepository->findAll());
		$this->view->assign('userReports', $this->userReportRepository->findAll());
	}

	/**
	 * @param  Tx_MmForum_Domain_Model_Moderation_UserReport $userReport
	 * @param  Tx_MmForum_Domain_Model_Moderation_PostReport $postReport
	 * @return void
	 */
	public function editReportAction(Tx_MmForum_Domain_Model_Moderation_UserReport $userReport = NULL,
									 Tx_MmForum_Domain_Model_Moderation_PostReport $postReport = NULL) {
		// Validate arguments
		if ($userReport === NULL && $postReport === NULL) {
			throw new \TYPO3\CMS\Extbase\Mvc\Exception\InvalidArgumentValueException("You need to show a user report or post report!", 1285059341);
		}
		if ($postReport) {
			$report = $postReport;
			$type = 'Post';
			$this->authenticationService->assertModerationAuthorization($postReport->getTopic()->getForum());
		} else {
			$type = 'User';
			$report = $userReport;
		}

		$this->view->assign('report', $report)
			->assign('type', $type);
	}

	/**
	 * @param Tx_MmForum_Domain_Model_Moderation_Report $report
	 * @param Tx_MmForum_Domain_Model_Moderation_ReportComment $comment
	 *
	 * @dontvalidate $comment
	 */
	public function newReportCommentAction(Tx_MmForum_Domain_Model_Moderation_Report $report,
										   Tx_MmForum_Domain_Model_Moderation_ReportComment $comment = NULL) {
		$this->view->assignMultiple(array('report' => $report,
			'comment' => $comment));
	}


	/**
	 * @param  Tx_MmForum_Domain_Model_Moderation_UserReport $report
	 * @param Tx_MmForum_Domain_Model_Moderation_ReportComment $comment
	 * @return void
	 */
	public function createUserReportCommentAction(Tx_MmForum_Domain_Model_Moderation_UserReport $report = NULL,
											  Tx_MmForum_Domain_Model_Moderation_ReportComment $comment) {



		// Validate arguments
		if ($report === NULL) {
			throw new \TYPO3\CMS\Extbase\Mvc\Exception\InvalidArgumentValueException("You need to comment a user report!", 1285059341);
		}

		$comment->setAuthor($this->authenticationService->getUser());
		$report->addComment($comment);
		$this->reportRepository->update($report);

		$this->controllerContext->getFlashMessageQueue()->addMessage(
			new \TYPO3\CMS\Core\Messaging\FlashMessage(
				Tx_MmForum_Utility_Localization::translate('Report_NewComment_Success')
			)
		);

		$this->clearCacheForCurrentPage();
		$this->redirect('editReport', NULL, NULL, array('userReport' => $report));

	}
	/**
	 * @param  Tx_MmForum_Domain_Model_Moderation_PostReport $report
	 * @param Tx_MmForum_Domain_Model_Moderation_ReportComment $comment
	 * @return void
	 */
	public function createPostReportCommentAction(Tx_MmForum_Domain_Model_Moderation_PostReport $report = NULL,
												  Tx_MmForum_Domain_Model_Moderation_ReportComment $comment) {

		// Assert authorization
		$this->authenticationService->assertModerationAuthorization($report->getTopic()->getForum());

		// Validate arguments
		if ($report === NULL) {
			throw new \TYPO3\CMS\Extbase\Mvc\Exception\InvalidArgumentValueException("You need to comment a user report!", 1285059341);
		}

		$comment->setAuthor($this->authenticationService->getUser());
		$report->addComment($comment);
		$this->reportRepository->update($report);

		$this->controllerContext->getFlashMessageQueue()->addMessage(
			new \TYPO3\CMS\Core\Messaging\FlashMessage(
				Tx_MmForum_Utility_Localization::translate('Report_NewComment_Success')
			)
		);

		$this->clearCacheForCurrentPage();
		$this->redirect('editReport', NULL, NULL, array('postReport' => $report));

	}
	/**
	 * Sets the workflow status of a report.
	 *
	 * @param Tx_MmForum_Domain_Model_Moderation_UserReport               $userReport   The report for which to set the status.
	 * @param Tx_MmForum_Domain_Model_Moderation_ReportWorkflowStatus $status   The report's new status.
	 * @param string                                                  $redirect Where to redirect after updating the report ('index' or 'show').
	 */
	public function updateUserReportStatusAction(Tx_MmForum_Domain_Model_Moderation_UserReport $report,
											 Tx_MmForum_Domain_Model_Moderation_ReportWorkflowStatus $status,
											 $redirect = 'indexReport') {

		// Set status and update the report. Add a comment to the report that
		// documents the status change.
		$report->setWorkflowStatus($status);
		$comment = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('Tx_MmForum_Domain_Model_Moderation_ReportComment');
		$comment->setAuthor($this->getCurrentUser());
		$comment->setText(Tx_MmForum_Utility_Localization::translate('Report_Edit_SetStatus', 'MmForum',
			array($status->getName())));
		$report->addComment($comment);
		$this->reportRepository->update($report);

		// Add flash message and clear cache.
		$this->addLocalizedFlashmessage('Report_UpdateStatus_Success', array($report->getUid(), $status->getName()));
		$this->clearCacheForCurrentPage();

		if ($redirect === 'show') {
			$this->redirect('editReport', NULL, NULL, array('userReport' => $report));
		}

		$this->redirect('indexReport');
	}

	/**
	 * Sets the workflow status of a report.
	 *
	 * @param Tx_MmForum_Domain_Model_Moderation_PostReport               $postReport   The report for which to set the status.
	 * @param Tx_MmForum_Domain_Model_Moderation_ReportWorkflowStatus $status   The report's new status.
	 * @param string                                                  $redirect Where to redirect after updating the report ('index' or 'show').
	 */
	public function updatePostReportStatusAction(Tx_MmForum_Domain_Model_Moderation_PostReport $report,
											 Tx_MmForum_Domain_Model_Moderation_ReportWorkflowStatus $status,
											 $redirect = 'indexReport') {

		// Assert authorization
		$this->authenticationService->assertModerationAuthorization($report->getTopic()->getForum());

		// Set status and update the report. Add a comment to the report that
		// documents the status change.
		$report->setWorkflowStatus($status);
		$comment = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('Tx_MmForum_Domain_Model_Moderation_ReportComment');
		$comment->setAuthor($this->getCurrentUser());
		$comment->setText(Tx_MmForum_Utility_Localization::translate('Report_Edit_SetStatus', 'MmForum',
			array($status->getName())));
		$report->addComment($comment);
		$this->reportRepository->update($report);

		// Add flash message and clear cache.
		$this->addLocalizedFlashmessage('Report_UpdateStatus_Success', array($report->getUid(), $status->getName()));
		$this->clearCacheForCurrentPage();

		if ($redirect === 'show') {
			$this->redirect('editReport', NULL, NULL, array('postReport' => $report));
		}

		$this->redirect('indexReport');
	}


	/**
	 * Displays a form for editing a topic with special moderator-powers!
	 *
	 * @param  Tx_MmForum_Domain_Model_Forum_Topic $topic The topic that is to be edited.
	 *
	 * @return void
	 */
	public function editTopicAction(Tx_MmForum_Domain_Model_Forum_Topic $topic) {
		$this->authenticationService->assertModerationAuthorization($topic->getForum());
		$this->view->assign('topic', $topic);
	}



	/**
	 * Updates a forum with special super-moderator-powers!
	 *
	 * @param  Tx_MmForum_Domain_Model_Forum_Topic  $topic           The topic that is be edited.
	 * @param  boolean                              $moveTopic       TRUE, if the topic is to be moved to another forum.
	 * @param  Tx_MmForum_Domain_Model_Forum_Forum  $moveTopicTarget The forum to which the topic is to be moved.
	 *
	 * @return void
	 */
	public function updateTopicAction(Tx_MmForum_Domain_Model_Forum_Topic $topic, $moveTopic = FALSE,
									  Tx_MmForum_Domain_Model_Forum_Forum $moveTopicTarget = NULL) {
		$this->authenticationService->assertModerationAuthorization($topic->getForum());
		$this->topicRepository->update($topic);

		if ($moveTopic) {
			$this->topicFactory->moveTopic($topic, $moveTopicTarget);
		}

		$this->controllerContext->getFlashMessageQueue()->addMessage(
			new \TYPO3\CMS\Core\Messaging\FlashMessage(
				Tx_MmForum_Utility_Localization::translate('Moderation_UpdateTopic_Success',
					'MmForum')
			)
		);
		$this->clearCacheForCurrentPage();
		$this->redirect('show', 'Topic', NULL, Array('topic' => $topic));
	}

	/**
	 * Delete a topic from repository!
	 *
	 * @param  Tx_MmForum_Domain_Model_Forum_Topic  $topic           The topic that is be deleted.
	 *
	 * @return void
	 */
	public function topicConformDeleteAction(Tx_MmForum_Domain_Model_Forum_Topic $topic) {
		$this->authenticationService->assertModerationAuthorization($topic->getForum());
		foreach($topic->getPosts() as $post){
			$this->postRepository->remove($post);
		}

		$forum = $topic->getForum();
		$this->topicRepository->remove($topic);

		$this->persistenceManager->persistAll();

		$forum->_resetLastPost();

		$lastPost = $this->postRepository->findLastByForum($forum);
		$forum->setLastPost($lastPost);
		$forum->setLastTopic($lastPost->getTopic());
		$this->forumRepository->update($forum);

		$this->controllerContext->getFlashMessageQueue()->addMessage(
			new \TYPO3\CMS\Core\Messaging\FlashMessage(
				Tx_MmForum_Utility_Localization::translate('Moderation_DeleteTopic_Success',
					'MmForum')
			)
		);
		$this->clearCacheForCurrentPage();

		$this->redirect('show', 'Forum', NULL, Array('forum' => $topic->getForum()));
	}
}