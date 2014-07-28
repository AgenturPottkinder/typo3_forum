<?php
namespace Mittwald\MmForum\Domain\Factory\Moderation;


/*                                                                    - *
 *  COPYRIGHT NOTICE                                                    *
 *                                                                      *
 *  (c) 2010 Martin Helmich <m.helmich@mittwald.de>                     *
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
 * Factory class for post reports.
 *
 * @author     Martin Helmich <m.helmich@mittwald.de>
 * @package    MmForum
 * @subpackage Domain_Factory_Forum
 * @version    $Id$
 *
 * @copyright  2012 Martin Helmich <m.helmich@mittwald.de>
 *             Mittwald CM Service GmbH & Co. KG
 *             http://www.mittwald.de
 * @license    GNU Public License, version 2
 *             http://opensource.org/licenses/gpl-license.php
 *
 */
class ReportFactory extends \Mittwald\MmForum\Domain\Factory\AbstractFactory {



	/*
	 * ATTRIBUTES
	 */



	/**
	 * The workflow status repository.
	 * @var \Mittwald\MmForum\Domain\Repository\Moderation\ReportWorkflowStatusRepository
	 */
	protected $workflowStatusRepository;



	/*
	 * DEPENDENCY INJECTORS
	 */



	/**
	 * Constructor.
	 * @param \Mittwald\MmForum\Domain\Repository\Moderation\ReportWorkflowStatusRepository $workflowStatusRepository
	 */
	public function __construct(\Mittwald\MmForum\Domain\Repository\Moderation\ReportWorkflowStatusRepository $workflowStatusRepository) {
		$this->workflowStatusRepository = $workflowStatusRepository;
	}



	/*
	 * FACTORY METHODS
	 */



	/**
	 *
	 * Creates a new User report.
	 *
	 * @param \Mittwald\MmForum\Domain\Model\Moderation\ReportComment $firstComment
	 *                             The first report comment for this report.
	 * @param \Mittwald\MmForum\Domain\Model\Forum\Post               $post
	 *                             The post that is to be reported.
	 * @return \Mittwald\MmForum\Domain\Model\Moderation\Report
	 *                             The new report.
	 *
	 */
	public function createUserReport(\Mittwald\MmForum\Domain\Model\Moderation\ReportComment $firstComment) {
		$user = & $this->getCurrentUser();
		$firstComment->setAuthor($user);
		$report = $this->objectManager->create('Mittwald\\MmForum\\Domain\\Model\\Moderation\\UserReport');
		$report->setWorkflowStatus($this->workflowStatusRepository->findInitial());
		$report->setReporter($user);
		$report->addComment($firstComment);
		return $report;
	}

	/**
	 *
	 * Creates a new User report.
	 *
	 * @param \Mittwald\MmForum\Domain\Model\Moderation\ReportComment $firstComment
	 *                             The first report comment for this report.
	 * @param \Mittwald\MmForum\Domain\Model\Forum\Post               $post
	 *                             The post that is to be reported.
	 * @return \Mittwald\MmForum\Domain\Model\Moderation\Report
	 *                             The new report.
	 *
	 */
	public function createPostReport(\Mittwald\MmForum\Domain\Model\Moderation\ReportComment $firstComment) {
		$user = & $this->getCurrentUser();
		$firstComment->setAuthor($user);
		$report = $this->objectManager->create('Mittwald\\MmForum\\Domain\\Model\\Moderation\\PostReport');
		$report->setWorkflowStatus($this->workflowStatusRepository->findInitial());
		$report->setReporter($user);
		$report->addComment($firstComment);
		return $report;
	}



}

?>