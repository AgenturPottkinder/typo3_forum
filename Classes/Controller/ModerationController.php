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

class ModerationController extends AbstractController {


	/**
	 * The topic repository.
	 *
	 * @var \Mittwald\MmForum\Domain\Repository\Forum\ForumRepository
	 */
	protected $forumRepository;

	/**
	 * The topic repository.
	 *
	 * @var \Mittwald\MmForum\Domain\Repository\Forum\TopicRepository
	 */
	protected $topicRepository;

	/**
	 * The topic repository.
	 *
	 * @var \Mittwald\MmForum\Domain\Repository\Forum\PostRepository
	 */
	protected $postRepository;

	/**
	 * @var \Mittwald\MmForum\Domain\Repository\Moderation\UserReportRepository
	 */
	protected $userReportRepository = NULL;

	/**
	 * @var \Mittwald\MmForum\Domain\Repository\Moderation\PostReportRepository
	 */
	protected $postReportRepository = NULL;

	/**
	 * @var \Mittwald\MmForum\Domain\Repository\Moderation\ReportRepository
	 */
	protected $reportRepository = NULL;

	/**
	 * @var \TYPO3\CMS\Extbase\Persistence\PersistenceManagerInterface
	 */
	protected $persistenceManager;


	/**
	 * @var \Mittwald\MmForum\Domain\Factory\Forum\TopicFactory
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
	 * @param  \Mittwald\MmForum\Domain\Repository\Moderation\UserReportRepository $userReportRepository
	 *
	 * @return void
	 */
	public function injectUserReportRepository(
		\Mittwald\MmForum\Domain\Repository\Moderation\UserReportRepository $userReportRepository) {
		$this->userReportRepository = $userReportRepository;
	}


	/**
	 * Injects an instance of the topic repository.
	 *
	 * @param  \Mittwald\MmForum\Domain\Repository\Forum\ForumRepository $forumRepository
	 *
	 * @return void
	 */
	public function injectForumRepository(\Mittwald\MmForum\Domain\Repository\Forum\ForumRepository $forumRepository) {
		$this->forumRepository = $forumRepository;
	}


	/**
	 * Injects an instance of the topic repository.
	 *
	 * @param  \Mittwald\MmForum\Domain\Repository\Forum\TopicRepository $topicRepository
	 *
	 * @return void
	 */
	public function injectTopicRepository(\Mittwald\MmForum\Domain\Repository\Forum\TopicRepository $topicRepository) {
		$this->topicRepository = $topicRepository;
	}

	/**
	 * Injects an instance of the topic repository.
	 *
	 * @param  \Mittwald\MmForum\Domain\Repository\Forum\PostRepository $postRepository
	 *
	 * @return void
	 */
	public function injectPostRepository(\Mittwald\MmForum\Domain\Repository\Forum\PostRepository $postRepository) {
		$this->postRepository = $postRepository;
	}

	/**
	 * Injects an instance of the report repository.
	 *
	 * @param  \Mittwald\MmForum\Domain\Repository\Moderation\ReportRepository $reportRepository
	 *
	 * @return void
	 */
	public function injectReportRepository(\Mittwald\MmForum\Domain\Repository\Moderation\ReportRepository $reportRepository) {
		$this->reportRepository = $reportRepository;
	}

	/**
	 * Injects an instance of the report repository.
	 *
	 * @param  \Mittwald\MmForum\Domain\Repository\Moderation\PostReportRepository $postReportRepository
	 *
	 * @return void
	 */
	public function injectPostReportRepository(
		\Mittwald\MmForum\Domain\Repository\Moderation\PostReportRepository $postReportRepository) {
		$this->postReportRepository = $postReportRepository;
	}


	/**
	 * Injects an instance of the topic factory.
	 *
	 * @param \Mittwald\MmForum\Domain\Factory\Forum\TopicFactory $topicFactory
	 *
	 * @return void
	 */
	public function injectTopicFactory(\Mittwald\MmForum\Domain\Factory\Forum\TopicFactory $topicFactory) {
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
	 * @param  \Mittwald\MmForum\Domain\Model\Moderation\UserReport $userReport
	 * @param  \Mittwald\MmForum\Domain\Model\Moderation\PostReport $postReport
	 * @return void
	 */
	public function editReportAction(\Mittwald\MmForum\Domain\Model\Moderation\UserReport $userReport = NULL,
									 \Mittwald\MmForum\Domain\Model\Moderation\PostReport $postReport = NULL) {
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
	 * @param \Mittwald\MmForum\Domain\Model\Moderation\Report $report
	 * @param \Mittwald\MmForum\Domain\Model\Moderation\ReportComment $comment
	 *
	 * @dontvalidate $comment
	 */
	public function newReportCommentAction(\Mittwald\MmForum\Domain\Model\Moderation\Report $report,
										   \Mittwald\MmForum\Domain\Model\Moderation\ReportComment $comment = NULL) {
		$this->view->assignMultiple(array('report' => $report,
			'comment' => $comment));
	}


	/**
	 * @param  \Mittwald\MmForum\Domain\Model\Moderation\UserReport $report
	 * @param \Mittwald\MmForum\Domain\Model\Moderation\ReportComment $comment
	 * @return void
	 */
	public function createUserReportCommentAction(\Mittwald\MmForum\Domain\Model\Moderation\UserReport $report = NULL,
											  \Mittwald\MmForum\Domain\Model\Moderation\ReportComment $comment) {

		// Validate arguments
		if ($report === NULL) {
			throw new \TYPO3\CMS\Extbase\Mvc\Exception\InvalidArgumentValueException("You need to comment a user report!", 1285059341);
		}

		$comment->setAuthor($this->authenticationService->getUser());
		$report->addComment($comment);
		$this->reportRepository->update($report);

		$this->controllerContext->getFlashMessageQueue()->addMessage(
			new \TYPO3\CMS\Core\Messaging\FlashMessage(
				\Mittwald\MmForum\Utility\Localization::translate('Report_NewComment_Success')
			)
		);

		$this->clearCacheForCurrentPage();
		$this->redirect('editReport', NULL, NULL, array('userReport' => $report));

	}
	/**
	 * @param  \Mittwald\MmForum\Domain\Model\Moderation\PostReport $report
	 * @param \Mittwald\MmForum\Domain\Model\Moderation\ReportComment $comment
	 * @return void
	 */
	public function createPostReportCommentAction(\Mittwald\MmForum\Domain\Model\Moderation\PostReport $report = NULL,
												  \Mittwald\MmForum\Domain\Model\Moderation\ReportComment $comment) {

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
				\Mittwald\MmForum\Utility\Localization::translate('Report_NewComment_Success')
			)
		);

		$this->clearCacheForCurrentPage();
		$this->redirect('editReport', NULL, NULL, array('postReport' => $report));

	}
	/**
	 * Sets the workflow status of a report.
	 *
	 * @param \Mittwald\MmForum\Domain\Model\Moderation\UserReport               $userReport   The report for which to set the status.
	 * @param \Mittwald\MmForum\Domain\Model\Moderation\ReportWorkflowStatus $status   The report's new status.
	 * @param string                                                  $redirect Where to redirect after updating the report ('index' or 'show').
	 */
	public function updateUserReportStatusAction(\Mittwald\MmForum\Domain\Model\Moderation\UserReport $report,
											 \Mittwald\MmForum\Domain\Model\Moderation\ReportWorkflowStatus $status,
											 $redirect = 'indexReport') {

		// Set status and update the report. Add a comment to the report that
		// documents the status change.
		$report->setWorkflowStatus($status);
		$comment = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('Mittwald\\MmForum\\Domain\\Model\\Moderation\\ReportComment');
		$comment->setAuthor($this->getCurrentUser());
		$comment->setText(\Mittwald\MmForum\Utility\Localization::translate('Report_Edit_SetStatus', 'MmForum',
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
	 * @param \Mittwald\MmForum\Domain\Model\Moderation\PostReport               $postReport   The report for which to set the status.
	 * @param \Mittwald\MmForum\Domain\Model\Moderation\ReportWorkflowStatus $status   The report's new status.
	 * @param string                                                  $redirect Where to redirect after updating the report ('index' or 'show').
	 */
	public function updatePostReportStatusAction(\Mittwald\MmForum\Domain\Model\Moderation\PostReport $report,
											 \Mittwald\MmForum\Domain\Model\Moderation\ReportWorkflowStatus $status,
											 $redirect = 'indexReport') {

		// Assert authorization
		$this->authenticationService->assertModerationAuthorization($report->getTopic()->getForum());

		// Set status and update the report. Add a comment to the report that
		// documents the status change.
		$report->setWorkflowStatus($status);
		$comment = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('Mittwald\\MmForum\\Domain\\Model\\Moderation\\ReportComment');
		$comment->setAuthor($this->getCurrentUser());
		$comment->setText(\Mittwald\MmForum\Utility\Localization::translate('Report_Edit_SetStatus', 'MmForum',
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
	 * @param  \Mittwald\MmForum\Domain\Model\Forum\Topic $topic The topic that is to be edited.
	 *
	 * @return void
	 */
	public function editTopicAction(\Mittwald\MmForum\Domain\Model\Forum\Topic $topic) {
		$this->authenticationService->assertModerationAuthorization($topic->getForum());
		$this->view->assign('topic', $topic);
	}



	/**
	 * Updates a forum with special super-moderator-powers!
	 *
	 * @param  \Mittwald\MmForum\Domain\Model\Forum\Topic  $topic           The topic that is be edited.
	 * @param  boolean                              $moveTopic       TRUE, if the topic is to be moved to another forum.
	 * @param  \Mittwald\MmForum\Domain\Model\Forum\Forum  $moveTopicTarget The forum to which the topic is to be moved.
	 *
	 * @return void
	 */
	public function updateTopicAction(\Mittwald\MmForum\Domain\Model\Forum\Topic $topic, $moveTopic = FALSE,
									  \Mittwald\MmForum\Domain\Model\Forum\Forum $moveTopicTarget = NULL) {
		$this->authenticationService->assertModerationAuthorization($topic->getForum());
		$this->topicRepository->update($topic);

		if ($moveTopic) {
			$this->topicFactory->moveTopic($topic, $moveTopicTarget);
		}

		$this->controllerContext->getFlashMessageQueue()->addMessage(
			new \TYPO3\CMS\Core\Messaging\FlashMessage(
				\Mittwald\MmForum\Utility\Localization::translate('Moderation_UpdateTopic_Success',
					'MmForum')
			)
		);
		$this->clearCacheForCurrentPage();
		$this->redirect('show', 'Topic', NULL, Array('topic' => $topic));
	}

	/**
	 * Delete a topic from repository!
	 *
	 * @param  \Mittwald\MmForum\Domain\Model\Forum\Topic  $topic           The topic that is be deleted.
	 *
	 * @return void
	 */
	public function topicConformDeleteAction(\Mittwald\MmForum\Domain\Model\Forum\Topic $topic) {
		$this->authenticationService->assertModerationAuthorization($topic->getForum());
		foreach($topic->getPosts() as $post){
			$this->postRepository->remove($post);
		}
		$this->topicRepository->remove($topic);

//		$forum = $topic->getForum();
//
//
//		$this->persistenceManager->persistAll();
//
//		$forum->_resetLastPost();
//
//		$lastTopic = $this->topicRepository->findLastByForum($forum);
//		if($lastTopic !== NULL) {
//			$lastPost = $lastTopic->getLastPost();
//		} else {
//			$lastPost = NULL;
//		}
//		$forum->setLastPost($lastPost);
//		$forum->setLastTopic($lastTopic);
//		$this->forumRepository->update($forum);

		$this->controllerContext->getFlashMessageQueue()->addMessage(
			new \TYPO3\CMS\Core\Messaging\FlashMessage(
				\Mittwald\MmForum\Utility\Localization::translate('Moderation_DeleteTopic_Success',
					'MmForum')
			)
		);
		$this->clearCacheForCurrentPage();

		$this->redirect('show', 'Forum', NULL, Array('forum' => $topic->getForum()));
	}
}