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

use Mittwald\Typo3Forum\Domain\Factory\Forum\TopicFactory;
use Mittwald\Typo3Forum\Domain\Model\Forum\Forum;
use Mittwald\Typo3Forum\Domain\Model\Forum\Topic;
use Mittwald\Typo3Forum\Domain\Model\Moderation\PostReport;
use Mittwald\Typo3Forum\Domain\Model\Moderation\ReportComment;
use Mittwald\Typo3Forum\Domain\Model\Moderation\ReportWorkflowStatus;
use Mittwald\Typo3Forum\Domain\Model\Moderation\UserReport;
use Mittwald\Typo3Forum\Domain\Repository\Forum\ForumRepository;
use Mittwald\Typo3Forum\Domain\Repository\Forum\PostRepository;
use Mittwald\Typo3Forum\Domain\Repository\Forum\TopicRepository;
use Mittwald\Typo3Forum\Domain\Repository\Moderation\PostReportRepository;
use Mittwald\Typo3Forum\Domain\Repository\Moderation\ReportRepository;
use Mittwald\Typo3Forum\Domain\Repository\Moderation\UserReportRepository;
use Mittwald\Typo3Forum\Utility\Localization;
use TYPO3\CMS\Core\Messaging\FlashMessage;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Annotation\IgnoreValidation;
use TYPO3\CMS\Extbase\Mvc\Exception\InvalidArgumentValueException;
use TYPO3\CMS\Extbase\Persistence\Generic\PersistenceManager;

class ModerationController extends AbstractController
{
    protected ForumRepository $forumRepository;
    protected PersistenceManager $persistenceManager;
    protected PostReportRepository $postReportRepository;
    protected PostRepository $postRepository;
    protected ReportRepository $reportRepository;
    protected TopicFactory $topicFactory;
    protected TopicRepository $topicRepository;
    protected UserReportRepository $userReportRepository;

    public function __construct(
        ForumRepository $forumRepository,
        PersistenceManager $persistenceManager,
        PostReportRepository $postReportRepository,
        PostRepository $postRepository,
        ReportRepository $reportRepository,
        TopicFactory $topicFactory,
        TopicRepository $topicRepository,
        UserReportRepository $userReportRepository
    ) {
        $this->forumRepository = $forumRepository;
        $this->persistenceManager = $persistenceManager;
        $this->postReportRepository = $postReportRepository;
        $this->postRepository = $postRepository;
        $this->reportRepository = $reportRepository;
        $this->topicFactory = $topicFactory;
        $this->topicRepository = $topicRepository;
        $this->userReportRepository = $userReportRepository;
    }

    public function indexReportAction(int $page = 1): void
    {
        $this->view->assign('postReports', $this->postReportRepository->findAllAuthorizedToEdit());
        $this->view->assign('userReports', $this->userReportRepository->findAll());
        $this->view->assign('page', $page);
    }

    /**
     * @throws InvalidArgumentValueException
     */
    public function editReportAction(?UserReport $userReport = null, ?PostReport $postReport = null): void
    {
        // Validate arguments
        if ($userReport === null && $postReport === null) {
            throw new InvalidArgumentValueException('You need to select a user report or post report!', 1285059341);
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

    public function createUserReportCommentAction(UserReport $report, ReportComment $comment): void
    {
        // Validate arguments
        if ($report === null) {
            throw new InvalidArgumentValueException('You need to comment a user report!', 1285059341);
        }

        $comment->setAuthor($this->authenticationService->getUser());
        $report->addComment($comment);
        $this->reportRepository->update($report);

        $this->getFlashMessageQueue()->enqueue(
            new FlashMessage(Localization::translate('Report_NewComment_Success'))
        );

        $this->clearCacheForCurrentPage();
        $this->redirect('editReport', null, null, ['userReport' => $report]);
    }

    /**
     * @throws InvalidArgumentValueException
     * @throws \TYPO3\CMS\Extbase\Persistence\Exception\IllegalObjectTypeException
     */
    public function createPostReportCommentAction(PostReport $report, ReportComment $comment)
    {
        // Assert authorization
        $this->authenticationService->assertModerationAuthorization($report->getTopic()->getForum());

        $comment->setAuthor($this->authenticationService->getUser());
        $report->addComment($comment);
        $this->reportRepository->update($report);

        $this->getFlashMessageQueue()->enqueue(
            new FlashMessage(Localization::translate('Report_NewComment_Success'))
        );

        $this->clearCacheForCurrentPage();
        $this->redirect('editReport', null, null, ['postReport' => $report]);
    }

    /**
     * Sets the workflow status of a report.
     *
     * @param UserReport $report
     * @param ReportWorkflowStatus $status
     * @param string $redirect
     *
     * @throws \TYPO3\CMS\Extbase\Persistence\Exception\IllegalObjectTypeException
     */
    public function updateUserReportStatusAction(UserReport $report, ReportWorkflowStatus $status, $redirect = 'indexReport')
    {

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

        $this->redirect('editReport', null, null, ['userReport' => $report]);
    }

    /**
     * Sets the workflow status of a report.
     */
    public function updatePostReportStatusAction(PostReport $report, ReportWorkflowStatus $status): void
    {
        // Assert authorization
        $this->authenticationService->assertModerationAuthorization($report->getTopic()->getForum());

        // Set status and update the report. Add a comment to the report that
        // documents the status change.
        $report->setWorkflowStatus($status);
        /** @var ReportComment $comment */
        $comment = GeneralUtility::makeInstance(ReportComment::class);
        $comment->setAuthor($this->getCurrentUser());
        $comment->setText(Localization::translate(
            'Report_Edit_SetStatus',
            'Typo3Forum',
            [$status->getName()]
        ));
        $report->addComment($comment);
        $this->reportRepository->update($report);

        $this->clearCacheForCurrentPage();

        $this->redirect('editReport', null, null, ['postReport' => $report]);
    }

    /**
     * Displays a form for editing a topic with special moderator-powers!
     *
     * @param Topic $topic The topic that is to be edited.
     * @IgnoreValidation("topic")
     */
    public function editTopicAction(Topic $topic)
    {
        $this->authenticationService->assertModerationAuthorization($topic->getForum());
        $this->view->assign('topic', $topic);
    }

    /**
     * Updates a forum with special super-moderator-powers!
     *
     * @param Topic $topic The topic that is be edited.
     * @param Forum $moveTopicTarget The forum to which the topic is to be moved, or null if no movement is desired.
     */
    public function updateTopicAction(Topic $topic, Forum $moveTopicTarget = null)
    {
        $isQuestion = $topic->getQuestion();
        if ($isQuestion !== $topic->_getCleanProperty('question')) {
            $author = $topic->getAuthor();
            $isQuestion ? $author->increaseQuestionCount() : $author->decreaseQuestionCount();
        }

        $this->authenticationService->assertModerationAuthorization($topic->getForum());
        $this->topicRepository->update($topic);
        if ($moveTopicTarget !== null && $moveTopicTarget !== $topic->getForum()) {
            $this->topicFactory->moveTopic($topic, $moveTopicTarget);
        }
        $this->persistenceManager->persistAll();

        $this->getFlashMessageQueue()->enqueue(
            new FlashMessage(Localization::translate('Moderation_UpdateTopic_Success', 'Typo3Forum'))
        );
        $this->clearCacheForCurrentPage();

        $this->redirect('show', 'Topic', null, ['topic' => $topic]);
    }

    public function confirmDeleteTopicAction(Topic $topic): void
    {
        $this->authenticationService->assertDeleteTopicAuthorization($topic);

        $this->view->assign('topic', $topic);
    }

    /**
     * Delete a topic from repository
     */
    public function deleteTopicAction(Topic $topic): void
    {
        $this->authenticationService->assertDeleteTopicAuthorization($topic->getForum());
        $this->topicFactory->deleteTopic($topic);

        $this->getFlashMessageQueue()->enqueue(
            new FlashMessage(Localization::translate('Moderation_DeleteTopic_Success', 'Typo3Forum'))
        );
        $this->clearCacheForCurrentPage();

        $this->redirect('show', 'Forum', null, ['forum' => $topic->getForum()]);
    }
}
