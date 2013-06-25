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
 * Controller class for post reports. This controller offers functionality for
 * reporting posts to the forum's moderation team.
 *
 * @author     Martin Helmich <m.helmich@mittwald.de>
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
class Tx_MmForum_Controller_ReportController extends Tx_MmForum_Controller_AbstractController {



	/*
	 * ATTRIBUTES
	 */



	/**
	 * A report factory class.
	 *
	 * @var Tx_MmForum_Domain_Factory_Moderation_ReportFactory
	 */
	protected $reportFactory;



	/**
	 * The report repository.
	 *
	 * @var Tx_MmForum_Domain_Repository_Moderation_ReportRepository
	 */
	protected $reportRepository;



	/*
	 * DEPENDENCY INJECTORS
	 */



	/**
	 * @param Tx_MmForum_Domain_Repository_Moderation_ReportRepository $reportRepository
	 */
	public function injectReportRepository(Tx_MmForum_Domain_Repository_Moderation_ReportRepository $reportRepository) {
		$this->reportRepository = $reportRepository;
	}



	/**
	 * @param Tx_MmForum_Domain_Factory_Moderation_ReportFactory $reportFactory
	 */
	public function injectReportFactory(Tx_MmForum_Domain_Factory_Moderation_ReportFactory $reportFactory) {
		$this->reportFactory = $reportFactory;
	}



	/*
	 * ACTION METHODS
	 */



	/**
	 * Displays a form for creating a new post report.
	 *
	 * @param Tx_MmForum_Domain_Model_Forum_Post               $post
	 * @param Tx_MmForum_Domain_Model_Moderation_ReportComment $firstComment
	 *
	 * @dontvalidate $firstComment
	 * @return void
	 */
	public function newAction(Tx_MmForum_Domain_Model_Forum_Post $post,
	                          Tx_MmForum_Domain_Model_Moderation_ReportComment $firstComment = NULL) {
		$this->authenticationService->assertReadAuthorization($post);
		$this->view->assign('firstComment', $firstComment)->assign('post', $post);
	}



	/**
	 * Creates a new post report and stores it into the database.
	 *
	 * @param Tx_MmForum_Domain_Model_Forum_Post               $post
	 * @param Tx_MmForum_Domain_Model_Moderation_ReportComment $firstComment
	 *
	 * @return void
	 */
	public function createAction(Tx_MmForum_Domain_Model_Forum_Post $post,
	                             Tx_MmForum_Domain_Model_Moderation_ReportComment $firstComment = NULL) {
		// Assert authorization;
		$this->authenticationService->assertReadAuthorization($post);

		// Create the new report using the factory class and persist the new object
		$report = $this->reportFactory->createReport($firstComment, $post);
		$this->reportRepository->add($report);

		// Notify observers.
		$this->signalSlotDispatcher->dispatch('Tx_MmForum_Domain_Model_Moderation_Report', 'reportCreated',
		                                      array('report' => $report));

		// Display success message and redirect to topic->show action.
		$this->controllerContext->getFlashMessageQueue()->addMessage(
			new \TYPO3\CMS\Core\Messaging\FlashMessage(
				\TYPO3\CMS\Extbase\Utility\LocalizationUtility::translate('Report_New_Success', 'MmForum')
			)
		);
		$this->redirect('show', 'Topic', NULL, array('topic' => $post->getTopic()));
	}



}
