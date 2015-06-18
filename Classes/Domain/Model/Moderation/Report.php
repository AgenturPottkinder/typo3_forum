<?php
namespace Mittwald\Typo3Forum\Domain\Model\Moderation;
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
 * Models a post report. Reports are the central object of the moderation
 * component of the typo3_forum extension. Each user can report a forum post
 * to the respective moderator group. In this case, a report object is
 * created.
 *
 * These report objects can be assigned to moderators ans be organized in
 * different workflow stages. Moderators can post comments to each report.
 *
 * @author     Martin Helmich <m.helmich@mittwald.de>
 * @package    Typo3Forum
 * @subpackage Domain_Model_Moderation
 * @version    $Id$
 *
 * @copyright  2012 Martin Helmich <m.helmich@mittwald.de>
 *             Mittwald CM Service GmbH & Co. KG
 *             http://www.mittwald.de
 * @license    GNU Public License, version 2
 *             http://opensource.org/licenses/gpl-license.php
 *
 */
class Report extends \TYPO3\CMS\Extbase\DomainObject\AbstractEntity {

	/*
	 * ATTRIBUTES
	 */


	/**
	 * The frontend user that created this post.
	 * @var \Mittwald\Typo3Forum\Domain\Model\User\FrontendUser
	 */
	protected $reporter;


	/**
	 * The moderator that is assigned to this report.
	 * @var \Mittwald\Typo3Forum\Domain\Model\User\FrontendUser
	 */
	protected $moderator;


	/**
	 * The current status of this report.
	 * @var \Mittwald\Typo3Forum\Domain\Model\Moderation\ReportWorkflowStatus
	 */
	protected $workflowStatus;


	/**
	 * A set of comments that are assigned to this report.
	 * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\Mittwald\Typo3Forum\Domain\Model\Moderation\ReportComment>
	 */
	protected $comments;


	/**
	 * The creation timestamp of this report.
	 * @var \DateTime
	 */
	protected $crdate;



	/*
	  * CONSTRUCTOR
	  */



	/**
	 * Creates a new report.
	 */
	public function __construct() {
		$this->comments = New \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
	}



	/*
	 * GETTERS
	 */



	/**
	 * Gets the reporter of this report.
	 * @return \Mittwald\Typo3Forum\Domain\Model\User\FrontendUser The reporter
	 */
	public function getReporter() {
		if ($this->reporter instanceof \TYPO3\CMS\Extbase\Persistence\Generic\LazyLoadingProxy) {
			$this->reporter->_loadRealInstance();
		}
		if ($this->reporter === NULL) {
			$this->reporter = new \Mittwald\Typo3Forum\Domain\Model\User\AnonymousFrontendUser();
		}
		return $this->reporter;
	}



	/**
	 * Gets the moderator that is assigned to this report.
	 * @return \Mittwald\Typo3Forum\Domain\Model\User\FrontendUser The moderator
	 */
	public function getModerator() {
		if ($this->moderator instanceof \TYPO3\CMS\Extbase\Persistence\Generic\LazyLoadingProxy) {
			$this->moderator->_loadRealInstance();
		}
		if ($this->moderator === NULL) {
			$this->moderator = new \Mittwald\Typo3Forum\Domain\Model\User\AnonymousFrontendUser();
		}
		return $this->moderator;
	}



	/**
	 * Gets the current status of this report.
	 * @return \Mittwald\Typo3Forum\Domain\Model\Moderation\ReportWorkflowStatus
	 *                             The current workflow status of this report.
	 */
	public function getWorkflowStatus() {
		return $this->workflowStatus;
	}



	/**
	 * Gets all comments for this report.
	 * @return \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\Mittwald\Typo3Forum\Domain\Model\Moderation\ReportComment>
	 *                             All comments for this report.
	 */
	public function getComments() {
		return $this->comments;
	}



	/**
	 * Returns the first comment for this report.
	 * @return \Mittwald\Typo3Forum\Domain\Model\Moderation\ReportComment The first comment.
	 */
	public function getFirstComment() {
		return array_shift($this->comments->toArray());
	}



	/**
	 * Returns the creation time of this report.
	 * @return \DateTime The creation time.
	 */
	public function getCrdate() {
		return $this->crdate;
	}



	/*
	 * SETTERS
	 */


	/**
	 * Sets the reporter.
	 *
	 * @param  \Mittwald\Typo3Forum\Domain\Model\User\FrontendUser $reporter The reporter.
	 * @return void
	 */
	public function setReporter(\Mittwald\Typo3Forum\Domain\Model\User\FrontendUser $reporter) {
		$this->reporter = $reporter;
	}



	/**
	 * Sets the moderator.
	 *
	 * @param  \Mittwald\Typo3Forum\Domain\Model\User\FrontendUser $moderator The moderator.
	 * @return void
	 */
	public function setModerator(\Mittwald\Typo3Forum\Domain\Model\User\FrontendUser $moderator) {
		$this->moderator = $moderator;
	}



	/**
	 * Sets the current workflow status.
	 *
	 * @param  \Mittwald\Typo3Forum\Domain\Model\Moderation\ReportWorkflowStatus $workflowStatus
	 *                             The workflow status.
	 * @return void
	 */
	public function setWorkflowStatus(\Mittwald\Typo3Forum\Domain\Model\Moderation\ReportWorkflowStatus $workflowStatus) {
		If (!$this->workflowStatus || ($this->workflowStatus && $this->workflowStatus->hasFollowupStatus($workflowStatus))) {
			$this->workflowStatus = $workflowStatus;
		}
	}



	/**
	 * Adds a comment to this report.
	 *
	 * @param  \Mittwald\Typo3Forum\Domain\Model\Moderation\ReportComment $comment A comment
	 * @return void
	 */
	public function addComment(\Mittwald\Typo3Forum\Domain\Model\Moderation\ReportComment $comment) {
		$comment->setReport($this);
		$this->comments->attach($comment);
	}



	/**
	 * Removes a comment from this report.
	 *
	 * @param  \Mittwald\Typo3Forum\Domain\Model\Moderation\ReportComment $comment A comment.
	 * @return void
	 */
	public function removeComment(\Mittwald\Typo3Forum\Domain\Model\Moderation\ReportComment $comment) {
		if (count($this->comments) === 1) {
			throw new \Mittwald\Typo3Forum\Domain\Exception\InvalidOperationException('You cannot delete the last remaining comment!', 1334687977);
		}
		$this->comments->detach($comment);
	}
}
