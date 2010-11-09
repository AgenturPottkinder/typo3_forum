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
	 * A service class that handles the entire authentication.
	 *
	 * @author     Martin Helmich <m.helmich@mittwald.de>
	 * @package    MmForum
	 * @subpackage Domain_Service
	 * @version    $Id$
	 *
	 * @copyright  2010 Martin Helmich <m.helmich@mittwald.de>
	 *             Mittwald CM Service GmbH & Co. KG
	 *             http://www.mittwald.de
	 * @license    GNU Public License, version 2
	 *             http://opensource.org/licenses/gpl-license.php
	 *
	 */

Class Tx_MmForum_Domain_Service_AuthenticationService
	Extends    Tx_MmForum_Service_AbstractService
	Implements Tx_MmForum_Domain_Service_AuthenticationServiceInterface{





		/*
		 * ATTRIBUTES
		 */





		/**
		 * The current frontend user.
		 * @var Tx_MmForum_Domain_Model_User_FrontendUser
		 */
	Protected $user = NULL;





		/*
		 * INITIALIZATION
		 */





		/**
		 *
		 * Injects the current frontend user.
		 * @param Tx_MmForum_Domain_Model_User_FrontendUser $user
		 *                             The current frontend user.
		 * @return void
		 *
		 */

	Public Function injectFrontendUser(Tx_MmForum_Domain_Model_User_FrontendUser $user=NULL) {
		$this->user = $user;
	}





		/*
		 * AUTHENTICATION METHODS
		 */





		/**
		 *
		 * Asserts that the current user is authorized to read a specific object.
		 * @param  Tx_MmForum_Domain_Model_AccessibleInterface $object
		 *                             The object that is to be accessed.
		 * @return void
		 *
		 */

	Public Function assertReadAuthorization(Tx_MmForum_Domain_Model_AccessibleInterface $object) {
		If($this->settings['debug']['disableACLs']) Return TRUE;
		If(!$object->_checkAccess($user, 'read')) {
			$type = array_pop(explode('_',get_class($object)));
			Throw New Tx_MmForum_Domain_Exception_Authentication_NoAccessException
				("You are not authorized to access this $type!", 1284716340);
		}
	}



		/**
		 *
		 * Asserts that the current user is authorized to create a new topic in a
		 * certain forum.
		 * @param  Tx_MmForum_Domain_Model_Forum_Forum $forum
		 *                             The forum in which the new topic is to be created.
		 * @return void
		 *
		 */

	Public Function assertNewTopicAuthorization(Tx_MmForum_Domain_Model_Forum_Forum $forum) {
		If($this->settings['debug']['disableACLs']) Return TRUE;
		If($this->user === NULL)
			Throw New Tx_MmForum_Domain_Exception_Authentication_NotLoggedInException
				("You need to be logged in to create new topics!", 1284709853);
		If(!$forum->checkNewTopicAccess($this->user))
			Throw New Tx_MmForum_Domain_Exception_Authentication_NoAccessException
				("You are not authorized to create new topics!", 1284709854);
	}



		/**
		 *
		 * Asserts that the current user is authorized to create a new post within a
		 * topic.
		 * @param  Tx_MmForum_Domain_Model_Forum_Topic $topic
		 *                             The topic in which the new post is to be created.
		 * @return void
		 *
		 */

	Public Function assertNewPostAuthorization(Tx_MmForum_Domain_Model_Forum_Topic $topic) {
		If($this->settings['debug']['disableACLs']) Return TRUE;
		If($this->user === NULL)
			Throw New Tx_MmForum_Domain_Exception_Authentication_NotLoggedInException
				("You need to be logged in to write posts!", 1284709849);
		If(!$topic->checkNewPostAccess($this->user))
			Throw New Tx_MmForum_Domain_Exception_Authentication_NoAccessException
				("You are not authorized to create new posts!", 1284709850);
	}



		/**
		 *
		 * Asserts that the current user is authorized to edit an existing post.
		 * @param  Tx_MmForum_Domain_Model_Forum_Post $post
		 *                             The post that shall be edited.
		 * @return void
		 *
		 */

	Public Function assertEditPostAuthorization(Tx_MmForum_Domain_Model_Forum_Post $post) {
		If($this->settings['debug']['disableACLs']) Return TRUE;
		If($this->user === NULL)
			Throw New Tx_MmForum_Domain_Exception_Authentication_NotLoggedInException
				("You need to be logged in to edit posts!", 1284709851);
		If(!$post->checkEditPostAccess($this->user))
			Throw New Tx_MmForum_Domain_Exception_Authentication_NoAccessException
				("You are not authorized to edit this post!", 1284709852);
	}



		/**
		 *
		 * Asserts that the current user is authorized to delete a post.
		 * @param  Tx_MmForum_Domain_Model_Forum_Post $post
		 *                             The post that is to be deleted.
		 * @return void
		 *
		 */

	Public Function assertDeletePostAuthorization(Tx_MmForum_Domain_Model_Forum_Post $post) {
		If($this->settings['debug']['disableACLs']) Return TRUE;
		If($this->user === NULL)
			Throw New Tx_MmForum_Domain_Exception_Authentication_NotLoggedInException
				("You need to be logged in to delete posts!", 1284709851);
		If(!$post->checkDeletePostAccess($this->user))
			Throw New Tx_MmForum_Domain_Exception_Authentication_NoAccessException
				("You are not authorized to delete this post!", 1284709852);
	}



		/**
		 *
		 * Asserts that the current user has moderator access to a certain forum.
		 * @param  Tx_MmForum_Domain_Model_Forum_Forum $forum
		 *                             The forum that is to be moderated.
		 * @return void
		 *
		 */

	Public Function assertModerationAuthorization(Tx_MmForum_Domain_Model_Forum_Forum $forum) {
		If($this->settings['debug']['disableACLs']) Return TRUE;
		If($this->user === NULL)
			Throw New Tx_MmForum_Domain_Exception_Authentication_NotLoggedInException
				("You need to be logged in to edit posts!", 1284709851);
		If(!$forum->checkModerationAccess($this->user))
			Throw New Tx_MmForum_Domain_Exception_Authentication_NoAccessException
				("You are not authorized to moderate this forum!", 1284709852);
	}

}

?>
