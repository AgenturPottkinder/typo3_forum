<?php
namespace Mittwald\Typo3Forum\Controller;

/*                                                                      *
 *  COPYRIGHT NOTICE                                                    *
 *                                                                      *
 *  (c) 2015 Mittwald CM Service GmbH & Co KG                           *
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

use Mittwald\Typo3Forum\Domain\Model\Forum\Forum;
use Mittwald\Typo3Forum\Domain\Model\Forum\Topic;
use Mittwald\Typo3Forum\Domain\Model\Moderation\PostReport;
use Mittwald\Typo3Forum\Domain\Model\Moderation\Report;
use Mittwald\Typo3Forum\Domain\Model\Moderation\ReportComment;
use Mittwald\Typo3Forum\Domain\Model\Moderation\ReportWorkflowStatus;
use Mittwald\Typo3Forum\Domain\Model\Moderation\UserReport;
use Mittwald\Typo3Forum\Utility\Localization;
use TYPO3\CMS\Core\Messaging\FlashMessage;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Mvc\Exception\InvalidArgumentValueException;

class ModerationController extends AbstractController {

	/**
	 * @var \Mittwald\Typo3Forum\Domain\Repository\Forum\ForumRepository
	 * @inject
	 */
	protected $forumRepository;

	/**
	 * @var \TYPO3\CMS\Extbase\Persistence\PersistenceManagerInterface
	 * @inject
	 */
	protected $persistenceManager;

	/**
	 * @var \Mittwald\Typo3Forum\Domain\Repository\Moderation\PostReportRepository
	 * @inject
	 */
	protected $postReportRepository = NULL;

	/**
	 * @var \Mittwald\Typo3Forum\Domain\Repository\Forum\PostRepository
	 * @inject
	 */
	protected $postRepository;

	/**
	 * @var \Mittwald\Typo3Forum\Domain\Repository\Moderation\ReportRepository
	 * @inject
	 */
	protected $reportRepository = NULL;

	/**
	 * @var \Mittwald\Typo3Forum\Domain\Factory\Forum\TopicFactory
	 * @inject
	 */
	protected $topicFactory;

	/**
	 * @var \Mittwald\Typo3Forum\Domain\Repository\Forum\TopicRepository
	 * @inject
	 */
	protected $topicRepository;

	/**
	 * @var \Mittwald\Typo3Forum\Domain\Repository\Moderation\UserReportRepository
	 * @inject
	 */
	protected $userReportRepository = NULL;

	/**
	 * @return void
	 */
	public function indexReportAction() {
		$this->view->assign('postReports', $this->postReportRepository->findAll());
		$this->view->assign('userReports', $this->userReportRepository->findAll());
	}

	/**
	 * editReportAction
	 *
	 * @param UserReport $userReport
	 * @param PostReport|NULL $postReport
	 *
	 * @return void
	 * @throws InvalidArgumentValueException
	 */
	public function editReportAction(UserReport $userReport = NULL, PostReport $postReport = NULL) {
		// Validate arguments
		if ($userReport === NULL && $postReport === NULL) {
			throw new InvalidArgumentValueException("You need to show a user report or post report!", 1285059341);
		}
		if ($postReport) {
			$report = $postReport;
			$type = 'Post';
			$this->authenticationService->assertModerationAuthorization($postReport->getTopic()->getForum());
		} else {
			$type = 'User';
			$report = $userReport;
		}

		$this->view->assignMultiple([
			'report' => $report,
			'type' => $type,
		]);
	}

	/**
	 * @param Report $report
	 * @param ReportComment $comment
	 *
	 * @ignorevalidation $comment
	 */
	public function newReportCommentAction(Report $report, ReportComment $comment = NULL) {
		$this->view->assignMultiple([
			'comment' => $comment,
			'report' => $report,
		]);
	}

	/**
	 * @param UserReport $report
	 * @param ReportComment $comment
	 * @return void
	 * @throws InvalidArgumentValueException
	 */
	public function createUserReportCommentAction(UserReport $report = NULL, ReportComment $comment) {

		// Validate arguments
		if ($report === NULL) {
			throw new InvalidArgumentValueException("You need to comment a user report!", 1285059341);
		}

		$comment->setAuthor($this->authenticationService->getUser());
		$report->addComment($comment);
		$this->reportRepository->update($report);

		$this->controllerContext->getFlashMessageQueue()->enqueue(
			new FlashMessage(Localization::translate('Report_NewComment_Success'))
		);

		$this->clearCacheForCurrentPage();
		$this->redirect('editReport', NULL, NULL, ['userReport' => $report]);

	}

	/**
	 * createPostReportCommentAction
	 *
	 * @param PostReport|NULL $report
	 * @param ReportComment   $comment
	 *
	 * @return void
	 * @throws InvalidArgumentValueException
	 * @throws \TYPO3\CMS\Extbase\Persistence\Exception\IllegalObjectTypeException
	 */
	public function createPostReportCommentAction(PostReport $report = NULL, ReportComment $comment) {

		// Assert authorization
		$this->authenticationService->assertModerationAuthorization($report->getTopic()->getForum());

		// Validate arguments
		if ($report === NULL) {
			throw new InvalidArgumentValueException("You need to comment a user report!", 1285059341);
		}

		$comment->setAuthor($this->authenticationService->getUser());
		$report->addComment($comment);
		$this->reportRepository->update($report);

		$this->controllerContext->getFlashMessageQueue()->enqueue(
			new FlashMessage(Localization::translate('Report_NewComment_Success'))
		);

		$this->clearCacheForCurrentPage();
		$this->redirect('editReport', NULL, NULL, ['postReport' => $report]);

	}

	/**
	 * Sets the workflow status of a report.
	 *
	 * @param UserReport $report
	 * @param ReportWorkflowStatus $status
	 * @param string $redirect
	 *
	 * @return void
	 * @throws \TYPO3\CMS\Extbase\Persistence\Exception\IllegalObjectTypeException
	 */
	public function updateUserReportStatusAction(UserReport $report, ReportWorkflowStatus $status, $redirect = 'indexReport') {

		// Set status and update the report. Add a comment to the report that
		// documents the status change.
		$report->setWorkflowStatus($status);
		/** @var ReportComment $comment */
		$comment = GeneralUtility::makeInstance(ReportComment::class);
		$comment->setAuthor($this->getCurrentUser());
		$comment->setText(Localization::translate('Report_Edit_SetStatus', 'Typo3Forum', [$status->getName()]));
		$report->addComment($comment);
		$this->reportRepository->update($report);

		// Add flash message and clear cache.
		$this->addLocalizedFlashmessage('Report_UpdateStatus_Success', [$report->getUid(), $status->getName()]);
		$this->clearCacheForCurrentPage();

		if ($redirect === 'show') {
			$this->redirect('editReport', NULL, NULL, ['userReport' => $report]);
		}

		$this->redirect('indexReport');
	}

	/**
	 * Sets the workflow status of a report.
	 *
	 * @param PostReport $report
	 * @param ReportWorkflowStatus $status
	 * @param string $redirect
	 */
	public function updatePostReportStatusAction(PostReport $report, ReportWorkflowStatus $status, $redirect = 'indexReport') {

		// Assert authorization
		$this->authenticationService->assertModerationAuthorization($report->getTopic()->getForum());

		// Set status and update the report. Add a comment to the report that
		// documents the status change.
		$report->setWorkflowStatus($status);
		/** @var ReportComment $comment */
		$comment = GeneralUtility::makeInstance(ReportComment::class);
		$comment->setAuthor($this->getCurrentUser());
		$comment->setText(Localization::translate('Report_Edit_SetStatus', 'Typo3Forum',
			[$status->getName()]));
		$report->addComment($comment);
		$this->reportRepository->update($report);

		// Add flash message and clear cache.
		$this->addLocalizedFlashmessage('Report_UpdateStatus_Success', [$report->getUid(), $status->getName()]);
		$this->clearCacheForCurrentPage();

		if ($redirect === 'show') {
			$this->redirect('editReport', NULL, NULL, ['postReport' => $report]);
		}

		$this->redirect('indexReport');
	}

	/**
	 * Displays a form for editing a topic with special moderator-powers!
	 *
	 * @param Topic $topic The topic that is to be edited.
	 */
	public function editTopicAction(Topic $topic) {
		$this->authenticationService->assertModerationAuthorization($topic->getForum());
		$this->view->assign('topic', $topic);
	}

	/**
	 * Updates a forum with special super-moderator-powers!
	 *
	 * @param Topic $topic The topic that is be edited.
	 * @param boolean $moveTopic TRUE, if the topic is to be moved to another forum.
	 * @param Forum $moveTopicTarget The forum to which the topic is to be moved.
	 */
	public function updateTopicAction(Topic $topic, $moveTopic = FALSE, Forum $moveTopicTarget = NULL) {
		$this->authenticationService->assertModerationAuthorization($topic->getForum());
		$this->topicRepository->update($topic);

		if ($moveTopic) {
			$this->topicFactory->moveTopic($topic, $moveTopicTarget);
		}

		$this->controllerContext->getFlashMessageQueue()->enqueue(
			new FlashMessage(Localization::translate('Moderation_UpdateTopic_Success', 'Typo3Forum'))
		);
		$this->clearCacheForCurrentPage();
		$this->redirect('show', 'Topic', NULL, ['topic' => $topic]);
	}

	/**
	 * Delete a topic from repository
	 *
	 * @param Topic $topic The topic to be deleted
	 *
	 * @return void
	 */
	public function deleteTopicAction(Topic $topic) {
		$this->authenticationService->assertModerationAuthorization($topic->getForum());
		foreach ($topic->getPosts() as $post) {
			$this->postRepository->remove($post);
		}
		$this->topicRepository->remove($topic);
		$this->controllerContext->getFlashMessageQueue()->enqueue(
			new FlashMessage(Localization::translate('Moderation_DeleteTopic_Success', 'Typo3Forum'))
		);
		$this->clearCacheForCurrentPage();

		$this->redirect('show', 'Forum', NULL, ['forum' => $topic->getForum()]);
	}
}
