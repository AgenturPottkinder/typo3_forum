<?php

/*                                                                      *
 *  COPYRIGHT NOTICE                                                    *
 *                                                                      *
 *  (c) 2013 Martin Helmich <m.helmich@mittwald.de>                     *
 *           Sebastian Gieselmann <s.gieselmann@mittwald.de>            *
 *           Ruven Fehling <r.fehling@mittwald.de>                      *
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
 * This class implements a simple dispatcher for a mm_form eID script.
 *
 * @author     Martin Helmich <m.helmich@mittwald.de>
 * @author     Sebastian Gieselmann <s.gieselmann@mittwald.de>
 * @author     Ruven Fehling <r.fehling@mittwald.de>
 * @package    MmForum
 * @subpackage Controller
 * @version    $Id$
 *
 * @copyright  2012 Martin Helmich <m.helmich@mittwald.de>
 *             Mittwald CM Service GmbH & Co. KG
 *             http://www.mittwald.de
 * @license    GNU Public License, version 2
 *             http://opensource.org/licenses/gpl-license.php
 *
 */
class Tx_MmForum_Controller_ForumController extends Tx_MmForum_Controller_AbstractController {



	//
	// ATTRIBUTES
	//



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
	 * The ads repository.
	 * @var Tx_MmForum_Domain_Repository_Forum_AdsRepository
	 */
	protected $adsRepository;

	/**
	 * The virtual root forum.
	 * @var Tx_MmForum_Domain_Model_Forum_RootForum
	 */
	protected $rootForum;


	//
	// DEPENDENCY INJECTION METHODS
	//



	/**
	 * Constructor of this controller. Needs to get all required repositories injected.
	 *
	 * @param Tx_MmForum_Domain_Repository_Forum_ForumRepository $forumRepository An instance of the forum repository.
	 * @param Tx_MmForum_Domain_Repository_Forum_TopicRepository $topicRepository An instance of the topic repository.
	 * @param Tx_MmForum_Domain_Model_Forum_RootForum            $rootForum       An instance of the virtual root forum.
	 * @param Tx_MmForum_Service_SessionHandlingService $sessionHandling
	 * @param Tx_MmForum_Domain_Repository_Forum_AdsRepository   $adsRepository
	 */
	public function __construct(Tx_MmForum_Domain_Repository_Forum_ForumRepository $forumRepository,
	                            Tx_MmForum_Domain_Repository_Forum_TopicRepository $topicRepository,
	                            Tx_MmForum_Domain_Model_Forum_RootForum $rootForum,
								Tx_MmForum_Service_SessionHandlingService $sessionHandling,
								Tx_MmForum_Domain_Repository_Forum_AdsRepository $adsRepository) {
		parent::__construct();
		$this->forumRepository	= $forumRepository;
		$this->topicRepository	= $topicRepository;
		$this->rootForum		= $rootForum;
		$this->sessionHandling	= $sessionHandling;
		$this->adsRepository	= $adsRepository;
	}



	//
	// ACTION METHODS
	//



	/**
	 * Index action. Displays the first two levels of the forum tree.
	 * @return void
	 */
	public function indexAction() {
		$this->authenticationService->assertReadAuthorization($this->rootForum);
		$forums = $this->forumRepository->findForIndex();
		$this->view->assign('forums', $forums);
	}



	/**
	 * Show action. Displays a single forum, all subforums of this forum and the
	 * topics contained in this forum.
	 *
	 * @param Tx_MmForum_Domain_Model_Forum_Forum $forum The forum that is to be displayed.
	 * @return void
	 */
	public function showAction(Tx_MmForum_Domain_Model_Forum_Forum $forum) {
		$topics = $this->topicRepository->findForIndex($forum);
		// AdHandling Start
		$actDatetime = new DateTime();
		if(!$this->sessionHandling->get('adTime')){
			$this->sessionHandling->set('adTime', $actDatetime);
			$adDateTime = $actDatetime;
		}else{
			$adDateTime = $this->sessionHandling->get('adTime');
		}
		$max = count($topics);
		if($actDatetime->getTimestamp() - $adDateTime->getTimestamp() > $this->settings['ads']['timeInterval'] && $max > 2){
			$this->sessionHandling->set('adTime', $actDatetime);
			if ($max > $this->settings['topicController']['show']['itemsPerPage']) {
				$max = $this->settings['topicController']['show']['itemsPerPage'];
			}
			if($max < 2) $max = 2;
			$ads = $this->adsRepository->findForForumView(1);
			$showAd = array('enabled' => TRUE, 'position' => mt_rand(1,$max-1), 'ads' => $ads);
			$this->view->assign('showAd', $showAd);
		}
		// AdHandling End
		$this->authenticationService->assertReadAuthorization($forum);
		$this->view
			->assign('forum', $forum)
			->assign('topics', $topics);
	}



	/**
	 * Updates a forum.
	 * This action method updates a forum. Admin authorization is required.
	 *
	 * @param Tx_MmForum_Domain_Model_Forum_Forum $forum The forum to be updated.
	 * @dontverifyrequesthash
	 */
	public function updateAction(Tx_MmForum_Domain_Model_Forum_Forum $forum) {
		$this->authenticationService->assertAdministrationAuthorization($forum);

		$this->forumRepository->update($forum);

		$this->clearCacheForCurrentPage();
		$this->addLocalizedFlashmessage('Forum_Update_Success');
		$this->redirect('index');
	}



	/**
	 * Creates a forum.
	 * This action method creates a new forum. Admin authorization is required for
	 * creating child forums, root forums may only be created from backend.
	 *
	 * @param Tx_MmForum_Domain_Model_Forum_Forum $forum The forum to be created.
	 *
	 * @throws Tx_MmForum_Domain_Exception_Authentication_NoAccessException
	 * @dontverifyrequesthash
	 */
	public function createAction(Tx_MmForum_Domain_Model_Forum_Forum $forum) {
		if ($forum->getParent() !== NULL) {
			$this->authenticationService->assertAdministrationAuthorization($forum->getParent());
		} /** @noinspection PhpUndefinedConstantInspection */ elseif (TYPO3_MODE !== 'BE') {
			throw new Tx_MmForum_Domain_Exception_Authentication_NoAccessException('This operation is allowed only from the TYPO3 backend.');
		}

		$this->forumRepository->add($forum);

		$this->clearCacheForCurrentPage();
		$this->addLocalizedFlashmessage('Forum_Create_Success');
		$this->redirect('index');
	}


	/**
	 * Mark a whole forum as read
	 * @param Tx_MmForum_Domain_Model_Forum_Forum $forum
	 *
	 * @throws Tx_MmForum_Domain_Exception_Authentication_NotLoggedInException
	 * @return void
	 */
	public function markReadAction(Tx_MmForum_Domain_Model_Forum_Forum $forum) {
		$user = $this->getCurrentUser();
		if ($user->isAnonymous()) {
			throw new Tx_MmForum_Domain_Exception_Authentication_NotLoggedInException("You need to be logged in.", 1288084981);
		}
		$forumStorage = array();
		$forumStorage[] = $forum;
		foreach($forum->getChildren() AS $children) {
			$forumStorage[] = $children;
		}

		foreach($forumStorage AS $checkForum) {
			$topics = $this->topicRepository->getUnreadTopics($checkForum,$user);

			foreach($topics AS $topic) {
				$values = array('uid_foreign' => intval($topic['uid']),
							   'uid_local'	 => intval($user->getUid()));
				$query = $GLOBALS['TYPO3_DB']->INSERTquery('tx_mmforum_domain_model_user_readtopic',$values);
				$res =  $GLOBALS['TYPO3_DB']->sql_query($query);
			}

			$checkForum->addReader($user);
			$this->forumRepository->update($checkForum);
		}

		$this->redirect('show', 'Forum', NULL, array('forum' => $forum));
	}


	/**
	 * Show all unread topics of the current user
	 * @param Tx_MmForum_Domain_Model_Forum_Forum $forum
	 *
	 * @throws Tx_MmForum_Domain_Exception_Authentication_NotLoggedInException
	 * @return void
	 */
	public function showUnreadAction(Tx_MmForum_Domain_Model_Forum_Forum $forum) {
		$user = $this->getCurrentUser();
		if ($user->isAnonymous()) {
			throw new Tx_MmForum_Domain_Exception_Authentication_NotLoggedInException("You need to be logged in.", 1288084981);
		}
		$topics       = array();
		$unreadTopics = array();
		$tmpTopics = $this->topicRepository->getUnreadTopics($forum,$user);
		foreach($tmpTopics AS $tmpTopic) {
			$unreadTopics[] = $tmpTopic['uid'];
		}
		if(!empty($unreadTopics)) {
			$topics = $this->topicRepository->findByUids($unreadTopics);
		}

		$this->view->assign('forum',$forum)->assign('topics',$topics);
	}


}
