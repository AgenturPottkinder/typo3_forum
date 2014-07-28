<?php
namespace Mittwald\MmForum\Domain\Factory\Forum;


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
 * @package    MmForum
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
class TopicFactory extends \Mittwald\MmForum\Domain\Factory\AbstractFactory {


	/*
	 * ATTRIBUTES
	 */


	/**
	 * The frontend user repository
	 * @var \Mittwald\MmForum\Domain\Repository\Forum\ForumRepository
	 */
	protected $forumRepository = NULL;


	/**
	 * The post repository.
	 * @var \Mittwald\MmForum\Domain\Repository\Forum\PostRepository
	 */
	protected $postRepository = NULL;


	/**
	 * The topic repository.
	 * @var \Mittwald\MmForum\Domain\Repository\Forum\TopicRepository
	 */
	protected $topicRepository = NULL;


	/**
	 * The post factory.
	 * @var \Mittwald\MmForum\Domain\Factory\Forum\PostFactory
	 */
	protected $postFactory = NULL;


	/**
	 * The criteria option repository.
	 * @var \Mittwald\MmForum\Domain\Repository\Forum\CriteriaOptionRepository
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
	 * @param \Mittwald\MmForum\Domain\Repository\Forum\ForumRepository $forumRepository
	 * @param \Mittwald\MmForum\Domain\Repository\Forum\TopicRepository $topicRepository
	 * @param \Mittwald\MmForum\Domain\Repository\Forum\PostRepository $postRepository
	 * @param \Mittwald\MmForum\Domain\Factory\Forum\PostFactory $postFactory
	 * @param \Mittwald\MmForum\Domain\Repository\Forum\CriteriaOptionRepository $criteriaOptionRepository
	 */
	public function __construct(\Mittwald\MmForum\Domain\Repository\Forum\ForumRepository $forumRepository,
								\Mittwald\MmForum\Domain\Repository\Forum\TopicRepository $topicRepository,
								\Mittwald\MmForum\Domain\Repository\Forum\PostRepository $postRepository,
								PostFactory $postFactory,
								\Mittwald\MmForum\Domain\Repository\Forum\CriteriaOptionRepository $criteriaOptionRepository) {
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
	 * @param \Mittwald\MmForum\Domain\Model\Forum\Forum $forum      The forum in which the new topic is to be created.
	 * @param \Mittwald\MmForum\Domain\Model\Forum\Post $firstPost  The first post of the new topic.
	 * @param string $subject    The subject of the new topic
	 * @param int $question   The flag if the new topic is declared as question
	 * @param array $criteriaOptions    All submitted criteria with option.
	 * @param \TYPO3\CMS\Extbase\Persistence\ObjectStorage $tags All user defined tags
	 * @param int $subscribe   The flag if the new topic is subscribed by author
	 *
	 * @return \Mittwald\MmForum\Domain\Model\Forum\Topic The new topic.
	 */
	public function createTopic(\Mittwald\MmForum\Domain\Model\Forum\Forum $forum,
								\Mittwald\MmForum\Domain\Model\Forum\Post $firstPost,
								$subject, $question = 0, array $criteriaOptions = array(), $tags=NULL, $subscribe=0) {
		/** @var $topic \Mittwald\MmForum\Domain\Model\Forum\Topic */
		$topic = $this->getClassInstance();
		$user = $this->getCurrentUser();

		$topic->setForum($forum);
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

		$topic->getForum()->setLastTopic($topic);
		$this->forumRepository->update($topic->getForum());

		return $topic;
	}


	/**
	 * Deletes a topic and all posts contained in it.
	 * @param \Mittwald\MmForum\Domain\Model\Forum\Topic $topic
	 */
	public function deleteTopic(\Mittwald\MmForum\Domain\Model\Forum\Topic $topic) {
		foreach ($topic->getPosts() as $post) {
			/** @var $post \Mittwald\MmForum\Domain\Model\Forum\Post */
			$post->getAuthor()->decreasePostCount();
			$post->getAuthor()->decreasePoints(intval($this->settings['rankScore']['newPost']));
			$this->frontendUserRepository->update($post->getAuthor());
		}

		$forum = $topic->getForum();
		$this->topicRepository->remove($topic);

		$this->persistenceManager->persistAll();

		$forum->_resetLastPost();

		$lastTopic = $this->topicRepository->findLastByForum($forum);
		if($lastTopic !== NULL) {
			$lastPost = $lastTopic->getLastPost();
		} else {
			$lastPost = NULL;
		}
		$forum->setLastPost($lastPost);
		$forum->setLastTopic($lastTopic);
		$this->forumRepository->update($forum);

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
	 * @param  \Mittwald\MmForum\Domain\Model\Forum\Topic $topic
	 *                                 The original topic. The newly created
	 *                                 shadow topic will then point towards
	 *                                 this topic.
	 * @return \Mittwald\MmForum\Domain\Model\Forum\ShadowTopic
	 *                                 The newly created shadow topic.
	 */
	public function createShadowTopic(\Mittwald\MmForum\Domain\Model\Forum\Topic $topic) {
		/** @var $shadowTopic \Mittwald\MmForum\Domain\Model\Forum\ShadowTopic */
		$shadowTopic = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('Mittwald\\MmForum\\Domain\\Model\\Forum\\ShadowTopic');
		$shadowTopic->setTarget($topic);

		Return $shadowTopic;
	}


	/**
	 * Moves a topic from one forum to another. This method will create a shadow
	 * topic in the original place that will point to the new location of the
	 * topic.
	 * @TODO: Has forumRepository->update() enough performance?
	 *
	 * @param \Mittwald\MmForum\Domain\Model\Forum\Topic $topic       The topic that is to be moved.
	 * @param \Mittwald\MmForum\Domain\Model\Forum\Forum $targetForum The target forum. The topic will be moved to this location.
	 *
	 * @throws \TYPO3\CMS\Extbase\Object\InvalidClassException
	 * @return void
	 */
	public function moveTopic(\Mittwald\MmForum\Domain\Model\Forum\Topic $topic,
							  \Mittwald\MmForum\Domain\Model\Forum\Forum $targetForum) {
		if ($topic instanceof \Mittwald\MmForum\Domain\Model\Forum\ShadowTopic) {
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
	 * @param \Mittwald\MmForum\Domain\Model\Forum\Topic $topic
	 * @param \Mittwald\MmForum\Domain\Model\Forum\Post $post
	 * @return void
	 */
	public function setPostAsSolution(\Mittwald\MmForum\Domain\Model\Forum\Topic $topic, \Mittwald\MmForum\Domain\Model\Forum\Post $post) {
		$topic->setSolution($post);
		$this->topicRepository->update($topic);
		$this->forumRepository->update($topic->getForum());
	}


}
