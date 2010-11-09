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
	 * Post factory class. Is used to encapsulate post creation logic from the controller
	 * classes.
	 *
	 * @author     Martin Helmich <m.helmich@mittwald.de>
	 * @package    MmForum
	 * @subpackage Domain_Factory_Forum
	 * @version    $Id$
	 *
	 * @copyright  2010 Martin Helmich <m.helmich@mittwald.de>
	 *             Mittwald CM Service GmbH & Co. KG
	 *             http://www.mittwald.de
	 * @license    GNU Public License, version 2
	 *             http://opensource.org/licenses/gpl-license.php
	 *
	 */

Class Tx_MmForum_Domain_Factory_Forum_PostFactory
	Extends Tx_MmForum_Domain_Factory_AbstractFactory {





		/*
		 * ATTRIBUTES
		 */





		/**
		 * The post repository.
		 * @var Tx_MmForum_Domain_Repository_Forum_PostRepository
		 */
	Protected $postRepository = NULL;

		/**
		 * The topic repository.
		 * @var Tx_MmForum_Domain_Repository_Forum_TopicRepository
		 */
	Protected $topicRepository = NULL;





		/*
		 * METHODS
		 */





		/**
		 *
		 * Creates an empty post
		 * @return Tx_MmForum_Domain_Model_Forum_Post An empty post.
		 *
		 */

	Public Function createEmptyPost() {
		Return $this->getClassInstance();
	}



		/**
		 *
		 * Creates a new post that quotes an already existing post.
		 * @param Tx_MmForum_Domain_Model_Forum_Post $quotedPost
		 *                             The post that is to be quoted. The post text of
		 *                             this post will be wrapped in [quote] bb codes.
		 * @return Tx_MmForum_Domain_Model_Forum_Post
		 *                             The new post.
		 *
		 */

	Public Function createPostWithQuote(Tx_MmForum_Domain_Model_Forum_Post $quotedPost) {
		$post = $this->getClassInstance();
		$post->setText('[quote='.$quotedPost->getUid().']'.$quotedPost->getText().'[/quote]');

		Return $post;
	}



		/**
		 *
		 * Assigns a user to a forum post and increases the user's post count.
		 * @param Tx_MmForum_Domain_Model_Forum_Post $post
		 *                             The post to which a user is to be assigned.
		 * @param Tx_MmForum_Domain_Model_User_FrontendUser $user
		 *                             The user that is to be assigned to the post. If
		 *                             this value is NULL, the currently logged in user
		 *                             will be used instead.
		 *
		 */
	
	Public Function assignUserToPost ( Tx_MmForum_Domain_Model_Forum_Post $post,
	                                   Tx_MmForum_Domain_Model_User_FrontendUser $user=NULL ) {

			# If no user is set, use current user is set.
		If($user === NULL) $user = $this->getCurrentUser();

			# If still no user is set, abort.
		If($user === NULL) Throw New Tx_MmForum_Domain_Exception_Authentication_NotLoggedInException();

			# If the post's author is already set, decrease this user's post count.
		If($post->getAuthor() !== NULL) {
			$post->getAuthor()->decreasePostCount();
			$this->userRepository->update($post->getAuthor());
		}

			# Increase the new user's post count.
		$post->setAuthor($user);
		$user->increasePostCount();
		$this->frontendUserRepository->update($user);
	}



		/**
		 *
		 * Deletes a post and decreases the user's post count by 1.
		 * @param Tx_MmForum_Domain_Model_Forum_Post $post
		 *
		 */
	
	Public Function deletePost ( Tx_MmForum_Domain_Model_Forum_Post $post ) {
		$topic = $post->getTopic();

			# If the post is the only one in the topic, delete the whole topic instead of
			# this single post. Empty topics are not allowed.
		If($topic->getPostCount() === 1) {
			$topicFactory
				=& t3lib_div::makeInstance('Tx_MmForum_Domain_Factory_Forum_TopicFactory');
			$topicFactory->deleteTopic($topic);
		} Else {
			$this->topicRepository
				=& t3lib_div::makeInstance('Tx_MmForum_Domain_Repository_Forum_TopicRepository');
			$post->getAuthor()->decreasePostCount();
			$this->frontendUserRepository->update($user);
			$topic->removePost($post);
			$this->topicRepository->update($topic);
		}
	}

}

?>
