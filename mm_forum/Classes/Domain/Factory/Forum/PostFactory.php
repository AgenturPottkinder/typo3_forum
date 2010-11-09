<?php

Class Tx_MmForum_Domain_Factory_Forum_PostFactory
	Extends Tx_MmForum_Domain_Factory_AbstractFactory {

	Public Function createEmptyPost() {
		Return $this->getClassInstance();
	}

	Public Function createPostWithQuote(Tx_MmForum_Domain_Model_Forum_Post $quotedPost) {
		$post = $this->getClassInstance();
		$post->setText('[quote='.$quotedPost->getUid().']'.$quotedPost->getText().'[/quote]');

		Return $post;
	}

	Public Function assignUserToPost ( Tx_MmForum_Domain_Model_Forum_Post $post,
	                                   Tx_MmForum_Domain_Model_User_FrontendUser $user=NULL ) {
		If($user === NULL) $user = $this->getCurrentUser();

		If($post->getAuthor() !== NULL) {
			$post->getAuthor()->decreasePostCount();
			$this->userRepository->update($post->getAuthor());
		}

		$post->setAuthor($user);
		$user->increasePostCount();
		$this->userRepository->update($user);
	}

}

?>
