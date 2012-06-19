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
 * A report workflow status.
 *
 * @author     Martin Helmich <m.helmich@mittwald.de>
 * @package    MmForum
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
class Tx_MmForum_Domain_Model_Moderation_ReportWorkflowStatus extends Tx_Extbase_DomainObject_AbstractValueObject {



	/*
	 * ATTRIBUTES
	 */



	/**
	 * The name.
	 * @var string
	 */
	protected $name;


	/**
	 * A list of allowed follow-up status.
	 * @var Tx_Extbase_Persistence_ObjectStorage<Tx_MmForum_Domain_Model_Moderation_ReportWorkflowStatus>
	 */
	protected $followupStatus;


	/**
	 * Defines whether this status shall be used as initial status for new reports.
	 * @var boolean
	 */
	protected $initial;


	/**
	 * Defines whether this status marks a final status of a report.
	 * @var boolean
	 */
	protected $final = FALSE;


	/**
	 * An icon filename.
	 * @var string
	 */
	protected $icon;



	/*
	 * CONSTRUCTOR
	 */



	/**
	 * Constructor.
	 * @param string  $name    The status name
	 * @param boolean $initial TRUE to mark this status as initial status.
	 * @param boolean $final   TRUE to mark this status as final status.
	 */
	public function __construct($name = NULL, $initial = NULL, $final = NULL) {
		$this->followupStatus = new Tx_Extbase_Persistence_ObjectStorage();
		$this->name           = $name;
		$this->initial        = $initial;
		$this->final          = $final;
	}



	/*
	  * GETTERS
	  */



	/**
	 * Gets the status name.
	 * @return string The status name.
	 */
	public function getName() {
		return $this->name;
	}



	/**
	 * Gets the allowed follow-up status.
	 * @return Tx_Extbase_Persistence_ObjectStorage<Tx_MmForum_Domain_Model_Moderation_ReportWorkflowStatus>
	 *                             The allowed follow-up status.
	 */
	public function getFollowupStatus() {
		return $this->followupStatus;
	}



	/**
	 * Determines if a workflow status is an allowed follow-up status for this status.
	 *
	 * @param  Tx_MmForum_Domain_Model_Moderation_ReportWorkflowStatus $status
	 *                             The status that is to be checked.
	 * @return boolean             TRUE, if $status is a valid follow-up status,
	 *                             otherwise FALSE.
	 */
	public function hasFollowupStatus(Tx_MmForum_Domain_Model_Moderation_ReportWorkflowStatus $status) {
		return $this->followupStatus->contains($status);
	}



	/**
	 * Determines if this status is the initial status for newly created reports.
	 * @return boolean TRUE, if this status is the initial status for newly created
	 *                 reports, otherwise FALSE.
	 */
	public function isInitial() {
		return $this->initial;
	}



	/**
	 * Determines if this status is a final status for edited reports.
	 * @return boolean TRUE, if this status is a final status for edited reports, otherwise FALSE.
	 */
	public function isFinal() {
		return $this->final;
	}



	/**
	 * Return the icon filename.
	 * @return string The icon filename.
	 */
	public function getIcon() {
		return $this->icon;
	}



	/**
	 * Returns the site relative path of this status' icon. This method first
	 * looks in the configured upload directory (uploads/tx_mmforum/workflowstatus
	 * by default) and the extensions' Resources/Public directory as fallback.
	 *
	 * @global type $TCA
	 * @return string The site relative path of this status' icon.
	 */
	public function getIconFullpath() {
		t3lib_div::loadTCA(strtolower(__CLASS__));
		global $TCA;

		$imageDirectoryName = $TCA[strtolower(__CLASS__)]['columns']['icon']['config']['uploadfolder'];
		$imageFilename      = rtrim($imageDirectoryName, '/') . '/' . $this->icon;

		if (!file_exists(PATH_site . '/' . $imageFilename)) {
			$imageDirectoryName = t3lib_extMgm::siteRelPath('mm_forum') . 'Resources/Public/Images/Icons/Moderation';
			$imageFilename      = "$imageDirectoryName/{$this->icon}";
		}

		return file_exists(PATH_site . '/' . $imageFilename) ? $imageFilename : NULL;
	}



	/*
	 * SETTERS
	 */



	/**
	 * Adds an additional allowed followup status.
	 * @param Tx_MmForum_Domain_Model_Moderation_ReportWorkflowStatus $followupStatus
	 */
	public function addAllowedFollowupStatus(Tx_MmForum_Domain_Model_Moderation_ReportWorkflowStatus $followupStatus) {
		$this->followupStatus->attach($followupStatus);
	}



}
