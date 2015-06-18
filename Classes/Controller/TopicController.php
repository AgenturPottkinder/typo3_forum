<?php
namespace Mittwald\Typo3Forum\Controller;
/*                                                                      *
 *  COPYRIGHT NOTICE                                                    *
 *                                                                      *
 *  (c) 2013 Martin Helmich <m.helmich@mittwald.de>                     *
 *           Sebastian Gieselmann <s.gieselmann@mittwald.de>            *
 *           Ruven Fehling <r.fehling@mittwald.de>                      *
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
 * This class implements a simple dispatcher for a mm_form eID script.
 *
 * @author     Martin Helmich <m.helmich@mittwald.de>
 * @author     Sebastian Gieselmann <s.gieselmann@mittwald.de>
 * @author     Ruven Fehling <r.fehling@mittwald.de>
 * @package    Typo3Forum
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
class TopicController extends \Mittwald\Typo3Forum\Controller\AbstractController {



	/*
	 * ATTRIBUTES
	 */



	/**
	 * The topic repository.
	 * @var \Mittwald\Typo3Forum\Domain\Repository\Forum\TopicRepository
	 */
	protected $topicRepository;


	/**
	 * The forum repository.
	 * @var \Mittwald\Typo3Forum\Domain\Repository\Forum\ForumRepository
	 */
	protected $forumRepository;


	/**
	 * The post repository.
	 * @var \Mittwald\Typo3Forum\Domain\Repository\Forum\postRepository
	 */
	protected $postRepository;


	/**
	 * The ads repository.
	 * @var \Mittwald\Typo3Forum\Domain\Repository\Forum\AdsRepository
	 */
	protected $adsRepository;



	/**
	 * The tags repository.
	 * @var \Mittwald\Typo3Forum\Domain\Repository\Forum\TagRepository
	 */
	protected $tagRepository;


	/**
	 * A factory class for creating topics.
	 * @var \Mittwald\Typo3Forum\Domain\Factory\Forum\TopicFactory
	 */
	protected $topicFactory;


	/**
	 * A factory class for creating posts.
	 * @var \Mittwald\Typo3Forum\Domain\Factory\Forum\PostFactory
	 */
	protected $postFactory;


	/**
	 * The criteria repository.
	 * @var \Mittwald\Typo3Forum\Domain\Repository\Forum\CriteriaRepository
	 */
	protected $criteraRepository;


	/**
	 * SessionHandling
	 * @var \Mittwald\Typo3Forum\Service\SessionHandlingService
	 */
	protected $sessionHandling;


	/**
	 * @var \Mittwald\Typo3Forum\Service\AttachmentService
	 */
	protected $attachmentService = NULL;


	/**
	 * @var \Mittwald\Typo3Forum\Service\TagService
	 */
	protected $tagService = NULL;



	/*
	 * CONSTRUCTOR
	 */



	/**
	 * Constructor of this controller. Used primarily for dependency injection.
	 *
	 * @param \Mittwald\Typo3Forum\Domain\Repository\Forum\ForumRepository	$forumRepository
	 * @param \Mittwald\Typo3Forum\Domain\Repository\Forum\TopicRepository	$topicRepository
	 * @param \Mittwald\Typo3Forum\Domain\Repository\Forum\postRepository		$postRepository
	 * @param \Mittwald\Typo3Forum\Domain\Factory\Forum\TopicFactory			$topicFactory
	 * @param \Mittwald\Typo3Forum\Domain\Factory\Forum\PostFactory			$postFactory
	 * @param \Mittwald\Typo3Forum\Domain\Repository\Forum\CriteriaRepository $criteraRepository
	 * @param \Mittwald\Typo3Forum\Service\SessionHandlingService             $sessionHandling
	 * @param \Mittwald\Typo3Forum\Service\AttachmentService					$attachmentService
	 * @param \Mittwald\Typo3Forum\Domain\Repository\Forum\AdsRepository		$adsRepository
	 * @param \Mittwald\Typo3Forum\Service\TagService							$tagService
	 * @param \Mittwald\Typo3Forum\Domain\Repository\Forum\TagRepository		$tagRepository
	 */
	public function __construct(\Mittwald\Typo3Forum\Domain\Repository\Forum\ForumRepository $forumRepository,
								\Mittwald\Typo3Forum\Domain\Repository\Forum\TopicRepository $topicRepository,
								\Mittwald\Typo3Forum\Domain\Repository\Forum\postRepository $postRepository,
								\Mittwald\Typo3Forum\Domain\Factory\Forum\TopicFactory $topicFactory,
								\Mittwald\Typo3Forum\Domain\Factory\Forum\PostFactory $postFactory,
								\Mittwald\Typo3Forum\Domain\Repository\Forum\CriteriaRepository $criteraRepository,
								\Mittwald\Typo3Forum\Service\SessionHandlingService $sessionHandling,
								\Mittwald\Typo3Forum\Service\AttachmentService $attachmentService,
								\Mittwald\Typo3Forum\Domain\Repository\Forum\AdsRepository $adsRepository,
								\Mittwald\Typo3Forum\Service\TagService $tagService,
								\Mittwald\Typo3Forum\Domain\Repository\Forum\TagRepository $tagRepository) {
		parent::__construct();
		$this->forumRepository   = $forumRepository;
		$this->topicRepository   = $topicRepository;
		$this->postRepository    = $postRepository;
		$this->topicFactory      = $topicFactory;
		$this->postFactory       = $postFactory;
		$this->sessionHandling   = $sessionHandling;
		$this->criteraRepository = $criteraRepository;
		$this->attachmentService = $attachmentService;
		$this->adsRepository	 = $adsRepository;
		$this->tagService		 = $tagService;
		$this->tagRepository     = $tagRepository;
	}



	/*
	 * ACTION METHODS
	 */


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
				$dataset = $this->topicRepository->findQuestions(6);
				$partial = 'Topic/QuestionBox';
				break;
			case '4':
				$dataset = $this->topicRepository->findPopularTopics(intval($this->settings['popularTopicTimeDiff']),6);
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
	 * @return void
	 */
	public function listLatestAction() {
            if(!empty($this->settings['countLatestPost'])){
                $limit = intval($this->settings['countLatestPost']);
            }else{
                $limit = 3;
            }
            
		$topics      = $this->topicRepository->findLatest(0,$limit);
		$this->view->assign('topics', $topics);
	}

	/**
	 * Show action. Displays a single topic and all posts contained in this topic.
	 * @TODO: Remove $dummy variable when datamapper is stable
	 *
	 * @param \Mittwald\Typo3Forum\Domain\Model\Forum\Topic $topic
	 *                                                         The topic that is to be displayed.
	 * @param  \Mittwald\Typo3Forum\Domain\Model\Forum\Post $quote An optional post that will be quoted within the
	 *                                                    bodytext of the new post.
	 * @param int $showForm ShowForm
	 * @return void
	 */
	public function showAction(\Mittwald\Typo3Forum\Domain\Model\Forum\Topic $topic, \Mittwald\Typo3Forum\Domain\Model\Forum\Post $quote = NULL, $showForm = 0) {
		$posts = $this->postRepository->findForTopic($topic);

		if($quote != FALSE){
			$this->view->assign('quote', $this->postFactory->createPostWithQuote($quote));
		}
		// Set Title
		$GLOBALS['TSFE']->page['title'] = $topic->getTitle();

		// This variable is needed, equal if it is used or not. It's needed for creating the correct datamapping
		// of the model and the repository. Otherwise an ajax request will destroy the datamapping of this model (Core Bug 6.X)
		$dummy = $this->adsRepository->findForTopicView(1);

		$subresults = $this->controllerContext->getRequest()->getOriginalRequestMappingResults()->getSubResults();

		$googlePlus = $topic->getAuthor()->getGoogle();
		if($googlePlus != "")
		{
			$this->response->addAdditionalHeaderData('<link rel="author" href="' . $googlePlus . '"/>');
		}
		// AdHandling End
		$this->authenticationService->assertReadAuthorization($topic);
		$this->markTopicRead($topic);
		$this->view->assign('topic', $topic)->assign('posts', $posts)->assign('user',$this->authenticationService->getUser())
			 ->assign('showForm',$showForm);
	}



	/**
	 * New action. Displays a form for creating a new topic.
	 *
	 * @param \Mittwald\Typo3Forum\Domain\Model\Forum\Forum $forum
	 *                                                          The forum in which the new topic is to be created.
	 * @param \Mittwald\Typo3Forum\Domain\Model\Forum\Post  $post
	 *                                                          The first post of the new topic.
	 * @param string                              $subject      The subject of the new topic
	 *
	 * @dontvalidate $post
	 */
	public function newAction(\Mittwald\Typo3Forum\Domain\Model\Forum\Forum $forum,
							  \Mittwald\Typo3Forum\Domain\Model\Forum\Post $post = NULL, $subject = NULL) {
		$this->authenticationService->assertNewTopicAuthorization($forum);
		$this->view->assign('forum', $forum)->assign('post', $post)->assign('subject', $subject)
			->assign('currentUser', $this->frontendUserRepository->findCurrent())
			->assign('criteria', $forum->getCriteria());
	}


//	/**
//	 * initializeCreateAction
//	 *
//	 * manipulate attachments
//	 */
//	public function initializeCreateAction() {
//		$this->request->setArgument('attachments', $this->attachmentService->initAttachments($this->request->getArgument('attachments')));
//	}


	/**
	 * Creates a new topic.
	 *
	 * @param \Mittwald\Typo3Forum\Domain\Model\Forum\Forum $forum       The forum in which the new topic is to be created.
	 * @param \Mittwald\Typo3Forum\Domain\Model\Forum\Post $post        The first post of the new topic.
	 * @param string $subject     The subject of the new topic
	 * @param array $attachments File attachments for the post.
	 * @param string $question    The flag if the new topic is declared as question
	 * @param array $criteria    All submitted criteria with option.
	 * @param string $tags All defined tags for this topic
	 * @param string $subscribe    The flag if the new topic is subscribed by author
	 *
	 * @validate $post \Mittwald\Typo3Forum\Domain\Validator\Forum\PostValidator
	 * @validate $attachments \Mittwald\Typo3Forum\Domain\Validator\Forum\AttachmentPlainValidator
	 * @validate $subject NotEmpty
	 */
	public function createAction(\Mittwald\Typo3Forum\Domain\Model\Forum\Forum $forum, \Mittwald\Typo3Forum\Domain\Model\Forum\Post $post,
								 $subject, array $attachments = array(), $question = '', array $criteria = array(), $tags = '', $subscribe = '') {

		// Assert authorization
		$this->authenticationService->assertNewTopicAuthorization($forum);

		// Create the new post; add the new post to a new topic and add the new
		// topic to the forum. Then persist the forum object. Not as complicated
		// as is sounds, honestly!
		$this->postFactory->assignUserToPost($post);

		if(!empty($attachments)) {
			$attachments = $this->attachmentService->initAttachments($attachments);
			$post->setAttachments($attachments);
		}

		if($tags != '') {
			$tags = $this->tagService->initTags($tags);
			foreach($tags AS $tag) {
				if($tag->getUid === NULL) {
					$this->tagRepository->add($tag);
				}
			}
		} else {
			$tags = NULL;
		}

		$topic = $this->topicFactory->createTopic($forum, $post, $subject, intval($question), $criteria, $tags, intval($subscribe));

		// Notify potential listeners.
		$this->signalSlotDispatcher->dispatch('\Mittwald\Typo3Forum\Domain\Model\Forum\Topic', 'topicCreated',
											  array('topic' => $topic));
		$this->clearCacheForCurrentPage();
		$uriBuilder = $this->controllerContext->getUriBuilder();
		$uri = $uriBuilder->setTargetPageUid($this->settings['pids']['Forum'])->setArguments(array('tx_typo3forum_pi1[forum]' => $forum->getUid(), 'tx_typo3forum_pi1[controller]' => 'Forum', 'tx_typo3forum_pi1[action]' => 'show'))->build();
		$this->purgeUrl('http://'.$_SERVER['HTTP_HOST'].'/'.$uri);
		// Redirect to single forum display view
		$this->redirect('show', 'Forum', NULL, array('forum' => $forum));
	}


	/**
	 * Sets a post as solution
	 *
	 * @param \Mittwald\Typo3Forum\Domain\Model\Forum\Post $post  The post to be marked as solution.
	 *
	 * @throws \Mittwald\Typo3Forum\Domain\Exception\Authentication\NoAccessException
	 * @return void
	 */
	public function solutionAction(\Mittwald\Typo3Forum\Domain\Model\Forum\Post $post) {
		if(!$post->getTopic()->checkSolutionAccess($this->authenticationService->getUser())) {
			throw new \Mittwald\Typo3Forum\Domain\Exception\Authentication\NoAccessException('Not allowed to set solution by current user.');
		}
		$this->topicFactory->setPostAsSolution($post->getTopic(),$post);
		$this->redirect('show', 'Topic', NULL, array('topic' => $post->getTopic()));
	}

	/*
	 * HELPER METHODS
	 */



	/**
	 * Marks a topic as read by the current user.
	 *
	 * @param  \Mittwald\Typo3Forum\Domain\Model\Forum\Topic $topic
	 *                             The topic that is to be marked as read.
	 *
	 * @return void
	 */
	protected function markTopicRead(\Mittwald\Typo3Forum\Domain\Model\Forum\Topic $topic) {
		$currentUser = $this->getCurrentUser();
		if ($currentUser === NULL || $currentUser->isAnonymous()) {
			return;
		}
		else {
			if(intval($this->settings['useSqlStatementsOnCriticalFunctions']) == 0) {
				if($topic->hasBeenReadByUser($currentUser)) {
					$currentUser->addReadObject($topic);
					$this->frontendUserRepository->update($currentUser);
				}
			} else {
				if($this->topicRepository->getTopicReadByUser($topic,$currentUser)) {
					$values = array(
						'uid_local'   => $currentUser->getUid(),
						'uid_foreign' => $topic->getUid(),
					);
					$sql = $GLOBALS['TYPO3_DB']->INSERTquery('tx_typo3forum_domain_model_user_readtopic',$values);
					$GLOBALS['TYPO3_DB']->sql_query($sql);
				}
			}
		}
	}



}

