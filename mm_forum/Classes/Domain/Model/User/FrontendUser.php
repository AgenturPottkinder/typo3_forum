<?php

/***************************************************************
*  Copyright notice
*
*  (c) 2010 Martin Helmich <m.helmich@mittwald.de>, Mittwald CM Service
*  			
*  All rights reserved
*
*  This script is part of the TYPO3 project. The TYPO3 project is
*  free software; you can redistribute it and/or modify
*  it under the terms of the GNU General Public License as published by
*  the Free Software Foundation; either version 2 of the License, or
*  (at your option) any later version.
*
*  The GNU General Public License can be found at
*  http://www.gnu.org/copyleft/gpl.html.
*
*  This script is distributed in the hope that it will be useful,
*  but WITHOUT ANY WARRANTY; without even the implied warranty of
*  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
*  GNU General Public License for more details.
*
*  This copyright notice MUST APPEAR in all copies of the script!
***************************************************************/

/**
 * User
 *
 * @version $Id$
 * @copyright Copyright belongs to the respective authors
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 */
Class Tx_MmForum_Domain_Model_User_FrontendUser Extends Tx_Extbase_Domain_Model_FrontendUser {





		/*
		 * ATTRIBUTES
		 */





		/**
		 *
		 * Forum post count
		 * @var integer
		 *
		 */

	Protected $postCount;



		/**
		 *
		 * @var string
		 *
		 */

	Protected $signature;



		/**
		 *
		 * topicSubscriptions
		 * @var Tx_Extbase_Persistence_ObjectStorage<Tx_MmForum_Domain_Model_Forum_Topic>
		 * @lazy
		 *
		 */
	Protected $topicSubscriptions;



		/**
		 *
		 * forumSubscriptions
		 * @var Tx_Extbase_Persistence_ObjectStorage<Tx_MmForum_Domain_Model_Forum_Forum>
		 * @lazy
		 *
		 */
	Protected $forumSubscriptions;



		/**
		 *
		 * @var DateTime
		 *
		 */

	Protected $crdate;





	/*
	 * GETTERS
	 */





		/**
		 *
		 * Getter for postCount
		 * @return integer Forum post count
		 *
		 */

	Public Function getPostCount() {
		Return $this->postCount;
	}



		/**
		 *
		 * Getter for topicSubscriptions
		 * @return Tx_Extbase_Persistence_ObjectStorage<Tx_MmForum_Domain_Model_Forum_Topic> topicSubscriptions
		 *
		 */

	Public Function getTopicSubscriptions() {
		Return $this->topicSubscriptions;
	}



		/**
		 *
		 * Getter for forumSubscriptions
		 * @return Tx_MmForum_Domain_Model_Forum_Forum forumSubscriptions
		 *
		 */

	Public Function getForumSubscriptions() {
		Return $this->forumSubscriptions;
	}



		/**
		 *
		 * Gets the user's registration date.
		 * @return DateTime The registration date
		 *
		 */

	Public Function getTimestamp() {
		Return $this->crdate;
	}
	
	
	
		/**
		 *
		 * Determines if this user is member of a specific group.
		 * 
		 * @param Tx_MmForum_Domain_Model_User_FrontendUserGroup $checkGroup
		 * @return boolean
		 * 
		 */
	
	Public Function isInGroup(Tx_MmForum_Domain_Model_User_FrontendUserGroup $checkGroup) {
		ForEach($this->getUsergroups() As $group) {
			If($group == $checkGroup) Return TRUE;
		} Return FALSE;
	}



		/**
		 *
		 * @return string
		 *
		 */
	
	Public Function getSignature() {
		Return $this->signature;
	}





		/*
		 * SETTERS
		 */





		/**
		 *
		 * Setter for postCount
		 *
		 * @param integer $postCount Forum post count
		 * @return void
		 *
		 */

	Public Function setPostCount($postCount) {
		$this->postCount = $postCount;
	}



		/**
		 *
		 * Subscribes this user to a subscribeable object, like a topic or a forum.
		 *
		 * @param Tx_MmForum_Domain_Model_SubscribeableInterface $object
		 * @return void
		 *
		 */
	
	Public Function addSubscription(Tx_MmForum_Domain_Model_SubscribeableInterface $object) {
		If($object InstanceOf Tx_MmForum_Domain_Model_Forum_Topic)
			$this->topicSubscriptions->attach($object);
		ElseIf($object InstanceOf Tx_MmForum_Domain_Model_Forum_Forum)
			$this->forumSubscriptions->attach($object);
	}



		/**
		 *
		 * Unsubscribes this user form a subscribeable object.
		 *
		 * @param Tx_MmForum_Domain_Model_SubscribeableInterface $object
		 * @return void
		 *
		 */

	Public Function removeSubscription(Tx_MmForum_Domain_Model_SubscribeableInterface $object) {
		If($object InstanceOf Tx_MmForum_Domain_Model_Forum_Topic)
			$this->topicSubscriptions->detach($object);
		ElseIf($object InstanceOf Tx_MmForum_Domain_Model_Forum_Forum)
			$this->forumSubscriptions->detach($object);
	}



		/**
		 *
		 * Decrease the user's post count.
		 * @return void
		 *
		 */
	
	Public Function decreasePostCount() {
		$this->postCount --;
	}



		/**
		 *
		 * Increase the user's post count.
		 * @return void;
		 *
		 */
	
	Public Function increasePostCount() {
		$this->postCount ++;
	}
	
}
?>
