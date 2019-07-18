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
use Mittwald\Typo3Forum\Domain\Model\Forum\Forum;
use Mittwald\Typo3Forum\Domain\Model\Forum\Post;
use Mittwald\Typo3Forum\Domain\Model\Forum\Topic;

class TopicController extends AbstractController {

	/**
	 * @var \Mittwald\Typo3Forum\Domain\Repository\Forum\AdRepository
	 * @inject
	 */
	protected $adRepository;

	/**
	 * @var \Mittwald\Typo3Forum\Service\AttachmentService
	 * @inject
	 */
	protected $attachmentService;

	/**
	 * @var \Mittwald\Typo3Forum\Domain\Repository\Forum\CriteriaRepository
	 * @inject
	 */
	protected $criteraRepository;

	/**
	 * @var \TYPO3\CMS\Core\Database\DatabaseConnection
	 */
	protected $databaseConnection;

	/**
	 * @var \Mittwald\Typo3Forum\Domain\Repository\Forum\ForumRepository
	 * @inject
	 */
	protected $forumRepository;

	/**
	 * @var \Mittwald\Typo3Forum\Domain\Factory\Forum\PostFactory
	 * @inject
	 */
	protected $postFactory;

	/**
	 * @var \Mittwald\Typo3Forum\Domain\Repository\Forum\PostRepository
	 * @inject
	 */
	protected $postRepository;

	/**
	 * @var \Mittwald\Typo3Forum\Service\SessionHandlingService
	 * @inject
	 */
	protected $sessionHandling;

	/**
	 * @var \Mittwald\Typo3Forum\Domain\Repository\Forum\TagRepository
	 * @inject
	 */
	protected $tagRepository;

	/**
	 * @var \Mittwald\Typo3Forum\Service\TagService
	 * @inject
	 */
	protected $tagService = NULL;

	/**
	 * @var \Mittwald\Typo3Forum\Domain\Factory\Forum\TopicFactory
	 * @inject
	 */
	protected $topicFactory;

	/**
	 * @var \Mittwald\Typo3Forum\Domain\Repository\Forum\TopicRepository
	 * @inject
	 */
	protected $topicRepository;

	/**
	 *
	 */
	public function initializeObject() {
		$this->databaseConnection = $GLOBALS['TYPO3_DB'];
	}

    /**
     *  Listing Action.
     * @return void
     */
    public function listAction() {

        $showPaginate = FALSE;
        switch ($this->settings['listTopics']) {
            case '2':
                $dataset = $this->topicRepository->findQuestions();
                $showPaginate = TRUE;
                $partial = 'Topic/List';
                break;
            case '3':
                $dataset = $this->topicRepository->findQuestions(intval($this->settings['maxTopicItems']));
                $partial = 'Topic/QuestionBox';
                break;
            case '4':
                $dataset = $this->topicRepository->findPopularTopics(intval($this->settings['popularTopicTimeDiff']), intval($this->settings['maxTopicItems']));
                $partial = 'Topic/ListBox';
                break;
            default:
                $dataset      = $this->topicRepository->findAll();
                $partial      = 'Topic/List';
                $showPaginate = TRUE;
                break;
        }
        $this->view->assign('showPaginate', $showPaginate);
        $this->view->assign('partial', $partial);
        $this->view->assign('topics', $dataset);
    }

	/**
	 *  Listing Action.
	 */
	public function listLatestAction() {
		if (!empty($this->settings['countLatestPost'])) {
			$limit = (int)$this->settings['countLatestPost'];
		} else {
			$limit = 3;
		}

		$topics = $this->topicRepository->findLatest(0, $limit);
		$this->view->assign('topics', $topics);
	}

	/**
	 * Show action. Displays a single topic and all posts contained in this topic.
	 *
	 * @param Topic $topic The topic that is to be displayed.
	 * @param Post $quote An optional post that will be quoted within the bodytext of the new post.
	 * @param int $showForm ShowForm
	 */
	public function showAction(Topic $topic, Post $quote = NULL, $showForm = 0) {
		$posts = $this->postRepository->findForTopic($topic);

		if ($quote != FALSE) {
			$this->view->assign('quote', $this->postFactory->createPostWithQuote($quote));
		}
		// Set Title
		$GLOBALS['TSFE']->page['title'] = $topic->getTitle();

		$googlePlus = $topic->getAuthor()->getGoogle();
		if ($googlePlus) {
			$this->response->addAdditionalHeaderData('<link rel="author" href="' . $googlePlus . '"/>');
		}

		// send signal for simple read count
		$this->signalSlotDispatcher->dispatch(Topic::class, 'topicDisplayed', ['topic' => $topic]);

		$this->authenticationService->assertReadAuthorization($topic);
		$this->markTopicRead($topic);
		$this->view->assignMultiple([
			'posts' => $posts,
			'showForm' => $showForm,
			'topic' => $topic,
			'user' => $this->authenticationService->getUser(),
		]);
	}

	/**
	 * New action. Displays a form for creating a new topic.
	 *
	 * @param Forum $forum The forum in which the new topic is to be created.
	 * @param Post $post The first post of the new topic.
	 * @param string $subject The subject of the new topic
	 *
	 * @ignorevalidation $post
	 */
	public function newAction(Forum $forum, Post $post = NULL, $subject = NULL) {
		$this->authenticationService->assertNewTopicAuthorization($forum);
		$this->view->assignMultiple([
			'criteria' => $forum->getCriteria(),
			'currentUser' => $this->frontendUserRepository->findCurrent(),
			'forum' => $forum,
			'post' => $post,
			'subject' => $subject,
		]);
	}

	/**
	 * Creates a new topic.
	 *
	 * @param Forum $forum The forum in which the new topic is to be created.
	 * @param Post $post The first post of the new topic.
	 * @param string $subject The subject of the new topic
	 * @param array $attachments File attachments for the post.
	 * @param string $question The flag if the new topic is declared as question
	 * @param array $criteria All submitted criteria with option.
	 * @param string $tags All defined tags for this topic
	 * @param string $subscribe The flag if the new topic is subscribed by author
	 *
	 * @validate $post \Mittwald\Typo3Forum\Domain\Validator\Forum\PostValidator
	 * @validate $attachments \Mittwald\Typo3Forum\Domain\Validator\Forum\AttachmentPlainValidator
	 * @validate $subject NotEmpty
	 */
	public function createAction(Forum $forum, Post $post, $subject, $attachments = [], $question = '', $criteria = [], $tags = '', $subscribe = '') {

		// Assert authorization
		$this->authenticationService->assertNewTopicAuthorization($forum);

		// Create the new post; add the new post to a new topic and add the new
		// topic to the forum. Then persist the forum object. Not as complicated
		// as is sounds, honestly!
		$this->postFactory->assignUserToPost($post);

		if (!empty($attachments)) {
			$attachments = $this->attachmentService->initAttachments($attachments);
			$post->setAttachments($attachments);
		}

		if ($tags) {
			$tags = $this->tagService->initTags($tags);
			foreach ($tags as $tag) {
				if ($tag->getUid === NULL) {
					$this->tagRepository->add($tag);
				}
			}
		} else {
			$tags = NULL;
		}

		$topic = $this->topicFactory->createTopic($forum, $post, $subject, (int)$question, $criteria, $tags, (int)$subscribe);

		// Notify potential listeners.
		$this->signalSlotDispatcher->dispatch(Topic::class, 'topicCreated', ['topic' => $topic]);
		$this->clearCacheForCurrentPage();

		if ($this->settings['purgeCache']) {
			$uriBuilder = $this->controllerContext->getUriBuilder();
			$uri = $uriBuilder->setTargetPageUid($this->settings['pids']['Forum'])->setArguments(['tx_typo3forum_pi1[forum]' => $forum->getUid(), 'tx_typo3forum_pi1[controller]' => 'Forum', 'tx_typo3forum_pi1[action]' => 'show'])->build();
			$this->purgeUrl('http://' . $_SERVER['HTTP_HOST'] . '/' . $uri);
		}

		// Redirect to single forum display view
		$this->redirect('show', 'Forum', NULL, ['forum' => $forum]);
	}

	/**
	 * Sets a post as solution
	 *
	 * @param Post $post The post to be marked as solution.
	 *
	 * @throws NoAccessException
	 */
	public function solutionAction(Post $post) {
		if (!$post->getTopic()->checkSolutionAccess($this->authenticationService->getUser())) {
			throw new NoAccessException('Not allowed to set solution by current user.');
		}
		$this->topicFactory->setPostAsSolution($post->getTopic(), $post);
		$this->redirect('show', 'Topic', NULL, ['topic' => $post->getTopic()]);
	}

	/**
	 * Marks a topic as read by the current user.
	 *
	 * @param Topic $topic The topic that is to be marked as read.
	 *
	 */
	protected function markTopicRead(Topic $topic) {
		$currentUser = $this->getCurrentUser();
		if ($currentUser === NULL || $currentUser->isAnonymous()) {
			return;
		} else {
			if ((false === $topic->hasBeenReadByUser($currentUser))) {
				$currentUser->addReadObject($topic);
				$this->frontendUserRepository->update($currentUser);
			}
		}
	}

}
