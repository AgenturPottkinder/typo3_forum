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
class ShadowTopic extends Topic
{

    /**
     * The target topic, i.e. the topic this shadow is pointing to.
     */
    protected Topic $target;

    /**
     * Gets the target topic, i.e. the topic this shadow is pointing to.
     */
    public function getTarget(): Topic
    {
        return $this->target;
    }

    /**
     * Sets the target topic. Also reads the topic subject and the last post pointer
     * from the target object.
     */
    public function setTarget(Topic $topic): self
    {
        $this->target = $topic;
        $this->lastPost = $topic->getLastPost();
        $this->lastPostCrdate = $this->lastPost->getTimestamp();
        $this->subject = $topic->getSubject();
        $this->author = $topic->getAuthor();

        return $this;
    }

    /**
     * Checks if the user has access to this topic. Nobody can post in a shadow topic,
     * so that access always returns false.
     */
    public function checkAccess(FrontendUser $user = null, $accessType = Access::TYPE_READ): bool
    {
        if ($accessType === Access::TYPE_NEW_POST) {
            return false;
        }
        return parent::checkAccess($user, $accessType);
    }

    /**
     * Nobody can post in a shadow topic, so new post access always returns false.
     */
    public function checkNewPostAccess(FrontendUser $user = null): bool
    {
        return false;
    }
}
