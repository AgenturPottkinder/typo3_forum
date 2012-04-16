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
 * Topic factory class. Is used to encapsulate topic creation logic from the
 * controller classes.
 *
 * @author	 Martin Helmich <m.helmich@mittwald.de>
 * @package	MmForum
 * @subpackage Domain_Factory_Forum
 * @version	$Id$
 *
 * @copyright  2012 Martin Helmich <m.helmich@mittwald.de>
 *			 Mittwald CM Service GmbH & Co. KG
 *			 http://www.mittwald.de
 * @license	GNU Public License, version 2
 *			 http://opensource.org/licenses/gpl-license.php
 *
 */
class Tx_MmForum_Domain_Factory_Forum_TopicFactory extends Tx_MmForum_Domain_Factory_AbstractFactory {



	/*
	 * ATTRIBUTES
	 */



	/**
	 * The frontend user repository
	 * @var Tx_MmForum_Domain_Repository_Forum_ForumRepository
	 */
	protected $forumRepository = NULL;



	/**
	 * The post repository.
	 * @var Tx_MmForum_Domain_Repository_Forum_PostRepository
	 */
	protected $postRepository = NULL;



	/**
	 * The topic repository.
	 * @var Tx_MmForum_Domain_Repository_Forum_TopicRepository
	 */
	protected $topicRepository = NULL;



	/**
	 * The post factory.
	 * @var Tx_MmForum_Domain_Factory_Forum_PostFactory
	 */
	protected $postFactory = NULL;



	/*
	 * METHODS
	 */



	/**
	 * Constructor.
	 *
	 * @param Tx_MmForum_Domain_Repository_Forum_ForumRepository $forumRepository
	 * @param Tx_MmForum_Domain_Repository_Forum_TopicRepository $topicRepository
	 * @param Tx_MmForum_Domain_Repository_Forum_PostRepository  $postRepository
	 * @param Tx_MmForum_Domain_Factory_Forum_PostFactory        $postFactory
	 */
	public function __construct(Tx_MmForum_Domain_Repository_Forum_ForumRepository $forumRepository,
	                            Tx_MmForum_Domain_Repository_Forum_TopicRepository $topicRepository,
	                            Tx_MmForum_Domain_Repository_Forum_PostRepository $postRepository,
	                            Tx_MmForum_Domain_Factory_Forum_PostFactory $postFactory) {
		$this->forumRepository = $forumRepository;
		$this->topicRepository = $topicRepository;
		$this->postRepository  = $postRepository;
		$this->postFactory     = $postFactory;
	}



	/**
	 * Creates a new topic.
	 *
	 * @param Tx_MmForum_Domain_Model_Forum_Forum $forum      The forum in which the new topic is to be created.
	 * @param Tx_MmForum_Domain_Model_Forum_Post  $firstPost  The first post of the new topic.
	 * @param string                              $subject    The subject of the new topic
	 *
	 * @return Tx_MmForum_Domain_Model_Forum_Topic The new topic.
	 */
	public function createTopic(Tx_MmForum_Domain_Model_Forum_Forum $forum,
	                            Tx_MmForum_Domain_Model_Forum_Post $firstPost, $subject) {
		/** @var $topic Tx_MmForum_Domain_Model_Forum_Topic */
		$topic = $this->getClassInstance();

		$topic->setForum($forum);
		$topic->setSubject($subject);
		$topic->setAuthor($this->getCurrentUser());
		$topic->addPost($firstPost);

		$forum->addTopic($topic);
		$this->forumRepository->update($forum);

		return $topic;
	}



	/**
	 * Deletes a topic and all posts contained in it.
	 * @param Tx_MmForum_Domain_Model_Forum_Topic $topic
	 */
	public function deleteTopic(Tx_MmForum_Domain_Model_Forum_Topic $topic) {
		foreach ($topic->getPosts() as $post) {
			/** @var $post Tx_MmForum_Domain_Model_Forum_Post */
			$post->getAuthor()->decreasePostCount();
			$this->frontendUserRepository->update($post->getAuthor());
		}

		$topic->getForum()->removeTopic($topic);
		$this->forumRepository->update($topic->getForum());
	}



	/**
	 * Creates a new shadow topic.
	 *
	 * @param  Tx_MmForum_Domain_Model_Forum_Topic $topic
	 *                                 The original topic. The newly created
	 *                                 shadow topic will then point towards
	 *                                 this topic.
	 * @return Tx_MmForum_Domain_Model_Forum_ShadowTopic
	 *                                 The newly created shadow topic.
	 */
	public function createShadowTopic(Tx_MmForum_Domain_Model_Forum_Topic $topic) {
		/** @var $shadowTopic Tx_MmForum_Domain_Model_Forum_ShadowTopic */
		$shadowTopic = $this->objectManager->create('Tx_MmForum_Domain_Model_Forum_ShadowTopic');
		$shadowTopic->setTarget($topic);

		Return $shadowTopic;
	}



	/**
	 * Moves a topic from one forum to another. This method will create a shadow
	 * topic in the original place that will point to the new location of the
	 * topic.
	 *
	 * @param Tx_MmForum_Domain_Model_Forum_Topic $topic       The topic that is to be moved.
	 * @param Tx_MmForum_Domain_Model_Forum_Forum $targetForum The target forum. The topic will be moved to this location.
	 */
	public function moveTopic(Tx_MmForum_Domain_Model_Forum_Topic $topic,
	                          Tx_MmForum_Domain_Model_Forum_Forum $targetForum) {
		if ($topic instanceof Tx_MmForum_Domain_Model_Forum_ShadowTopic) {
			throw new Tx_Extbase_Object_InvalidClass("Topic is already a shadow topic", 1288702422);
		}
		$shadowTopic = $this->createShadowTopic($topic);

		$topic->getForum()->removeTopic($topic);
		$topic->getForum()->addTopic($shadowTopic);
		$targetForum->addTopic($topic);

		$this->forumRepository->update($topic->getForum());
		$this->forumRepository->update($targetForum);
	}



}
