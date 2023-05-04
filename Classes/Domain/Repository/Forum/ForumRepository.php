<?php
namespace Mittwald\Typo3Forum\Domain\Repository\Forum;

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

use Mittwald\Typo3Forum\Domain\Model\Forum\Access;
use Mittwald\Typo3Forum\Domain\Model\Forum\Forum;
use Mittwald\Typo3Forum\Domain\Model\User\FrontendUser;
use Mittwald\Typo3Forum\Service\Authentication\AuthenticationServiceInterface;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Persistence\ObjectStorage;
use TYPO3\CMS\Extbase\Persistence\QueryInterface;
use TYPO3\CMS\Extbase\Persistence\QueryResultInterface;
use TYPO3\CMS\Extbase\Persistence\Repository;

/**
 * Repository class for forum objects.
 */
class ForumRepository extends Repository
{
    protected AuthenticationServiceInterface $authenticationService;

    public function injectAuthenticationService(AuthenticationServiceInterface $authenticationService): void
    {
        $this->authenticationService = $authenticationService;
    }

    public function createQuery(): QueryInterface
    {
        $query = parent::createQuery();

        // don't add sys_language_uid constraint
        $query->getQuerySettings()->setRespectSysLanguage(false);

        return $query;
    }

    /**
     * Finds all forums for the index view.
     * @return ObjectStorage<Forum>
     */
    public function findForIndex(): ObjectStorage
    {
        return $this->findRootForums();
    }

    /**
     * Finds all root forums.
     * @return ObjectStorage<Forum>
     */
    public function findRootForums(): ObjectStorage
    {
        $query = $this->createQuery();
        $result = $query
            ->matching($query->equals('forum', 0))
            ->setOrderings(['sorting' => 'ASC', 'uid' => 'ASC'])
            ->execute();

        return $this->filterByAccess($result, Access::TYPE_READ);
    }

    protected function filterByAccess(QueryResultInterface $objects, string $action = Access::TYPE_READ): ObjectStorage
    {
        $result = GeneralUtility::makeInstance(ObjectStorage::class);
        foreach ($objects as $forum) {
            if ($this->authenticationService->checkAuthorization($forum, $action)) {
                $result->attach($forum);
            }
        }

        return $result;
    }

    /**
     * Finds forum for a specific filterset.
     *
     * @return QueryResultInterface<Forum>
     */
    public function findByUids(array $uids = []): QueryResultInterface
    {
        $query = $this->createQuery();
        $constraints = [];
        if (count($uids) > 0) {
            $constraints[] = $query->in('uid', $uids);
        }
        if (count($constraints) > 0) {
            $query->matching($query->logicalAnd($constraints));
        }

        return $query->execute();
    }

    /**
     * @return QueryResultInterface<Forum>
     */
    public function findBySubscriber(FrontendUser $user): QueryResultInterface
    {
        $query = $this->createQuery();
        $query
            ->matching($query->contains('subscribers', $user))
            ->setOrderings(['lastPost.crdate' => 'ASC']);

        return $query->execute();
    }
}
