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
 * @author     Martin Helmich <m.helmich@mittwald.de>
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
class Tx_MmForum_Controller_ForumController
	extends Tx_MmForum_Controller_AbstractController
{



	/*
	 * ATTRIBUTES
	 */



	/**
	 * A forum repository.
	 *
	 * @var Tx_MmForum_Domain_Repository_Forum_ForumRepository
	 */
	protected $forumRepository;



	/**
	 * A topic repository
	 *
	 * @var Tx_MmForum_Domain_Repository_Forum_TopicRepository
	 */
	protected $topicRepository;



	/*
	 * DEPENDENCY INJECTION METHODS
	 */



	/**
	 *
	 * Constructor of this controller. Needs to get all required repositories
	 * injected.
	 *
	 * @param Tx_MmForum_Domain_Repository_Forum_ForumRepository $forumRepository
	 *                                 An instance of the forum repository.
	 * @param Tx_MmForum_Domain_Repository_Forum_TopicRepository $topicRepository
	 *                                 An instance of the topic repository.
	 *
	 */
	public function __construct(Tx_MmForum_Domain_Repository_Forum_ForumRepository $forumRepository,
	                            Tx_MmForum_Domain_Repository_Forum_TopicRepository $topicRepository)
	{
		parent::__construct();
		$this->forumRepository = $forumRepository;
		$this->topicRepository = $topicRepository;
	}



	/*
	 * ACTION METHODS
	 */



	/**
	 *
	 * Index action. Displays the first two levels of the forum tree.
	 *
	 * @return void
	 *
	 */
	public Function indexAction()
	{
		$forums = $this->forumRepository->findForIndex();
		$this->view->assign('forums', $forums);
	}



	/**
	 *
	 * Show action. Displays a single forum, all subforums of this forum and the
	 * topics contained in this forum.
	 *
	 * @param Tx_MmForum_Domain_Model_Forum_Forum $forum
	 *                             The forum that is to be displayed.
	 *
	 * @return void
	 *
	 */
	public function showAction(Tx_MmForum_Domain_Model_Forum_Forum $forum)
	{
		$this->authenticationService->assertReadAuthorization($forum);
		$this->view
			->assign('forum', $forum)
			->assign('topics', $this->topicRepository->findForIndex($forum));
	}



	/**
	 *
	 * @param Tx_MmForum_Domain_Model_Forum_Forum $forum
	 *
	 * @dontverifyrequesthash
	 *
	 */
	public function updateAction(Tx_MmForum_Domain_Model_Forum_Forum $forum)
	{
		$this->authenticationService->assertAdministrationAuthorization($forum);

		$this->forumRepository->update($forum);

		$this->addLocalizedFlashmessage('Forum_Update_Success');
		$this->redirect('index');
	}



}
