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

use Mittwald\Typo3Forum\Domain\Exception\Authentication\NotLoggedInException;
use Mittwald\Typo3Forum\Domain\Model\Forum\Forum;
use Mittwald\Typo3Forum\Domain\Model\Forum\Topic;
use Mittwald\Typo3Forum\Domain\Model\User\FrontendUser;
use TYPO3\CMS\Extbase\Persistence\ObjectStorage;

class ForumController extends AbstractController {

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
	 * @var \Mittwald\Typo3Forum\Domain\Repository\Forum\TopicRepository
	 * @inject
	 */
	protected $topicRepository;

	/**
	 * @var \Mittwald\Typo3Forum\Domain\Repository\Forum\AdRepository
	 * @inject
	 */
	protected $adRepository;

	/**
	 * @var \Mittwald\Typo3Forum\Domain\Model\Forum\RootForum
	 * @inject
	 */
	protected $rootForum;

	/**
	 *
	 */
	public function initializeAction() {
		$this->databaseConnection = $GLOBALS['TYPO3_DB'];
	}

	/**
	 * Index action. Displays the first two levels of the forum tree.
	 * @return void
	 */
	public function indexAction() {
		if(($forum = $this->forumRepository->findOneByForum(0))) {
			$this->forward('show', 'Forum', 'Typo3Forum',array(
				'forum' => $forum
			));
		}
	}

	/**
	 * Show action. Displays a single forum, all subforums of this forum and the
	 * topics contained in this forum.
	 *
	 * @param Forum $forum The forum that is to be displayed.
	 * @return void
	 */
	public function showAction(Forum $forum) {
		$topics = $this->topicRepository->findForIndex($forum);
		$this->authenticationService->assertReadAuthorization($forum);
		$this->view->assignMultiple([
			'forum' => $forum,
			'topics' => $topics,
		]);
	}

	/**
	 * Mark a whole forum as read
	 * @param Forum $forum
	 * @throws NotLoggedInException
	 * @return void
	 */
	public function markReadAction(Forum $forum) {
		$user = $this->getCurrentUser();
		if (!$user instanceof FrontendUser || $user->isAnonymous()) {
			throw new NotLoggedInException('You need to be logged in.', 1288084981);
		}
		$forumsToMarkAsRead = new ObjectStorage();
		$forumsToMarkAsRead->attach($forum);
		foreach ($forum->getChildren() as $child) {
			$forumsToMarkAsRead->attach($child);
		}

		foreach ($forumsToMarkAsRead as $checkForum) {
			/** @var Forum $checkForum */
			foreach ($checkForum->getTopics() as $topic) {
				/** @var Topic $topic */
				$topic->addReader($user);
			}
			$checkForum->addReader($user);
			$this->forumRepository->update($checkForum);
		}

		$this->redirect('show', 'Forum', NULL, ['forum' => $forum]);
	}

	/**
	 * Show all unread topics of the current user
	 * @param Forum $forum
	 * @throws NotLoggedInException
	 * @return void
	 */
	public function showUnreadAction(Forum $forum) {
		$user = $this->getCurrentUser();
		if (!$user instanceof FrontendUser || $user->isAnonymous()) {
			throw new NotLoggedInException('You need to be logged in.', 1436620398);
		}
		$topics = [];
		$unreadTopics = [];

		$tmpTopics = $this->topicRepository->getUnreadTopics($forum, $user);
		foreach ($tmpTopics as $tmpTopic) {
			$unreadTopics[] = $tmpTopic['uid'];
		}
		if (!empty($unreadTopics)) {
			$topics = $this->topicRepository->findByUids($unreadTopics);
		}

		$this->view->assign('forum', $forum)->assign('topics', $topics);
	}

}
