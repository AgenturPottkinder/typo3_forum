<?php
namespace Mittwald\MmForum\Domain\Repository\Forum;


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
 * Repository class for post objects.
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
class PostRepository extends \Mittwald\MmForum\Domain\Repository\AbstractRepository {



	/*
	 * REPOSITORY METHODS
	 */

	/**
	 *
	 * Finds posts for a specific filterset. Page navigation is possible.
	 *
	 * @param  integer $limit
	 * @param  array $orderings
	 *
	 * @return Array<\Mittwald\MmForum\Domain\Model\Forum\Post>
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
	 * @return \Mittwald\MmForum\Domain\Model\Forum\Topic[]
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
	 *
	 * Finds posts for a specific topic. Page navigation is possible.
	 *
	 * @param  \Mittwald\MmForum\Domain\Model\Forum\Topic $topic
	 *                               The topic for which the posts are to be loaded.
	 *
	 * @return Array<\Mittwald\MmForum\Domain\Model\Forum\Post>
	 *                               The selected subset of posts in the specified
	 *                               topic.
	 *
	 */
	public function findForTopic(\Mittwald\MmForum\Domain\Model\Forum\Topic $topic) {
		$query = $this->createQuery();
		$query->getQuerySettings()->setRespectSysLanguage(FALSE) ;
		return $query->matching($query->equals('topic', $topic))
			->setOrderings(array('crdate' => \TYPO3\CMS\Extbase\Persistence\QueryInterface::ORDER_ASCENDING))
			->setOffset(1)
			->execute();
	}



	/**
	 *
	 * Finds the last post in a topic.
	 *
	 * @param  \Mittwald\MmForum\Domain\Model\Forum\Topic $topic
	 *                             The topic for which the last post is to be
	 *                             loaded.
	 * @param int $offset
	 * 								If you want to get the next to last post post
	 *
	 * @return \Mittwald\MmForum\Domain\Model\Forum\Post
	 *                             The last post of the specified topic.
	 *
	 */
	public function findLastByTopic(\Mittwald\MmForum\Domain\Model\Forum\Topic $topic, $offset=0) {
		$query = $this->createQuery();
		$query->matching($query->equals('topic', $topic))
			->setOrderings(Array('crdate' => \TYPO3\CMS\Extbase\Persistence\QueryInterface::ORDER_DESCENDING))->setLimit(1);
		if($offset > 0) {
			$query->setOffset($offset);
		}
		return $query->execute()->getFirst();
	}





	/**
	 *
	 * Finds the last post in a forum.
	 *
	 * @param  \Mittwald\MmForum\Domain\Model\Forum\Forum $forum
	 *                             The forum for which to load the last post.
	 * @param int $offset
	 * 								If you want to get the next to last post post
	 *
	 * @return \Mittwald\MmForum\Domain\Model\Forum\Post
	 *                             The last post of the specified forum.
	 *
	 */
	public function findLastByForum(\Mittwald\MmForum\Domain\Model\Forum\Forum $forum, $offset=0) {
		$query = $this->createQuery();
		$query->matching($query->equals('topic.forum', $forum))
			->setOrderings(array('crdate' => \TYPO3\CMS\Extbase\Persistence\QueryInterface::ORDER_DESCENDING))->setLimit(1);
		if($offset > 0) {
			$query->setOffset($offset);
		}
		return $query->execute()->getFirst();
	}


	/**
	 * Deletes the post with sql statements.
	 * Only used on performance problems (Activate useSqlStatementsOnCriticalFunctions in settings).
	 *
	 * @param \Mittwald\MmForum\Domain\Model\Forum\Post $post
	 * @return void
	 */
	public function deletePostWithSqlStatement(\Mittwald\MmForum\Domain\Model\Forum\Post $post) {
		$lastPost = $post->getTopic()->getLastPost();
		if($post->getUid() == $lastPost->getUid()) {
			$lastPost = $this->findLastByTopic($post->getTopic(),1);
		}

		$queries = array(
			'UPDATE tx_mmforum_domain_model_forum_topic SET post_count = post_count - 1, last_post = '.intval($lastPost->getUid()).', last_post_crdate = '.intval($lastPost->getTimestamp()->getTimestamp()).'
					WHERE uid = '.intval($post->getTopic()->getUid()),
			'UPDATE tx_mmforum_domain_model_forum_forum SET post_count = post_count - 1, last_post = '.intval($lastPost->getUid()).'
					WHERE uid = '.intval($post->getTopic()->getForum()->getUid()),
			'UPDATE tx_mmforum_domain_model_forum_post SET deleted=1 WHERE uid='.intval($post->getUid()),
		);

		foreach($queries AS $sql) {
			$GLOBALS['TYPO3_DB']->sql_query($sql);
		}
	}


}
