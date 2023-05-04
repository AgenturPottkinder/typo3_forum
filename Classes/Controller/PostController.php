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

use Mittwald\Typo3Forum\Domain\Factory\Forum\PostFactory;
use Mittwald\Typo3Forum\Domain\Factory\Forum\TopicFactory;
use Mittwald\Typo3Forum\Domain\Model\Forum\Attachment;
use Mittwald\Typo3Forum\Domain\Model\Forum\Post;
use Mittwald\Typo3Forum\Domain\Model\Forum\Tag;
use Mittwald\Typo3Forum\Domain\Model\Forum\Topic;
use Mittwald\Typo3Forum\Domain\Model\User\FrontendUser;
use Mittwald\Typo3Forum\Domain\Repository\Forum\AttachmentRepository;
use Mittwald\Typo3Forum\Domain\Repository\Forum\ForumRepository;
use Mittwald\Typo3Forum\Domain\Repository\Forum\PostRepository;
use Mittwald\Typo3Forum\Domain\Repository\Forum\TagRepository;
use Mittwald\Typo3Forum\Domain\Repository\Forum\TopicRepository;
use Mittwald\Typo3Forum\Service\AttachmentService;
use Mittwald\Typo3Forum\Service\TagService;
use Mittwald\Typo3Forum\Utility\Localization;
use TYPO3\CMS\Core\Messaging\FlashMessage;
use TYPO3\CMS\Extbase\Annotation\IgnoreValidation;
use TYPO3\CMS\Extbase\Annotation\Validate;
use TYPO3\CMS\Extbase\Persistence\Generic\PersistenceManager;

class PostController extends AbstractController
{
    protected AttachmentRepository $attachmentRepository;
    protected AttachmentService $attachmentService;
    protected ForumRepository $forumRepository;
    protected PostRepository $postRepository;
    protected PostFactory $postFactory;
    protected TopicRepository $topicRepository;
    protected TopicFactory $topicFactory;
    protected PersistenceManager $persistenceManager;
    protected TagRepository $tagRepository;
    protected TagService $tagService;

    public function __construct(
        AttachmentRepository $attachmentRepository,
        AttachmentService $attachmentService,
        ForumRepository $forumRepository,
        PostRepository $postRepository,
        PostFactory $postFactory,
        TopicRepository $topicRepository,
        TopicFactory $topicFactory,
        PersistenceManager $persistenceManager,
        TagRepository $tagRepository,
        TagService $tagService
    ) {
        $this->attachmentRepository = $attachmentRepository;
        $this->attachmentService = $attachmentService;
        $this->forumRepository = $forumRepository;
        $this->postRepository = $postRepository;
        $this->postFactory = $postFactory;
        $this->topicRepository = $topicRepository;
        $this->topicFactory = $topicFactory;
        $this->persistenceManager = $persistenceManager;
        $this->tagRepository = $tagRepository;
        $this->tagService = $tagService;
    }

    public function listAction(int $page = 1): void
    {
        $showPaginate = false;
        switch ($this->settings['listPosts']) {
            case '2':
                $posts = $this->postRepository->findByFilter(
                    $this->settings['maxItems'] ?? null,
                    ['crdate' => 'DESC']
                );
                break;
            default:
                $posts = $this->postRepository->findByFilter(
                    $this->settings['maxItems'] ?? null,
                    ['crdate' => 'DESC']
                );
                $showPaginate = true;
                break;
        }
        $this->view->assign('showPaginate', $showPaginate);
        $this->view->assign('posts', $posts);
        $this->view->assign('page', $page);
    }

    public function supportAction(Post $post): void
    {
        /** @var FrontendUser $currentUser */
        $currentUser = $this->authenticationService->getUser();

        // Return if User not logged in or user is post author or user has already supported the post
        if ($currentUser === null || $currentUser->isAnonymous() || $post->hasBeenSupportedByUser($currentUser)) {
            $this->redirect('show', 'Post', null, ['post' => $post]);
        }

        // Set helpfulCount for Author
        $post->addSupporter($currentUser);
        $this->postRepository->update($post);

        $post->getAuthor()->setHelpfulCount($post->getAuthor()->getHelpfulCount() + 1);
        $post->getAuthor()->increasePoints((int)$this->settings['rankScore']['gotHelpful']);
        $this->frontendUserRepository->update($post->getAuthor());

        $currentUser->increasePoints((int)$this->settings['rankScore']['markHelpful']);
        $this->frontendUserRepository->update($currentUser);

        $this->clearCacheForCurrentPage();

        $this->redirect('show', 'Post', null, ['post' => $post]);
    }

    public function unsupportAction(Post $post): void
    {
        /** @var FrontendUser $currentUser */
        $currentUser = $this->authenticationService->getUser();

        if (!$post->hasBeenSupportedByUser($currentUser)) {
            $this->redirect('show', 'Post', null, ['post' => $post]);
        }

        // Set helpfulCount for Author
        $post->removeSupporter($currentUser);
        $this->postRepository->update($post);

        $post->getAuthor()->setHelpfulCount($post->getAuthor()->getHelpfulCount() - 1);
        $post->getAuthor()->decreasePoints((int)$this->settings['rankScore']['gotHelpful']);
        $this->frontendUserRepository->update($post->getAuthor());
        $currentUser->decreasePoints((int)$this->settings['rankScore']['markHelpful']);
        $this->frontendUserRepository->update($currentUser);

        $this->clearCacheForCurrentPage();

        $this->redirect('show', 'Post', null, ['post' => $post]);
    }

    public function showAction(Post $post, Post $quote = null): void
    {
        // Assert authentication
        $this->authenticationService->assertReadAuthorization($post);

        // Get correct page for post.
        $topic = $post->getTopic();
        $pageWithPost = 1;
        if ($topic->getPageCount() > 1) {
            $postOffsetInTopic = array_search($post, $topic->getPosts()->toArray(), true);

            $pageWithPost = intdiv(
                $postOffsetInTopic,
                (int)($topic->getSettings()['pagebrowser']['topicShow']['itemsPerPage'] ?? 10)
            ) + 1;
            $pageWithPost = max(1, $pageWithPost);
        }

        // Create target URL.
        $sectionWrap = $this->settings['topicController']['show']['postIdWrap'] ?? 'post-|';
        $targetUrl = $this->uriBuilder
            ->reset()

            ->setSection(str_replace('|', $post->getUid(), $sectionWrap))
            ->uriFor(
                'show',
                [
                    'topic' => $post->getTopic(),
                    'quote' => $quote,
                    'page' => $pageWithPost,
                ],
                'Topic',
            )
        ;
        $this->redirectToUri($targetUrl);
    }

    /**
     * Displays the form for creating a new post.
     *
     * @IgnoreValidation("post")
     */
    public function newAction(Topic $topic, Post $post = null, Post $quote = null): void
    {
        // Assert authorization
        $this->authenticationService->assertNewPostAuthorization($topic);

        // If no post is specified, create an optionally pre-filled post (if a
        // quoted post was specified).
        if ($post === null) {
            $post = ($quote !== null) ? $this->postFactory->createPostWithQuote($quote) : $this->postFactory->createEmptyPost();
        } else {
            $this->authenticationService->assertEditPostAuthorization($post);
        }

        $this->view->assignMultiple([
            'topic' => $topic,
            'post' => $post,
            'currentUser' => $this->frontendUserRepository->findCurrent()
        ]);
    }

    /**
     * Creates a new post.
     *
     * @Validate("\Mittwald\Typo3Forum\Domain\Validator\Forum\PostValidator", param="post")
     * @Validate("\Mittwald\Typo3Forum\Domain\Validator\Forum\AttachmentPlainValidator", param="attachments")
     */
    public function createAction(Topic $topic, Post $post, array $attachments = []): void
    {
        // Assert authorization
        $this->authenticationService->assertNewPostAuthorization($topic);

        // Create new post, add the new post to the topic and persist the topic.
        $this->postFactory->assignUserToPost($post);

        if (!empty($attachments)) {
            $attachments = $this->attachmentService->initAttachments($attachments);
            $post->setAttachments($attachments);
        }

        $topic->addPost($post);
        $this->topicRepository->update($topic);

        // Persist early so we can redirect to the post properly.
        $this->persistenceManager->persistAll();

        // All potential listeners
        $this->signalSlotDispatcher->dispatch(Post::class, 'postCreated', [$post]);

        // Display flash message and redirect to topic->show action.
        $this->getFlashMessageQueue()->enqueue(
            new FlashMessage(Localization::translate('Post_Create_Success'))
        );
        $this->clearCacheForCurrentPage();

        $this->redirect('show', 'Post', null, ['post' => $post]);
    }

    /**
     * Displays a form for editing a post.
     *
     * @IgnoreValidation("post")
     */
    public function editAction(Post $post): void
    {
        if ($post->getAuthor() != $this->authenticationService->getUser() || $post->getTopic()->getLastPost()->getAuthor() != $post->getAuthor()) {
            $this->authenticationService->assertModerationAuthorization($post->getTopic()->getForum());
        }
        $this->view->assign('post', $post);
        $this->view->assign('availableTags', $this->tagRepository->findAll());
    }

    /**
     * Updates a post and its containing topic (if this is a first post).
     */
    public function updateAction(Post $post, array $newAttachments = [], array $keepAttachments = [], array $tags = []):  void
    {
        if (
            $post->getAuthor()->getUid() != $this->getCurrentUser()->getUid()
            || $post->getTopic()->getLastPost()->getAuthor() != $post->getAuthor()
        ) {
            $this->authenticationService->assertModerationAuthorization($post->getTopic()->getForum());
        }

        // Manage new and deleted attachments.
        $keepAttachments = array_map('intval', $keepAttachments);
        foreach ($post->getAttachments()->toArray() as $existingAttachment) {
            if (!in_array($existingAttachment->getUid(), $keepAttachments)) {
                $post->removeAttachment($existingAttachment);
            }
        }

        if (count($newAttachments) > 0) {
            $newAttachments = $this->attachmentService->initAttachments($newAttachments);
            foreach ($newAttachments as $newAttachment) {
                $post->addAttachments($newAttachment);
            }
        }

        // If a first post was being edited, update tags and tag topic counts.
        $topic = $post->getTopic();
        if ($post->isFirstPost()) {
            $tags = $this->tagService->hydrateTags($tags);

            $existingTags = $topic->getTags();
            foreach ($existingTags->toArray() as $existingTag) {
                /** @var Tag $existingTag */
                $existingTag->decreaseTopicCount();
                $existingTags->detach($existingTag);
                $this->tagRepository->update($existingTag);
            }

            foreach ($tags as $tag) {
                $tag->increaseTopicCount();
                $existingTags->attach($tag);
                $this->tagRepository->update($tag);
            }
        }

        // If a first post was being edited, make sure that the topic is marked as
        // unsolved when the question flag is unset.
        if ($post->isFirstPost() && !$topic->isQuestion() && $topic->isSolved()) {
            $this->topicFactory->setPostAsSolution($topic, null);
        }

        $this->postRepository->update($post);

        $this->signalSlotDispatcher->dispatch(Post::class, 'postUpdated', [$post]);
        $this->getFlashMessageQueue()->enqueue(
            new FlashMessage(Localization::translate('Post_Update_Success'))
        );
        $this->clearCacheForCurrentPage();
        $this->redirect('show', 'Post', null, ['post' => $post]);
    }

    /**
     * Displays a confirmation screen in which the user is prompted if a post
     * should really be deleted.
     */
    public function confirmDeleteAction(Post $post): void
    {
        $this->authenticationService->assertDeletePostAuthorization($post);

        $this->view->assign('post', $post);
    }

    /**
     * Deletes a post.
     */
    public function deleteAction(Post $post): void
    {
        // Assert authorization
        $this->authenticationService->assertDeletePostAuthorization($post);

        // Delete the post.
        $postCount = $post->getTopic()->getPostCount();
        $this->postFactory->deletePost($post);
        $this->getFlashMessageQueue()->enqueue(
            new FlashMessage(Localization::translate('Post_Delete_Success'))
        );

        // Notify observers and clear cache.
        $this->signalSlotDispatcher->dispatch(Post::class, 'postDeleted', [$post]);
        $this->clearCacheForCurrentPage();

        // If there is still on post left in the topic, redirect to the topic
        // view. If we have deleted the last post of a topic (i.e. the topic
        // itself), redirect to the forum view instead.
        if ($postCount > 1) {
            $this->redirect('show', 'Topic', null, ['topic' => $post->getTopic()]);
        } else {
            $this->redirect('show', 'Forum', null, ['forum' => $post->getForum()]);
        }
    }

    /**
     * Downloads an attachment and increase the download counter
     */
    public function downloadAttachmentAction(Attachment $attachment): void
    {
        $attachment->increaseDownloadCount();
        $this->attachmentRepository->update($attachment);

        //Enforce persistence, since it will not happen regularly because of die() at the end
        $this->persistenceManager->persistAll();

        while(ob_get_level() > 0) {
            ob_end_clean();
        }
        ob_start();
        header('Content-Type: ' . $attachment->getFileReference()->getOriginalResource()->getType());
        header('Content-Type: application/download');
        header('Content-Disposition: attachment; filename="' . $attachment->getName() . '"');
        echo($attachment->getFileReference()->getOriginalResource()->getContents());
        ob_flush();
        exit();
    }
}
