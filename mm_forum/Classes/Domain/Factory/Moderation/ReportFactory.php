<?php

Class Tx_MmForum_Domain_Factory_Moderation_ReportFactory
	Extends Tx_MmForum_Domain_Factory_AbstractFactory {

		/**
		 * @var Tx_MmForum_Domain_Repository_Moderation_ReportWorkflowStatusRepository
		 */
	Protected $workflowStatusRepository;

	Public Function __construct() {
		$this->workflowStatusRepository =&
			t3lib_div::makeInstance('Tx_MmForum_Domain_Repository_Moderation_ReportWorkflowStatusRepository');
	}

	Public Function createReport ( Tx_MmForum_Domain_Model_Moderation_ReportComment $firstComment,
	                               Tx_MmForum_Domain_Model_Forum_Post $post ) {
		$user =& $this->getCurrentUser();

		$post->setAuthor($user);
		$report = $this->getClassInstance();
		$report->setWorkflowStatus($this->workflowStatusRepository->findInitial());
		$report->setPost($post);
		$report->setReporter($user);
		$report->addComment($firstComment);

		Return $report;
	}

}

?>
