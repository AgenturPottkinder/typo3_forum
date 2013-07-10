<?php

/*                                                                    - *
 *  COPYRIGHT NOTICE                                                    *
 *                                                                      *
 *  (c) 2012 Martin Helmich <m.helmich@mittwald.de>                     *
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
 * Controller for the Forum object. This class implements basic forum-related
 * operations, like listing forums and subforums, and the respective topic lists.
 *
 * @author        Martin Helmich <m.helmich@mittwald.de>
 * @package       MmForum
 * @subpackage    Controller
 * @version       $Id$
 *
 * @copyright     2012 Martin Helmich <m.helmich@mittwald.de>
 *                Mittwald CM Service GmbH & Co. KG
 *                http://www.mittwald.de
 * @license       GNU Public License, version 2
 *                http://opensource.org/licenses/gpl-license.php
 *
 */
class Tx_MmForum_Controller_AjaxController extends Tx_MmForum_Controller_AbstractController {




	/*
	 * ATTRIBUTES
	 */


	/**
	 * A forum repository.
	 * @var Tx_MmForum_Domain_Repository_Forum_ForumRepository
	 */
	protected $forumRepository;


	/**
	 * A topic repository.
	 * @var Tx_MmForum_Domain_Repository_Forum_TopicRepository
	 */
	protected $topicRepository;


	/**
	 * A post repository.
	 * @var Tx_MmForum_Domain_Repository_Forum_PostRepository
	 */
	protected $postRepository;


	/**
	 * A post factory.
	 * @var Tx_MmForum_Domain_Factory_Forum_PostFactory
	 */
	protected $postFactory;


	/**
	 * A post factory.
	 * @var Tx_MmForum_Domain_Repository_Forum_AttachmentRepository
	 */
	protected $attachmentRepository;

	/**
	 * @var Tx_MmForum_Service_AttachmentService
	 */
	protected $attachmentService = NULL;

	/**
	 * Constructor. Used primarily for dependency injection.
	 *
	 * @param Tx_MmForum_Domain_Repository_Forum_ForumRepository $forumRepository
	 * @param Tx_MmForum_Domain_Repository_Forum_TopicRepository $topicRepository
	 * @param Tx_MmForum_Domain_Repository_Forum_PostRepository $postRepository
	 * @param Tx_MmForum_Domain_Factory_Forum_PostFactory $postFactory
	 * @param Tx_MmForum_Domain_Repository_Forum_AttachmentRepository $attachmentRepository
	 * @param Tx_MmForum_Service_SessionHandlingService $sessionHandling
	 * @param Tx_MmForum_Service_AttachmentService $attachmentService
	 */
	public function __construct(Tx_MmForum_Domain_Repository_Forum_ForumRepository $forumRepository,
								Tx_MmForum_Domain_Repository_Forum_TopicRepository $topicRepository,
								Tx_MmForum_Domain_Repository_Forum_PostRepository $postRepository,
								Tx_MmForum_Domain_Factory_Forum_PostFactory $postFactory,
								Tx_MmForum_Domain_Repository_Forum_AttachmentRepository $attachmentRepository,
								Tx_MmForum_Service_SessionHandlingService $sessionHandling,
								Tx_MmForum_Service_AttachmentService $attachmentService) {
		$this->forumRepository = $forumRepository;
		$this->topicRepository = $topicRepository;
		$this->postRepository = $postRepository;
		$this->postFactory = $postFactory;
		$this->attachmentRepository = $attachmentRepository;
		$this->sessionHandling		= $sessionHandling;
		$this->attachmentService = $attachmentService;
	}

	//
	// ACTION METHODS
	//

	/**
	 * @param string $displayedUser
	 * @return void
	 */
	public function mainAction($displayedUser = "") {
		// json array
		$content = array();
		if (!empty($displayedUser)) {
			$content['onlineUser'] = $this->_getOnlineUser($displayedUser);
		}
		$this->view->assign('content', json_encode($content));
	}
	/**
	 * @param int $uid
	 * @param string $type
	 * @param int $hiddenImage
	 * @return void
	 */
	public function postSummaryAction($uid = null, $type='', $hiddenImage = 0) {
		switch($type){
			case 'lastForumPost':
				$forum  = $this->forumRepository->findByUid($uid);
				/* @var Tx_MmForum_Domain_Model_Forum_Post */
				$data = $forum->getLastPost();
				break;
			case 'lastTopicPost':
				$topic  = $this->topicRepository->findByUid($uid);
				/* @var Tx_MmForum_Domain_Model_Forum_Post */
				$data = $topic->getLastPost();
				break;
		}
		// check read access
		$this->view->assign('post', $data);
		$this->view->assign('hiddenImage', $hiddenImage);
	}
	private function _getOnlineUser($displayedUser) {
		// OnlineUser
		$displayedUser = json_decode($displayedUser);
		$onlineUsers = $this->frontendUserRepository->findByFilter("", array(), true, $displayedUser);
		// write online user
		foreach ($onlineUsers as $onlineUser) {
			$output[] = $onlineUser->getUid();
		}
		if (!empty($output)) return $output;
	}


}