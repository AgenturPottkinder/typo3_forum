<?php

Class Tx_MmForum_Domain_Service_AuthenticationService {

	Protected $settings;

	Protected $user = NULL;

	Public Function injectSettings($settings) {
		$this->settings = $settings;
	}

	Public Function injectFrontendUser(Tx_MmForum_Domain_Model_User_FrontendUser $user=NULL) {
		$this->user = $user;
	}

	Public Function assertReadAuthorization(Tx_MmForum_Domain_Model_AccessibleInterface $object) {
		If(!$this->settings['debug']['disableACLs']) Return TRUE;
		If(!$object->_checkAccess($user, 'read')) {
			$type = array_pop(explode('_',get_class($object)));
			Throw New Tx_MmForum_Domain_Exception_Authentication_NoAccessException
				("You are not authorized to access this $type!", 1284716340);
		}
	}

	Public Function assertNewTopicAuthorization(Tx_MmForum_Domain_Model_Forum_Forum $forum) {
		If(!$this->settings['debug']['disableACLs']) Return TRUE;
		If($this->user === NULL)
			Throw New Tx_MmForum_Domain_Exception_Authentication_NotLoggedInException
				("You need to be logged in to create new topics!", 1284709853);
		If(!$forum->checkNewTopicAccess($this->user))
			Throw New Tx_MmForum_Domain_Exception_Authentication_NoAccessException
				("You are not authorized to create new topics!", 1284709854);
	}

	Public Function assertNewPostAuthorization(Tx_MmForum_Domain_Model_Forum_Topic $topic) {
		If(!$this->settings['debug']['disableACLs']) Return TRUE;
		If($this->user === NULL)
			Throw New Tx_MmForum_Domain_Exception_Authentication_NotLoggedInException
				("You need to be logged in to write posts!", 1284709849);
		If(!$topic->checkNewPostAccess($this->user))
			Throw New Tx_MmForum_Domain_Exception_Authentication_NoAccessException
				("You are not authorized to create new posts!", 1284709850);
	}

	Public Function assertEditPostAuthorization(Tx_MmForum_Domain_Model_Forum_Post $post) {
		If(!$this->settings['debug']['disableACLs']) Return TRUE;
		If($this->user === NULL)
			Throw New Tx_MmForum_Domain_Exception_Authentication_NotLoggedInException
				("You need to be logged in to edit posts!", 1284709851);
		If(!$post->checkEditPostAccess($this->user))
			Throw New Tx_MmForum_Domain_Exception_Authentication_NoAccessException
				("You are not authorized to edit this post!", 1284709852);
	}

}

?>
