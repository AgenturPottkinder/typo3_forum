<?php
namespace Mittwald\Typo3Forum\Service\Authentication;

/*                                                                    - *
*  COPYRIGHT NOTICE                                                    *
*                                                                      *
*  (c) 2015 Mittwald CM Service GmbH & Co KG                           *
*           All rights reserved                                        *
*                                                                      *
*  This script is part of the TYPO3 project. The TYPO3 project is      *
*  free software; you can redistribute it and/or modify                *
*  it under the terms of the GNU General public License as published   *
*  by the Free Software Foundation; either version 2 of the License,   *
*  or (at your option) any later version.                              *
*                                                                      *
*  The GNU General public License can be found at                      *
*  http://www.gnu.org/copyleft/gpl.html.                               *
*                                                                      *
*  This script is distributed in the hope that it will be useful,      *
*  but WITHOUT ANY WARRANTY; without even the implied warranty of      *
*  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the       *
*  GNU General public License for more details.                        *
*                                                                      *
*  This copyright notice MUST APPEAR in all copies of the script!      *
*                                                                      */

use Mittwald\Typo3Forum\Domain\Exception\Authentication\NoAccessException;
use Mittwald\Typo3Forum\Domain\Model\AccessibleInterface;
use Mittwald\Typo3Forum\Domain\Model\Forum\Access;
use Mittwald\Typo3Forum\Domain\Model\Forum\Forum;
use Mittwald\Typo3Forum\Domain\Model\Forum\Post;
use Mittwald\Typo3Forum\Domain\Model\Forum\Topic;
use Mittwald\Typo3Forum\Domain\Model\User\FrontendUser;
use Mittwald\Typo3Forum\Domain\Repository\User\FrontendUserRepository;
use Mittwald\Typo3Forum\Service\AbstractService;
use TYPO3\CMS\Core\Cache\Frontend\FrontendInterface;

/**
* A service class that handles the entire authentication.
*/
class AuthenticationService extends AbstractService implements AuthenticationServiceInterface
{
    protected FrontendUserRepository $frontendUserRepository;
    protected FrontendInterface $cache;
    protected ?FrontendUser $user = null;

    private ?string $userGroupIdentifier = null;

    public function __construct(
        FrontendInterface $cache,
        FrontendUserRepository $frontendUserRepository
    ) {
        $this->cache = $cache;
        $this->frontendUserRepository = $frontendUserRepository;
    }

    /*
    * AUTHENTICATION METHODS
    */

    /**
    * Asserts that the current user is authorized to read a specific object.
    *
    * @param AccessibleInterface $object The object that is to be accessed.
    */
    public function assertReadAuthorization(AccessibleInterface $object): void
    {
        $this->assertAuthorization($object, Access::TYPE_READ);
    }

    /**
    * Asserts that the current user is authorized to create a new topic in a
    * certain forum.
    *
    * @param Forum $forum The forum in which the new topic is to be created.
    */
    public function assertNewTopicAuthorization(Forum $forum): void
    {
        $this->assertAuthorization($forum, Access::TYPE_NEW_TOPIC);
    }

    /**
    * Asserts that the current user is authorized to create a new post within a
    * topic.
    *
    * @param Topic $topic The topic in which the new post is to be created.
    */
    public function assertNewPostAuthorization(Topic $topic): void
    {
        $this->assertAuthorization($topic, Access::TYPE_NEW_POST);
    }

    /**
    * Asserts that the current user is authorized to edit an existing post.
    *
    * @param Post $post The post that shall be edited.
    */
    public function assertEditPostAuthorization(Post $post): void
    {
        $this->assertAuthorization($post, Access::TYPE_EDIT_POST);
    }

    /**
    * Asserts that the current user is authorized to delete a post.
    *
    * @param Post $post The post that is to be deleted.
    */
    public function assertDeletePostAuthorization(Post $post): void
    {
        $this->assertAuthorization($post, Access::TYPE_DELETE_POST);
    }

    /**
    * Asserts that the current user has moderator access to a certain forum.
    */
    public function assertModerationAuthorization(AccessibleInterface $object): void
    {
        $this->assertAuthorization($object, Access::TYPE_MODERATE);
    }

    /**
    * Asserts that the current user has moderator access to a certain forum.
    *
    * @param AccessibleInterface $object The object that is to be moderated.
    */
    public function assertDeleteTopicAuthorization(AccessibleInterface $object): void
    {
        $this->assertAuthorization($object, Access::TYPE_DELETE_TOPIC);
    }

    /**
    * Asserts that the current user is authorized to perform a certain
    * action on an potentially protected object.
    *
    * @param AccessibleInterface $object The object for which the access is to be checked.
    * @param string $action The action for which the access check is to be performed.
    * @throws NoAccessException
    */
    public function assertAuthorization(AccessibleInterface $object, string $action): void
    {
        if ($this->checkAuthorization($object, $action) === false) {
            throw new NoAccessException('You are not authorized to perform this action!', 1284709852);
        }
    }

    public function checkReadAuthorization(AccessibleInterface $object): bool
    {
        return $this->checkAuthorization($object, Access::TYPE_READ);
    }

    public function checkModerationAuthorization(AccessibleInterface $object): bool
    {
        return $this->checkAuthorization($object, Access::TYPE_MODERATE);
    }

    /**
    * Checks whether the current user is authorized to perform a certain
    * action on an object.
    *
    * @param AccessibleInterface $object The object for which the access is to be checked.
    * @param string $action The action for which the access check is to be performed.
    * @return bool TRUE, when the user is authorized, otherwise FALSE.
    */
    public function checkAuthorization(AccessibleInterface $object, string $action): bool
    {
        $cacheIdentifier = $this->getCacheIdentifier($object, $action);
        if ($this->cache->has($cacheIdentifier)) {
            $value = $this->cache->get($cacheIdentifier);
        } else {
            $this->cache->set($cacheIdentifier, $value = $object->checkAccess($this->getUser(), $action));
        }
        return $value;
    }

    /**
    * Gets the cache identifier to use for a specific user/object/action
    * check.
    * INTERNAL USE ONLY!
    *
    * @param AccessibleInterface $object The object for which the access is to be checked.
    * @param string $action The action for which the access check is to be performed.
    * @return string              The cache identifier.
    */
    protected function getCacheIdentifier(AccessibleInterface $object, string $action): string
    {
        $objectName = explode('\\', get_class($object));
        $className = array_pop($objectName);
        /** @noinspection PhpUndefinedMethodInspection */
        $cacheIdentifier = 'acl-' . $className . '-' . $object->getUid() . '-' . $this->getUserGroupIdentifier() . '-' . $action;

        return $cacheIdentifier;
    }

    /**
    * Generates an identifier for all user groups the current user is a member of. This identifier can then be used
    * as part of a cache identifier.
    *
    * @return string An identifier for all current user groups.
    */
    protected function getUserGroupIdentifier(): string
    {
        if ($this->userGroupIdentifier === null) {
            $user = $this->getUser();
            if ($user === null) {
                $this->userGroupIdentifier = 'n';
            } elseif ($user->isAnonymous()) {
                $this->userGroupIdentifier = 'a';
            } else {
                $groupUids = [];
                foreach ($user->getUsergroup() as $group) {
                    /** @var \Mittwald\Typo3Forum\Domain\Model\User\FrontendUserGroup $group */
                    $groupUids[] = $group->getUid();
                }
                $this->userGroupIdentifier = implode('g', $groupUids);
            }
        }
        return $this->userGroupIdentifier;
    }

    public function getUser(): ?FrontendUser
    {
        if ($this->user === null) {
            $this->user = $this->frontendUserRepository->findCurrent();
        }
        return $this->user;
    }
}
