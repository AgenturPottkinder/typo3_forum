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
	 * A shadow topic. This type of topic is created when a topic is
	 * moved from one forum to another. The shadow topic remains in
	 * the original forum, while the topic itself is moved to the
	 * other forum.
	 *
	 * @author     Martin Helmich <m.helmich@mittwald.de>
	 * @package    MmForum
	 * @subpackage Domain_Model_Forum
	 * @version    $Id$
	 * @license    GNU Public License, version 2
	 *             http://opensource.org/licenses/gpl-license.php
	 *
	 */

Class Tx_MmForum_Domain_Model_Forum_ShadowTopic
	Extends Tx_MmForum_Domain_Model_Forum_Topic {





		/*
		 * ATTRIBUTES
		 */





		/**
		 * The target topic, i.e. the topic this shadow is pointing to.
		 * @var Tx_MmForum_Domain_Model_Forum_Topic
		 */

	Protected $target = NULL;





		/*
		 * GETTERS
		 */





		/**
		 *
		 * Gets the target topic, i.e. the topic this shadow is pointing to.
		 * @return Tx_MmForum_Domain_Model_Forum_Topic The target topic
		 *
		 */

	Public Function getTarget() { Return $this->target; }



		/**
		 *
		 * Checks if a user can create new posts inside this topic. Since this topic is
		 * only a shadow topic, this method will ALWAYS return FALSE.
		 *
		 * @param  Tx_MmForum_Domain_Model_User_FrontendUser $user The user.
		 * @return boolean TRUE, if the user can create new posts. Always FALSE.
		 *
		 */

	Public Function checkNewPostAccess(Tx_MmForum_Domain_Model_User_FrontendUser $user=NULL) {
		Return FALSE;
	}





		/*
		 * SETTERS
		 */





		/**
		 *
		 * Sets the target topic. Also reads the topic subject and the last post pointer
		 * from the target object.
		 *
		 * @param Tx_MmForum_Domain_Model_Forum_Topic $topic The target topic.
		 * @return void
		 *
		 */
	
	Public Function setTarget(Tx_MmForum_Domain_Model_Forum_Topic $topic) {
		$this->target = $topic;
		$this->lastPost = $topic->getLastPost();
		$this->subject = $topic->getSubject();
	}

}

?>