<?php

/*                                                                      *
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
	 * A report workflow status.
	 *
	 * @author     Martin Helmich <m.helmich@mittwald.de>
	 * @package    MmForum
	 * @subpackage Domain_Model_Moderation
	 * @version    $Id$
	 *
	 * @copyright  2010 Martin Helmich <m.helmich@mittwald.de>
	 *             Mittwald CM Service GmbH & Co. KG
	 *             http://www.mittwald.de
	 * @license    GNU Public License, version 2
	 *             http://opensource.org/licenses/gpl-license.php
	 *
	 */

Class Tx_MmForum_Domain_Model_Moderation_ReportWorkflowStatus
	Extends Tx_Extbase_DomainObject_AbstractValueObject {





		/*
		 * ATTRIBUTES
		 */





		/**
		 * The name.
		 * @var string
		 */
	Protected $name;

		/**
		 * A list of allowed follow-up status.
		 * @var Tx_Extbase_Persistence_ObjectStorage<Tx_MmForum_Domain_Model_Moderation_ReportWorkflowStatus>
		 */
	Protected $followupStatus;

		/**
		 * Defines whether this status shall be used as initial status for new reports.
		 * @var boolean
		 */
	Protected $initial;





		/*
		 * GETTERS
		 */





		/**
		 *
		 * Gets the status name.
		 * @return string The status name.
		 *
		 */
	
	Public Function getName() { Return $this->name; }



		/**
		 *
		 * Gets the allowed follow-up status.
		 * @return Tx_Extbase_Persistence_ObjectStorage<Tx_MmForum_Domain_Model_Moderation_ReportWorkflowStatus>
		 *                             The allowed follow-up status.
		 *
		 */
	Public Function getFollowupStatus() { Return $this->followupStatus; }



		/**
		 *
		 * Determines if a workflow status is an allowed follow-up status for this status.
		 *
		 * @param  Tx_MmForum_Domain_Model_Moderation_ReportWorkflowStatus $status
		 *                             The status that is to be checked.
		 * @return boolean             TRUE, if $status is a valid follow-up status,
		 *                             otherwise FALSE.
		 *
		 */

	Public Function hasFollowupStatus(Tx_MmForum_Domain_Model_Moderation_ReportWorkflowStatus $status) {
		ForEach($this->followupStatus As $followupStatus)
			If($followupStatus->getUid() == $status->getUid()) Return TRUE;
		Return FALSE;
	}



		/**
		 *
		 * Determines if this status is the initial status for newly created reports.
		 * @return boolean TRUE, if this status is the initial status for newly created
		 *                 reports, otherwise FALSE.
		 *
		 */
	
	Public Function isInitial() { Return $this->initial; }

}

?>