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

use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\DomainObject\AbstractValueObject;
use TYPO3\CMS\Extbase\Persistence\ObjectStorage;

/**
 * A report workflow status.
 */
class ReportWorkflowStatus extends AbstractValueObject
{

    /**
     * The name.
     */
    protected string $name = '';

    /**
     * A list of allowed follow-up status.
     * @var ObjectStorage<ReportWorkflowStatus>
     */
    protected ObjectStorage $followupStatus;

    /**
     * Defines whether this status shall be used as initial status for new reports.
     */
    protected bool $initial = false;

    /**
     * Defines whether this status marks a final status of a report.
     */
    protected bool $final = false;

    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->initializeObject();
    }

    public function initializeObject(): void
    {
        $this->ensureObjectStorages();
    }

    protected function ensureObjectStorages(): void
    {
        if (!isset($this->followupStatus)) {
            $this->followupStatus = GeneralUtility::makeInstance(ObjectStorage::class);
        }
    }

    /**
     * Gets the status name.
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Gets the allowed follow-up status.
     * @return ObjectStorage<ReportWorkflowStatus>
     */
    public function getFollowupStatus(): ObjectStorage
    {
        return $this->followupStatus;
    }

    /**
     * Determines if a workflow status is an allowed follow-up status for this status.
     */
    public function hasFollowupStatus(ReportWorkflowStatus $status): bool
    {
        return $this->followupStatus->contains($status);
    }

    /**
     * Determines if this status is the initial status for newly created reports.
     */
    public function isInitial(): bool
    {
        return $this->initial;
    }

    /**
     * Determines if this status is a final status for edited reports.
     */
    public function isFinal(): bool
    {
        return $this->final;
    }

    /**
     * Adds an additional allowed followup status.
     */
    public function addAllowedFollowupStatus(ReportWorkflowStatus $followupStatus): self
    {
        $this->followupStatus->attach($followupStatus);

        return $this;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function setInitial(bool $initial): self
    {
        $this->initial = $initial;

        return $this;
    }

    public function setFinal(bool $final): self
    {
        $this->final = $final;

        return $this;
    }
}
