<?php

Class Tx_MmForum_Domain_Model_Moderation_Report
	Extends Tx_Extbase_DomainObject_AbstractEntity {

		/*
		 * ATTRIBUTES
		 */

		/**
		 * @var Tx_MmForum_Domain_Model_Forum_Post
		 */
	Protected $post;

		/**
		 * @var Tx_MmForum_Domain_Model_User_FrontendUser
		 */
	Protected $reporter;

		/**
		 * @var Tx_MmForum_Domain_Model_User_FrontendUser
		 */
	Protected $moderator;

		/**
		 * @var Tx_MmForum_Domain_Model_Moderation_ReportWorkflowStatus
		 */
	Protected $workflowStatus;

		/**
		 * @var Tx_Extbase_Persistence_ObjectStorage<Tx_MmForum_Domain_Model_Moderation_ReportComment>
		 */
	Protected $comments;

	Public Function __construct() {
		$this->comments = New Tx_Extbase_Persistence_ObjectStorage();
	}

		/*
		 * GETTERS
		 */

	Public Function getPost() {
		Return $this->post;
	}

	Public Function getReporter() {
		Return $this->reporter;
	}

	Public Function getModerator() {
		Return $this->moderator;
	}

	Public Function getWorkflowStatus() {
		Return $this->workflowStatus;
	}

	Public Function getComments() {
		Return $this->comments;
	}

		/*
		 * SETTERS
		 */

	Public Function setPost(Tx_MmForum_Domain_Model_Forum_Post $post) {
		$this->post = $post;
	}

	Public Function setReporter(Tx_MmForum_Domain_Model_User_FrontendUser $reporter) {
		$this->reporter = $reporter;
	}

	Public Function setModerator(Tx_MmForum_Domain_Model_User_FrontendUser $moderator) {
		$this->moderator = $moderator;
	}

	Public Function setWorkflowStatus(Tx_MmForum_Domain_Model_Moderation_ReportWorkflowStatus $workflowStatus) {
		If(!$this->workflowStatus || ($this->workflowStatus && $this->workflowStatus->hasFollowupStatus($workflowStatus)))
			$this->workflowStatus = $workflowStatus;
	}

	Public Function addComment(Tx_MmForum_Domain_Model_Moderation_ReportComment $comment) {
		$this->comments->attach($comment);
	}

	Public Function removeComment(Tx_MmForum_Domain_Model_Moderation_ReportComment $comment) {
		$this->comments->detatch($comment);
	}

}

?>
