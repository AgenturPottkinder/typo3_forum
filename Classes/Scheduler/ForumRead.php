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
 * Check for any user which forum is read and which not. Best way to ensure performance.
 */
class ForumRead extends AbstractDatabaseTask
{

    /**
     * @var int
     */
    protected $forumPid;

    /**
     * @var int
     */
    protected $userPid;


    /**
     * @return int
     */
    public function getForumPid()
    {
        return $this->forumPid;
    }

    /**
     * @return int
     */
    public function getUserPid()
    {
        return $this->userPid;
    }

    /**
     * @param int $forumPid
     */
    public function setForumPid($forumPid)
    {
        $this->forumPid = $forumPid;
    }

    /**
     * @param int $userPid
     */
    public function setUserPid($userPid)
    {
        $this->userPid = $userPid;
    }

    /**
     * @return bool
     */
    public function execute()
    {
        if ($this->getForumPid() == false || $this->getUserPid() == false) {
            return false;
        }

        $limit = 86400;

        $queryBuilder = $this->getDatabaseConnection('tx_typo3forum_domain_model_forum_topic');
        $queryBuilder->from('tx_typo3forum_domain_model_forum_topic', 'topic');
        $queryBuilder->select('topic.forum');
        $queryBuilder->addSelectLiteral(
            $queryBuilder->expr()->count('*', 'topic_amount')
        );

        $queryBuilder->andWhere(
            $queryBuilder->expr()->eq('topic.pid',
                $queryBuilder->createNamedParameter($this->getForumPid(), \PDO::PARAM_INT))
        );

        $queryBuilder->addGroupBy('topic.forum');
        $result = $queryBuilder->execute();


        while ($forumRow = $result->fetch()) {
            $queryBuilder = $this->getDatabaseConnection('tx_typo3forum_domain_model_forum_topic');
            $queryBuilder->from('tx_typo3forum_domain_model_forum_topic', 'topic');
            $queryBuilder->select('topic.uid');
            $queryBuilder->andWhere(
                $queryBuilder->expr()->eq('topic.forum',
                    $queryBuilder->createNamedParameter($forumRow['forum'], \PDO::PARAM_INT))
            );

            $topicResult = $queryBuilder->execute();

            $topics = [];
            while ($topicRow = $topicResult->fetch()) {
                $topics[] = $topicRow['uid'];
            }

            $queryBuilder = $this->getDatabaseConnection('fe_users');
            $queryBuilder->from('fe_users', 'users');
            $queryBuilder->select('users.uid');
            $queryBuilder->addSelectLiteral(
                $queryBuilder->expr()->count('*', 'read_amount')
            );
            $queryBuilder->leftJoin('users', 'tx_typo3forum_domain_model_user_readtopic', 'read',
                $queryBuilder->expr()->andX()->addMultiple([
                        $queryBuilder->expr()->eq('read.uid_local', 'users.uid'),
                        $queryBuilder->expr()->in('read.uid_foreign', $topics)
                    ]
                )
            );

            $queryBuilder->andWhere(
                $queryBuilder->expr()->eq('users.tx_extbase_type',
                    $queryBuilder->createNamedParameter(FrontendUser::class, \PDO::PARAM_STR)),
                $queryBuilder->expr()->eq('users.pid',
                    $queryBuilder->createNamedParameter($this->getUserPid(), \PDO::PARAM_INT)),
                $queryBuilder->expr()->gt('users.lastLogin', (time() - $limit))
            );

            $queryBuilder->addGroupBy('users.uid');

            $userResult = $queryBuilder->execute();


            while ($userRow = $userResult->fetch()) {
                $queryBuilder = $this->getDatabaseConnection('tx_typo3forum_domain_model_user_readforum');
                $queryBuilder->delete('tx_typo3forum_domain_model_user_readforum');
                $queryBuilder->andWhere(
                    $queryBuilder->expr()->eq(
                        'uid_local',
                        $queryBuilder->createNamedParameter($userRow['uid'], \PDO::PARAM_INT)
                    ),
                    $queryBuilder->expr()->eq(
                        'uid_foreign',
                        $queryBuilder->createNamedParameter($forumRow['forum'], \PDO::PARAM_INT)
                    )
                );

                $queryBuilder->execute();


                if ($forumRow['topic_amount'] == $userRow['read_amount']) {
                    $insert = [
                        'uid_local' => $userRow['uid'],
                        'uid_foreign' => $forumRow['forum'],

                    ];

                    $queryBuilder = $this->getDatabaseConnection('tx_typo3forum_domain_model_user_readforum');
                    $queryBuilder->insert('tx_typo3forum_domain_model_user_readforum');
                    $queryBuilder->values($insert)->execute();

                }
            }
        }

        return true;
    }
}