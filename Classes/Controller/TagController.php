<?php

/*                                                                    - *
 *  COPYRIGHT NOTICE                                                    *
 *                                                                      *
 *  (c) 2013 Ruven Fehling <r.fehling@mittwald.de>                     *
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
 * Controller class for post reports. This controller offers functionality for
 * reporting posts to the forum's moderation team.
 *
 * @author     Ruven Fehling <r.fehling@mittwald.de>
 * @package    MmForum
 * @subpackage Controller
 * @version    $Id$
 *
 * @copyright  2013 Ruven Fehling <r.fehling@mittwald.de>
 *             Mittwald CM Service GmbH & Co. KG
 *             http://www.mittwald.de
 * @license    GNU Public License, version 2
 *             http://opensource.org/licenses/gpl-license.php
 *
 */
class Tx_MmForum_Controller_TagController extends Tx_MmForum_Controller_AbstractController {



	/*
	 * ATTRIBUTES
	 */

	/**
	 * @var Tx_MmForum_Domain_Repository_Forum_TagRepository
	 */
	protected $tagRepository;

	/**
	 * @var Tx_MmForum_Domain_Repository_Forum_TopicRepository
	 */
	protected $topicRepository;


	/**
	 * @param Tx_MmForum_Domain_Repository_Forum_TagRepository $tagRepository
	 * @return void
	 */
	public function injectTagRepository(Tx_MmForum_Domain_Repository_Forum_TagRepository $tagRepository) {
		$this->tagRepository = $tagRepository;
	}


	/**
	 * @param Tx_MmForum_Domain_Repository_Forum_TopicRepository $topicRepository
	 * @return void
	 */
	public function injectTopicRepository(Tx_MmForum_Domain_Repository_Forum_TopicRepository $topicRepository) {
		$this->topicRepository = $topicRepository;
	}


	/*
	 * ACTION METHODS
	 */

	/**
	 * Listing all tags of this forum.
	 * @param int $mine
	 * @return void
	 */
	public function listAction($mine = 0) {
		$user = $this->getCurrentUser();
		if($mine == 0) {
			$tags = $this->tagRepository->findAllOrderedByCounter();
		} else {
			$tags = $this->tagRepository->findTagsOfUser($user);
		}
		$this->view->assign('tags', $tags)->assign('user',$user)->assign('mine',$mine);
	}

	/**
	 * Show all topics of a given tag
	 * @param Tx_MmForum_Domain_Model_Forum_Tag $tag
	 * @return void
	 */
	public function showAction(Tx_MmForum_Domain_Model_Forum_Tag $tag) {
		$this->view->assign('tag',$tag);
		$this->view->assign('topics',$this->topicRepository->findAllTopicsWithGivenTag($tag));
	}


	/**
	 * List all subscribed tags of a user
	 * @throws Tx_MmForum_Domain_Exception_Authentication_NotLoggedInException
	 * @return void
	 */
	public function listUserTagsAction() {
		$user = $this->getCurrentUser();
		if ($user->isAnonymous()) {
			throw new Tx_MmForum_Domain_Exception_Authentication_NotLoggedInException("You need to be logged in.", 1288084981);
		}
		$this->redirect('list',NULL,NULL,array('mine' => 1));
	}


	/**
	 * @param Tx_MmForum_Domain_Model_Forum_Tag $tag
	 *
	 * @throws Tx_MmForum_Domain_Exception_Authentication_NotLoggedInException
	 * @return void
	 */
	public function newUserTagAction(Tx_MmForum_Domain_Model_Forum_Tag $tag) {
		$user = $this->getCurrentUser();
		if ($user->isAnonymous()) {
			throw new Tx_MmForum_Domain_Exception_Authentication_NotLoggedInException("You need to be logged in.", 1288084981);
		}
		$tag->addFeuser($user);
		$this->tagRepository->update($tag);
		$this->redirect('list',NULL,NULL,array('mine' => 1));
	}


	/**
	 * @param Tx_MmForum_Domain_Model_Forum_Tag $tag
	 *
	 * @throws Tx_MmForum_Domain_Exception_Authentication_NotLoggedInException
	 * @return void
	 */
	public function deleteUserTagAction(Tx_MmForum_Domain_Model_Forum_Tag $tag) {
		$user = $this->getCurrentUser();
		if ($user->isAnonymous()) {
			throw new Tx_MmForum_Domain_Exception_Authentication_NotLoggedInException("You need to be logged in.", 1288084981);
		}
		$tag->removeFeuser($user);
		$this->tagRepository->update($tag);
		$this->redirect('list',NULL,NULL,array('mine' => 1));
	}


	/**
	 * @param string $value
	 * @return string as json array
	 */
	public function autoCompleteAction($value) {
		$result = array();
		$tagObj = $this->tagRepository->findTagLikeAName($value);
		foreach($tagObj AS $tag) {
			$result[] = $tag->getName();
		}
		return json_encode($result);
	}


}
