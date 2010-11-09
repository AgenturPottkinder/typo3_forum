<?php

/***************************************************************
*  Copyright notice
*
*  (c) 2010 Martin Helmich <m.helmich@mittwald.de>, Mittwald CM Service GmbH & Co. KG
*  			Ruven Fehling <r.fehling@mittwald.de>, Mittwald CM Service GmbH & Co. KG
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
 * Controller for the Message object
 *
 * @version $Id$
 * @copyright Copyright belongs to the respective authors
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 */

// TODO: As your extension matures, you should use Tx_Extbase_MVC_Controller_ActionController as base class, instead of the ScaffoldingController used below.
class Tx_MmMessaging_Controller_MessageController extends Tx_MmForum_Controller_AbstractController {
	
	/**
	 * @var Tx_MmMessaging_Domain_Repository_MessageRepository
	 */
	protected $messageRepository;

	/**
	 * Initializes the current action
	 *
	 * @return void
	 */
	protected function initializeAction() {
		parent::initializeAction();
		$this->messageRepository = t3lib_div::makeInstance('Tx_MmMessaging_Domain_Repository_MessageRepository');
	}

		/**
		 * @param int $mode
		 */
	public function indexAction($mode=0) {
        $this->view->assign('mode', $mode);
		if($mode == 0 || $mode == 1)
			$this->view->assign('messages', $this->messageRepository->findForInboxOrOutbox($mode));
		else $this->view->assign('messages', $this->messageRepository->findForArchive());
	}

		/**
		 * @param Tx_MmMessaging_Domain_Model_Message $message
		 */
	public function showAction(Tx_MmMessaging_Domain_Model_Message $message) {

		If($message->getRecipient() == $this->frontendUserRepository->findCurrent() && $message->isUserUnread()) {
			$message->setUserRead(TRUE);
			$this->messageRepository->update($message);
		}

		$this->view->assign('message', $message);
	}

		/**
		 * @param Tx_MmMessaging_Domain_Model_Message $newMessage
		 * @param string $recipientName
		 * @param Tx_MmMessaging_Domain_Model_Message $replyTo
		 * @dontvalidate $newMessage
		 */
	public function newAction(Tx_MmMessaging_Domain_Model_Message $newMessage=NULL, $recipientName=NULL, Tx_MmMessaging_Domain_Model_Message $replyTo=NULL) {

		If($newMessage === NULL && $replyTo !== NULL) {
			$newMessage = new Tx_MmMessaging_Domain_Model_Message();
			$newMessage->setSubject('Re: '.$replyTo->getSubject());
			$newMessage->setText("\n\n\n[quote]".$replyTo->getText()."[/quote]");
			$recipientName = $replyTo->getSender()->getUsername();
		}

		$this->view->assign('newMessage', $newMessage)
		           ->assign('recipientName', $recipientName);
	}

		/**
		 * @param Tx_MmMessaging_Domain_Model_Message $newMessage
		 * @param string $recipientName
		 * @validate $recipientName NotEmpty
		 */
	public function createAction(Tx_MmMessaging_Domain_Model_Message $newMessage, $recipientName) {
		$newMessage->setRecipient($this->frontendUserRepository->findByUsername($recipientName));
		$newMessage->setSender($this->frontendUserRepository->findCurrent());

		$this->messageRepository->add($newMessage);
	}

		/**
		 * @param Tx_MmMessaging_Domain_Model_Message $message
		 */
	public function archiveAction(Tx_MmMessaging_Domain_Model_Message $message) {
		$message->setArchived(TRUE);
		$this->messageRepository->update($message);
		$this->redirect('index');
	}

		/**
		 * @param Tx_MmMessaging_Domain_Model_Message $message
		 */
	public function deleteAction(Tx_MmMessaging_Domain_Model_Message $message) {
		$this->messageRepository->remove($message);
		$this->redirect('index');
	}

	
}
?>