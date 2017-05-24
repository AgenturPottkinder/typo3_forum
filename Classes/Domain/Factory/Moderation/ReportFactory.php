<?php
namespace Mittwald\Typo3Forum\Domain\Factory\Moderation;

/*                                                                    - *
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

use Mittwald\Typo3Forum\Domain\Factory\AbstractFactory;
use Mittwald\Typo3Forum\Domain\Model\Moderation\PostReport;
use Mittwald\Typo3Forum\Domain\Model\Moderation\ReportComment;
use Mittwald\Typo3Forum\Domain\Model\Moderation\ReportWorkflowStatus;
use Mittwald\Typo3Forum\Domain\Model\Moderation\UserReport;

class ReportFactory extends AbstractFactory {

	/**
	 * @var \Mittwald\Typo3Forum\Domain\Repository\Moderation\ReportWorkflowStatusRepository
	 * @inject
	 */
	protected $workflowStatusRepository;

	/**
	 *
	 * Creates a new User report.
	 *
	 * @param ReportComment $firstComment The first report comment for this report.
	 *
	 * @return UserReport
	 *
	 */
	public function createUserReport(ReportComment $firstComment) {
		$user = &$this->getCurrentUser();
		$firstComment->setAuthor($user);
		/** @var UserReport $report */
		$report = $this->objectManager->get(UserReport::class);
		$report->setWorkflowStatus($this->getInitialWorkflowStatus());
		$report->setReporter($user);
		$report->addComment($firstComment);

		return $report;
	}

	/**
	 *
	 * Creates a new User report.
	 *
	 * @param ReportComment $firstComment The first report comment for this report.
	 *
	 * @return object
	 */
	public function createPostReport(ReportComment $firstComment) {
		$user = &$this->getCurrentUser();
		$firstComment->setAuthor($user);
		$report = $this->objectManager->get(PostReport::class);
		$report->setWorkflowStatus($this->getInitialWorkflowStatus());
		$report->setReporter($user);
		$report->addComment($firstComment);

		return $report;
	}

	/**
	 * @return ReportWorkflowStatus
	 * @throws \Exception
	 */
	protected function getInitialWorkflowStatus() {
		$initialWorkStatus = $this->workflowStatusRepository->findInitial();
		if (!$initialWorkStatus instanceof ReportWorkflowStatus) {
			throw new \Exception('No initial workflow status configured', 1436529800);
		}
		return $initialWorkStatus;
	}

}
