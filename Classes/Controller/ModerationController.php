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
 * Controller for special moderator options.
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
class Tx_MmForum_Controller_ModerationController extends Tx_MmForum_Controller_AbstractController {



	/*
	 * ATTRIBUTES
	 */



	/**
	 * The topic repository.
	 *
	 * @var Tx_MmForum_Domain_Repository_Forum_TopicRepository
	 */
	protected $topicRepository;



	/**
	 * The topic factory.
	 *
	 * @var Tx_MmForum_Domain_Factory_Forum_TopicFactory
	 */
	protected $topicFactory;



	/**
	 * @var Tx_MmForum_Domain_Repository_Moderation_ReportRepository
	 */
	protected $reportRepository = NULL;



	/*
	 * DEPENDENCY INJECTORS
	 */



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
	 * Injects an instance of the topic factory.
	 *
	 * @param  Tx_MmForum_Domain_Factory_Forum_TopicFactory $topicFactory
	 *
	 * @return void
	 */
	public function injectTopicFactory(Tx_MmForum_Domain_Factory_Forum_TopicFactory $topicFactory) {
		$this->topicFactory = $topicFactory;
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



	/*
	 * ACTION METHODS
	 */



	/**
	 * @return void
	 */
	public function indexReportAction() {
		$this->view->assign('reports', $this->reportRepository->findOpen());
	}



	/**
	 * Sets the workflow status of a report.
	 *
	 * @param Tx_MmForum_Domain_Model_Moderation_Report               $report   The report for which to set the status.
	 * @param Tx_MmForum_Domain_Model_Moderation_ReportWorkflowStatus $status   The report's new status.
	 * @param string                                                  $redirect Where to redirect after updating the report ('index' or 'show').
	 */
	public function updateReportStatusAction(Tx_MmForum_Domain_Model_Moderation_Report $report,
	                                         Tx_MmForum_Domain_Model_Moderation_ReportWorkflowStatus $status,
	                                         $redirect = 'index') {
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
			$this->redirect('editReport', NULL, NULL, array('report' => $report));
		}

		$this->redirect('indexReport');
	}



	/**
	 * @param Tx_MmForum_Domain_Model_Moderation_Report $report
	 */
	public function editReportAction(Tx_MmForum_Domain_Model_Moderation_Report $report) {
		// Assert authorization
		$this->authenticationService->assertModerationAuthorization($report->getTopic()->getForum());
		$this->view->assignMultiple(array('report' => $report));
	}



	/**
	 * @param Tx_MmForum_Domain_Model_Moderation_Report        $report
	 * @param Tx_MmForum_Domain_Model_Moderation_ReportComment $comment
	 *
	 * @dontvalidate $comment
	 */
	public function newReportCommentAction(Tx_MmForum_Domain_Model_Moderation_Report $report,
	                                       Tx_MmForum_Domain_Model_Moderation_ReportComment $comment = NULL) {
		$this->view->assignMultiple(array('report'  => $report,
		                                 'comment'  => $comment));
	}



	/**
	 * @param Tx_MmForum_Domain_Model_Moderation_Report        $report
	 * @param Tx_MmForum_Domain_Model_Moderation_ReportComment $comment
	 */
	public function createReportCommentAction(Tx_MmForum_Domain_Model_Moderation_Report $report,
	                                          Tx_MmForum_Domain_Model_Moderation_ReportComment $comment) {
		$report->addComment($comment);
		$this->reportRepository->update($report);

		$this->controllerContext->getFlashMessageQueue()->addMessage(
			new \TYPO3\CMS\Core\Messaging\FlashMessage(
				Tx_MmForum_Utility_Localization::translate('Report_NewComment_Success')
			)
		);
		$this->clearCacheForCurrentPage();
		$this->redirect('editReport', NULL, NULL, array('report' => $report));
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



}
