<?php
namespace Mittwald\MmForum\Domain\Factory\Forum;


/*                                                                      *
 *  COPYRIGHT NOTICE                                                    *
 *                                                                      *
 *  (c) 2010 Martin Helmich <m.helmich@mittwald.de>                     *
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
 * Post factory class. Is used to encapsulate post creation logic from the controller
 * classes.
 *
 * @author        Martin Helmich <m.helmich@mittwald.de>
 * @package       MmForum
 * @subpackage    Domain\Factory\Forum
 * @version       $Id$
 *
 * @copyright     2012 Martin Helmich <m.helmich@mittwald.de>
 *                Mittwald CM Service GmbH & Co. KG
 *                http://www.mittwald.de
 * @license       GNU Public License, version 2
 *                http://opensource.org/licenses/gpl-license.php
 *
 */
class PostFactory extends \Mittwald\MmForum\Domain\Factory\AbstractFactory {



	/*
	 * ATTRIBUTES
	 */



	/**
	 * The topic repository.
	 * @var \Mittwald\MmForum\Domain\Repository\Forum\TopicRepository
	 */
	protected $topicRepository = NULL;



	/**
	 * The post repository.
	 * @var \Mittwald\MmForum\Domain\Repository\Forum\PostRepository
	 */
	protected $postRepository = NULL;



	/**
	 * The topic factory.
	 * @var \Mittwald\MmForum\Domain\Factory\Forum\TopicFactory
	 */
	protected $topicFactory = NULL;




	/**
	 * An instance of the mm_forum authentication service.
	 * @var TYPO3\CMS\Extbase\Service\TypoScriptService
	 */
	protected $typoScriptService = NULL;

	/**
	 * Whole TypoScript mm_forum settings
	 * @var array
	 */
	protected $settings;



	/*
	 * DEPENDENCY INJECTORS
	 */



	/**
	 * @param \Mittwald\MmForum\Domain\Repository\Forum\TopicRepository $topicRepository
	 */
	public function injectTopicRepository(\Mittwald\MmForum\Domain\Repository\Forum\TopicRepository $topicRepository) {
		$this->topicRepository = $topicRepository;
	}


	/**
	 * @param \Mittwald\MmForum\Domain\Repository\Forum\PostRepository $postRepository
	 */
	public function injectPostRepository(\Mittwald\MmForum\Domain\Repository\Forum\PostRepository $postRepository) {
		$this->postRepository = $postRepository;
	}


	/**
	 * @param \Mittwald\MmForum\Domain\Factory\Forum\TopicFactory $topicFactory
	 */
	public function injectTopicFactory(TopicFactory $topicFactory) {
		$this->topicFactory = $topicFactory;
	}


	/**
	 * Injects an instance of the \TYPO3\CMS\Extbase\Service\TypoScriptService.
	 * @param \TYPO3\CMS\Extbase\Service\TypoScriptService $typoScriptService
	 */
	public function injectTyposcriptService(\TYPO3\CMS\Extbase\Service\TypoScriptService $typoScriptService) {
		$this->typoScriptService = $typoScriptService;
		$ts = $this->typoScriptService->convertTypoScriptArrayToPlainArray(\TYPO3\CMS\Extbase\Configuration\FrontendConfigurationManager::getTypoScriptSetup());
		$this->settings = $ts['plugin']['tx_mmforum']['settings'];
	}



	/*
	 * METHODS
	 */



	/**
	 * Creates an empty post
	 * @return \Mittwald\MmForum\Domain\Model\Forum\Post An empty post.
	 */
	public function createEmptyPost() {
		return $this->getClassInstance();
	}



	/**
	 * Creates a new post that quotes an already existing post.
	 *
	 * @param \Mittwald\MmForum\Domain\Model\Forum\Post $quotedPost
	 *                                 The post that is to be quoted. The post
	 *                                 text of this post will be wrapped in
	 *                                 [quote] bb codes.
	 * @return \Mittwald\MmForum\Domain\Model\Forum\Post
	 *                                 The new post.
	 */
	public function createPostWithQuote(\Mittwald\MmForum\Domain\Model\Forum\Post $quotedPost) {
		/** @var $post \Mittwald\MmForum\Domain\Model\Forum\Post */
		$post = $this->getClassInstance();
		$post->setText('[quote=' . $quotedPost->getUid() . ']' . $quotedPost->getText() . '[/quote]');

		return $post;
	}



	/**
	 * Assigns a user to a forum post and increases the user's post count.
	 *
	 * @param \Mittwald\MmForum\Domain\Model\Forum\Post        $post
	 *                             The post to which a user is to be assigned.
	 * @param \Mittwald\MmForum\Domain\Model\User\FrontendUser $user
	 *                             The user that is to be assigned to the post. If
	 *                             this value is NULL, the currently logged in user
	 *                             will be used instead.
	 */
	public function assignUserToPost(\Mittwald\MmForum\Domain\Model\Forum\Post $post,
	                                 \Mittwald\MmForum\Domain\Model\User\FrontendUser $user = NULL) {
		// If no user is set, use current user is set.
		if ($user === NULL) {
			$user = $this->getCurrentUser();
		}

		// If still no user is set, abort.
		if ($user === NULL) {
			throw new \Mittwald\MmForum\Domain\Exception\Authentication\NotLoggedInException();
		}

		// If the post's author is already set, decrease this user's post count.
		if (!$post->getAuthor()->isAnonymous()) {
			$post->getAuthor()->decreasePostCount();
			$this->frontendUserRepository->update($post->getAuthor());
		}

		// Increase the new user's post count.
		if (!$user->isAnonymous()) {
			$post->setAuthor($user);
			$user->increasePostCount();
			$user->increasePoints(intval($this->settings['rankScore']['newPost']));
			$this->frontendUserRepository->update($user);
		}
	}



	/**
	 *
	 * Deletes a post and decreases the user's post count by 1.
	 *
	 * @param \Mittwald\MmForum\Domain\Model\Forum\Post $post
	 *
	 */

	public function deletePost(\Mittwald\MmForum\Domain\Model\Forum\Post $post) {
		$topic = $post->getTopic();

		// If the post is the only one in the topic, delete the whole topic instead of
		// this single post. Empty topics are not allowed.
		if ($topic->getPostCount() === 1) {
			$this->topicFactory->deleteTopic($topic);
		} else {
			$post->getAuthor()->decreasePostCount();
			$post->getAuthor()->decreasePoints(intval($this->settings['rankScore']['newPost']));
			$this->frontendUserRepository->update($post->getAuthor());
			if(intval($this->settings['useSqlStatementsOnCriticalFunctions']) == 0) {
				$topic->removePost($post);
				$this->topicRepository->update($topic);
			} else {
				$this->postRepository->deletePostWithSqlStatement($post);
			}
		}
	}

}

?>
