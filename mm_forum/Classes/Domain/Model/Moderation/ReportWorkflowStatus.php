<?php

Class Tx_MmForum_Domain_Model_Moderation_ReportWorkflowStatus
	Extends Tx_Extbase_DomainObject_AbstractEntity {

		/*
		 * ATTRIBUTES
		 */

		/**
		 * @var string
		 */
	Protected $name;

		/**
		 * @var Tx_Extbase_Persistence_ObjectStorage<Tx_MmForum_Domain_Model_Moderation_ReportWorkflowStatus>
		 */
	Protected $followupStatus;

		/**
		 * @var boolean
		 */
	Protected $initial;

		/*
		 * GETTERS
		 */

	Public Function getName() {
		Return $this->name;
	}

	Public Function getFollowupStatus() {
		Return $this->followupStatus;
	}

	Public Function hasFollowupStatus(Tx_MmForum_Domain_Model_Moderation_ReportWorkflowStatus $status) {
		ForEach($this->followupStatus As $followupStatus)
			If($followupStatus->getUid() == $status->getUid()) Return TRUE;
		Return FALSE;
	}

	Public Function isInitial() {
		Return $this->initial;
	}

}

?>