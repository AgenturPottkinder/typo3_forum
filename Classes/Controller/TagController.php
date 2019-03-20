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
use Mittwald\Typo3Forum\Domain\Model\Forum\Tag;

class TagController extends AbstractController {

	/**
	 * @var \Mittwald\Typo3Forum\Domain\Repository\Forum\TagRepository
	 * @inject
	 */
	protected $tagRepository;

	/**
	 * @var \Mittwald\Typo3Forum\Domain\Repository\Forum\TopicRepository
	 * @inject
	 */
	protected $topicRepository;

	/**
	 * Listing all tags of this forum.
	 * @param int $mine
	 */
	public function listAction($mine = 0) {
		$user = $this->getCurrentUser();
		if ($mine == 0) {
			$tags = $this->tagRepository->findAllOrderedByCounter();
		} else {
			$tags = $this->tagRepository->findTagsOfUser($user);
		}
		$this->view->assignMultiple([
			'tags' => $tags,
			'user' => $user,
			'mine' => $mine
		]);
	}

	/**
	 * Show all topics of a given tag
	 * @param Tag $tag
	 */
	public function showAction(Tag $tag) {
		$this->view->assign('tag', $tag);
		$this->view->assign('topics', $this->topicRepository->findAllTopicsWithGivenTag($tag));
	}

	/**
	 * @throws NotLoggedInException
	 */
	public function newAction() {
		$user = $this->getCurrentUser();
		if ($user->isAnonymous()) {
			throw new NotLoggedInException('You need to be logged in.', 1288084981);
		}
	}

	/**
	 * @param string $name
	 * @param string $subscribe
	 *
	 * @validate $name \Mittwald\Typo3Forum\Domain\Validator\Forum\TagValidator
	 * @throws NotLoggedInException
	 */
	public function createAction($name = '', $subscribe = '') {
		$user = $this->getCurrentUser();
		if ($user->isAnonymous()) {
			throw new NotLoggedInException("You need to be logged in.", 1288084981);
		}
		/** @var Tag $tag */
		$tag = $this->objectManager->get(Tag::class);
		$tag->setName($name);
		$tag->setCrdate(new \DateTime());
		if ((int)$subscribe === 1) {
			$tag->addFeuser($user);
		}
		$this->tagRepository->add($tag);

		if ((int)$subscribe === 0) {
			$this->redirect('list');
		} else {
			$this->redirect('listUserTags');
		}
	}

	/**
	 * List all subscribed tags of a user
	 * @throws NotLoggedInException
	 */
	public function listUserTagsAction() {
		$user = $this->getCurrentUser();
		if ($user->isAnonymous()) {
			throw new NotLoggedInException("You need to be logged in.", 1288084981);
		}
		$this->redirect('list', NULL, NULL, ['mine' => 1]);
	}

	/**
	 * @param Tag $tag
	 * @param int $mine
	 * @throws NotLoggedInException
	 */
	public function newUserTagAction(Tag $tag, $mine) {
		$user = $this->getCurrentUser();
		if ($user->isAnonymous()) {
			throw new NotLoggedInException("You need to be logged in.", 1288084981);
		}
		$tag->addFeuser($user);
		$this->tagRepository->update($tag);
		$this->redirect('list', NULL, NULL, ['mine' => $mine]);
	}

	/**
	 * @param Tag $tag
	 * @param int $mine
	 * @throws NotLoggedInException
	 */
	public function deleteUserTagAction(Tag $tag, $mine) {
		$user = $this->getCurrentUser();
		if ($user->isAnonymous()) {
			throw new NotLoggedInException("You need to be logged in.", 1288084981);
		}
		$tag->removeFeuser($user);
		$this->tagRepository->update($tag);
		$this->redirect('list', NULL, NULL, ['mine' => $mine]);
	}

	/**
	 * @param string $value
	 * @return string as json array
	 */
	public function autoCompleteAction($value) {
		$result = [];
		$tagObj = $this->tagRepository->findTagLikeAName($value);
		foreach ($tagObj as $tag) {
			$result[] = $tag->getName();
		}
		return json_encode($result);
	}

}
