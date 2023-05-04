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
use Mittwald\Typo3Forum\Domain\Model\User\FrontendUser;

/**
 * An interface for authentication services, in case anyone wants to
 * implement his own solution... ;)
 */
interface AuthenticationServiceInterface
{
    public function assertReadAuthorization(AccessibleInterface $object): void;

    public function assertNewTopicAuthorization(Forum $forum): void;

    public function assertNewPostAuthorization(Topic $topic): void;

    public function assertEditPostAuthorization(Post $post): void;

    public function assertDeletePostAuthorization(Post $post): void;

    public function assertModerationAuthorization(AccessibleInterface $object): void;

    public function assertDeleteTopicAuthorization(AccessibleInterface $object): void;

    public function assertAuthorization(AccessibleInterface $object, string $action): void;

    public function checkReadAuthorization(AccessibleInterface $object): bool;

    public function checkModerationAuthorization(AccessibleInterface $object): bool;

    public function checkAuthorization(AccessibleInterface $object, string $action): bool;

    public function getUser(): ?FrontendUser;
}
