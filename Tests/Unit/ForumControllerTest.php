<?php
namespace Mittwald\Typo3Forum\Tests\Unit;

use Mittwald\Typo3Forum\Controller\ForumController;
use TYPO3\CMS\Core\Tests\UnitTestCase;

class ForumControllerTest extends UnitTestCase {

	/**
	 * @var \Mittwald\Typo3Forum\Controller\ForumController
	 */
	protected $forumController;

	/**
	 * @test
	 */
	public function mockIsBuildable() {
		$this->forumController = $this->getAccessibleMock('Mittwald\\Typo3Forum\\Controller\\ForumController');
		$this->assertTrue($this->forumController instanceof ForumController);
	}

}
