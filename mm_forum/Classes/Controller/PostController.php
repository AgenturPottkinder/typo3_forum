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
	 * @author     Martin Helmich <m.helmich@mittwald.de>
	 * @package    MmForum
	 * @subpackage Controller
	 * @version    $Id$
	 * @license    GNU Public License, version 2
	 *             http://opensource.org/licenses/gpl-license.php
	 */

Class Tx_MmForum_Controller_PostController Extends Tx_MmForum_Controller_AbstractController {





		/*
		 * ATTRIBUTES
		 */





		/**
		 * A forum repository.
		 * @var Tx_MmForum_Domain_Repository_Forum_ForumRepository
		 */
	Protected $forumRepository;

		/**
		 * A topic repository.
		 * @var Tx_MmForum_Domain_Repository_Forum_TopicRepository
		 */
	Protected $topicRepository;

		/**
		 * A post repository.
		 * @var Tx_MmForum_Domain_Repository_Forum_PostRepository
		 */
	Protected $postRepository;

		/**
		 * A post factory.
		 * @var Tx_MmForum_Domain_Factory_Forum_PostFactory
		 */
	Protected $postFactory;





		/*
		 * INITIALIZATION METHODS
		 */





		/**
		 *
		 * Initializes the current action
		 * @return void
		 *
		 */

	Protected Function initializeAction() {
		parent::initializeAction();
		$this->forumRepository =&
			t3lib_div::makeInstance('Tx_MmForum_Domain_Repository_Forum_ForumRepository');
		$this->postFactory =&
			t3lib_div::makeInstance('Tx_MmForum_Domain_Factory_Forum_PostFactory');
	}
	
	
	
		/**
		 * 
		 * Initializes the create action.
		 * @return void
		 * 
		 */
	
	Protected Function initializeCreateAction() {
		$this->topicRepository =&
			t3lib_div::makeInstance('Tx_MmForum_Domain_Repository_Forum_TopicRepository');
	}



		/**
		 *
		 * Initializes the update action.
		 * @return void
		 *
		 */

	Protected Function initializeUpdateAction() {
		$this->postRepository =&
			t3lib_div::makeInstance('Tx_MmForum_Domain_Repository_Forum_PostRepository');
	}





		/*
		 * ACTION METHODS
		 */





		/**
		 *
		 * Displays the form for creating a new post.
		 *
		 * @param Tx_MmForum_Domain_Model_Forum_Topic $topic
		 * @param Tx_MmForum_Domain_Model_Forum_Post $post
		 * @dontvalidate $post
		 *
		 */

	Public Function newAction ( Tx_MmForum_Domain_Model_Forum_Topic $topic,
	                            Tx_MmForum_Domain_Model_Forum_Post  $post  = NULL,
								Tx_MmForum_Domain_Model_Forum_Post  $quote = NULL ) {

		$this->authenticationService->assertNewPostAuthorization($topic);

		If($post === NULL)
			$post = ($quote !== NULL) ? $this->postFactory->createPostWithQuote($quote) : $this->postFactory->createEmptyPost();

		$this->view->assign('topic', $topic)
		           ->assign('post',  $post);
	}



		/**
		 *
		 * Creates a new post.
		 *
		 * @param Tx_MmForum_Domain_Model_Forum_Topic $topic
		 * @param Tx_MmForum_Domain_Model_Forum_Post $post
		 * @return void
		 *
		 */

	Public Function createAction(Tx_MmForum_Domain_Model_Forum_Topic $topic, Tx_MmForum_Domain_Model_Forum_Post $post) {

		$this->authenticationService->assertNewPostAuthorization($topic);

		$this->postFactory->assignUserToPost($post);
		$topic->addPost($post);
		$this->topicRepository->update($topic);

		$this->flashMessages->add('Beitrag gespeichert.');
		$this->redirect('show', 'Topic', NULL, Array('topic' => $topic));
	}



		/**
		 *
		 * Displays a form for editing a post.
		 *
		 * @param Tx_MmForum_Domain_Model_Forum_Post $post
		 * @return void
		 * 
		 */

	Public Function editAction(Tx_MmForum_Domain_Model_Forum_Post $post) {

		$this->authenticationService->assertEditPostAuthorization($post);
		$this->view->assign('post', $post);

	}



		/**
		 *
		 * Updates a post
		 * @param Tx_MmForum_Domain_Model_Forum_Post $post
		 * @return void
		 * 
		 */
	Public Function updateAction(Tx_MmForum_Domain_Model_Forum_Post $post) {

		$this->authenticationService->assertEditPostAuthorization($post);

		$this->postRepository->update($post);

		$this->flashMessages->add('Post successfully edited.');
		$this->redirect('show', 'Topic', NULL, array('topic' => $post->getTopic()));

	}

}
?>