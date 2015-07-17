<?php
namespace Mittwald\Typo3Forum\Domain\Factory\Forum;

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
use Mittwald\Typo3Forum\Domain\Factory\AbstractFactory;
use Mittwald\Typo3Forum\Domain\Model\Forum\Post;
use Mittwald\Typo3Forum\Domain\Model\User\FrontendUser;

class PostFactory extends AbstractFactory {

	/**
	 * @var \Mittwald\Typo3Forum\Domain\Repository\Forum\PostRepository
	 * @inject
	 */
	protected $postRepository;

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
	 * Creates an empty post
	 * @return Post An empty post.
	 */
	public function createEmptyPost() {
		return $this->getClassInstance();
	}

	/**
	 * Creates a new post that quotes an already existing post.
	 *
	 * @param Post $quotedPost The post that is to be quoted. The post text of this post will be wrapped in [quote] bb codes.
	 *
	 * @return Post The new post.
	 */
	public function createPostWithQuote(Post $quotedPost) {
		/** @var $post Post */
		$post = $this->getClassInstance();
		$post->setText('[quote=' . $quotedPost->getUid() . ']' . $quotedPost->getText() . '[/quote]');

		return $post;
	}

	/**
	 * Assigns a user to a forum post and increases the user's post count.
	 *
	 * @param Post         $post The post to which a user is to be assigned.
	 * @param FrontendUser $user The user that is to be assigned to the post. If this value is NULL, the currently logged in user will be used instead.
	 *
	 * @throws NotLoggedInException
	 * @throws \TYPO3\CMS\Extbase\Persistence\Exception\IllegalObjectTypeException
	 */
	public function assignUserToPost(Post $post, FrontendUser $user = NULL) {
		// If no user is set, use current user is set.
		if ($user === NULL) {
			$user = $this->getCurrentUser();
		}

		// If still no user is set, abort.
		if ($user === NULL) {
			throw new NotLoggedInException();
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
			$user->increasePoints((int) $this->settings['rankScore']['newPost']);
			$this->frontendUserRepository->update($user);
		}
	}

	/**
	 *
	 * Deletes a post and decreases the user's post count by 1.
	 *
	 * @param Post $post
	 */
	public function deletePost(Post $post) {
		$topic = $post->getTopic();

		// If the post is the only one in the topic, delete the whole topic instead of
		// this single post. Empty topics are not allowed.
		if ($topic->getPostCount() === 1) {
			$this->topicFactory->deleteTopic($topic);
		} else {
			$post->getAuthor()->decreasePostCount();
			$post->getAuthor()->decreasePoints((int) $this->settings['rankScore']['newPost']);
			$this->frontendUserRepository->update($post->getAuthor());
			$topic->removePost($post);
			$this->topicRepository->update($topic);
		}
	}

}
