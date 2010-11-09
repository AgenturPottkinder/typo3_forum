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
	 * Controller for the Forum object
	 *
	 * @author     Martin Helmich <m.helmich@mittwald.de>
	 * @package    MmForum
	 * @subpackage Controller
	 * @version    $Id$
	 * @license    GNU Public License, version 2
	 *             http://opensource.org/licenses/gpl-license.php
	 */

Class Tx_MmForum_Controller_TopicController Extends Tx_MmForum_Controller_AbstractController {





		/*
		 * ATTRIBUTES
		 */





		/**
		 * @var Tx_MmForum_Domain_Repository_Forum_TopicRepository
		 */
	Protected $topicRepository;

		/**
		 * @var Tx_MmForum_Domain_Repository_Forum_ForumRepository
		 */
	Protected $forumRepository;

		/**
		 * @var Tx_MmForum_Domain_Factory_Forum_TopicFactory
		 */
	Protected $topicFactory;

		/**
		 * @var Tx_MmForum_Domain_Factory_Forum_PostFactory
		 */
	Protected $postFactory;





		/*
		 * INITIALIZATION METHODS
		 */





	Protected Function initializeCreateAction() {
		$this->topicRepository =&
			t3lib_div::makeInstance('Tx_MmForum_Domain_Repository_Forum_TopicRepository');
		$this->forumRepository =&
			t3lib_div::makeInstance('Tx_MmForum_Domain_Repository_Forum_ForumRepository');
		$this->topicFactory =&
			t3lib_div::makeInstance('Tx_MmForum_Domain_Factory_Forum_TopicFactory');
		$this->postFactory =&
			t3lib_div::makeInstance('Tx_MmForum_Domain_Factory_Forum_PostFactory');
	}





		/*
		 * ACTION METHOD
		 */





		/**
		 *
		 * @param Tx_MmForum_Domain_Model_Forum_Topic $topic The topic to be displayed.
		 * @param integer $page
		 * @return void
		 *
		 */

	Public Function showAction(Tx_MmForum_Domain_Model_Forum_Topic $topic, $page=1) {
		$this->authenticationService->assertReadAuthorization($topic);
		$this->view->assign('topic', $topic);
	}



		/**
		 *
		 * @param Tx_MmForum_Domain_Model_Forum_Forum $forum
		 * @param Tx_MmForum_Domain_Model_Forum_Topic $topic
		 * @param string $subject
		 * @dontvalidate $topic
		 *
		 */

	Public Function newAction ( Tx_MmForum_Domain_Model_Forum_Forum $forum,
	                            Tx_MmForum_Domain_Model_Forum_Post $post = NULL,
	                            $subject = NULL) {
		$this->authenticationService->assertNewTopicAuthorization($forum);

		$this->view->assign('forum', $forum)
		           ->assign('post', $post)
		           ->assign('subject', $subject);
	}



		/**
		 *
		 * @param Tx_MmForum_Domain_Model_Forum_Forum $forum
		 * @param Tx_MmForum_Domain_Model_Forum_Post $post
		 * @param string $subject
		 *
		 */

	Public Function createAction ( Tx_MmForum_Domain_Model_Forum_Forum $forum,
	                               Tx_MmForum_Domain_Model_Forum_Post $post,
	                               $subject ) {
		$this->authenticationService->assertNewTopicAuthorization($forum);

		$this->postFactory->assignUserToPost($post);
		$topic = $this->topicFactory->createTopic($forum, $post, $subject);

		$forum->addTopic($topic);
		$this->forumRepository->update($forum);

		$this->redirect('show', 'Forum', NULL, array('forum' => $forum));
	}

}
?>