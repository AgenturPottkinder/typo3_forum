<?php
namespace Mittwald\Typo3Forum\Tests\Unit;

use Mittwald\Typo3Forum\Controller\ForumController;
use Mittwald\Typo3Forum\Domain\Model\Forum\RootForum;
use Mittwald\Typo3Forum\Domain\Repository\Forum\ForumRepository;
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

	}

	/**
	 * @test
	 */
	public function indexActionAssertsReadAuthorization() {
		$this->authenticationServiceMock->expects($this->once())
			->method('assertReadAuthorization')
			->with($this->isInstanceOf('Mittwald\\Typo3Forum\\Domain\\Model\\Forum\\Forum'))
			->will($this->returnValue(TRUE));
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

}
