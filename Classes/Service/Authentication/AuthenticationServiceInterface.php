<?php
namespace Mittwald\Typo3Forum\Service\Authentication;

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

use Mittwald\Typo3Forum\Domain\Model\AccessibleInterface;
use Mittwald\Typo3Forum\Domain\Model\Forum\Forum;
use Mittwald\Typo3Forum\Domain\Model\Forum\Post;
use Mittwald\Typo3Forum\Domain\Model\Forum\Topic;

/**
 * An interface for authentication services, in case anyone wants to
 * implement his own solution... ;)
 */
interface AuthenticationServiceInterface
{

    /**
     * @param AccessibleInterface $object
     */
    public function assertReadAuthorization(AccessibleInterface $object);

    /**
     * @param Forum $forum
     */
    public function assertNewTopicAuthorization(Forum $forum);

    /**
     * @param Topic $topic
     */
    public function assertNewPostAuthorization(Topic $topic);

    /**
     * @param Post $post
     */
    public function assertEditPostAuthorization(Post $post);

    /**
     * @param Post $post
     */
    public function assertDeletePostAuthorization(Post $post);

    /**
     * @param AccessibleInterface $object
     */
    public function assertModerationAuthorization(AccessibleInterface $object);

    /**
     * @param AccessibleInterface $object
     * @param $action
     */
    public function assertAuthorization(AccessibleInterface $object, $action);

    /**
     * @param AccessibleInterface $object
     * @param $action
     */
    public function checkAuthorization(AccessibleInterface $object, $action);
}
