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

use Mittwald\Typo3Forum\Domain\Model\Forum\Forum;
use Mittwald\Typo3Forum\Domain\Model\Forum\Tag;
use Mittwald\Typo3Forum\Domain\Model\Forum\Topic;
use Mittwald\Typo3Forum\Domain\Model\User\FrontendUser;
use Mittwald\Typo3Forum\Domain\Repository\AbstractRepository;
use TYPO3\CMS\Extbase\Persistence\Generic\Query;
use TYPO3\CMS\Extbase\Persistence\QueryInterface;
use TYPO3\CMS\Extbase\Persistence\QueryResultInterface;

class TopicRepository extends AbstractRepository
{
    public function createQuery(): QueryInterface
    {
        $query = parent::createQuery();

        // don't add sys_language_uid constraint
        $query->getQuerySettings()->setRespectSysLanguage(false);

        return $query;
    }

    /**
     * Finds topics for a specific filterset.
     *
     * @return QueryResultInterface<Topic>
     */
    public function findByFilter(?int $limit = null, ?array $orderings = null): QueryResultInterface
    {
        $query = $this->createQuery();
        if ($limit !== null) {
            $query->setLimit($limit);
        }
        if (!empty($orderings)) {
            $query->setOrderings($orderings);
        }
        return $query->execute();
    }

    /**
     * @return QueryResultInterface<Topic>
     */
    public function findByUids(array $uids): QueryResultInterface
    {
        $query = $this->createQuery();
        $constraints = [];
        if (count($uids) > 0) {
            $constraints[] = $query->in('uid', $uids);
        }
        if (!empty($constraints)) {
            $query->matching($query->logicalAnd($constraints));
        }

        return $query->execute();
    }

    /**
     * Finds topics for the forum show view. Page navigation is possible.
     *
     * @return QueryResultInterface<Topic>
     */
    public function findForIndex(Forum $forum): QueryResultInterface
    {
        $query = $this->createQuery();
        $query
            ->matching(
                $query->equals('forum', $forum)
            )
            ->setOrderings([
                'sticky' => 'DESC',
                'last_post_crdate' => 'DESC'
            ])
        ;

        return $query->execute();
    }

    /**
     * Finds topics with questions flag.
     *
     * @return QueryResultInterface<Topic>
     */
    public function findQuestions(?int $limit = null, bool $showAnswered = false, FrontendUser $user = null): QueryResultInterface
    {
        $query = $this->createQuery();

        $constraint = [$query->equals('question', '1')];
        if ($user != null) {
            $constraint[] = $query->equals('author', $user);
        }
        if ($showAnswered === false) {
            $constraint[] = $query->equals('solved', 0);
        }
        $query->setOrderings([
            'sticky' => 'DESC',
            'last_post_crdate' => 'DESC'
        ]);
        if ($limit != null && is_numeric($limit)) {
            $query->setLimit($limit);
        }
        $query->matching($query->logicalAnd($constraint));

        return $query->execute();
    }

    /**
     * Finds topics by post authors, i.e. all topics that contain at least one post
     * by a specific author. Page navigation is possible.
     */
    public function findTopicsCreatedByAuthor(
        FrontendUser $user,
        ?int $limit = null,
        bool $includeShadowTopics = true
    ): QueryResultInterface {
        $query = $this->createQuery();

        $constraints = [
            $query->equals('author', $user)
        ];

        if (!$includeShadowTopics) {
            $constraints[] = $query->logicalNot($query->equals('type', 1));
        }

        $query
            ->matching($query->logicalAnd($constraints))
            ->setOrderings(['crdate' => 'DESC']);
        if ($limit !== null) {
            $query->setLimit($limit);
        }

        return $query->execute();
    }

    /**
     * Counts topics by post authors. See findByPostAuthor.
     */
    public function countByPostAuthor(FrontendUser $user): int
    {
        return $this->findByPostAuthor($user)->count();
    }

    /**
     * Finds topics by post authors, i.e. all topics that contain at least one post
     * by a specific author. Page navigation is possible.
     *
     * @return QueryResultInterface<Topic>
     */
    public function findByPostAuthor(FrontendUser $user): QueryResultInterface
    {
        $query = $this->createQuery();
        $query->matching($query->equals('posts.author', $user))->setOrderings(['crdate' => 'DESC']);
        return $query->execute();
    }

    /**
     * Finds all topic that have been subscribed by a certain user.
     *
     * @return QueryResultInterface<Topic>
     */
    public function findBySubscriber(FrontendUser $user): QueryResultInterface
    {
        $query = $this->createQuery();
        $query
            ->matching($query->contains('subscribers', $user))
            ->setOrderings(['lastPost.crdate' => 'ASC']);

        return $query->execute();
    }

    /**
     * Finds all topic that have a specific tag
     *
     * @param Tag $tag
     * @return QueryResultInterface<Topic>
     */
    public function findByTag(Tag $tag): QueryResultInterface
    {
        $query = $this->createQuery();
        $query
            ->matching($query->contains('tags', $tag))
            ->setOrderings(['lastPost.crdate' => 'ASC']);

        return $query->execute();
    }

    /**
     * Finds all popular topics
     * @return QueryResultInterface<Topic>
     */
    public function findPopularTopics(int $timeDiff = 0, ?int $limit = null): QueryResultInterface
    {
        if ($timeDiff == 0) {
            $timeLimit = 0;
        } else {
            $timeLimit = time() - $timeDiff;
        }

        $query = $this->createQuery();
        $query->matching(
            $query->logicalAnd(
                $query->logicalNot($query->equals('type', 1)),
                $query->greaterThan('lastPost.crdate', $timeLimit)
            )
        );
        $query->setOrderings(['postCount' => 'DESC']);
        if ($limit !== null) {
            $query->setLimit($limit);
        }

        return $query->execute();
    }

    /**
     * Finds the last topic in a forum.
     */
    public function findLastByForum(Forum $forum, ?int $offset = null): ?Topic
    {
        $query = $this->createQuery();
        $query->matching($query->equals('forum', $forum))
            ->setOrderings(['last_post_crdate' => QueryInterface::ORDER_DESCENDING])->setLimit(1);
        if ($offset !== null) {
            $query->setOffset($offset);
        }

        return $query->execute()->getFirst();
    }

    /**
     * Finds the topics with the latest updates.
     * @return QueryResultInterface<Topic>
     */
    public function findLatest(?int $offset = null, ?int $limit = null): QueryResultInterface
    {
        $query = $this->createQuery();
        $query
            ->matching($query->logicalNot($query->equals('type', 1)))
            ->setOrderings(['last_post_crdate' => QueryInterface::ORDER_DESCENDING])
        ;
        if ($offset !== null) {
            $query->setOffset($offset);
        }
        if ($limit !== null) {
            $query->setLimit($limit);
        }

        return $query->execute();
    }

    /**
     * @return array<Topic>
     */
    public function getUnreadTopics(Forum $forum, FrontendUser $user): array
    {
        $sql = 'SELECT t.uid
               FROM tx_typo3forum_domain_model_forum_topic AS t
               LEFT JOIN tx_typo3forum_domain_model_user_readtopic AS rt
                       ON rt.uid_foreign = t.uid AND rt.uid_local = ' . (int)$user->getUid() . '
               WHERE rt.uid_local IS NULL AND t.forum=' . (int)$forum->getUid();
        /** @var Query $query */
        $query = $this->createQuery();
        $query->statement($sql);
        return $query->execute()->toArray();
    }
}
