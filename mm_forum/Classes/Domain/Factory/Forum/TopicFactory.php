<?php

Class Tx_MmForum_Domain_Factory_Forum_TopicFactory
	Extends Tx_MmForum_Domain_Factory_AbstractFactory {

	Public Function createTopic ( Tx_MmForum_Domain_Model_Forum_Forum $forum,
	                              Tx_MmForum_Domain_Model_Forum_Post  $firstPost,
	                              $subject) {

		$topic = $this->getClassInstance();
		$topic->setForum($forum);
		$topic->addPost($firstPost);

		$topic->setSubject($subject);
		$topic->setAuthor($this->getCurrentUser());

		Return $topic;

	}

}

?>
