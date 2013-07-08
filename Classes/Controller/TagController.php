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
	 * @return void
	 */
	public function listAction() {
		$this->view->assign('tags', $this->tagRepository->findAllOrderedByCounter());
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


}
