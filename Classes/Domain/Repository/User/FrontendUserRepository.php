<?php
namespace Mittwald\Typo3Forum\Domain\Repository\User;

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
use Mittwald\Typo3Forum\Domain\Model\User\AnonymousFrontendUser;
use Mittwald\Typo3Forum\Domain\Model\User\FrontendUser;
use Mittwald\Typo3Forum\Domain\Repository\AbstractRepository;
use TYPO3\CMS\Extbase\Configuration\FrontendConfigurationManager;
use TYPO3\CMS\Extbase\Persistence\QueryInterface;
use TYPO3\CMS\Extbase\Persistence\QueryResultInterface;

/**
 * Repository class for frontend users.
 *
 * @author     Martin Helmich <m.helmich@mittwald.de>
 * @version    $Id$
 *
 * @copyright  2012 Martin Helmich <m.helmich@mittwald.de>
 *             Mittwald CM Service GmbH & Co. KG
 *             http://www.mittwald.de
 * @license    GNU Public License, version 2
 *             http://opensource.org/licenses/gpl-license.php
 */
class FrontendUserRepository extends AbstractRepository
{
    protected FrontendConfigurationManager $frontendConfigurationManager;

    public function injectFrontendConfigurationManager(FrontendConfigurationManager $frontendConfigurationManager): void
    {
        $this->frontendConfigurationManager = $frontendConfigurationManager;
    }

    /**
     * Finds the user that is currently logged in, or AnonymousFrontendUser if no user is logged in.
     */
    public function findCurrent(): FrontendUser
    {
        $currentUserUid = (int)(($GLOBALS['TSFE']->fe_user->user ?? [])['uid'] ?? 0);
        $return = $currentUserUid ? $this->findByUid($currentUserUid) : new AnonymousFrontendUser();
        $return->ensureObjectStorages();
        return $return;
    }

    /**
     * Finds users for a specific filterset.
     * @return QueryResultInterface<\Mittwald\Typo3Forum\Domain\Model\User\FrontendUser>
     */
    public function findByFilter(
        ?int $limit = null,
        ?array $orderings = null,
        bool $onlyOnline = false,
        ?array $uids = null,
        ?string $nameSearch = null
    ): QueryResultInterface {
        $query = $this->createQuery();
        $constraints = [];
        if ($limit !== null && $limit > 0) {
            $query->setLimit($limit);
        }
        if ($orderings !== null && count($orderings) > 0) {
            $query->setOrderings($orderings);
        }
        if ($onlyOnline) {
            $constraints[] = $query->greaterThan('is_online', time() - ($this->settings['timeIntervals']['onlineUser'] ?? 900));
        }
        if ($uids !== null && count($uids) > 0) {
            $constraints[] = $query->in('uid', $uids);
        }
        if (!in_array($nameSearch, [null, ''], true)) {
            $constraints[] = $query->like('username', '%' . $nameSearch . '%');
        }
        if (count($constraints) > 0) {
            $query->matching($query->logicalAnd($constraints));
        }

        return $query->execute();
    }

    /**
     * Returns an anonymous frontend user.
     */
    public function findAnonymous(): AnonymousFrontendUser
    {
        return new AnonymousFrontendUser();
    }

    /**
     * @return QueryResultInterface<FrontendUser> The Top $limit User of this forum.
     */
    public function findTopUserByPoints(?int $limit = null, ?string $nameSearch = null): QueryResultInterface
    {
        return $this->findByFilter(
            $limit,
            [
                'points' => QueryInterface::ORDER_DESCENDING,
                'username' => QueryInterface::ORDER_ASCENDING,
            ],
            false,
            null,
            $nameSearch
        );
    }

    /**
     * @return QueryResultInterface<FrontendUser> The Top $limit User of this forum.
     */
    public function findMostHelpfulUsers(?int $limit = null, ?string $nameSearch = null): QueryResultInterface
    {
        return $this->findByFilter(
            $limit,
            [
                'helpfulCount' => QueryInterface::ORDER_DESCENDING,
                'username' => QueryInterface::ORDER_ASCENDING,
            ],
            false,
            null,
            $nameSearch
        );
    }
}
