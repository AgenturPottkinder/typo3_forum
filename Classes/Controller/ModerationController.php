<?php
/*                                                                      *
 *  COPYRIGHT NOTICE                                                    *
 *                                                                      *
 *  (c) 2013 Sebastian Gieselmann <s.gieselmann@mittwald.de>            *
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
 * Controller for special moderator options.
 *
 * @author     Sebastian Gieselmann <s.gieselmann@mittwald.de>
 * @package    MmForum
 * @subpackage Controller
 * @version    $Id$
 *
 * @copyright  Sebastian Gieselmann <s.gieselmann@mittwald.de>
 *             Mittwald CM Service GmbH & Co. KG
 *             http://www.mittwald.de
 * @license    GNU Public License, version 2
 *             http://opensource.org/licenses/gpl-license.php
 *
 */

class Tx_MmForum_Controller_ModerationController extends Tx_MmForum_Controller_AbstractController {

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
	 * @param  Tx_MmForum_Domain_Model_Moderation_UserReport $userReport
	 * @param  Tx_MmForum_Domain_Model_Moderation_PostReport $postReport
	 * @param Tx_MmForum_Domain_Model_Moderation_ReportComment $comment
	 */
	public function createReportCommentAction(Tx_MmForum_Domain_Model_Moderation_UserReport $userReport = NULL,
											  Tx_MmForum_Domain_Model_Moderation_PostReport $postReport = NULL,
											  Tx_MmForum_Domain_Model_Moderation_ReportComment $comment) {
		// Validate arguments
		if ($userReport === NULL && $postReport === NULL) {
			throw new \TYPO3\CMS\Extbase\Mvc\Exception\InvalidArgumentValueException("You need to show a user report or post report!", 1285059341);
		}

		if ($postReport) {
			$report = $postReport;
			$type = 'postReport';
			$this->authenticationService->assertModerationAuthorization($postReport->getTopic()->getForum());
		} else {
			$type = 'userReport';
			$report = $userReport;
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
		$this->redirect('editReport', NULL, NULL, array($type => $report));

	}

}