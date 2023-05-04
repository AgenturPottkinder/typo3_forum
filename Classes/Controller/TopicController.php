<?php
namespace Mittwald\Typo3Forum\Controller;

/*                                                                      *
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

use Mittwald\Typo3Forum\Domain\Exception\Authentication\NoAccessException;
use Mittwald\Typo3Forum\Domain\Exception\InvalidOperationException;
use Mittwald\Typo3Forum\Domain\Factory\Forum\PostFactory;
use Mittwald\Typo3Forum\Domain\Factory\Forum\TopicFactory;
use Mittwald\Typo3Forum\Domain\Model\Forum\Forum;
use Mittwald\Typo3Forum\Domain\Model\Forum\Post;
use Mittwald\Typo3Forum\Domain\Model\Forum\Tag;
use Mittwald\Typo3Forum\Domain\Model\Forum\Topic;
use Mittwald\Typo3Forum\Domain\Repository\Forum\ForumRepository;
use Mittwald\Typo3Forum\Domain\Repository\Forum\PostRepository;
use Mittwald\Typo3Forum\Domain\Repository\Forum\TagRepository;
use Mittwald\Typo3Forum\Domain\Repository\Forum\TopicRepository;
use Mittwald\Typo3Forum\Service\AttachmentService;
use Mittwald\Typo3Forum\Service\TagService;
use TYPO3\CMS\Extbase\Annotation\IgnoreValidation;
use TYPO3\CMS\Extbase\Annotation\Validate;
use TYPO3\CMS\Extbase\Persistence\Generic\PersistenceManager;
use TYPO3\CMS\Extbase\Persistence\ObjectStorage;

class TopicController extends AbstractController
{
    protected AttachmentService $attachmentService;
    protected ForumRepository $forumRepository;
    protected PostFactory $postFactory;
    protected PostRepository $postRepository;
    protected TagRepository $tagRepository;
    protected TagService $tagService;
    protected TopicFactory $topicFactory;
    protected TopicRepository $topicRepository;
    protected PersistenceManager $persistenceManager;

    public function __construct(
        AttachmentService $attachmentService,
        ForumRepository $forumRepository,
        PostFactory $postFactory,
        PostRepository $postRepository,
        TagRepository $tagRepository,
        TagService $tagService,
        TopicFactory $topicFactory,
        TopicRepository $topicRepository,
        PersistenceManager $persistenceManager
    ) {
        $this->attachmentService = $attachmentService;
        $this->forumRepository = $forumRepository;
        $this->postFactory = $postFactory;
        $this->postRepository = $postRepository;
        $this->tagRepository = $tagRepository;
        $this->tagService = $tagService;
        $this->topicFactory = $topicFactory;
        $this->topicRepository = $topicRepository;
        $this->persistenceManager = $persistenceManager;
    }

    /**
     *  Listing Action.
     */
    public function listAction(int $page = 1): void
    {
        $showPaginate = false;
        switch ($this->settings['listTopics']) {
            case '2':
                $dataset = $this->topicRepository->findQuestions($this->settings['maxItems'] ?? null, true);
                $showPaginate = true;
                break;
            case '3':
                $dataset = $this->topicRepository->findQuestions($this->settings['maxItems'] ?? null, false);
                $showPaginate = true;
                break;
            case '4':
                $dataset = $this->topicRepository->findPopularTopics((int)($this->settings['popularTopicTimeDiff']), $this->settings['maxItems'] ?? null);
                break;
            default:
                $dataset = $this->topicRepository->findLatest(null, $this->settings['maxItems'] ?? null);
                $showPaginate = true;
                break;
        }
        $this->view->assign('showPaginate', $showPaginate);
        $this->view->assign('topics', $dataset);
        $this->view->assign('page', $page);
    }

    /**
     * Show action. Displays a single topic and all posts contained in this topic.
     */
    public function showAction(Topic $topic, Post $quote = null, int $page = 1): void
    {
        $posts = $this->postRepository->findForTopic($topic);

        if ($quote !== null) {
            $this->view->assign('quote', $this->postFactory->createPostWithQuote($quote));
        }
        // Set Title
        $GLOBALS['TSFE']->page['title'] = $topic->getTitle();

        // Send signal for read count
        $this->signalSlotDispatcher->dispatch(Topic::class, 'topicDisplayed', [$topic]);

        $this->authenticationService->assertReadAuthorization($topic);
        $this->markTopicRead($topic);
        $this->view->assignMultiple([
            'posts' => $posts,
            'topic' => $topic,
            'user' => $this->getCurrentUser(),
            'page' => $page,
        ]);
    }

    /**
     * New action. Displays a form for creating a new topic.
     *
     * @IgnoreValidation("post")
     */
    public function newAction(Forum $forum, Post $post = null, string $subject = ''): void
    {
        $this->authenticationService->assertNewTopicAuthorization($forum);
        $this->view->assignMultiple([
            'currentUser' => $this->frontendUserRepository->findCurrent(),
            'forum' => $forum,
            'post' => $post,
            'subject' => $subject,
            'availableTags' => $this->tagRepository->findAll(),
        ]);
    }

    /**
     * Creates a new topic.
     *
     * @Validate("\Mittwald\Typo3Forum\Domain\Validator\Forum\PostValidator", param="post")
     * @Validate("\Mittwald\Typo3Forum\Domain\Validator\Forum\AttachmentPlainValidator", param="attachments")
     * @Validate("NotEmpty", param="subject")
     */
    public function createAction(
        Forum $forum,
        Post $post,
        string $subject,
        array $tags = [],
        array $attachments = [],
        bool $question = false,
        bool $subscribe = false
    ): void {
        // Assert authorization
        $this->authenticationService->assertNewTopicAuthorization($forum);

        $this->postFactory->assignUserToPost($post);

        if (count($attachments) > 0) {
            $attachments = $this->attachmentService->initAttachments($attachments);
            $post->setAttachments($attachments);
        }

        $tags = $this->tagService->hydrateTags($tags);

        $topic = $this->topicFactory->createTopic($forum, $post, $subject, $question, $tags, $subscribe);

        // Persist early so we can redirect to the topic in event listeners.
        $this->persistenceManager->persistAll();

        // Notify potential listeners.
        $this->signalSlotDispatcher->dispatch(Topic::class, 'topicCreated', [$topic]);
        $this->clearCacheForCurrentPage();

        if ($this->settings['purgeCache']) {
            $uriBuilder = $this->uriBuilder;
            $uri = $uriBuilder->setTargetPageUid($this->settings['pids']['Forum'])->setArguments(['tx_typo3forum_forum[forum]' => $forum->getUid(), 'tx_typo3forum_forum[controller]' => 'Forum', 'tx_typo3forum_forum[action]' => 'show'])->build();
            $this->purgeUrl('http://' . $_SERVER['HTTP_HOST'] . '/' . $uri);
        }

        // Redirect to single forum display view
        $this->redirect('show', 'Topic', null, ['topic' => $topic]);
    }

    /**
     * Sets a post as solution.
     *
     * @throws NoAccessException
     * @throws InvalidOperationException
     */
    public function solutionAction(Post $post): void
    {
        if (!$post->getTopic()->checkSolutionAccess($this->getCurrentUser())) {
            throw new NoAccessException('Not allowed to set solution by current user.');
        }
        if ($post->isFirstPost()) {
            throw new InvalidOperationException('The first post of a topic cannot be its solution.');
        }
        $this->topicFactory->setPostAsSolution($post->getTopic(), $post);

        $this->clearCacheForCurrentPage();

        $this->redirect('show', 'Topic', null, ['topic' => $post->getTopic()]);
    }

    /**
     * Removes the topic's solution.
     *
     * @throws NoAccessException
     * @throws InvalidOperationException
     */
    public function removeSolutionAction(Topic $topic): void
    {
        if (!$topic->checkSolutionAccess($this->getCurrentUser())) {
            throw new NoAccessException('Not allowed to remove solution by current user.');
        }
        $this->topicFactory->setPostAsSolution($topic, null);

        $this->clearCacheForCurrentPage();

        $this->redirect('show', 'Topic', null, ['topic' => $topic]);
    }

    /**
     * Marks a topic as read by the current user.
     */
    protected function markTopicRead(Topic $topic): void
    {
        $currentUser = $this->getCurrentUser();
        if ($currentUser === null || $currentUser->isAnonymous()) {
            return;
        }
        if ((false === $topic->hasBeenReadByUser($currentUser))) {
            $currentUser->addReadObject($topic);
            $this->frontendUserRepository->update($currentUser);
        }
    }
}
