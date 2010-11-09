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
	 * Controller for special moderator options.
	 *
	 * @author     Martin Helmich <m.helmich@mittwald.de>
	 * @package    MmForum
	 * @subpackage Controller
	 * @version    $Id$
	 *
	 * @copyright  2010 Martin Helmich <m.helmich@mittwald.de>
	 *             Mittwald CM Service GmbH & Co. KG
	 *             http://www.mittwald.de
	 * @license    GNU Public License, version 2
	 *             http://opensource.org/licenses/gpl-license.php
	 *
	 */

Class Tx_MmForum_Controller_ModerationController Extends Tx_MmForum_Controller_AbstractController {





		/*
		 * ATTRIBUTES
		 */





		/**
		 * The topic repository.
		 * @var Tx_MmForum_Domain_Repository_Forum_TopicRepository
		 */
	Protected $topicRepository;

		/**
		 * The topic factory.
		 * @var Tx_MmForum_Domain_Factory_Forum_TopicFactory
		 */
	Protected $topicFactory;





		/*
		 * INITIALIZATION METHODS
		 */





		/**
		 *
		 * Initializes the update action.
		 * @return void
		 *
		 */

	Protected Function initializeUpdateTopicAction() {
		$this->topicRepository
			=& t3lib_div::makeInstance('Tx_MmForum_Domain_Repository_Forum_TopicRepository');
		$this->topicFactory
			=& t3lib_div::makeInstance('Tx_MmForum_Domain_Factory_Forum_TopicFactory');
	}





		/*
		 * ACTION METHODS
		 */





		/**
		 *
		 * Displays a form for editing a topic with special moderator-powers!
		 *
		 * @param  Tx_MmForum_Domain_Model_Forum_Topic $topic
		 *                             The topic that is to be edited.
		 * @return void
		 *
		 */

	Public Function editTopicAction(Tx_MmForum_Domain_Model_Forum_Topic $topic) {
		$this->authenticationService->assertModerationAuthorization($topic->getForum());
		$this->view->assign('topic', $topic);
	}

		/**
		 *
		 * Updates a forum with special super-moderator-powers!
		 *
		 * @param Tx_MmForum_Domain_Model_Forum_Topic $topic
		 *                             The topic that is be edited.
		 * @param boolean $moveTopic   TRUE, if the topic is to be moved to another
		 *                             forum.
		 * @param Tx_MmForum_Domain_Model_Forum_Forum $moveTopicTarget
		 *                             The forum to which the topic is to be moved.
		 *
		 */

	Public Function updateTopicAction ( Tx_MmForum_Domain_Model_Forum_Topic $topic,
	                                    $moveTopic = FALSE,
	                                    Tx_MmForum_Domain_Model_Forum_Forum $moveTopicTarget = NULL ) {
		$this->authenticationService->assertModerationAuthorization($topic->getForum());
		$this->topicRepository->update($topic);

		If($moveTopic)
			$this->topicFactory->moveTopic($topic, $moveTopicTarget);

		$this->flashMessages->add(Tx_MmForum_Utility_Localization::translate('Moderation_UpdateTopic_Success', 'MmForum'));
		$this->redirect('show', 'Topic', NULL, Array('topic' => $topic));
	}

}

?>