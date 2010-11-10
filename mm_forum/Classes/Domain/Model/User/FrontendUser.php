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
	 * A frontend user.
	 *
	 * @author     Martin Helmich <m.helmich@mittwald.de>
	 * @package    MmForum
	 * @subpackage Domain_Model_User
	 * @version    $Id$
	 *
	 * @copyright  2010 Martin Helmich <m.helmich@mittwald.de>
	 *             Mittwald CM Service GmbH & Co. KG
	 *             http://www.mittwald.de
	 * @license    GNU Public License, version 2
	 *             http://opensource.org/licenses/gpl-license.php
	 *
	 */

Class Tx_MmForum_Domain_Model_User_FrontendUser Extends Tx_Extbase_Domain_Model_FrontendUser {





		/*
		 * ATTRIBUTES
		 */





		/**
		 * Forum post count
		 * @var integer
		 */
	Protected $postCount;

		/**
		 * The signature. This will be displayed below this user's posts.
		 * @var string
		 */
	Protected $signature;

		/**
		 * Subscribed topics.
		 * @var Tx_Extbase_Persistence_ObjectStorage<Tx_MmForum_Domain_Model_Forum_Topic>
		 * @lazy
		 */
	Protected $topicSubscriptions;

		/**
		 * Subscribed forums.
		 * @var Tx_Extbase_Persistence_ObjectStorage<Tx_MmForum_Domain_Model_Forum_Forum>
		 * @lazy
		 */
	Protected $forumSubscriptions;

		/**
		 * The creation date of this user.
		 * @var DateTime
		 */
	Protected $crdate;

		/**
		 * Userfield values.
		 * @var Tx_Extbase_Persistence_ObjectStorage<Tx_MmForum_Domain_Model_User_Userfield_Value>
		 */
	Protected $userfieldValues;

		/**
		 * Read topics.
		 * @var Tx_Extbase_Persistence_ObjectStorage<Tx_MmForum_Domain_Model_Forum_Topic>
		 */
	Protected $readTopics;

		/**
		 * The country.
		 * @var string
		 */
	Protected $staticInfoCountry;

		/**
		 * The gender.
		 * @var integer
		 */
	Protected $gender;




	Public Function  __construct($username = '', $password = '') {
		parent::__construct($username, $password);

		$this->readTopics = New Tx_Extbase_Persistence_ObjectStorage();
	}

		/*
		 * GETTERS
		 */





		/**
		 *
		 * Gets the post count of this user.
		 * @return integer The post count.
		 *
		 */

	Public Function getPostCount() { Return $this->postCount; }



		/**
		 *
		 * Gets the subscribed topics.
		 * @return Tx_Extbase_Persistence_ObjectStorage<Tx_MmForum_Domain_Model_Forum_Topic>
		 *                             The subscribed topics.
		 *
		 */

	Public Function getTopicSubscriptions() { Return $this->topicSubscriptions; }



		/**
		 *
		 * Gets the subscribed forums.
		 * @return Tx_Extbase_Persistence_ObjectStorage<Tx_MmForum_Domain_Model_Forum_Forum>
		 *                             The subscribed forums.
		 *
		 */

	Public Function getForumSubscriptions() { Return $this->forumSubscriptions; }



		/**
		 *
		 * Gets the user's registration date.
		 * @return DateTime The registration date
		 *
		 */

	Public Function getTimestamp() { Return $this->crdate; }



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
		 * Gets the user's signature.
		 * @return string The signature.
		 *
		 */

	Public Function getSignature() { Return $this->signature; }



		/**
		 *
		 * Gets the userfield values for this user.
		 * @return Tx_Extbase_Persistence_ObjectStorage<Tx_MmForum_Domain_Model_User_Userfield_Value>
		 *
		 */

	Public Function getUserfieldValues() { Return $this->userfieldValues; }





		/*
		 * SETTERS
		 */





		/**
		 *
		 * Subscribes this user to a subscribeable object, like a topic or a forum.
		 *
		 * @param Tx_MmForum_Domain_Model_SubscribeableInterface $object
		 *                             The object that is to be subscribed. This may
		 *                             either be a topic or a forum.
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
		 * Unsubscribes this user from a subscribeable object.
		 *
		 * @param Tx_MmForum_Domain_Model_SubscribeableInterface $object
		 *                             The object that is to be unsubscribed.
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
		 * Adds a readable object to the list of objects read by this user.
		 *
		 * @param  Tx_MmForum_Domain_Model_ReadableInterface $readObject
		 *                             The object that is to be marked as read.
		 * @return void
		 *
		 */

	Public Function addReadObject(Tx_MmForum_Domain_Model_ReadableInterface $readObject) {
		If($readObject InstanceOf Tx_MmForum_Domain_Model_Forum_Topic)
			$this->readTopics->attach($readObject);
	}



		/**
		 *
		 * Removes a readable object from the list of objects read by this user.
		 *
		 * @param  Tx_MmForum_Domain_Model_ReadableInterface $readObject
		 *                             The object that is to be marked as unread.
		 * @return void
		 *
		 */

	Public Function removeReadObject(Tx_MmForum_Domain_Model_ReadableInterface $readObject) {
		If($readObject InstanceOf Tx_MmForum_Domain_Model_Forum_Topic)
			$this->readTopics->detach($readObject);
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
