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
 * Controller for the User object
 *
 * @version $Id$
 * @copyright Copyright belongs to the respective authors
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 */

Class Tx_MmForum_Controller_UserController
	Extends Tx_MmForum_Controller_AbstractController {





		/*
		 * ATTRIBUTES
		 */





		/**
		 * @var Tx_MmForum_Domain_Repository_User_FrontendUserRepository
		 */
	Protected $userRepository;





		/*
		 * INITIALIZATION METHODS
		 */





		/**
		 *
		 * @return void
		 *
		 */

	Protected Function  initializeAction() {
		parent::initializeAction();
		$this->userRepository =&
			t3lib_div::makeInstance('Tx_MmForum_Domain_Repository_User_FrontendUserRepository');
	}





		/*
		 * ACTION METHODS
		 */





	Public Function indexAction() {
		Return "Userlist here";
	}



	Public Function showAction(Tx_MmForum_Domain_Model_User_FrontendUser $user) {
		$this->view->assign('user', $user);
	}



		/**
		 *
		 * @param Tx_MmForum_Domain_Model_Forum_Forum $forum
		 * @param Tx_MmForum_Domain_Model_Forum_Topic $topic
		 * @param boolean $unsubscribe
		 * 
		 */

	Public Function subscribeAction ( Tx_MmForum_Domain_Model_Forum_Forum $forum=NULL,
	                                  Tx_MmForum_Domain_Model_Forum_Topic $topic=NULL,
	                                  $unsubscribe=FALSE ) {
		If($forum === NULL && $topic === NULL)
			Throw New Tx_Extbase_MVC_Exception_InvalidArgumentValue ("You need to subscribe a Forum or Topic!", 1285059341);

		$object =  $forum ? $forum : $topic;
		$user   =& $this->getCurrentUser();

		If($unsubscribe) $user->removeSubscription($object);
		Else             $user->addSubscription($object);

		$this->userRepository->update($user);
		$this->flashMessages->add($this->getSubscriptionFlashMessage($object, $unsubscribe));
		$this->redirectToSubscriptionObject($object);
	}





		/*
		 * HELPER METHODS
		 */





		/**
		 *
		 * @param Tx_MmForum_Domain_Model_SubscribeableInterface $object
		 *
		 */

	Protected Function redirectToSubscriptionObject(Tx_MmForum_Domain_Model_SubscribeableInterface $object) {
		If($object InstanceOf Tx_MmForum_Domain_Model_Forum_Forum)
			$this->redirect ('show', 'Forum', NULL, array('forum' => $object));
		If($object InstanceOf Tx_MmForum_Domain_Model_Forum_Topic)
			$this->redirect ('show', 'Topic', NULL, array('topic' => $object));
	}

		/**
		 *
		 * @return string
		 *
		 */

	Protected Function getSubscriptionFlashMessage(Tx_MmForum_Domain_Model_SubscribeableInterface $object, $unsubscribe=FALSE) {
		$type = array_pop(explode('_',get_class($object)));
		$key  = 'User_'.($unsubscribe ? 'Uns' : 'S').'ubscribe_'.$type.'_Success';
		Return Tx_Extbase_Utility_Localization::translate($key, 'MmForum', Array($object->getTitle()));
	}
	
}
?>