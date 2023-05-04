<?php

namespace Mittwald\Typo3Forum\Command;

use TYPO3\CMS\Core\Site\Entity\SiteInterface;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CounterUpdateCommand extends AbstractDatabaseBasedCommand
{
    protected function executeForSite(SiteInterface $site, InputInterface $input, OutputInterface $output): void
    {
        $this->updateTopicCounters();
        $this->updateForumCounters();
        $this->updateUserCounters();
    }

    private function updateTopicCounters()
    {
        $topicCount = [];

        $queryBuilder = $this->getQueryBuilder('tx_typo3forum_domain_model_forum_post');
        $queryBuilder->from('tx_typo3forum_domain_model_forum_post', 'post');
        $queryBuilder->select('post.topic');
        $queryBuilder->addSelectLiteral(
            $queryBuilder->expr()->count('post.uid', 'counter')
        );
        $queryBuilder->join(
            'post',
            'tx_typo3forum_domain_model_forum_topic',
            'topic',
            $queryBuilder->expr()->eq('post.topic', 'topic.uid')
        );
        $queryBuilder->andWhere(
            $queryBuilder->expr()->eq(
                'post.pid',
                $queryBuilder->createNamedParameter($this->storagePage, \PDO::PARAM_INT)
            )
        );

        $queryBuilder->addGroupBy('post.topic');
        $queryBuilder->addOrderBy('counter', 'ASC');

        $result = $queryBuilder->execute();

        while ($row = $result->fetchAssociative()) {
            $topicCount[$row['topic']] = $row['counter'];
        }

        $lastCount = 1;
        $lastCountArray = [];
        $topicCount[PHP_INT_MAX] = 0; // Fix a bug in the loop skipping all topics with the highest number of posts.
        foreach ($topicCount as $topicUid => $postCount) {
            if ($lastCount != $postCount) {
                $updateQueryBuilder = $this->getQueryBuilder('tx_typo3forum_domain_model_forum_topic');
                $updateQueryBuilder->update('tx_typo3forum_domain_model_forum_topic');
                $updateQueryBuilder->andWhere(
                    $updateQueryBuilder->expr()->in('uid', $lastCountArray)
                );
                $updateQueryBuilder->set('post_count', $lastCount);

                $updateQueryBuilder->execute();
                $lastCountArray = [];
            }
            $lastCountArray[] = (int)$topicUid;
            $lastCount = $postCount;
        }
    }

    private function updateForumCounters()
    {
        $queryBuilderTopic = $this->getQueryBuilder('tx_typo3forum_domain_model_forum_topic');
        $statement = $queryBuilderTopic
            ->select('forum')
            ->addSelectLiteral($queryBuilderTopic->expr()->count('uid', 'topic_count'))
            ->addSelectLiteral($queryBuilderTopic->expr()->sum('post_count', 'post_count'))
            ->from('tx_typo3forum_domain_model_forum_topic')
            ->where(
                $queryBuilderTopic->expr()->eq(
                    'pid',
                    $queryBuilderTopic->createNamedParameter($this->storagePage, \PDO::PARAM_INT)
                )
            )
            ->groupBy('forum')
            ->execute();

        $postAndTopicCountPerForum = [];

        while ($forum = $statement->fetchAssociative()) {
            $postAndTopicCountPerForum[(int)$forum['forum']] = [
                'topic_count' => (int)$forum['topic_count'],
                'post_count' => (int)$forum['post_count']
            ];
        }

        $queryBuilderForum = $this->getQueryBuilder('tx_typo3forum_domain_model_forum_forum');
        $statement = $queryBuilderForum
            ->select('uid')
            ->from('tx_typo3forum_domain_model_forum_forum')
            ->where(
                $queryBuilderForum->expr()->eq(
                    'pid',
                    $queryBuilderForum->createNamedParameter($this->storagePage, \PDO::PARAM_INT)
                )
            )
            ->execute();

        $updateForumConnection = $this->getConnectionPool()->getConnectionForTable('tx_typo3forum_domain_model_forum_forum');

        while ($forum = $statement->fetchAssociative()) {
            $forumUid = (int)$forum['uid'];
            $updateForumConnection->update(
                'tx_typo3forum_domain_model_forum_forum',
                [
                    'topic_count' => (int)$postAndTopicCountPerForum[$forumUid]['topic_count'],
                    'post_count' => (int)$postAndTopicCountPerForum[$forumUid]['post_count']
                ],
                ['uid' => $forumUid],
                [\PDO::PARAM_INT, \PDO::PARAM_INT, \PDO::PARAM_INT]
            );
        }
    }

    private function updateUserCounters()
    {
        $forumPid = $this->storagePage;
        $userUpdate = [];
        $rankScore = $this->settings['rankScore.'];

        //Find any post_count
        $queryBuilder = $this->getQueryBuilder('tx_typo3forum_domain_model_forum_post');
        $queryBuilder->from('tx_typo3forum_domain_model_forum_post', 'post');
        $queryBuilder->select('post.author');
        $queryBuilder->addSelectLiteral($queryBuilder->expr()->count('post.uid', 'counter'));
        $queryBuilder->andWhere(
            $queryBuilder->expr()->gt('post.author', 0),
            $queryBuilder->expr()->eq('post.pid', $queryBuilder->createNamedParameter($forumPid, \PDO::PARAM_INT))
        );
        $queryBuilder->addGroupBy('post.author');
        $result = $queryBuilder->execute();

        while ($row = $result->fetchAssociative()) {
            $userUpdate[$row['author']]['post_count'] = $row['counter'];
        }

        //Find any topic count
        $queryBuilder = $this->getQueryBuilder('tx_typo3forum_domain_model_forum_topic');
        $queryBuilder->from('tx_typo3forum_domain_model_forum_topic', 'topic');
        $queryBuilder->select('topic.author');
        $queryBuilder->addSelectLiteral($queryBuilder->expr()->count('topic.uid', 'counter'));
        $queryBuilder->andWhere(
            $queryBuilder->expr()->gt('topic.author', 0),
            $queryBuilder->expr()->eq('topic.pid', $queryBuilder->createNamedParameter($forumPid, \PDO::PARAM_INT))
        );
        $queryBuilder->addGroupBy('topic.author');
        $result = $queryBuilder->execute();

        while ($row = $result->fetchAssociative()) {
            $userUpdate[$row['author']]['topic_count'] = $row['counter'];
        }

        // Find any question topic count
        $queryBuilder = $this->getQueryBuilder('tx_typo3forum_domain_model_forum_topic');
        $queryBuilder->from('tx_typo3forum_domain_model_forum_topic', 'topic');
        $queryBuilder->select('topic.author');
        $queryBuilder->addSelectLiteral($queryBuilder->expr()->count('topic.uid', 'counter'));
        $queryBuilder->andWhere(
            $queryBuilder->expr()->gt('topic.author', 0),
            $queryBuilder->expr()->eq('topic.question', true),
            $queryBuilder->expr()->eq('topic.pid', $queryBuilder->createNamedParameter($forumPid, \PDO::PARAM_INT))
        );
        $queryBuilder->addGroupBy('topic.author');
        $result = $queryBuilder->execute();

        while ($row = $result->fetchAssociative()) {
            $userUpdate[$row['author']]['question_count'] = $row['counter'];
        }

        //Supported Post User X got
        $queryBuilder = $this->getQueryBuilder('tx_typo3forum_domain_model_user_supportpost');
        $queryBuilder->from('tx_typo3forum_domain_model_user_supportpost', 'support');
        $queryBuilder->join(
            'support',
            'tx_typo3forum_domain_model_forum_post',
            'post',
            $queryBuilder->expr()->eq('support.uid_foreign', 'post.uid')
        );
        $queryBuilder->select('post.author');
        $queryBuilder->addSelectLiteral($queryBuilder->expr()->count('*', 'counter'));
        $queryBuilder->addGroupBy('post.author');
        $result = $queryBuilder->execute();

        while ($row = $result->fetchAssociative()) {
            $userUpdate[$row['author']]['support_count'] = $row['counter'];
        }

        $queryBuilder = $this->getQueryBuilder('tx_typo3forum_domain_model_user_supportpost');
        $queryBuilder->from('tx_typo3forum_domain_model_user_supportpost', 'support');
        $queryBuilder->select('support.uid_local');
        $queryBuilder->addSelectLiteral($queryBuilder->expr()->count('*', 'counter'));
        $queryBuilder->addGroupBy('support.uid_local');
        $result = $queryBuilder->execute();

        while ($row = $result->fetchAssociative()) {
            $userUpdate[$row['uid_local']]['markSupport_count'] = $row['counter'];
        }

        //Find all users with their current rank
        $queryBuilder = $this->getQueryBuilder('fe_users');
        $queryBuilder->from('fe_users', 'user');
        $queryBuilder->select('user.uid AS author', 'user.tx_typo3forum_rank');
        $queryBuilder->andWhere(
            $queryBuilder->expr()->eq(
                'user.pid',
                $queryBuilder->createNamedParameter($this->storagePage, \PDO::PARAM_INT)
            )
        );
        $result = $queryBuilder->execute();

        while ($row = $result->fetchAssociative()) {
            $userUpdate[$row['author']]['rank'] = $row['tx_typo3forum_rank'];
        }

        //Find all ranks
        $rankArray = [];

        $queryBuilder = $this->getQueryBuilder('tx_typo3forum_domain_model_user_rank');
        $queryBuilder->from('tx_typo3forum_domain_model_user_rank', 'rank');
        $queryBuilder->select('rank.uid', 'rank.point_limit');
        $queryBuilder->andWhere(
            $queryBuilder->expr()->eq(
                'rank.pid',
                $queryBuilder->createNamedParameter($this->storagePage, \PDO::PARAM_INT)
            )
        );
        $queryBuilder->addOrderBy('point_limit', 'ASC');
        $result = $queryBuilder->execute();

        while ($row = $result->fetchAssociative()) {
            $rankArray[$row['uid']] = $row;
        }

        $updateFeUserConnection = $this->getConnectionPool()->getConnectionForTable('fe_users');

        //Now check this giant array
        foreach ($userUpdate as $userUid => $array) {
            $points = 0;
            $points = $points + (int)$array['post_count'] * (int)$rankScore['newPost'];
            $points = $points + (int)$array['markSupport_count'] * (int)$rankScore['markHelpful'];
            $points = $points + (int)$array['support_count'] * (int)$rankScore['gotHelpful'];

            $lastPointLimit = 0;
            $lastRankUid = 0;

            foreach ($rankArray as $key => $rank) {
                if ($points >= $lastPointLimit && $points < $rank['point_limit']) {
                    $array['rank'] = $rank['uid'];
                }
                $lastPointLimit = $rank['point_limit'];
                $lastRankUid = $rank['uid'];
            }
            if ($lastRankUid > 0 && $points >= $lastPointLimit) {
                $array['rank'] = $lastRankUid;
            }

            $updateFeUserConnection->update(
                'fe_users',
                [
                    'tx_typo3forum_post_count' => (int)$array['post_count'],
                    'tx_typo3forum_topic_count' => (int)$array['topic_count'],
                    'tx_typo3forum_question_count' => (int)$array['question_count'],
                    'tx_typo3forum_helpful_count' => (int)$array['support_count'],
                    'tx_typo3forum_points' => (int)$points,
                    'tx_typo3forum_rank' => (int)$array['rank'],
                ],
                ['uid' => (int)$userUid],
                [\PDO::PARAM_INT, \PDO::PARAM_INT, \PDO::PARAM_INT, \PDO::PARAM_INT, \PDO::PARAM_INT, \PDO::PARAM_INT, \PDO::PARAM_INT]
            );
        }

        //At last, update the rank count
        $queryBuilder = $this->getQueryBuilder('fe_users');
        $queryBuilder->from('fe_users', 'user');
        $queryBuilder->select('user.tx_typo3forum_rank');
        $queryBuilder->addSelectLiteral(
            $queryBuilder->expr()->count('*', 'counter')
        );
        $queryBuilder->andWhere(
            $queryBuilder->expr()->eq(
                'user.pid',
                $queryBuilder->createNamedParameter($this->storagePage, \PDO::PARAM_INT)
            )
        );
        $queryBuilder->addGroupBy('tx_typo3forum_rank');
        $result = $queryBuilder->execute();

        $updateRankConnection = $this->getConnectionPool()->getConnectionForTable('tx_typo3forum_domain_model_user_rank');
        while ($row = $result->fetchAssociative()) {
            $updateRankConnection->update(
                'tx_typo3forum_domain_model_user_rank',
                ['user_count' => (int)$row['counter']],
                ['uid' => (int)$row['tx_typo3forum_rank']],
                [\PDO::PARAM_INT, \PDO::PARAM_INT]
            );
        }
    }
}
