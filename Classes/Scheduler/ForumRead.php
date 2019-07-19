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

		/*
		 * In order to be able to select forums without topics (using a left join) currently its necessary to manually apply the restrictions in the join as
		 * otherwise only forums with topic will be returned (due to topic.deleted=0 != topic.deleted=NULL)
		 *
		 * @link https://forge.typo3.org/issues/86385
		 */
        $forumQueryBuilder = $this->getDatabaseConnection('tx_typo3forum_domain_model_forum_topic');
		$forumQueryBuilder->getRestrictions()->removeAll();
		$result = $forumQueryBuilder
			->select('forum.uid AS forum')
			->addSelectLiteral(
				$forumQueryBuilder->expr()->count('topic.uid', 'topic_amount')
			)
			->from('tx_typo3forum_domain_model_forum_forum', 'forum')
			->leftJoin(
				'forum',
				'tx_typo3forum_domain_model_forum_topic',
				'topic',
				$forumQueryBuilder->expr()->andX(
					$forumQueryBuilder->expr()->eq('forum.uid',
						$forumQueryBuilder->quoteIdentifier('topic.forum')
					),
					$forumQueryBuilder->expr()->eq('topic.pid',
						$forumQueryBuilder->createNamedParameter($this->getForumPid(), \PDO::PARAM_INT)
					),
					$forumQueryBuilder->expr()->eq('topic.deleted',
						$forumQueryBuilder->createNamedParameter(0, \PDO::PARAM_INT)
					),
					$forumQueryBuilder->expr()->eq('topic.hidden',
						$forumQueryBuilder->createNamedParameter(0, \PDO::PARAM_INT)
					)
				)
			)
			->where($forumQueryBuilder->expr()->eq('forum.pid',
				$forumQueryBuilder->createNamedParameter($this->getForumPid(), \PDO::PARAM_INT))
			)
			->andWhere($forumQueryBuilder->expr()->eq('forum.deleted',
				$forumQueryBuilder->createNamedParameter(0, \PDO::PARAM_INT))
			)
			->andWhere($forumQueryBuilder->expr()->eq('forum.hidden',
				$forumQueryBuilder->createNamedParameter(0, \PDO::PARAM_INT))
			)
			->addGroupBy('forum.uid')
			->execute();

        while ($forumRow = $result->fetch()) {
            $topicQueryBuilder = $this->getDatabaseConnection('tx_typo3forum_domain_model_forum_topic');
			$topics = $topicQueryBuilder
				->select('topic.uid')
				->from('tx_typo3forum_domain_model_forum_topic', 'topic')
				->where($topicQueryBuilder->expr()->eq('topic.forum',
					$topicQueryBuilder->createNamedParameter($forumRow['forum'], \PDO::PARAM_INT))
				)
				->execute()
				->fetchAll(\PDO::FETCH_COLUMN);

            $userQueryBuilder = $this->getDatabaseConnection('fe_users');
			$userQueryBuilder
				->select('users.uid')
				->from('fe_users', 'users');

            if (!empty($topics)) {
				$userQueryBuilder
					->addSelectLiteral(
						$userQueryBuilder->expr()->count('*', 'read_amount')
					)
					->leftJoin('users', 'tx_typo3forum_domain_model_user_readtopic', 'read',
					$userQueryBuilder->expr()->andX()->addMultiple([
							$userQueryBuilder->expr()->eq('read.uid_local', 'users.uid'),
							$userQueryBuilder->expr()->in('read.uid_foreign', $topics)
						]
					)
				);
			}

			$userResult = $userQueryBuilder
				->andWhere(
					$userQueryBuilder->expr()->eq('users.tx_extbase_type',
						$userQueryBuilder->createNamedParameter(FrontendUser::class, \PDO::PARAM_STR)),
					$userQueryBuilder->expr()->eq('users.pid',
						$userQueryBuilder->createNamedParameter($this->getUserPid(), \PDO::PARAM_INT)),
					$userQueryBuilder->expr()->gt('users.lastlogin', (time() - $limit))
				)
				->addGroupBy('users.uid')
				->execute();

            while ($userRow = $userResult->fetch()) {
                $deleteQueryBuilder = $this->getDatabaseConnection('tx_typo3forum_domain_model_user_readforum');
				$deleteQueryBuilder->delete('tx_typo3forum_domain_model_user_readforum');
				$deleteQueryBuilder->andWhere(
					$deleteQueryBuilder->expr()->eq(
                        'uid_local',
						$deleteQueryBuilder->createNamedParameter($userRow['uid'], \PDO::PARAM_INT)
                    ),
					$deleteQueryBuilder->expr()->eq(
                        'uid_foreign',
						$deleteQueryBuilder->createNamedParameter($forumRow['forum'], \PDO::PARAM_INT)
                    )
                );

				$deleteQueryBuilder->execute();

                if ($forumRow['topic_amount'] == 0 || $forumRow['topic_amount'] == $userRow['read_amount']) {
                    $insert = [
                        'uid_local' => $userRow['uid'],
                        'uid_foreign' => $forumRow['forum'],
                    ];

                    $insertQueryBuilder = $this->getDatabaseConnection('tx_typo3forum_domain_model_user_readforum');
					$insertQueryBuilder->insert('tx_typo3forum_domain_model_user_readforum');
					$insertQueryBuilder->values($insert)->execute();

                }
            }
        }

        return true;
    }
}
