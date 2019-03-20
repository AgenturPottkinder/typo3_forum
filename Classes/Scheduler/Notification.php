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

use Mittwald\Typo3Forum\Domain\Model\Forum\Post;
use Mittwald\Typo3Forum\Domain\Model\Forum\Tag;

class Notification extends AbstractDatabaseTask
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
    protected $notificationPid;

    /**
     * @var int
     */
    protected $lastExecutedCron = 0;

    /**
     * @var int
     */
    protected $executedOn = 0;

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
    public function getNotificationPid()
    {
        return $this->notificationPid;
    }


    /**
     * @return int
     */
    public function getLastExecutedCron()
    {
        return (int)$this->lastExecutedCron;
    }


    /**
     * @return int
     */
    public function getExecutedOn()
    {
        return (int)$this->executedOn;
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
     * @param int $notificationPid
     */
    public function setNotificationPid($notificationPid)
    {
        $this->notificationPid = $notificationPid;
    }

    /**
     * @param int $lastExecutedCron
     * @return void
     */
    public function setLastExecutedCron($lastExecutedCron)
    {
        $this->lastExecutedCron = $lastExecutedCron;
    }


    /**
     * @param int $executedOn
     * @return void
     */
    public function setExecutedOn($executedOn)
    {
        $this->executedOn = $executedOn;
    }

    /**
     * @return bool
     */
    public function execute()
    {
        if ($this->getForumPids() == false || $this->getUserPids() == false) {
            return false;
        }

        $this->setLastExecutedCron((int)$this->findLastCronExecutionDate());
        $this->setExecutedOn(time());

        $this->checkPostNotifications();
        $this->checkTagsNotification();

        return true;
    }

    /**
     * @return void
     */
    private function checkPostNotifications()
    {
        $topicResult = $this->getNotifiablePosts();

        while ($topicRow = $topicResult->fetch()) {
            $involvedUser = $this->getUserInvolvedInTopic($topicRow['uid']);
            $queryBuilder = $this->getDatabaseConnection('tx_typo3forum_domain_model_forum_post');
            $queryBuilder->from('tx_typo3forum_domain_model_forum_post', 'post');
            $queryBuilder->select('post.uid', 'post.author');
            $queryBuilder->andWhere(
                $queryBuilder->expr()->eq('post.topic',
                    $queryBuilder->createNamedParameter($topicRow['uid'], \PDO::PARAM_INT)),
                $queryBuilder->expr()->gt('post.crdate',
                    $queryBuilder->createNamedParameter($this->getLastExecutedCron(), \PDO::PARAM_INT)),
                $queryBuilder->expr()->in('post.pid', $this->getForumPids())
            );

            $postResult = $queryBuilder->execute();


            while ($postRow = $postResult->fetch()) {
                foreach ($involvedUser as $user) {
                    if ($user['author'] == $postRow['author']) {
                        continue;
                    }
                    if ($user['firstPostOfUser'] > $postRow['uid']) {
                        continue;
                    }

                    $insert = [
                        'crdate' => $this->getExecutedOn(),
                        'pid' => $this->getNotificationPid(),
                        'feuser' => (int)$user['author'],
                        'post' => (int)$postRow['uid'],
                        'type' => Post::class,
                        'user_read' => (($this->getLastExecutedCron() == 0) ? 1 : 0)

                    ];


                    $queryBuilder = $this->getDatabaseConnection('tx_typo3forum_domain_model_user_notification');
                    $queryBuilder->insert('tx_typo3forum_domain_model_user_notification');
                    $queryBuilder->values($insert);
                    $queryBuilder->execute();
                }
            }
        }
    }

    /**
     * @return boolean
     */
    private function checkTagsNotification()
    {
        $tagsResult = $this->getNotifiableTags();

        while ($tagsRow = $tagsResult->fetch()) {
            $subscribedTagUser = [];
            $queryBuilder = $this->getDatabaseConnection('tx_typo3forum_domain_model_forum_tag');
            $queryBuilder->from('tx_typo3forum_domain_model_forum_tag', 'tag');
            $queryBuilder->select('users.uid');
            $queryBuilder->join(
                'tag',
                'tx_typo3forum_domain_model_forum_tag_user',
                'mm',
                $queryBuilder->expr()->eq('mm.uid_local', 'tag.uid')
            );

            $queryBuilder->join('mm', 'fe_users', 'users', $queryBuilder->expr()->eq('users.uid', 'mm.uid_foreign'));
            $queryBuilder->andWhere($queryBuilder->expr()->eq(
                'tag.uid',
                $queryBuilder->createNamedParameter($tagsRow['tagUid'], \PDO::PARAM_INT)
            ));

            $userResult = $queryBuilder->execute();


            while ($userRow = $userResult->fetch()) {
                $subscribedTagUser[] = $userRow['uid'];
            }
            $queryBuilder = $this->getDatabaseConnection('tx_typo3forum_domain_model_forum_post');
            $queryBuilder->from('tx_typo3forum_domain_model_forum_post', 'post');
            $queryBuilder->select('*');
            $queryBuilder->andWhere(
                $queryBuilder->expr()->eq('post.topic',
                    $queryBuilder->createNamedParameter($tagsRow['topicUid'], \PDO::PARAM_INT)),
                $queryBuilder->expr()->gt('post.author', 0),
                $queryBuilder->expr()->gt('post.crdate',
                    $queryBuilder->createNamedParameter($this->getLastExecutedCron(), \PDO::PARAM_INT)),
                $queryBuilder->expr()->in('post.pid', $this->getForumPids())
            );


            $postResult = $queryBuilder->execute();

            while ($postRow = $postResult->fetch()) {
                foreach ($subscribedTagUser as $userUid) {

                    if ($postRow['author'] == $userUid) {
                        continue;
                    }

                    $insert = [
                        'crdate' => $this->getExecutedOn(),
                        'pid' => $this->getNotificationPid(),
                        'feuser' => (int)$userUid,
                        'post' => (int)$postRow['uid'],
                        'tag' => (int)$tagsRow['tagUid'],
                        'type' => Tag::class,
                        'user_read' => (($this->getLastExecutedCron() == 0) ? 1 : 0)

                    ];

                    $queryBuilder = $this->getDatabaseConnection('tx_typo3forum_domain_model_user_notification');
                    $queryBuilder->insert('tx_typo3forum_domain_model_user_notification');
                    $queryBuilder->values($insert);
                    $queryBuilder->execute();
                }
            }
        }
        return true;
    }

    /**
     * Get the CrDate of the last inserted notification
     * @return int
     */
    private function findLastCronExecutionDate()
    {
        $queryBuilder = $this->getDatabaseConnection('tx_typo3forum_domain_model_user_notification');
        $queryBuilder->from('tx_typo3forum_domain_model_user_notification', 'notification');
        $queryBuilder->select('notification.crdate');
        $queryBuilder->andWhere(
            $queryBuilder->expr()->eq('notification.pid',
                $queryBuilder->createNamedParameter($this->getNotificationPid(), \PDO::PARAM_INT))
        );
        $queryBuilder->addOrderBy('notification.crdate', 'DESC');
        $queryBuilder->setMaxResults(1);
        $result = $queryBuilder->execute();
        $row = $result->fetch();
        return (int)$row['crdate'];
    }

    /**
     * Get all users who are involved in this topic
     * @param int $topicUid
     * @return array
     */
    private function getUserInvolvedInTopic($topicUid)
    {
        $user = [];
        $queryBuilder = $this->getDatabaseConnection('tx_typo3forum_domain_model_forum_post');
        $queryBuilder->from('tx_typo3forum_domain_model_forum_post', 'post');
        $queryBuilder->select('post.author', 'post.uid');
        $queryBuilder->andWhere(
            $queryBuilder->expr()->in('post.pid', $this->getForumPids()),
            $queryBuilder->expr()->gt('post.author', 0),
            $queryBuilder->expr()->eq('post.topic', $queryBuilder->createNamedParameter($topicUid, \PDO::PARAM_INT))
        );
        $queryBuilder->addGroupBy('post.author');
        $queryBuilder->addGroupBy('post.uid');

        $result = $queryBuilder->execute();


        while ($row = $result->fetch()) {
            $user[] = [
                'author' => (int)$row['author'],
                'firstPostOfUser' => (int)$row['uid'],
            ];
        }

        return $user;
    }

    /**
     * getNotifiableTags.
     * @return \Doctrine\DBAL\Driver\Statement|int
     */
    private function getNotifiableTags()
    {
        $queryBuilder = $this->getDatabaseConnection('tx_typo3forum_domain_model_forum_tag');
        $queryBuilder->from('tx_typo3forum_domain_model_forum_tag', 'tag');
        $queryBuilder->select('tag.uid AS tagUid', 'topic.uid AS topicUid');
        $queryBuilder->join('tag', 'tx_typo3forum_domain_model_forum_tag_topic', 'mm',
            $queryBuilder->expr()->eq('mm.uid_foreign', 'tag.uid'));
        $queryBuilder->join('mm', 'tx_typo3forum_domain_model_forum_topic', 'topic',
            $queryBuilder->expr()->eq('mm.uid_local', 'topic.uid'));
        $queryBuilder->join('topic', 'tx_typo3forum_domain_model_forum_post', 'post',
            $queryBuilder->expr()->eq('post.uid', 'topic.last_post'));
        $queryBuilder->andWhere(
            $queryBuilder->expr()->in('tag.pid', $this->getForumPids()),
            $queryBuilder->expr()->gt('post.crdate',
                $queryBuilder->createNamedParameter($this->getLastExecutedCron(), \PDO::PARAM_INT))
        );

        $queryBuilder->addOrderBy('topic.last_post', 'DESC');

        return $queryBuilder->execute();
    }

    /**
     * getNotifiablePosts.
     * @return \Doctrine\DBAL\Driver\Statement|int
     */
    private function getNotifiablePosts()
    {
        $queryBuilder = $this->getDatabaseConnection('tx_typo3forum_domain_model_forum_topic');
        $queryBuilder->from('tx_typo3forum_domain_model_forum_topic', 'topic');
        $queryBuilder->select('topic.uid');
        $queryBuilder->join(
            'topic',
            'tx_typo3forum_domain_model_forum_post',
            'post',
            $queryBuilder->expr()->eq('post.uid', 'topic.last_post')
        );
        $queryBuilder->andWhere(
            $queryBuilder->expr()->in('topic.pid', $this->getForumPids()),
            $queryBuilder->expr()->gt('post.crdate',
                $queryBuilder->createNamedParameter($this->getLastExecutedCron(), \PDO::PARAM_INT))
        );
        $queryBuilder->addGroupBy('topic.uid');
        $queryBuilder->addOrderBy('topic.last_post', 'DESC');

        return $queryBuilder->execute();
    }
}
