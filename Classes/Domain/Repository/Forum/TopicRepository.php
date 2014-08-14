<?php

/*                                                                    - *
 *  COPYRIGHT NOTICE                                                    *
 *                                                                      *
 *  (c) 2012 Martin Helmich <m.helmich@mittwald.de>                     *
 *           Mittwald CM Service GmbH & Co KG                           *
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



/**
 *
 * Repository class for topic objects.
 *
 * @author     Martin Helmich <m.helmich@mittwald.de>
 * @package    MmForum
 * @subpackage Domain_Repository_Forum
 * @version    $Id$
 *
 * @copyright  2012 Martin Helmich <m.helmich@mittwald.de>
 *             Mittwald CM Service GmbH & Co. KG
 *             http://www.mittwald.de
 * @license    GNU Public License, version 2
 *             http://opensource.org/licenses/gpl-license.php
 *
 */
class Tx_MmForum_Domain_Repository_Forum_TopicRepository extends Tx_MmForum_Domain_Repository_AbstractRepository {



	/*
	 * REPOSITORY METHODS
	 */

	/**
	 *
	 * Finds topics for a specific filterset. Page navigation is possible.
	 *
	 * @param  integer $limit
	 * @param  array $orderings
	 *
	 * @return Array<Tx_MmForum_Domain_Model_Forum_Topic>
	 *                               The selected subset of posts
	 *
	 */
	public function findByFilter($limit = '', $orderings = array()) {
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
	 * @param  array $uids
	 *
	 * @return Tx_MmForum_Domain_Model_Forum_Topic[]
	 *                               The selected subset of topcis
	 *
	 */
	public function findByUids($uids) {

		$query = $this->createQuery();
		$constraints = array();
		if(!empty($uids)) {
			$constraints[] = $query->in('uid', $uids);
		}
		if(!empty($constraints)){
			$query->matching($query->logicalAnd($constraints));
		}

		return  $query->execute();
	}

	/**
	 * Finds topics for the forum show view. Page navigation is possible.
	 *
	 * @param  Tx_MmForum_Domain_Model_Forum_Forum $forum
	 *                               The forum for which to load the topics.
	 * @return Tx_MmForum_Domain_Model_Forum_Topic[]
	 *                               The selected subset of topics.
	 */
	public function findForIndex(Tx_MmForum_Domain_Model_Forum_Forum $forum) {
		$query = $this->createQuery();
		$query
			->matching($query->equals('forum', $forum))
			->setOrderings(array('sticky'           => 'DESC',
			                    'last_post_crdate'  => 'DESC'));
		return $query->execute();
	}

	/**
	 * Finds topics with questions flag.
	 *
	 * @param null $limit
	 * @param bool $showAnswered
	 * @param Tx_MmForum_Domain_Model_User_FrontendUser $user
	 * @return Tx_MmForum_Domain_Model_Forum_Topic[]
	 */

	public function findQuestions($limit = NULL, $showAnswered = FALSE, Tx_MmForum_Domain_Model_User_FrontendUser $user = NULL) {

		$query = $this->createQuery();

		$constraint = array($query->equals('question', '1'));
		if ($user != null) {
			$constraint[] = $query->equals('author', $user);
		}
		if ($showAnswered == FALSE) {
			$constraint[] = $query->equals('solution', 0);
		}
		$query->setOrderings(array('sticky' => 'DESC',
			'posts.crdate' => 'DESC'));
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
	 * @param  Tx_MmForum_Domain_Model_User_FrontendUser $user
	 *                               The frontend user whose topics are to be loaded.
	 * @return Tx_MmForum_Domain_Model_Forum_Topic[]
	 *                               All topics that contain a post by the specified
	 *                               user.
	 */
	public function findByPostAuthor(Tx_MmForum_Domain_Model_User_FrontendUser $user) {
		$query = $this->createQuery();
		$query
			->matching($query->equals('posts.author', $user))
			->setOrderings(array('posts.crdate' => 'DESC'));
		return $query->execute();
	}



	/**
	 * Finds topics by post authors, i.e. all topics that contain at least one post
	 * by a specific author. Page navigation is possible.
	 *
	 * @param  Tx_MmForum_Domain_Model_User_FrontendUser $user
	 *                               The frontend user whose topics are to be loaded.
	 * @param int $limit
	 * @return Tx_MmForum_Domain_Model_Forum_Topic[]
	 *                               All topics that contain a post by the specified
	 *                               user.
	 */
	public function findTopicsCreatedByAuthor(Tx_MmForum_Domain_Model_User_FrontendUser $user, $limit=0) {
		$query = $this->createQuery();
		$query
			->matching($query->equals('author', $user))
			->setOrderings(array('crdate' => 'DESC'));
		if($limit > 0) {
			$query->setLimit($limit);
		}
		return $query->execute();
	}

	/**
	 * Finds topics by post authors, i.e. all topics that contain at least one post
	 * by a specific author. Page navigation is possible.
	 *
	 * @param  Tx_MmForum_Domain_Model_User_FrontendUser $user
	 *                               The frontend user whose topics are to be loaded.
	 * @param int $limit
	 * @return Tx_MmForum_Domain_Model_Forum_Topic[]
	 *                               All topics that contain a post by the specified
	 *                               user.
	 */
	public function findTopicsFavSubscribedByUser(Tx_MmForum_Domain_Model_User_FrontendUser $user, $limit=0) {
		$query = $this->createQuery();
		$query
			->matching($query->contains('favSubscribers', $user))
			->setOrderings(array('crdate' => 'DESC'));
		if($limit > 0) {
			$query->setLimit($limit);
		}
		return $query->execute();
	}




	/**
	 * Counts topics by post authors. See findByPostAuthor.
	 *
	 * @param  Tx_MmForum_Domain_Model_User_FrontendUser $user
	 *                               The frontend user whose topics are to be loaded.
	 * @return integer               The number of topics that contain a post by the
	 *                               specified user.
	 */
	public function countByPostAuthor(Tx_MmForum_Domain_Model_User_FrontendUser $user) {
		return $this
			->findByPostAuthor($user)
			->count();
	}



	/**
	 * Counts all topics for the forum show view.
	 *
	 * @param  Tx_MmForum_Domain_Model_Forum_Forum $forum
	 *                             The forum for which the topics are to be counted.
	 * @return integer             The topic count.
	 */
	public function countForIndex(Tx_MmForum_Domain_Model_Forum_Forum $forum) {
		return $this->countByForum($forum);
	}



	/**
	 * Finds all topic that have been subscribed by a certain user.
	 *
	 * @param Tx_MmForum_Domain_Model_User_FrontendUser $user
	 *                             The user for whom the subscribed topics are to be loaded.
	 * @return Tx_Extbase_Persistence_QueryInterface
	 *                             The topics subscribed by the given user.
	 */
	public function findBySubscriber(Tx_MmForum_Domain_Model_User_FrontendUser $user) {
		$query = $this->createQuery();
		$query
			->matching($query->contains('subscribers', $user))
			->setOrderings(array('lastPost.crdate' => 'ASC'));
		return $query->execute();
	}


	/**
	 * Finds all topic that have a specific tag
	 *
	 * @param Tx_MmForum_Domain_Model_Forum_Tag $tag
	 * @return Tx_Extbase_Persistence_QueryInterface
	 *                             The topics of this tag.
	 */
	public function findAllTopicsWithGivenTag(Tx_MmForum_Domain_Model_Forum_Tag $tag) {
		$query = $this->createQuery();
		$query
			->matching($query->contains('tags', $tag))
			->setOrderings(array('lastPost.crdate' => 'ASC'));
		return $query->execute();
	}



	/**
	 * Finds all popular topics
	 *
	 * @param int $timeDiff
	 * @param int $displayLimit
	 * @return Tx_Extbase_Persistence_QueryInterface
	 *                             The topics of this tag.
	 */
	public function findPopularTopics($timeDiff=0, $displayLimit=0) {
		if($timeDiff == 0) {
			$timeLimit = 0;
		} else {
			$timeLimit = time() - $timeDiff;
		}

		$query = $this->createQuery();
		$query->matching($query->greaterThan('lastPost.crdate',$timeLimit));
		$query->setOrderings(array('postCount' => 'DESC'));
		if($displayLimit > 0) {
			$query->setLimit($displayLimit);
		}
		return $query->execute();
	}



	/**
	 *
	 * Finds the last topic in a forum.
	 *
	 * @param  Tx_MmForum_Domain_Model_Forum_Forum $forum
	 *                             The forum for which to load the last topic.
	 * @param int $offset
	 * 								If you want to get the next to last topic topic
	 *
	 * @return Tx_MmForum_Domain_Model_Forum_Topic
	 *                             The last topic of the specified forum.
	 *
	 */
	public function findLastByForum(Tx_MmForum_Domain_Model_Forum_Forum $forum, $offset=0) {
		$query = $this->createQuery();
		$query->matching($query->equals('forum', $forum))
			->setOrderings(array('last_post_crdate' => \TYPO3\CMS\Extbase\Persistence\QueryInterface::ORDER_DESCENDING))->setLimit(1);
		if($offset > 0) {
			$query->setOffset($offset);
		}
		return $query->execute()->getFirst();
	}

        
        /**
	 *
	 * Finds the last topic in a forum.
	 *
	 * @param int $limit		The Limit
	 * @param int $offset		The Offset
	 *
	 * @return Tx_MmForum_Domain_Model_Forum_Topic
	 *                             The last topics
	 *
	 */
	public function findLatest($offset=0, $limit=5) {
		$query = $this->createQuery();
		$query->setOrderings(array('last_post_crdate' => \TYPO3\CMS\Extbase\Persistence\QueryInterface::ORDER_DESCENDING))
                        ->setLimit($limit);
		if($offset > 0) {
			$query->setOffset($offset);
		}
		return $query->execute();
	}




	/**
	 * @param Tx_MmForum_Domain_Model_Forum_Forum $forum
	 * @param Tx_MmForum_Domain_Model_User_FrontendUser $user
	 * @return array
	 */
	public function getUnreadTopics(Tx_MmForum_Domain_Model_Forum_Forum $forum, Tx_MmForum_Domain_Model_User_FrontendUser $user) {

		$sql ='SELECT t.uid
			   FROM tx_mmforum_domain_model_forum_topic AS t
			   LEFT JOIN tx_mmforum_domain_model_user_readtopic AS rt
					   ON rt.uid_foreign = t.uid AND rt.uid_local = '.intval($user->getUid()).'
			   WHERE rt.uid_local IS NULL AND t.forum='.intval($forum->getUid());
		$query = $this->createQuery();
		$query->getQuerySettings()->setReturnRawQueryResult(TRUE);
		$query->statement($sql);
		return $query->execute();
	}


	/**
	 * @param Tx_MmForum_Domain_Model_Forum_Topic $topic
	 * @param Tx_MmForum_Domain_Model_User_FrontendUser $user
	 * @return bool
	 */
	public function getTopicReadByUser(Tx_MmForum_Domain_Model_Forum_Topic $topic, Tx_MmForum_Domain_Model_User_FrontendUser $user) {
		$sql ='SELECT t.uid
			   FROM tx_mmforum_domain_model_forum_topic AS t
			   LEFT JOIN tx_mmforum_domain_model_user_readtopic AS rt
					   ON rt.uid_foreign = t.uid AND rt.uid_local = '.intval($user->getUid()).'
			   WHERE rt.uid_local IS NULL AND t.uid='.intval($topic->getUid());
		$query = $this->createQuery();
		$query->getQuerySettings()->setReturnRawQueryResult(TRUE);
		$query->statement($sql);
		$res = $query->execute();
		if($res != false) {
			return true;
		} else {
			return false;
		}
	}

}
