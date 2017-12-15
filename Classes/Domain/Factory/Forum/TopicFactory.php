<?php
namespace Mittwald\Typo3Forum\Domain\Factory\Forum;

/*                                                                    - *
 *  COPYRIGHT NOTICE                                                    *
 *                                                                      *
 *  (c) 2015 Mittwald CM Service GmbH & Co KG                           *
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

use Mittwald\Typo3Forum\Domain\Factory\AbstractFactory;
use Mittwald\Typo3Forum\Domain\Model\Forum\CriteriaOption;
use Mittwald\Typo3Forum\Domain\Model\Forum\Forum;
use Mittwald\Typo3Forum\Domain\Model\Forum\Post;
use Mittwald\Typo3Forum\Domain\Model\Forum\ShadowTopic;
use Mittwald\Typo3Forum\Domain\Model\Forum\Topic;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Object\InvalidClassException;

class TopicFactory extends AbstractFactory {

	/**
	 * @var \Mittwald\Typo3Forum\Domain\Repository\Forum\CriteriaOptionRepository
	 * @inject
	 */
	protected $criteriaOptionRepository = NULL;

	/**
	 * @var \Mittwald\Typo3Forum\Domain\Repository\Forum\ForumRepository
	 * @inject
	 */
	protected $forumRepository = NULL;

	/**
	 * @var \Mittwald\Typo3Forum\Domain\Factory\Forum\PostFactory
	 * @inject
	 */
	protected $postFactory = NULL;

	/**
	 * @var \Mittwald\Typo3Forum\Domain\Repository\Forum\PostRepository
	 * @inject
	 */
	protected $postRepository = NULL;

	/**
	 * @var \Mittwald\Typo3Forum\Domain\Repository\Forum\TopicRepository
	 * @inject
	 */
	protected $topicRepository = NULL;

	/**
	 * @var \TYPO3\CMS\Extbase\Persistence\PersistenceManagerInterface
	 * @inject
	 */
	protected $persistenceManager;

	/**
	 * Creates a new topic.
	 *
	 * @param Forum                                        $forum           The forum in which the new topic is to be created.
	 * @param Post                                         $firstPost       The first post of the new topic.
	 * @param string                                       $subject         The subject of the new topic
	 * @param int                                          $question        The flag if the new topic is declared as question
	 * @param array                                        $criteriaOptions All submitted criteria with option.
	 * @param \TYPO3\CMS\Extbase\Persistence\ObjectStorage $tags            All user defined tags
	 * @param int                                          $subscribe       The flag if the new topic is subscribed by author
	 *
	 * @return Topic The new topic.
	 */
	public function createTopic(Forum $forum, Post $firstPost, $subject, $question = 0, array $criteriaOptions = [], $tags = NULL, $subscribe = 0) {
		/** @var $topic Topic */
		$topic = $this->getClassInstance();
		$user = $this->getCurrentUser();

		$forum->addTopic($topic);
		$topic->setSubject($subject);
		$topic->setAuthor($user);
		$topic->setQuestion($question);
		$topic->addPost($firstPost);

		if ($tags != NULL) {
			$topic->setTags($tags);
		}
		if (!empty($criteriaOptions)) {
			foreach ($criteriaOptions as $criteriaUid => $optionUid) {
				/** @var CriteriaOption $criteriaOption */
				$criteriaOption = $this->criteriaOptionRepository->findByUid($optionUid);
				if ($criteriaOption->getCriteria()->getUid() == $criteriaUid) {
					$topic->addCriteriaOption($criteriaOption);
				}
			}
		}
		if ((int)$subscribe === 1) {
			$topic->addSubscriber($user);
		}

		if (!$user->isAnonymous()) {
			$user->increaseTopicCount();
			if ($topic->getQuestion() === 1) {
				$user->increaseQuestionCount();
			}
			$this->frontendUserRepository->update($user);
		}
		$this->topicRepository->add($topic);

		return $topic;
	}

	/**
	 * Deletes a topic and all posts contained in it.
	 *
	 * @param Topic $topic
	 */
	public function deleteTopic(Topic $topic) {
		foreach ($topic->getPosts() as $post) {
			/** @var $post Post */
			$post->getAuthor()->decreasePostCount();
			$post->getAuthor()->decreasePoints((int)$this->settings['rankScore']['newPost']);
			$this->frontendUserRepository->update($post->getAuthor());
		}

		$forum = $topic->getForum();
		$forum->removeTopic($topic);
		$this->topicRepository->remove($topic);

		$this->persistenceManager->persistAll();

		$user = $this->getCurrentUser();

		if (!$user->isAnonymous()) {
			$user->decreaseTopicCount();
			if ($topic->getQuestion() == 1) {
				$user->decreaseQuestionCount();
			}
			$this->frontendUserRepository->update($user);
		}
	}

	/**
	 * Creates a new shadow topic.
	 *
	 * @param Topic $topic The original topic. The newly created shadow topic will then point towards this topic.
	 *
	 * @return ShadowTopic The newly created shadow topic.
	 */
	public function createShadowTopic(Topic $topic) {
		/** @var $shadowTopic ShadowTopic */
		$shadowTopic = GeneralUtility::makeInstance(ShadowTopic::class);
		$shadowTopic->setTarget($topic);

		return $shadowTopic;
	}

	/**
	 * Moves a topic from one forum to another. This method will create a shadow
	 * topic in the original place that will point to the new location of the
	 * topic.
	 *
	 * @param Topic $topic       The topic that is to be moved.
	 * @param Forum $targetForum The target forum. The topic will be moved to this location.
	 *
	 * @throws InvalidClassException
	 */
	public function moveTopic(Topic $topic, Forum $targetForum) {
		if ($topic instanceof ShadowTopic) {
			throw new InvalidClassException("Topic is already a shadow topic", 1288702422);
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
	 * @param Topic $topic
	 * @param Post  $post
	 */
	public function setPostAsSolution(Topic $topic, Post $post) {
		$topic->setSolution($post);
		$this->topicRepository->update($topic);
		$this->forumRepository->update($topic->getForum());
	}

}
