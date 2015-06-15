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
class ReportController extends \Mittwald\Typo3Forum\Controller\AbstractController {



	/*
	 * ATTRIBUTES
	 */



	/**
	 * A report factory class.
	 *
	 * @var \Mittwald\Typo3Forum\Domain\Factory\Moderation\ReportFactory
	 */
	protected $reportFactory;



	/**
	 * The report repository.
	 *
	 * @var Tx_Typo3Forum_Domain_Repository_Moderation_ReportRepository
	 */
	protected $reportRepository;

	/**
	 * The report repository.
	 *
	 * @var Tx_Typo3Forum_Domain_Repository_Moderation_UserReportRepository
	 */
	protected $userReportRepository;

	/**
	 * The report repository.
	 *
	 * @var Tx_Typo3Forum_Domain_Repository_Moderation_PostReportRepository
	 */
	protected $postReportRepository;



	/*
	 * DEPENDENCY INJECTORS
	 */



	/**
	 * @param Tx_Typo3Forum_Domain_Repository_Moderation_ReportRepository $reportRepository
	 */
	public function injectReportRepository(Tx_Typo3Forum_Domain_Repository_Moderation_ReportRepository $reportRepository) {
		$this->reportRepository = $reportRepository;

	}

	/**
	 * @param Tx_Typo3Forum_Domain_Repository_Moderation_UserReportRepository $userReportRepository
	 */
	public function injectUserReportRepository(Tx_Typo3Forum_Domain_Repository_Moderation_UserReportRepository $userReportRepository) {
		$this->userReportRepository = $userReportRepository;
	}

	/**
	 * @param Tx_Typo3Forum_Domain_Repository_Moderation_PostReportRepository $postReportRepository
	 */
	public function injectPostReportRepository(Tx_Typo3Forum_Domain_Repository_Moderation_PostReportRepository $postReportRepository) {
		$this->postReportRepository = $postReportRepository;
	}




	/**
	 * @param \Mittwald\Typo3Forum\Domain\Factory\Moderation\ReportFactory $reportFactory
	 */
	public function injectReportFactory(\Mittwald\Typo3Forum\Domain\Factory\Moderation\ReportFactory $reportFactory) {
		$this->reportFactory = $reportFactory;
	}



	/*
	 * ACTION METHODS
	 */

	/**
	 * Displays a form for creating a new post report.
	 *
	 * @param  \Mittwald\Typo3Forum\Domain\Model\User\FrontendUser       $user
	 * @param \Mittwald\Typo3Forum\Domain\Model\Moderation\ReportComment $firstComment
	 *
	 * @dontvalidate $firstComment
	 * @return void
	 */
	public function newUserReportAction(\Mittwald\Typo3Forum\Domain\Model\User\FrontendUser $user,
										\Mittwald\Typo3Forum\Domain\Model\Moderation\ReportComment $firstComment = NULL) {
		$this->view->assign('firstComment', $firstComment)->assign('user', $user);
	}


	/**
	 * Displays a form for creating a new post report.
	 *
	 * @param \Mittwald\Typo3Forum\Domain\Model\Forum\Post               $post
	 * @param \Mittwald\Typo3Forum\Domain\Model\Moderation\ReportComment $firstComment
	 *
	 * @dontvalidate $firstComment
	 * @return void
	 */
	public function newPostReportAction(\Mittwald\Typo3Forum\Domain\Model\Forum\Post $post,
	                          \Mittwald\Typo3Forum\Domain\Model\Moderation\ReportComment $firstComment = NULL) {
		$this->authenticationService->assertReadAuthorization($post);
		$this->view->assign('firstComment', $firstComment)->assign('post', $post);
	}



	/**
	 * Creates a new post report and stores it into the database.
	 *
	 * @param \Mittwald\Typo3Forum\Domain\Model\User\FrontendUser               $user
	 * @param \Mittwald\Typo3Forum\Domain\Model\Moderation\ReportComment $firstComment
	 *
	 * @return void
	 */
	public function createUserReportAction(\Mittwald\Typo3Forum\Domain\Model\User\FrontendUser $user,
								 \Mittwald\Typo3Forum\Domain\Model\Moderation\ReportComment $firstComment = NULL) {

		// Create the new report using the factory class and persist the new object
		$report = $this->reportFactory->createUserReport($firstComment);
		$report->setUser($user);
		$this->userReportRepository->add($report);

		// Notify observers.
		$this->signalSlotDispatcher->dispatch('\Mittwald\Typo3Forum\Domain\Model\Moderation\Report', 'reportCreated',
			array('report' => $report));

		// Display success message and redirect to topic->show action.
		$this->controllerContext->getFlashMessageQueue()->addMessage(
			new \TYPO3\CMS\Core\Messaging\FlashMessage(
				\TYPO3\CMS\Extbase\Utility\LocalizationUtility::translate('Report_New_Success', 'Typo3Forum')
			)
		);
		$this->redirect('show', 'User', NULL, array('user' => $user), $this->settings['pids']['UserShow']);
	}


	/**
	 * Creates a new post report and stores it into the database.
	 *
	 * @param \Mittwald\Typo3Forum\Domain\Model\Forum\Post               $post
	 * @param \Mittwald\Typo3Forum\Domain\Model\Moderation\ReportComment $firstComment
	 *
	 * @return void
	 */
	public function createPostReportAction(\Mittwald\Typo3Forum\Domain\Model\Forum\Post $post,
	                             \Mittwald\Typo3Forum\Domain\Model\Moderation\ReportComment $firstComment = NULL) {
		// Assert authorization;
		$this->authenticationService->assertReadAuthorization($post);

		// Create the new report using the factory class and persist the new object
		$report = $this->reportFactory->createPostReport($firstComment);
		$report->setPost($post);
		$this->postReportRepository->add($report);

		// Notify observers.
		$this->signalSlotDispatcher->dispatch('\Mittwald\Typo3Forum\Domain\Model\Moderation\Report', 'reportCreated',
		                                      array('report' => $report));

		// Display success message and redirect to topic->show action.
		$this->controllerContext->getFlashMessageQueue()->addMessage(
			new \TYPO3\CMS\Core\Messaging\FlashMessage(
				\TYPO3\CMS\Extbase\Utility\LocalizationUtility::translate('Report_New_Success', 'Typo3Forum')
			)
		);
		$this->redirect('show', 'Topic', NULL, array('topic' => $post->getTopic()));
	}



}
