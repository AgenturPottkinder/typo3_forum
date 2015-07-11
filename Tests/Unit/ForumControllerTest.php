<?php
namespace Mittwald\Typo3Forum\Tests\Unit;

use Mittwald\Typo3Forum\Controller\ForumController;
use Mittwald\Typo3Forum\Domain\Model\Forum\Forum;
use Mittwald\Typo3Forum\Domain\Model\Forum\RootForum;
use Mittwald\Typo3Forum\Domain\Repository\Forum\ForumRepository;
use Mittwald\Typo3Forum\Domain\Repository\Forum\TopicRepository;
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

	/**
	 *
	 */
	public function setUp() {
		parent::setUp();
		$this->forumController = new ForumController();

		$this->inject($this->forumController, 'authenticationService', $this->authenticationServiceMock);
		$this->inject($this->forumController, 'view', $this->viewMock);

		// inject root forum mock
		$this->rootForumMock = $this->getMock('Mittwald\\Typo3Forum\\Domain\\Model\\Forum\\RootForum');
		$this->inject($this->forumController, 'rootForum', $this->rootForumMock);

		// inject forum repository mock
		$this->forumRepositoryMock = $this->getMock(
			'Mittwald\\Typo3Forum\\Domain\\Repository\\Forum\\ForumRepository',
			[],
			[$this->getMock('TYPO3\\CMS\\Extbase\\Object\\ObjectManager')]
		);
		$this->inject($this->forumController, 'forumRepository', $this->forumRepositoryMock);

		// inject forum repository mock
		$this->topicRepositoryMock = $this->getMock(
			'Mittwald\\Typo3Forum\\Domain\\Repository\\Forum\\TopicRepository',
			[],
			[$this->getMock('TYPO3\\CMS\\Extbase\\Object\\ObjectManager')]
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
		$forum = $this->getMock('Mittwald\\Typo3Forum\\Domain\\Model\\Forum\\Forum');
		$this->assertReadAuthorizationForForum($forum);
		$this->forumController->showAction($forum);
	}

	/**
	 * @test
	 */
	public function showActionAssignsForumAndFoundTopicsToView() {
		/** @var Forum $forum */
		$forum = $this->getMock('Mittwald\\Typo3Forum\\Domain\\Model\\Forum\\Forum');
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
	 * @param Forum $forum
	 */
	protected function assertReadAuthorizationForForum(Forum $forum) {
		$this->authenticationServiceMock->expects($this->once())
			->method('assertReadAuthorization')
			->with($this->equalTo($forum))
			->will($this->returnValue(TRUE));
	}

}
