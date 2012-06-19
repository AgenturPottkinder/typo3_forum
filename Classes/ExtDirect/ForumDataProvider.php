<?php

/*                                                                    - *
 *  COPYRIGHT NOTICE                                                    *
 *                                                                      *
 *  (c) 2012 Martin Helmich <typo3@martin-helmich.de>                   *
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
 * ViewHelper that renders a big button.
 *
 * @author     Martin Helmich <m.helmich@mittwald.de>
 * @package    MmForum
 * @subpackage ViewHelpers_Control
 * @version    $Id: BigButtonViewHelper.php 52309 2011-09-20 18:54:26Z mhelmich $
 *
 * @copyright  2012 Martin Helmich <typo3@martin-helmich.de>
 *             http://www.martin-helmich.de
 * @license    GNU Public License, version 2
 *             http://opensource.org/licenses/gpl-license.php
 *
 */
class Tx_MmForum_ExtDirect_ForumDataProvider extends Tx_MmForum_ExtDirect_AbstractDataProvider {



	/**
	 * An instance of the forum repository.
	 *
	 * @var Tx_MmForum_Domain_Repository_Forum_ForumRepository
	 */
	protected $forumRepository = NULL;



	/**
	 * Constructor.
	 */
	public function __construct() {
		parent::__construct();

		// Since the ExtDirect provider is not created using the
		// object manager, no dependency injection is available here.
		$this->forumRepository = $this->objectManager->get('Tx_MmForum_Domain_Repository_Forum_ForumRepository');
	}



	/**
	 * @param integer $nodeId
	 *
	 * @return array
	 */
	public function getTreeNode($nodeId) {
		if ($nodeId === 'forum-root') {
			$forumId = NULL;
			$forums  = $this->forumRepository->findRootForums();
		} else {
			$forumId = (int)str_replace('forum-', '', $nodeId);
			$forums  = $this->forumRepository->findByUid($forumId)->getChildren();
		}

		$result = array();
		foreach ($forums as $forum) {
			$forumNode = array('text'       => $forum->getTitle(),
			                   'title'      => $forum->getTitle(),
			                   'topicCount' => $forum->getTopicCount(),
			                   'postCount'  => $forum->getPostCount(),
			                   'id'         => 'forum-' . $forum->getUid(),
			                   'iconCls'    => 'tx-mmforum-icon-16-forum',
			                   'draggable'  => TRUE,
			                   'allowDrop'  => TRUE,
			                   'isTarget'   => TRUE,
			                   '__identity' => $forum->getUid());

			if (count($forum->getChildren()) === 0) {
				#$forumNode['leaf'] = TRUE;
				$forumNode['expanded'] = TRUE;
				$forumNode['children'] = array();
			}

			$result[] = $forumNode;
		}

		return $result;
	}



	/**
	 * @param integer $forumId
	 *
	 * @return array
	 * @throws Exception
	 */
	public function getForum($forumId) {
		$forum = $this->forumRepository->findByUid($forumId);
		if ($forum === NULL) {
			throw new Exception("The forum $forumId does not exist!", 1332447187);
		}
		return array('success' => true,
		             'data'    => array('title'       => $forum->getTitle(),
		                                'description' => $forum->getDescription(),
		                                '__identity'  => $forum->getUid()));
	}



	/**
	 * @param array $parameters
	 *
	 * @formHandler
	 * @return array
	 */
	public function saveForum(array $parameters) {
		$this->extBaseConnector->setParameters($parameters);
		return unserialize($this->extBaseConnector->runControllerAction('Forum', 'update'));
	}



	/**
	 * @param $parentId
	 */
	public function createForum($parentId) {

	}



	/**
	 * @param $id
	 * @param $title
	 *
	 * @return mixed
	 */
	public function updateForumTitle($id, $title) {
		$this->extBaseConnector->setParameters(array('forum' => array('__identity' => $id,
		                                                              'title'      => $title)));
		return unserialize($this->extBaseConnector->runControllerAction('Forum', 'update'));
	}



	/**
	 * @param $forumId
	 * @param $relativeForum
	 * @param $position
	 */
	public function moveForum($forumId, $relativeForum, $position) {

	}



}
