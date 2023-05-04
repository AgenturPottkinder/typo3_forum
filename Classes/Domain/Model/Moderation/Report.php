<?php
namespace Mittwald\Typo3Forum\Domain\Model\Moderation;

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

use Mittwald\Typo3Forum\Domain\Exception\InvalidOperationException;
use Mittwald\Typo3Forum\Domain\Model\User\AnonymousFrontendUser;
use Mittwald\Typo3Forum\Domain\Model\User\FrontendUser;
use TYPO3\CMS\Extbase\DomainObject\AbstractEntity;
use TYPO3\CMS\Extbase\Persistence\Generic\LazyLoadingProxy;
use TYPO3\CMS\Extbase\Persistence\ObjectStorage;

/**
 * Models a post report. Reports are the central object of the moderation
 * component of the typo3_forum extension. Each user can report a forum post
 * to the respective moderator group. In this case, a report object is
 * created.
 *
 * These report objects can be assigned to moderators ans be organized in
 * different workflow stages. Moderators can post comments to each report.
 */
class Report extends AbstractEntity
{

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

    /**
     * Creates a new report.
     */
    public function __construct()
    {
        $this->comments = new ObjectStorage();
    }

    /**
     * Gets the reporter of this report.
     * @return FrontendUser The reporter
     */
    public function getReporter()
    {
        if ($this->reporter instanceof LazyLoadingProxy) {
            $this->reporter->_loadRealInstance();
        }
        if ($this->reporter === null) {
            $this->reporter = new AnonymousFrontendUser();
        }

        return $this->reporter;
    }

    /**
     * Sets the reporter.
     *
     * @param FrontendUser $reporter The reporter.
     */
    public function setReporter(FrontendUser $reporter)
    {
        $this->reporter = $reporter;
    }

    /**
     * Gets the moderator that is assigned to this report.
     * @return FrontendUser The moderator
     */
    public function getModerator()
    {
        if ($this->moderator instanceof LazyLoadingProxy) {
            $this->moderator->_loadRealInstance();
        }
        if ($this->moderator === null) {
            $this->moderator = new AnonymousFrontendUser();
        }

        return $this->moderator;
    }

    /**
     * Sets the moderator.
     *
     * @param FrontendUser $moderator The moderator.
     */
    public function setModerator(FrontendUser $moderator)
    {
        $this->moderator = $moderator;
    }

    /**
     * Gets the current status of this report.
     * @return ReportWorkflowStatus The current workflow status of this report.
     */
    public function getWorkflowStatus()
    {
        return $this->workflowStatus;
    }

    /**
     * Sets the current workflow status.
     *
     * @param ReportWorkflowStatus $workflowStatus The workflow status.
     */
    public function setWorkflowStatus(ReportWorkflowStatus $workflowStatus)
    {
        if (!$this->workflowStatus || ($this->workflowStatus && $this->workflowStatus->hasFollowupStatus($workflowStatus))) {
            $this->workflowStatus = $workflowStatus;
        }
    }

    /**
     * Gets all comments for this report.
     * @return ObjectStorage<\Mittwald\Typo3Forum\Domain\Model\Moderation\ReportComment>
     *                             All comments for this report.
     */
    public function getComments()
    {
        return $this->comments;
    }

    /**
     * Returns the first comment for this report.
     * @return ReportComment The first comment.
     */
    public function getFirstComment()
    {
        return array_shift($this->comments->toArray());
    }

    /**
     * Returns the creation time of this report.
     * @return \DateTime The creation time.
     */
    public function getCrdate()
    {
        return $this->crdate;
    }

    /**
     * Adds a comment to this report.
     *
     * @param ReportComment $comment A comment
     */
    public function addComment(ReportComment $comment)
    {
        $comment->setReport($this);
        $this->comments->attach($comment);
    }

    /**
     * Removes a comment from this report.
     *
     * @param ReportComment $comment
     * @throws InvalidOperationException
     */
    public function removeComment(ReportComment $comment)
    {
        if (count($this->comments) === 1) {
            throw new InvalidOperationException('You cannot delete the last remaining comment!', 1334687977);
        }
        $this->comments->detach($comment);
    }
}
