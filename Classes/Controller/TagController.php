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
class TagController extends \Mittwald\Typo3Forum\Controller\AbstractController {



	/*
	 * ATTRIBUTES
	 */

	/**
	 * @var \Mittwald\Typo3Forum\Domain\Repository\Forum\TagRepository
	 */
	protected $tagRepository;

	/**
	 * @var \Mittwald\Typo3Forum\Domain\Repository\Forum\TopicRepository
	 */
	protected $topicRepository;


	/**
	 * @param \Mittwald\Typo3Forum\Domain\Repository\Forum\TagRepository $tagRepository
	 * @return void
	 */
	public function injectTagRepository(\Mittwald\Typo3Forum\Domain\Repository\Forum\TagRepository $tagRepository) {
		$this->tagRepository = $tagRepository;
	}


	/**
	 * @param \Mittwald\Typo3Forum\Domain\Repository\Forum\TopicRepository $topicRepository
	 * @return void
	 */
	public function injectTopicRepository(\Mittwald\Typo3Forum\Domain\Repository\Forum\TopicRepository $topicRepository) {
		$this->topicRepository = $topicRepository;
	}


	/*
	 * ACTION METHODS
	 */

	/**
	 * Listing all tags of this forum.
	 * @param int $mine
	 * @return void
	 */
	public function listAction($mine = 0) {
		$user = $this->getCurrentUser();
		if($mine == 0) {
			$tags = $this->tagRepository->findAllOrderedByCounter();
		} else {
			$tags = $this->tagRepository->findTagsOfUser($user);
		}
		$this->view->assign('tags', $tags)->assign('user',$user)->assign('mine',$mine);
	}

	/**
	 * Show all topics of a given tag
	 * @param \Mittwald\Typo3Forum\Domain\Model\Forum\Tag $tag
	 * @return void
	 */
	public function showAction(\Mittwald\Typo3Forum\Domain\Model\Forum\Tag $tag) {
		$this->view->assign('tag',$tag);
		$this->view->assign('topics',$this->topicRepository->findAllTopicsWithGivenTag($tag));
	}

	/**
	 * @throws \Mittwald\Typo3Forum\Domain\Exception\Authentication\NotLoggedInException
	 * @return void
	 */
	public function newAction() {
		$user = $this->getCurrentUser();
		if ($user->isAnonymous()) {
			throw new \Mittwald\Typo3Forum\Domain\Exception\Authentication\NotLoggedInException("You need to be logged in.", 1288084981);
		}
	}

	/**
	 * @param string $name
	 * @param string $subscribe
	 *
	 * @validate $name \Mittwald\Typo3Forum\Domain\Validator\Forum\TagValidator
	 * @throws \Mittwald\Typo3Forum\Domain\Exception\Authentication\NotLoggedInException
	 * @return void
	 */
	public function createAction($name="",$subscribe="") {
		$user = $this->getCurrentUser();
		if ($user->isAnonymous()) {
			throw new \Mittwald\Typo3Forum\Domain\Exception\Authentication\NotLoggedInException("You need to be logged in.", 1288084981);
		}
		/** @var \Mittwald\Typo3Forum\Domain\Model\Forum\Tag $tag */
		$tag = $this->objectManager->create('\Mittwald\Typo3Forum\Domain\Model\Forum\Tag');
		$tag->setName($name);
		$tag->setCrdate(new DateTime());
		if(intval($subscribe) == 1) {
			$tag->addFeuser($user);
		}
		$this->tagRepository->add($tag);

		if(intval($subscribe) == 0) {
			$this->redirect('list');
		} else {
			$this->redirect('listUserTags');
		}
	}


	/**
	 * List all subscribed tags of a user
	 * @throws \Mittwald\Typo3Forum\Domain\Exception\Authentication\NotLoggedInException
	 * @return void
	 */
	public function listUserTagsAction() {
		$user = $this->getCurrentUser();
		if ($user->isAnonymous()) {
			throw new \Mittwald\Typo3Forum\Domain\Exception\Authentication\NotLoggedInException("You need to be logged in.", 1288084981);
		}
		$this->redirect('list',NULL,NULL,array('mine' => 1));
	}



	/**
	 * @param \Mittwald\Typo3Forum\Domain\Model\Forum\Tag $tag
	 * @param int $mine
	 *
	 * @throws \Mittwald\Typo3Forum\Domain\Exception\Authentication\NotLoggedInException
	 * @return void
	 */
	public function newUserTagAction(\Mittwald\Typo3Forum\Domain\Model\Forum\Tag $tag, $mine) {
		$user = $this->getCurrentUser();
		if ($user->isAnonymous()) {
			throw new \Mittwald\Typo3Forum\Domain\Exception\Authentication\NotLoggedInException("You need to be logged in.", 1288084981);
		}
		$tag->addFeuser($user);
		$this->tagRepository->update($tag);
		$this->redirect('list',NULL,NULL,array('mine' => $mine));
	}


	/**
	 * @param \Mittwald\Typo3Forum\Domain\Model\Forum\Tag $tag
	 * @param int $mine
	 *
	 * @throws \Mittwald\Typo3Forum\Domain\Exception\Authentication\NotLoggedInException
	 * @return void
	 */
	public function deleteUserTagAction(\Mittwald\Typo3Forum\Domain\Model\Forum\Tag $tag, $mine) {
		$user = $this->getCurrentUser();
		if ($user->isAnonymous()) {
			throw new \Mittwald\Typo3Forum\Domain\Exception\Authentication\NotLoggedInException("You need to be logged in.", 1288084981);
		}
		$tag->removeFeuser($user);
		$this->tagRepository->update($tag);
		$this->redirect('list',NULL,NULL,array('mine' => $mine));
	}


	/**
	 * @param string $value
	 * @return string as json array
	 */
	public function autoCompleteAction($value) {
		$result = array();
		$tagObj = $this->tagRepository->findTagLikeAName($value);
		foreach($tagObj AS $tag) {
			$result[] = $tag->getName();
		}
		return json_encode($result);
	}


}
