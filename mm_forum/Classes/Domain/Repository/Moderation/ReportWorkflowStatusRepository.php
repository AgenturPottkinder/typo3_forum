<?php

Class Tx_MmForum_Domain_Repository_Moderation_ReportWorkflowStatusRepository
	Extends Tx_MmForum_Domain_Repository_AbstractRepository {

	Public Function findInitial() {
		$query = $this->createQuery();
		Return array_pop($query
			->matching($query->equals('initial', TRUE))
			->setLimit(1)
			->execute());
	}

}

?>