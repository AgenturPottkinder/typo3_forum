<?php
namespace Mittwald\Typo3Forum\Domain\Factory\Forum;

use Mittwald\Typo3Forum\Domain\Factory\AbstractFactory;

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

use Mittwald\Typo3Forum\Domain\Model\Forum\Forum;
use Mittwald\Typo3Forum\Domain\Model\Forum\Post;
use Mittwald\Typo3Forum\Domain\Model\Forum\ShadowTopic;
use Mittwald\Typo3Forum\Domain\Model\Forum\Topic;
use Mittwald\Typo3Forum\Domain\Repository\Forum\ForumRepository;
use Mittwald\Typo3Forum\Domain\Repository\Forum\PostRepository;
use Mittwald\Typo3Forum\Domain\Repository\Forum\TopicRepository;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Persistence\Generic\Exception\InvalidClassException;
use TYPO3\CMS\Extbase\Persistence\Generic\PersistenceManager;
use TYPO3\CMS\Extbase\Persistence\ObjectStorage;

class TopicFactory extends AbstractFactory
{
    protected ForumRepository $forumRepository;
    protected PostRepository $postRepository;
    protected TopicRepository $topicRepository;
    protected PersistenceManager $persistenceManager;

    public function __construct(
        ForumRepository $forumRepository,
        PostRepository $postRepository,
        TopicRepository $topicRepository,
        PersistenceManager $persistenceManager
    ) {
        $this->forumRepository = $forumRepository;
        $this->postRepository = $postRepository;
        $this->topicRepository = $topicRepository;
        $this->persistenceManager = $persistenceManager;
    }

    /**
     * Creates a new topic.
     */
    public function createTopic(
        Forum $forum,
        Post $firstPost,
        string $subject,
        bool $question = false,
        ?ObjectStorage $tags = null,
        bool $subscribe = false
    ): Topic {
        /** @var Topic $topic */
        $topic = $this->getClassInstance();
        $user = $this->getCurrentUser();

        $forum->addTopic($topic);
        $topic->setSubject($subject);
        $topic->setAuthor($user);
        $topic->setQuestion($question);
        $topic->addPost($firstPost);

        if ($tags != null) {
            $topic->setTags($tags);
        }
        if ((int)$subscribe === 1) {
            $topic->addSubscriber($user);
        }

        if (!$user->isAnonymous()) {
            $user->increaseTopicCount();
            if ($topic->isQuestion()) {
                $user->increaseQuestionCount();
            }
            $this->frontendUserRepository->update($user);
        }
        $this->topicRepository->add($topic);

        // Redundant persistence needed for TYPO3 to generate slugs automatically. Bummer!
        $this->persistenceManager->persistAll();

        $topic->generateSlugIfEmpty();
        $this->topicRepository->update($topic);
        $this->persistenceManager->persistAll();

        return $topic;
    }

    /**
     * Deletes a topic and all posts contained in it.
     */
    public function deleteTopic(Topic $topic): void
    {
        if ($topic->isQuestion() && $topic->getSolution() !== null) {
            $solutionAuthor = $topic->getSolution()->getAuthor();
            $solutionAuthor->decreasePoints((int)$this->settings['rankScore']['gaveSolution']);
            $topic->getAuthor()->decreasePoints((int)$this->settings['rankScore']['selectedSolution']);
        }

        foreach ($topic->getPosts() as $post) {
            /** @var Post $post */
            $postAuthor = $post->getAuthor();

            if (!$postAuthor->isAnonymous()) {
                $postAuthor->decreasePostCount();
                $postAuthor->decreasePoints((int)$this->settings['rankScore']['newPost']);
                $this->frontendUserRepository->update($postAuthor);
            }
        }

        $forum = $topic->getForum();
        $forum->removeTopic($topic);
        $this->topicRepository->remove($topic);

        $this->persistenceManager->persistAll();

        $topicAuthor = $topic->getAuthor();

        if (!$topicAuthor->isAnonymous()) {
            $topicAuthor->decreaseTopicCount();
            if ($topic->isQuestion()) {
                $topicAuthor->decreaseQuestionCount();
            }
            $this->frontendUserRepository->update($topicAuthor);
        }
    }

    /**
     * Creates a new shadow topic.
     */
    public function createShadowTopic(Topic $topic): ShadowTopic
    {
        $shadowTopic = GeneralUtility::makeInstance(ShadowTopic::class);
        $shadowTopic->setTarget($topic);

        return $shadowTopic;
    }

    /**
     * Moves a topic from one forum to another. This method will create a shadow
     * topic in the original place that will point to the new location of the
     * topic.
     *
     * @throws InvalidClassException
     */
    public function moveTopic(Topic $topic, Forum $targetForum): void
    {
        if ($topic instanceof ShadowTopic) {
            throw new InvalidClassException('Topic is already a shadow topic', 1288702422);
        }
        $shadowTopic = $this->createShadowTopic($topic);
        $topic->getForum()->addTopic($shadowTopic);

        $topic->setForum($targetForum);
        $targetForum->addTopic($topic);

        $this->topicRepository->add($shadowTopic);
        $this->forumRepository->update($topic->getForum());
        $this->forumRepository->update($targetForum);
        $this->topicRepository->update($topic);
    }

    /**
     * Sets a post as solution
     */
    public function setPostAsSolution(Topic $topic, ?Post $solution): void
    {
        $oldSolution = $topic->getSolution();

        $topic->setSolution($solution);
        $this->topicRepository->update($topic);
        $this->forumRepository->update($topic->getForum());

        // If the solution changed we award and deduct points.
        if (
            ($solution !== $oldSolution)
            && (
                $solution === null
                || $oldSolution === null
                || $oldSolution->getUid() !== $solution->getUid()
            )
        ) {
            // Add points to the new solution's author and deduct them from the old.
            // If the authors are the same person this will change nothing.
            $pointsForGivingSolution = (int)$this->settings['rankScore']['gaveSolution'] ?? 5;
            $pointsForSelectingSolution = (int)$this->settings['rankScore']['selectedSolution'] ?? 2;

            // If a solution is given, award points to the user who gave it.
            if ($solution !== null) {
                $newSolutionAuthor = $solution->getAuthor();
                $newSolutionAuthor->increasePoints($pointsForGivingSolution);
                $this->frontendUserRepository->update($newSolutionAuthor);
            }

            // If this is not first solution, deduct points from the old solution's author.
            if ($oldSolution !== null) {
                $oldSolutionAuthor = $oldSolution->getAuthor();
                $oldSolutionAuthor->decreasePoints($pointsForGivingSolution);
                $this->frontendUserRepository->update($oldSolutionAuthor);
            }

            // Deduct or award points to the question author, depending on if a solution was marked or not.
            $questionAuthor = $topic->getAuthor();
            if ($solution === null && $oldSolution !== null) {
                $questionAuthor->decreasePoints($pointsForSelectingSolution);
            }
            if ($solution !== null && $oldSolution === null) {
                $questionAuthor->increasePoints($pointsForSelectingSolution);
            }
            $this->frontendUserRepository->update($questionAuthor);
        }
    }
}
