<?php
namespace Mittwald\Typo3Forum\Domain\Model\Forum;

/*                                                                    - *
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

use Mittwald\Typo3Forum\Domain\Model\User\FrontendUser;

/**
 * A shadow topic. This type of topic is created when a topic is
 * moved from one forum to another. The shadow topic remains in
 * the original forum, while the topic itself is moved to the
 * other forum.
 */
class ShadowTopic extends Topic {

	/**
	 * The target topic, i.e. the topic this shadow is pointing to.
	 * @var Topic
	 */
	protected $target = NULL;

	/**
	 * Gets the target topic, i.e. the topic this shadow is pointing to.
	 * @return Topic The target topic
	 */
	public function getTarget() {
		return $this->target;
	}

	/**
	 * Sets the target topic. Also reads the topic subject and the last post pointer
	 * from the target object.
	 *
	 * @param Topic $topic The target topic.
	 *
	 * @return void
	 */
	public function setTarget(Topic $topic) {
		$this->target = $topic;
		$this->lastPost = $topic->getLastPost();
		$this->lastPostCrdate = $this->lastPost->getTimestamp();
		$this->subject = $topic->getSubject();
	}

	/**
	 * Checks if a user can create new posts inside this topic. Since this topic is
	 * only a shadow topic, this method will ALWAYS return FALSE.
	 *
	 * @param FrontendUser $user The user.
	 * @param string $accessType The access type to be checked.
	 *
	 * @return boolean TRUE, if the user can create new posts. Always FALSE.
	 */
	public function checkAccess(FrontendUser $user = NULL, $accessType = Access::TYPE_READ) {
		if ($accessType === Access::TYPE_NEW_POST) {
			return FALSE;
		} else {
			return parent::checkAccess($user, $accessType);
		}
	}

	/**
	 * Checks if a user can create new posts inside this topic. Since this topic is
	 * only a shadow topic, this method will ALWAYS return FALSE.
	 *
	 * @param FrontendUser $user The user.
	 *
	 * @return boolean TRUE, if the user can create new posts. Always FALSE.
	 */
	public function checkNewPostAccess(FrontendUser $user = NULL) {
		return FALSE;
	}
}
