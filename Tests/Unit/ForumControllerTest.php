<?php
namespace Mittwald\Typo3Forum\Tests\Unit;

use Mittwald\Typo3Forum\Controller\ForumController;
use Mittwald\Typo3Forum\Domain\Model\Forum\Forum;
use Mittwald\Typo3Forum\Domain\Model\Forum\RootForum;
use Mittwald\Typo3Forum\Domain\Model\User\AnonymousFrontendUser;
use Mittwald\Typo3Forum\Domain\Model\User\FrontendUser;
use Mittwald\Typo3Forum\Domain\Repository\Forum\ForumRepository;
use Mittwald\Typo3Forum\Domain\Repository\Forum\TopicRepository;
use TYPO3\CMS\Extbase\Object\ObjectManager;
use TYPO3\CMS\Extbase\Persistence\ObjectStorage;

class ForumControllerTest extends AbstractControllerTest {

	/**
	 * @var ForumController
	 */
	protected $forumController;

	/**
	 * @var \PHPUnit_Framework_MockObject_MockObject|ForumRepository
	 */
	protected $forumRepositoryMock;

	/**
	 * @var \PHPUnit_Framework_MockObject_MockObject|RootForum
	 */
	protected $rootForumMock;

	/**
	 * @var \PHPUnit_Framework_MockObject_MockObject|TopicRepository
	 */
	protected $topicRepositoryMock;

	public function setUp() {
		parent::setUp();
		$this->forumController = new ForumController();

		$this->inject($this->forumController, 'authenticationService', $this->authenticationServiceMock);
		$this->inject($this->forumController, 'frontendUserRepository', $this->frontendUserRepositoryMock);
		$this->inject($this->forumController, 'objectManager', $this->objectManagerMock);
		$this->inject($this->forumController, 'request', $this->requestMock);
		$this->inject($this->forumController, 'response', $this->responseMock);
		$this->inject($this->forumController, 'uriBuilder', $this->uriBuilderMock);
		$this->inject($this->forumController, 'view', $this->viewMock);

		// inject root forum mock
		$this->rootForumMock = $this->getMock(RootForum::class);
		$this->inject($this->forumController, 'rootForum', $this->rootForumMock);

		// inject forum repository mock
		$this->forumRepositoryMock = $this->getMock(
            ForumRepository::class,
			[],
			[$this->getMock(ObjectManager::class)]
		);
		$this->inject($this->forumController, 'forumRepository', $this->forumRepositoryMock);

		// inject topic repository mock
		$this->topicRepositoryMock = $this->getMock(
            TopicRepository::class,
			[],
			[$this->getMock(ObjectManager::class)]
		);
		$this->inject($this->forumController, 'topicRepository', $this->topicRepositoryMock);

	}

	/**
	 * @test
	 */
	public function indexActionAssertsReadAuthorization() {
		$this->assertReadAuthorizationForForum($this->rootForumMock);
		$this->forumController->indexAction();
	}

	/**
	 * @test
	 */
	public function indexActionAssignsFoundForumsToView() {
		$foundForums = new ObjectStorage();
		$this->forumRepositoryMock->expects($this->once())
			->method('findForIndex')
			->will($this->returnValue($foundForums));
		$this->viewMock->expects($this->once())
			->method('assign')
			->with($this->isType('string'), $this->equalTo($foundForums));
		$this->forumController->indexAction();
	}

	/**
	 * @test
	 */
	public function showActionAssertsReadAuthorization() {
		/** @var Forum $forum */
		$forum = $this->getMock(Forum::class);
		$this->assertReadAuthorizationForForum($forum);
		$this->forumController->showAction($forum);
	}

	/**
	 * @test
	 */
	public function showActionAssignsForumAndFoundTopicsToView() {
		/** @var Forum $forum */
		$forum = $this->getMock(Forum::class);
		$foundTopics = new ObjectStorage();
		$this->topicRepositoryMock->expects($this->once())
			->method('findForIndex')
			->will($this->returnValue($foundTopics));
		$this->viewMock->expects($this->once())
			->method('assignMultiple')
			->with($this->logicalAnd(
				$this->arrayHasKey('forum'),
				$this->arrayHasKey('topics')
			));
		$this->forumController->showAction($forum);
	}

	/**
	 * @test
	 * @expectedException \Mittwald\Typo3Forum\Domain\Exception\Authentication\NotLoggedInException
	 * @expectedExceptionCode 1288084981
	 */
	public function markReadActionThrowsExceptionWhenNotLoggedIn() {
		/** @var Forum $forum */
		$forum = $this->getMock(Forum::class);
		$this->forumController->markReadAction($forum);
	}

	/**
	 * @test
	 * @expectedException \Mittwald\Typo3Forum\Domain\Exception\Authentication\NotLoggedInException
	 * @expectedExceptionCode 1288084981
	 */
	public function markReadActionThrowsExceptionWhenAnonymous() {
		/** @var Forum $forum */
		$forum = $this->getMock(Forum::class);
		$anonymousFrontendUserMock = $this->getMock(AnonymousFrontendUser::class);
		$anonymousFrontendUserMock->expects($this->once())
			->method('isAnonymous')
			->will($this->returnValue(TRUE));
		$this->frontendUserRepositoryMock->expects($this->once())
			->method('findCurrent')
			->will($this->returnValue($anonymousFrontendUserMock));
		$this->forumController->markReadAction($forum);
	}

	/**
	 * @test
	 * @expectedException \TYPO3\CMS\Extbase\Mvc\Exception\StopActionException
	 */
	public function markReadActionRedirectsToShowAction() {
		/** @var \PHPUnit_Framework_MockObject_MockObject|Forum $forum */
		$forum = $this->getMock(Forum::class);
		$forum->expects($this->once())
			->method('getChildren')
			->will($this->returnValue(new ObjectStorage()));
		$forum->expects($this->once())
			->method('getTopics')
			->will($this->returnValue(new ObjectStorage()));
		$frontendUserMock = $this->getMock(FrontendUser::class);
		$frontendUserMock->expects($this->once())
			->method('isAnonymous')
			->will($this->returnValue(FALSE));
		$this->frontendUserRepositoryMock->expects($this->once())
			->method('findCurrent')
			->will($this->returnValue($frontendUserMock));
		$this->requestMock->expects($this->once())
			->method('getFormat')
			->will($this->returnValue('html'));
		$this->uriBuilderMock->expects($this->once())
			->method('uriFor')
			->will($this->returnCallback(function($action) {
				return 'url/to/' . $action;
			}));
		$this->responseMock->expects($this->once())
			->method('setHeader')
			->with($this->equalTo('Location'), $this->callback(function($url){
				return array_pop(explode('/', $url)) === 'show';
			}));
		$this->forumController->markReadAction($forum);
	}

	/**
	 * @param Forum $forum
	 */
	protected function assertReadAuthorizationForForum(Forum $forum) {
		$this->authenticationServiceMock->expects($this->once())
			->method('assertReadAuthorization')
			->with($this->equalTo($forum))
			->will($this->returnValue(TRUE));
	}
}
