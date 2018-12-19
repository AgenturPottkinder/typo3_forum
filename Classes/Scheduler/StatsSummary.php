<?php

namespace Mittwald\Typo3Forum\Scheduler;

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
use TYPO3\CMS\Scheduler\Task\AbstractTask;

/**
 * Count all Topics, Posts and Users and write result into summary table
 */
class StatsSummary extends AbstractDatabaseTask
{

    /**
     * @var string
     */
    protected $forumPids;

    /**
     * @var string
     */
    protected $userPids;

    /**
     * @var int
     */
    protected $statsPid;

    /**
     * @return string
     */
    public function getForumPids()
    {
        return $this->forumPids;
    }

    /**
     * @return string
     */
    public function getUserPids()
    {
        return $this->userPids;
    }

    /**
     * @return int
     */
    public function getStatsPid()
    {
        return $this->statsPid;
    }

    /**
     * @param string $forumPids
     */
    public function setForumPids($forumPids)
    {
        $this->forumPids = $forumPids;
    }

    /**
     * @param string $userPids
     */
    public function setUserPids($userPids)
    {
        $this->userPids = $userPids;
    }

    /**
     * @param int $statsPid
     */
    public function setStatsPid($statsPid)
    {
        $this->statsPid = $statsPid;
    }

    /**
     * @return bool
     */
    public function execute()
    {
        if (!$this->getForumPids() || !$this->getUserPids() || !$this->getStatsPid()) {
            return false;
        }
        $results = [];

        $queryBuilder = $this->getDatabaseConnection('tx_typo3forum_domain_model_forum_post');
        $queryBuilder->from('tx_typo3forum_domain_model_forum_post', 'post');
        $queryBuilder->addSelectLiteral(
            $queryBuilder->expr()->count('*', 'counter')
        );
        $queryBuilder->andWhere($queryBuilder->expr()->in('post.pid', $this->getForumPids()));

        $res = $queryBuilder->execute();
        $row = $res->fetch();

        $results[] = (int)$row['counter'];

        $queryBuilder = $this->getDatabaseConnection('tx_typo3forum_domain_model_forum_topic');
        $queryBuilder->from('tx_typo3forum_domain_model_forum_topic', 'topic');
        $queryBuilder->addSelectLiteral(
            $queryBuilder->expr()->count('*', 'counter')
        );
        $queryBuilder->andWhere($queryBuilder->expr()->in('topic.pid', $this->getForumPids()));

        $res = $queryBuilder->execute();
        $row = $res->fetch();

        $results[] = (int)$row['counter'];

        $queryBuilder = $this->getDatabaseConnection('fe_users');
        $queryBuilder->from('fe_users', 'users');
        $queryBuilder->addSelectLiteral(
            $queryBuilder->expr()->count('*', 'counter')
        );
        $queryBuilder->andWhere(
            $queryBuilder->expr()->in('users.pid', $this->getForumPids()),
            $queryBuilder->expr()->eq('users.tx_extbase_type',
                $queryBuilder->createNamedParameter(FrontendUser::class, \PDO::PARAM_STR))
        );

        $res = $queryBuilder->execute();
        $row = $res->fetch();

        $results[] = (int)$row['counter'];

        foreach ($results as $typeUid => $amount) {
            $values = [
                'pid' => (int)$this->getStatsPid(),
                'tstamp' => time(),
                'type' => (int)$typeUid,
                'amount' => (int)$amount,
            ];


            $queryBuilder = $this->getDatabaseConnection('tx_typo3forum_domain_model_stats_summary');
            $queryBuilder->insert('tx_typo3forum_domain_model_stats_summary');
            $queryBuilder->values($values);
            $queryBuilder->execute();

        }

        return true;
    }
}
