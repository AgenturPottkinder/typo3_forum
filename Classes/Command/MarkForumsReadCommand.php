<?php

namespace Mittwald\Typo3Forum\Command;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use TYPO3\CMS\Core\Site\Entity\SiteInterface;

class MarkForumsReadCommand extends AbstractDatabaseBasedCommand
{
    protected function executeForSite(SiteInterface $site, InputInterface $input, OutputInterface $output): void
    {
        $limit = 86400;

        /*
         * In order to be able to select forums without topics (using a left join) currently its necessary to manually apply the restrictions in the join as
         * otherwise only forums with topic will be returned (due to topic.deleted=0 != topic.deleted=NULL)
         *
         * @link https://forge.typo3.org/issues/86385
         */
        $forumQueryBuilder = $this->getQueryBuilder('tx_typo3forum_domain_model_forum_topic');
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
                    $forumQueryBuilder->expr()->eq(
                        'forum.uid',
                        $forumQueryBuilder->quoteIdentifier('topic.forum')
                    ),
                    $forumQueryBuilder->expr()->eq(
                        'topic.pid',
                        $forumQueryBuilder->createNamedParameter($this->storagePage, \PDO::PARAM_INT)
                    ),
                    $forumQueryBuilder->expr()->eq(
                        'topic.deleted',
                        $forumQueryBuilder->createNamedParameter(0, \PDO::PARAM_INT)
                    ),
                    $forumQueryBuilder->expr()->eq(
                        'topic.hidden',
                        $forumQueryBuilder->createNamedParameter(0, \PDO::PARAM_INT)
                    ),
                    $forumQueryBuilder->expr()->neq(
                        'topic.type',
                        1
                    )
                )
            )
            ->where(
                $forumQueryBuilder->expr()->eq(
                    'forum.pid',
                    $forumQueryBuilder->createNamedParameter($this->storagePage, \PDO::PARAM_INT)
                )
            )
            ->andWhere(
                $forumQueryBuilder->expr()->eq(
                    'forum.deleted',
                    $forumQueryBuilder->createNamedParameter(0, \PDO::PARAM_INT)
                )
            )
            ->andWhere(
                $forumQueryBuilder->expr()->eq(
                    'forum.hidden',
                    $forumQueryBuilder->createNamedParameter(0, \PDO::PARAM_INT)
                )
            )
            ->addGroupBy('forum.uid')
            ->execute();

        while ($forumRow = $result->fetchAssociative()) {
            $topicQueryBuilder = $this->getQueryBuilder('tx_typo3forum_domain_model_forum_topic');
            $topics = $topicQueryBuilder
                ->select('topic.uid')
                ->from('tx_typo3forum_domain_model_forum_topic', 'topic')
                ->where(
                    $topicQueryBuilder->expr()->eq(
                        'topic.forum',
                        $topicQueryBuilder->createNamedParameter($forumRow['forum'], \PDO::PARAM_INT)
                    ),
                    $topicQueryBuilder->expr()->neq(
                        'topic.type',
                        '1'
                    )
                )
                ->execute()
                ->fetchFirstColumn();

            $userQueryBuilder = $this->getQueryBuilder('fe_users');
            $userQueryBuilder
                ->select('users.uid')
                ->from('fe_users', 'users');

            if (!empty($topics)) {
                $userQueryBuilder
                    ->addSelectLiteral(
                        $userQueryBuilder->expr()->count('*', 'read_amount')
                    )
                    ->leftJoin(
                        'users',
                        'tx_typo3forum_domain_model_user_readtopic',
                        'read',
                        $userQueryBuilder->expr()->andX()->addMultiple(
                            [
                                $userQueryBuilder->expr()->eq('read.uid_local', 'users.uid'),
                                $userQueryBuilder->expr()->in('read.uid_foreign', $topics)
                            ]
                        )
                    );
            }

            $userResult = $userQueryBuilder
                ->andWhere(
                    $userQueryBuilder->expr()->eq(
                        'users.pid',
                        $userQueryBuilder->createNamedParameter($this->storagePage, \PDO::PARAM_INT)
                    ),
                    $userQueryBuilder->expr()->gt('users.lastlogin', (time() - $limit))
                )
                ->addGroupBy('users.uid')
                ->execute();

            while ($userRow = $userResult->fetchAssociative()) {
                $deleteQueryBuilder = $this->getQueryBuilder('tx_typo3forum_domain_model_user_readforum');
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

                    $insertQueryBuilder = $this->getQueryBuilder('tx_typo3forum_domain_model_user_readforum');
                    $insertQueryBuilder->insert('tx_typo3forum_domain_model_user_readforum');
                    $insertQueryBuilder->values($insert)->execute();
                }
            }
        }
    }
}
