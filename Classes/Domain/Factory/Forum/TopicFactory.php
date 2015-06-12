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
 * @author     Martin Helmich <m.helmich@mittwald.de>
 * @package    Typo3Forum
 * @subpackage Domain_Factory_Forum
 * @version    $Id$
 *
 * @copyright  2012 Martin Helmich <m.helmich@mittwald.de>
 *             Mittwald CM Service GmbH & Co. KG
 *             http://www.mittwald.de
 * @license    GNU Public License, version 2
 *             http://opensource.org/licenses/gpl-license.php
 *
 */
class Tx_Typo3Forum_Domain_Factory_Forum_TopicFactory extends Tx_Typo3Forum_Domain_Factory_AbstractFactory {


	/*
	 * ATTRIBUTES
	 */


	/**
	 * The frontend user repository
	 * @var Tx_Typo3Forum_Domain_Repository_Forum_ForumRepository
	 */
	protected $forumRepository = NULL;


	/**
	 * The post repository.
	 * @var Tx_Typo3Forum_Domain_Repository_Forum_PostRepository
	 */
	protected $postRepository = NULL;


	/**
	 * The topic repository.
	 * @var Tx_Typo3Forum_Domain_Repository_Forum_TopicRepository
	 */
	protected $topicRepository = NULL;


	/**
	 * The post factory.
	 * @var Tx_Typo3Forum_Domain_Factory_Forum_PostFactory
	 */
	protected $postFactory = NULL;


	/**
	 * The criteria option repository.
	 * @var Tx_Typo3Forum_Domain_Repository_Forum_CriteriaOptionRepository
	 */
	protected $criteriaOptionRepository = NULL;


	/**
	 * @var \TYPO3\CMS\Extbase\Persistence\PersistenceManagerInterface
	 */
	protected $persistenceManager;


	/*
	 * METHODS
	 */


	/**
	 * Constructor.
	 *
	 * @param Tx_Typo3Forum_Domain_Repository_Forum_ForumRepository $forumRepository
	 * @param Tx_Typo3Forum_Domain_Repository_Forum_TopicRepository $topicRepository
	 * @param Tx_Typo3Forum_Domain_Repository_Forum_PostRepository $postRepository
	 * @param Tx_Typo3Forum_Domain_Factory_Forum_PostFactory $postFactory
	 * @param Tx_Typo3Forum_Domain_Repository_Forum_CriteriaOptionRepository $criteriaOptionRepository
	 */
	public function __construct(Tx_Typo3Forum_Domain_Repository_Forum_ForumRepository $forumRepository,
								Tx_Typo3Forum_Domain_Repository_Forum_TopicRepository $topicRepository,
								Tx_Typo3Forum_Domain_Repository_Forum_PostRepository $postRepository,
								Tx_Typo3Forum_Domain_Factory_Forum_PostFactory $postFactory,
								Tx_Typo3Forum_Domain_Repository_Forum_CriteriaOptionRepository $criteriaOptionRepository) {
		$this->forumRepository = $forumRepository;
		$this->topicRepository = $topicRepository;
		$this->postRepository = $postRepository;
		$this->postFactory = $postFactory;
		$this->criteriaOptionRepository = $criteriaOptionRepository;
	}


	/**
	 * @param \TYPO3\CMS\Extbase\Persistence\PersistenceManagerInterface $persistenceManager
	 * @return void
	 */
	public function injectPersistenceManager(\TYPO3\CMS\Extbase\Persistence\PersistenceManagerInterface $persistenceManager) {
		$this->persistenceManager = $persistenceManager;
	}


	/**
	 * Creates a new topic.
	 *
	 * @param Tx_Typo3Forum_Domain_Model_Forum_Forum $forum      The forum in which the new topic is to be created.
	 * @param Tx_Typo3Forum_Domain_Model_Forum_Post $firstPost  The first post of the new topic.
	 * @param string $subject    The subject of the new topic
	 * @param int $question   The flag if the new topic is declared as question
	 * @param array $criteriaOptions    All submitted criteria with option.
	 * @param \TYPO3\CMS\Extbase\Persistence\ObjectStorage $tags All user defined tags
	 * @param int $subscribe   The flag if the new topic is subscribed by author
	 *
	 * @return Tx_Typo3Forum_Domain_Model_Forum_Topic The new topic.
	 */
	public function createTopic(Tx_Typo3Forum_Domain_Model_Forum_Forum $forum,
								Tx_Typo3Forum_Domain_Model_Forum_Post $firstPost,
								$subject, $question = 0, array $criteriaOptions = array(), $tags=NULL, $subscribe=0) {
		/** @var $topic Tx_Typo3Forum_Domain_Model_Forum_Topic */
		$topic = $this->getClassInstance();
		$user = $this->getCurrentUser();

		$forum->addTopic($topic);
		$topic->setSubject($subject);
		$topic->setAuthor($user);
		$topic->setQuestion($question);
		$topic->addPost($firstPost);

		if($tags != NULL) {
			$topic->setTags($tags);
		}
		if (!empty($criteriaOptions)) {
			foreach ($criteriaOptions AS $criteria_uid => $option_uid) {
				$obj = $this->criteriaOptionRepository->findByUid($option_uid);
				if($obj->getCriteria()->getUid() == $criteria_uid) {
					$topic->addCriteriaOption($obj);
				}
			}
		}
		if(intval($subscribe) == 1) {
			$topic->addSubscriber($user);
		}

		if (!$user->isAnonymous()) {
			$user->increaseTopicCount();
			if($topic->getQuestion() == 1) {
				$user->increaseQuestionCount();
			}
			$this->frontendUserRepository->update($user);
		}
		$this->topicRepository->add($topic);

		return $topic;
	}


	/**
	 * Deletes a topic and all posts contained in it.
	 * @param Tx_Typo3Forum_Domain_Model_Forum_Topic $topic
	 */
	public function deleteTopic(Tx_Typo3Forum_Domain_Model_Forum_Topic $topic) {
		foreach ($topic->getPosts() as $post) {
			/** @var $post Tx_Typo3Forum_Domain_Model_Forum_Post */
			$post->getAuthor()->decreasePostCount();
			$post->getAuthor()->decreasePoints(intval($this->settings['rankScore']['newPost']));
			$this->frontendUserRepository->update($post->getAuthor());
		}

		$forum = $topic->getForum();
		$forum->removeTopic($topic);
		$this->topicRepository->remove($topic);

		$this->persistenceManager->persistAll();

		$user = $this->getCurrentUser();

		if (!$user->isAnonymous()) {
			$user->decreaseTopicCount();
			if($topic->getQuestion() == 1) {
				$user->decreaseQuestionCount();
			}
			$this->frontendUserRepository->update($user);
		}
	}


	/**
	 * Creates a new shadow topic.
	 *
	 * @param  Tx_Typo3Forum_Domain_Model_Forum_Topic $topic
	 *                                 The original topic. The newly created
	 *                                 shadow topic will then point towards
	 *                                 this topic.
	 * @return Tx_Typo3Forum_Domain_Model_Forum_ShadowTopic
	 *                                 The newly created shadow topic.
	 */
	public function createShadowTopic(Tx_Typo3Forum_Domain_Model_Forum_Topic $topic) {
		/** @var $shadowTopic Tx_Typo3Forum_Domain_Model_Forum_ShadowTopic */
		$shadowTopic = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('Tx_Typo3Forum_Domain_Model_Forum_ShadowTopic');
		$shadowTopic->setTarget($topic);

		Return $shadowTopic;
	}


	/**
	 * Moves a topic from one forum to another. This method will create a shadow
	 * topic in the original place that will point to the new location of the
	 * topic.
	 * @TODO: Has forumRepository->update() enough performance?
	 *
	 * @param Tx_Typo3Forum_Domain_Model_Forum_Topic $topic       The topic that is to be moved.
	 * @param Tx_Typo3Forum_Domain_Model_Forum_Forum $targetForum The target forum. The topic will be moved to this location.
	 *
	 * @throws \TYPO3\CMS\Extbase\Object\InvalidClassException
	 * @return void
	 */
	public function moveTopic(Tx_Typo3Forum_Domain_Model_Forum_Topic $topic,
							  Tx_Typo3Forum_Domain_Model_Forum_Forum $targetForum) {
		if ($topic instanceof Tx_Typo3Forum_Domain_Model_Forum_ShadowTopic) {
			throw new \TYPO3\CMS\Extbase\Object\InvalidClassException("Topic is already a shadow topic", 1288702422);
		}
		$shadowTopic = $this->createShadowTopic($topic);

		$topic->getForum()->removeTopic($topic);
		$topic->getForum()->addTopic($shadowTopic);
		$targetForum->addTopic($topic);

		$this->forumRepository->update($topic->getForum());
		$this->forumRepository->update($targetForum);
	}


	/**
	 * Sets a post as solution
	 *
	 * @param Tx_Typo3Forum_Domain_Model_Forum_Topic $topic
	 * @param Tx_Typo3Forum_Domain_Model_Forum_Post $post
	 * @return void
	 */
	public function setPostAsSolution(Tx_Typo3Forum_Domain_Model_Forum_Topic $topic, Tx_Typo3Forum_Domain_Model_Forum_Post $post) {
		$topic->setSolution($post);
		$this->topicRepository->update($topic);
		$this->forumRepository->update($topic->getForum());
	}


}
