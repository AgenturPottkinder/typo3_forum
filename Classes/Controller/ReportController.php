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

use Mittwald\Typo3Forum\Domain\Model\Forum\Post;
use Mittwald\Typo3Forum\Domain\Model\Moderation\PostReport;
use Mittwald\Typo3Forum\Domain\Model\Moderation\Report;
use Mittwald\Typo3Forum\Domain\Model\Moderation\ReportComment;
use Mittwald\Typo3Forum\Domain\Model\Moderation\UserReport;
use Mittwald\Typo3Forum\Domain\Model\User\FrontendUser;
use TYPO3\CMS\Core\Messaging\FlashMessage;
use TYPO3\CMS\Extbase\Utility\LocalizationUtility;

class ReportController extends AbstractController {

	/**
	 * @var \Mittwald\Typo3Forum\Domain\Repository\Moderation\PostReportRepository
	 * @inject
	 */
	protected $postReportRepository;

	/**
	 * @var \Mittwald\Typo3Forum\Domain\Factory\Moderation\ReportFactory
	 * @inject
	 */
	protected $reportFactory;

	/**
	 * @var \Mittwald\Typo3Forum\Domain\Repository\Moderation\ReportRepository
	 * @inject
	 */
	protected $reportRepository;

	/**
	 * @var \Mittwald\Typo3Forum\Domain\Repository\Moderation\UserReportRepository
	 * @inject
	 */
	protected $userReportRepository;

	/**
	 * Displays a form for creating a new post report.
	 *
	 * @param FrontendUser $user
	 * @param ReportComment $firstComment
	 *
	 * @ignorevalidation $firstComment
	 */
	public function newUserReportAction(FrontendUser $user, ReportComment $firstComment = NULL) {
		$this->view->assignMultiple([
			'firstComment' => $firstComment,
			'user' => $user,
		]);
	}

	/**
	 * Displays a form for creating a new post report.
	 *
	 * @param Post $post
	 * @param ReportComment $firstComment
	 *
	 * @ignorevalidation $firstComment
	 */
	public function newPostReportAction(Post $post, ReportComment $firstComment = NULL) {
		$this->authenticationService->assertReadAuthorization($post);
		$this->view->assign('firstComment', $firstComment)->assign('post', $post);
	}

	/**
	 * Creates a new post report and stores it into the database.
	 *
	 * @param FrontendUser $user
	 * @param ReportComment $firstComment
	 */
	public function createUserReportAction(FrontendUser $user, ReportComment $firstComment = NULL) {

		/** @var UserReport $report */
		$report = $this->reportFactory->createUserReport($firstComment);
		$report->setUser($user);
		$this->userReportRepository->add($report);

		// Notify observers.
		$this->signalSlotDispatcher->dispatch(Report::class, 'reportCreated', ['report' => $report]);

		// Display success message and redirect to topic->show action.
		$this->controllerContext->getFlashMessageQueue()->enqueue(
			new FlashMessage(LocalizationUtility::translate('Report_New_Success', 'Typo3Forum'))
		);
		$this->redirect('show', 'User', NULL, ['user' => $user], $this->settings['pids']['UserShow']);
	}

	/**
	 * Creates a new post report and stores it into the database.
	 *
	 * @param Post $post
	 * @param ReportComment $firstComment
	 */
	public function createPostReportAction(Post $post, ReportComment $firstComment = NULL) {
		// Assert authorization;
		$this->authenticationService->assertReadAuthorization($post);

		/** @var PostReport $report */
		$report = $this->reportFactory->createPostReport($firstComment);
		$report->setPost($post);
		$this->postReportRepository->add($report);

		// Notify observers.
		$this->signalSlotDispatcher->dispatch(Report::class, 'reportCreated', ['report' => $report]);

		// Display success message and redirect to topic->show action.
		$this->controllerContext->getFlashMessageQueue()->enqueue(
			new FlashMessage(LocalizationUtility::translate('Report_New_Success', 'Typo3Forum'))
		);
		$this->redirect('show', 'Topic', NULL, ['topic' => $post->getTopic()]);
	}

}
