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

class TopicRepository extends AbstractRepository {

	/**
     * Returns a query for objects of this repository
     *
     * @return \TYPO3\CMS\Extbase\Persistence\QueryInterface
     * @api
     */
    public function createQuery() {
        $query = parent::createQuery();

        // don't add sys_language_uid constraint
        $query->getQuerySettings()->setRespectSysLanguage(FALSE);

        return $query;
    }

	/**
	 *
	 * Finds topics for a specific filterset. Page navigation is possible.
	 *
	 * @param integer $limit
	 * @param array $orderings
	 *
	 * @return Array<\Mittwald\Typo3Forum\Domain\Model\Forum\Topic> The selected subset of posts
	 *
	 */
	public function findByFilter($limit = NULL, $orderings = []) {
		$query = $this->createQuery();
		if (!empty($limit)) {
			$query->setLimit($limit);
		}
		if (!empty($orderings)) {
			$query->setOrderings($orderings);
		}
		return $query->execute();
	}

	/**
	 *
	 * Finds topics for a specific filterset. Page navigation is possible.
	 *
	 * @param array $uids
	 *
	 * @return Topic[]
	 *                               The selected subset of topcis
	 *
	 */
	public function findByUids($uids) {

		$query = $this->createQuery();
		$constraints = [];
		if (!empty($uids)) {
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
	 * @param Forum $forum
	 *                               The forum for which to load the topics.
	 *
	 * @return Topic[]
	 *                               The selected subset of topics.
	 */
	public function findForIndex(Forum $forum) {
		$query = $this->createQuery();
		$query
			->matching($query->equals('forum', $forum))
			->setOrderings(['sticky' => 'DESC',
				'last_post_crdate' => 'DESC']);

		return $query->execute();
	}

	/**
	 * Finds topics with questions flag.
	 *
	 * @param int $limit
	 * @param bool $showAnswered
	 * @param FrontendUser $user
	 * @return Topic[]
	 */

	public function findQuestions($limit = NULL, $showAnswered = FALSE, FrontendUser $user = NULL) {

		$query = $this->createQuery();

		$constraint = [$query->equals('question', '1')];
		if ($user != null) {
			$constraint[] = $query->equals('author', $user);
		}
		if ($showAnswered == FALSE) {
			$constraint[] = $query->equals('solution', 0);
		}
		$query->setOrderings(['sticky' => 'DESC',
			'posts.crdate' => 'DESC']);
		if ($limit != NULL && is_numeric($limit)) {
			$query->setLimit($limit);
		}
		$query->matching($query->logicalAnd($constraint));

		return $query->execute();
	}

	/**
	 * Finds topics by post authors, i.e. all topics that contain at least one post
	 * by a specific author. Page navigation is possible.
	 *
	 * @param FrontendUser $user The frontend user whose topics are to be loaded.
	 * @param int $limit
	 * @return Topic[] All topics that contain a post by the specified user.
	 */
	public function findTopicsCreatedByAuthor(FrontendUser $user, $limit = 0) {
		$query = $this->createQuery();
		$query
			->matching($query->equals('author', $user))
			->setOrderings(['crdate' => 'DESC']);
		if ($limit > 0) {
			$query->setLimit($limit);
		}

		return $query->execute();
	}

	/**
	 * Finds topics by post authors, i.e. all topics that contain at least one post
	 * by a specific author. Page navigation is possible.
	 *
	 * @param FrontendUser $user The frontend user whose topics are to be loaded.
	 * @param int $limit
	 *
	 * @return Topic[] All topics that contain a post by the specified user
	 */
	public function findTopicsFavSubscribedByUser(FrontendUser $user, $limit = 0) {
		$query = $this->createQuery();
		$query->matching($query->contains('favSubscribers', $user))->setOrderings(['crdate' => 'DESC']);
		if ($limit > 0) {
			$query->setLimit($limit);
		}
		return $query->execute();
	}

	/**
	 * Counts topics by post authors. See findByPostAuthor.
	 *
	 * @param FrontendUser $user The frontend user whose topics are to be loaded.
	 * @return integer The number of topics that contain a post by the specified user.
	 */
	public function countByPostAuthor(FrontendUser $user) {
		return $this->findByPostAuthor($user)->count();
	}

	/**
	 * Finds topics by post authors, i.e. all topics that contain at least one post
	 * by a specific author. Page navigation is possible.
	 *
	 * @param FrontendUser $user
	 *
	 * @return \TYPO3\CMS\Extbase\Persistence\QueryResultInterface
	 */
	public function findByPostAuthor(FrontendUser $user) {
		$query = $this->createQuery();
		$query->matching($query->equals('posts.author', $user))->setOrderings(['crdate' => 'DESC']);
		return $query->execute();
	}

	/**
	 * Finds all topic that have been subscribed by a certain user.
	 *
	 * @param FrontendUser $user The user for whom the subscribed topics are to be loaded.
	 * @return QueryInterface The topics subscribed by the given user.
	 */
	public function findBySubscriber(FrontendUser $user) {
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
	 * @return QueryInterface The topics of this tag.
	 */
	public function findAllTopicsWithGivenTag(Tag $tag) {
		$query = $this->createQuery();
		$query
			->matching($query->contains('tags', $tag))
			->setOrderings(['lastPost.crdate' => 'ASC']);

		return $query->execute();
	}

	/**
	 * Finds all popular topics
	 *
	 * @param int $timeDiff
	 * @param int $displayLimit
	 * @return QueryInterface
	 */
	public function findPopularTopics($timeDiff = 0, $displayLimit = 0) {
		if ($timeDiff == 0) {
			$timeLimit = 0;
		} else {
			$timeLimit = time() - $timeDiff;
		}

		$query = $this->createQuery();
		$query->matching($query->greaterThan('lastPost.crdate', $timeLimit));
		$query->setOrderings(['postCount' => 'DESC']);
		if ($displayLimit > 0) {
			$query->setLimit($displayLimit);
		}

		return $query->execute();
	}

	/**
	 *
	 * Finds the last topic in a forum.
	 *
	 * @param Forum $forum The forum for which to load the last topic.
	 * @param int $offset If you want to get the next to last topic topic
	 * @return Topic The last topic of the specified forum.
	 *
	 */
	public function findLastByForum(Forum $forum, $offset = 0) {
		$query = $this->createQuery();
		$query->matching($query->equals('forum', $forum))
			->setOrderings(['last_post_crdate' => QueryInterface::ORDER_DESCENDING])->setLimit(1);
		if ($offset > 0) {
			$query->setOffset($offset);
		}

		return $query->execute()->getFirst();
	}

	/**
	 *
	 * Finds the last topic in a forum.
	 *
	 * @param int $limit  The Limit
	 * @param int $offset The Offset
	 * @return Topic The last topics
	 *
	 */
	public function findLatest($offset = 0, $limit = 5) {
		$query = $this->createQuery();
		$query->setOrderings(['last_post_crdate' => QueryInterface::ORDER_DESCENDING])
			->setLimit($limit);
		if ($offset > 0) {
			$query->setOffset($offset);
		}

		return $query->execute();
	}

	/**
	 * @param Forum $forum
	 * @param FrontendUser $user
	 * @return array
	 */
	public function getUnreadTopics(Forum $forum, FrontendUser $user) {
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
